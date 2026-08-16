<?php

declare(strict_types=1);

session_id('contact-security-test-' . bin2hex(random_bytes(4)));
session_start();

$rateLimitPath = sys_get_temp_dir() . '/politiekpraat-contact-security-' . bin2hex(random_bytes(6)) . '.json';
putenv('POLITIEKPRAAT_CONTACT_RATE_LIMIT_PATH=' . $rateLimitPath);
putenv('POLITIEKPRAAT_CONTACT_ATTEMPT_MAX=100');
putenv('POLITIEKPRAAT_TURNSTILE_SITE_KEY');
putenv('POLITIEKPRAAT_TURNSTILE_SECRET_KEY');
$_SERVER['REMOTE_ADDR'] = '203.0.113.42';
$_SERVER['REQUEST_URI'] = '/contact';

require_once __DIR__ . '/../../includes/contact_security.php';

function assert_true(bool $condition, string $message): void {
    if (!$condition) {
        fwrite(STDERR, "FAIL: {$message}\n");
        exit(1);
    }
}

function valid_post(array $overrides = [], ?int $issuedAt = null): array {
    return array_merge([
        'contact_challenge' => pp_contact_issue_challenge($issuedAt ?? time() - 5),
        'website' => '',
        'name' => 'Test Bezoeker',
        'email' => 'bezoeker@example.com',
        'subject' => 'Vraag over een artikel',
        'message' => 'Dit is een normale inhoudelijke vraag over een gepubliceerd artikel.',
    ], $overrides);
}

function reset_rate_state(string $path): void {
    if (is_file($path)) {
        unlink($path);
    }
}

$normal = pp_contact_validate_submission(valid_post());
assert_true($normal['ok'] === true, 'een normale inzending moet worden geaccepteerd');
assert_true($normal['data']['email'] === 'bezoeker@example.com', 'gevalideerde data moet behouden blijven');
$stableIpHash = pp_contact_hash_identifier($_SERVER['REMOTE_ADDR']);
$_SESSION['contact_form_secret'] = bin2hex(random_bytes(32));
assert_true(pp_contact_hash_identifier($_SERVER['REMOTE_ADDR']) === $stableIpHash, 'IP-throttle mag niet resetten bij een nieuwe sessiesleutel');

$honeypot = pp_contact_validate_submission(valid_post(['website' => 'https://spam.invalid']));
assert_true($honeypot['ok'] === false && $honeypot['reason'] === 'honeypot_triggered', 'gevulde honeypot moet blokkeren');

$forged = pp_contact_validate_submission(valid_post(['contact_challenge' => 'vervalst']));
assert_true($forged['ok'] === false && $forged['reason'] === 'challenge_invalid', 'vervalste challenge moet blokkeren');

$replayPost = valid_post();
$replayPost['message'] = 'Dit is een unieke inhoud voor de replay-regressietest.';
$firstUse = pp_contact_validate_submission($replayPost);
$secondUse = pp_contact_validate_submission($replayPost);
assert_true($firstUse['ok'] === true, 'eerste challengegebruik moet slagen');
assert_true($secondUse['ok'] === false && $secondUse['reason'] === 'challenge_unknown', 'challenge mag maar een keer bruikbaar zijn');

$tooFast = pp_contact_validate_submission(valid_post([], time()));
assert_true($tooFast['ok'] === false && $tooFast['reason'] === 'challenge_too_fast', 'instant submit moet blokkeren');

$expired = pp_contact_validate_submission(valid_post([], time() - 7201));
assert_true($expired['ok'] === false && $expired['reason'] === 'challenge_expired', 'oude challenge moet blokkeren');

$headerInjection = pp_contact_validate_submission(valid_post(['subject' => "Vraag\r\nBcc: spam@example.com"]));
assert_true($headerInjection['ok'] === false && $headerInjection['reason'] === 'invalid_subject', 'headerinjectie moet blokkeren');

$arrayField = pp_contact_validate_submission(valid_post(['name' => ['geen', 'string']]));
assert_true($arrayField['ok'] === false && $arrayField['reason'] === 'invalid_name', 'niet-scalaire velden moeten blokkeren');

$linkSpam = pp_contact_validate_submission(valid_post([
    'message' => 'Bekijk https://one.invalid https://two.invalid en https://three.invalid voor winst.',
]));
assert_true($linkSpam['ok'] === false && $linkSpam['reason'] === 'too_many_urls', 'meer dan twee links moet blokkeren');

$tooLong = pp_contact_validate_submission(valid_post(['message' => str_repeat('x', 5001)]));
assert_true($tooLong['ok'] === false && $tooLong['reason'] === 'invalid_message', 'te lang bericht moet blokkeren');

reset_rate_state($rateLimitPath);
$deliveryData = $normal['data'];
assert_true(pp_contact_reserve_delivery($deliveryData['email'], $deliveryData['fingerprint'], time())['ok'] === true, 'bezorging moet atomair worden gereserveerd');
$duplicate = pp_contact_delivery_allowed($deliveryData['email'], $deliveryData['fingerprint'], time() + 1);
assert_true($duplicate['ok'] === false && $duplicate['reason'] === 'duplicate_submission', 'identieke bezorging moet 24 uur blokkeren');

reset_rate_state($rateLimitPath);
for ($index = 0; $index < 3; $index++) {
    $email = 'rate-' . $index . '@example.com';
    assert_true(pp_contact_record_delivery($email, hash('sha256', 'unique-' . $index), time() + $index), 'rate test delivery registreren');
}
$rateLimited = pp_contact_delivery_allowed('fresh@example.com', hash('sha256', 'fresh'), time() + 4);
assert_true($rateLimited['ok'] === false && $rateLimited['reason'] === 'delivery_rate_limited', 'vierde IP-bezorging binnen uur moet blokkeren');

putenv('POLITIEKPRAAT_TURNSTILE_SITE_KEY=site-only');
$misconfigured = pp_contact_verify_turnstile('token');
assert_true($misconfigured['ok'] === false && $misconfigured['reason'] === 'turnstile_misconfigured', 'halve Turnstile-config moet fail-closed zijn');
putenv('POLITIEKPRAAT_TURNSTILE_SITE_KEY');

$controllerSource = file_get_contents(__DIR__ . '/../../controllers/contact.php');
$apiSource = file_get_contents(__DIR__ . '/../../api/endpoints/contact.php');
assert_true(is_string($controllerSource) && str_contains($controllerSource, 'pp_contact_validate_submission'), 'echte controller moet de guard gebruiken');
assert_true(is_string($apiSource) && !preg_match('/\bmail\s*\(/', $apiSource), 'oude API-route mag geen mail versturen');
assert_true(is_string($apiSource) && str_contains($apiSource, '410'), 'oude API-route moet expliciet gesloten zijn');

reset_rate_state($rateLimitPath);
echo "OK: contactbeveiliging blokkeert bot-, replay-, linkspam-, injectie-, duplicaat- en rate-limitpatronen\n";
