<?php
/**
 * Resend e-mailhelper.
 *
 * Verstuurt transactionele e-mails via de Resend API (https://resend.com).
 * De API-key en het afzenderadres komen uit de environment (includes/env.local.php
 * op de server). We gebruiken cURL, zodat er geen extra Composer-afhankelijkheid
 * nodig is.
 *
 * Benodigde env-variabelen:
 *   - RESEND_API_KEY            (verplicht) de Resend API-sleutel
 *   - POLITIEKPRAAT_RESEND_FROM (optioneel) afzender, bijv.
 *                               'PolitiekPraat <noreply@politiekprofiel.nl>'
 */

if (!function_exists('pp_resend_from')) {
    function pp_resend_from(): string {
        $from = getenv('POLITIEKPRAAT_RESEND_FROM');
        if (is_string($from) && trim($from) !== '') {
            return trim($from);
        }

        // Valt terug op het geverifieerde Resend-domein.
        return 'PolitiekPraat <noreply@politiekprofiel.nl>';
    }
}

if (!function_exists('pp_send_email')) {
    /**
     * Verstuur een transactionele e-mail via Resend.
     *
     * @return array{ok: bool, id?: string, error?: string}
     */
    function pp_send_email(string $to, string $subject, string $html, ?string $text = null, ?string $replyTo = null): array {
        $apiKey = getenv('RESEND_API_KEY');
        if (!is_string($apiKey) || trim($apiKey) === '') {
            error_log('[Resend] RESEND_API_KEY ontbreekt in de environment.');
            return ['ok' => false, 'error' => 'mail_not_configured'];
        }

        if (!function_exists('curl_init')) {
            error_log('[Resend] cURL-extensie is niet beschikbaar.');
            return ['ok' => false, 'error' => 'curl_missing'];
        }

        $payload = [
            'from' => pp_resend_from(),
            'to' => [$to],
            'subject' => $subject,
            'html' => $html,
        ];
        if (is_string($text) && $text !== '') {
            $payload['text'] = $text;
        }
        if (is_string($replyTo) && $replyTo !== '') {
            $payload['reply_to'] = $replyTo;
        }

        $ch = curl_init('https://api.resend.com/emails');
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_TIMEOUT => 15,
            CURLOPT_HTTPHEADER => [
                'Authorization: Bearer ' . trim($apiKey),
                'Content-Type: application/json',
            ],
            CURLOPT_POSTFIELDS => json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
        ]);

        $response = curl_exec($ch);
        $httpCode = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        curl_close($ch);

        if ($response === false) {
            error_log('[Resend] cURL-fout: ' . $curlError);
            return ['ok' => false, 'error' => 'network_error'];
        }

        $decoded = json_decode($response, true);
        if ($httpCode >= 200 && $httpCode < 300 && is_array($decoded) && isset($decoded['id'])) {
            return ['ok' => true, 'id' => (string) $decoded['id']];
        }

        $apiError = is_array($decoded) && isset($decoded['message'])
            ? (string) $decoded['message']
            : ('HTTP ' . $httpCode);
        error_log('[Resend] Verzenden mislukt: ' . $apiError . ' | response: ' . substr((string) $response, 0, 500));

        return ['ok' => false, 'error' => $apiError];
    }
}

if (!function_exists('pp_password_reset_email_html')) {
    /**
     * Bouw de HTML voor de wachtwoord-reset e-mail.
     */
    function pp_password_reset_email_html(string $resetUrl, string $username = ''): string {
        $safeUrl = htmlspecialchars($resetUrl, ENT_QUOTES, 'UTF-8');
        $greeting = $username !== ''
            ? 'Hoi ' . htmlspecialchars($username, ENT_QUOTES, 'UTF-8') . ','
            : 'Hoi,';

        return '<!DOCTYPE html>'
            . '<html lang="nl"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"></head>'
            . '<body style="margin:0;padding:0;background-color:#f4f3ef;font-family:-apple-system,BlinkMacSystemFont,Segoe UI,Roboto,Helvetica,Arial,sans-serif;">'
            . '<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#f4f3ef;padding:32px 16px;">'
            . '<tr><td align="center">'
            . '<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="max-width:520px;background-color:#ffffff;border:1px solid #e2e0d8;border-radius:8px;overflow:hidden;">'
            . '<tr><td style="padding:28px 32px 12px 32px;border-bottom:1px solid #eceae2;">'
            . '<span style="font-size:18px;font-weight:700;color:#1a2a44;letter-spacing:-0.01em;">PolitiekPraat</span>'
            . '</td></tr>'
            . '<tr><td style="padding:28px 32px;">'
            . '<h1 style="margin:0 0 16px 0;font-size:20px;line-height:1.3;color:#16181d;">Wachtwoord opnieuw instellen</h1>'
            . '<p style="margin:0 0 12px 0;font-size:15px;line-height:1.6;color:#3a3d44;">' . $greeting . '</p>'
            . '<p style="margin:0 0 24px 0;font-size:15px;line-height:1.6;color:#3a3d44;">Je hebt aangevraagd om je wachtwoord opnieuw in te stellen. Klik op de knop hieronder om een nieuw wachtwoord te kiezen. Deze link is 1 uur geldig.</p>'
            . '<table role="presentation" cellpadding="0" cellspacing="0" style="margin:0 0 24px 0;"><tr><td style="border-radius:6px;background-color:#1a2a44;">'
            . '<a href="' . $safeUrl . '" style="display:inline-block;padding:13px 28px;font-size:15px;font-weight:600;color:#ffffff;text-decoration:none;border-radius:6px;">Nieuw wachtwoord instellen</a>'
            . '</td></tr></table>'
            . '<p style="margin:0 0 8px 0;font-size:13px;line-height:1.6;color:#6b6e76;">Werkt de knop niet? Kopieer dan deze link naar je browser:</p>'
            . '<p style="margin:0 0 24px 0;font-size:13px;line-height:1.6;word-break:break-all;"><a href="' . $safeUrl . '" style="color:#1a2a44;">' . $safeUrl . '</a></p>'
            . '<p style="margin:0;font-size:13px;line-height:1.6;color:#6b6e76;">Heb je dit niet aangevraagd? Dan kun je deze e-mail negeren. Je wachtwoord blijft ongewijzigd.</p>'
            . '</td></tr>'
            . '<tr><td style="padding:18px 32px;border-top:1px solid #eceae2;background-color:#faf9f6;">'
            . '<p style="margin:0;font-size:12px;line-height:1.5;color:#9a9ca3;">Deze e-mail is verstuurd door PolitiekPraat. Reageren is niet nodig.</p>'
            . '</td></tr>'
            . '</table></td></tr></table></body></html>';
    }
}
