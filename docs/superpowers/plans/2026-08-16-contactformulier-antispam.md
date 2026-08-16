# Contactformulier Anti-spam Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Stop geautomatiseerde contactmail via de echte `/contact`-route, zonder normale bezoekers of lokale ontwikkeling onnodig te blokkeren.

**Architecture:** Alle contactbeveiliging komt in een kleine, testbare server-side module. De echte formuliercontroller gebruikt een sessiegebonden, eenmalige tijdchallenge, honeypot, strikte invoerlimieten, IP/e-mail/fingerprint-throttling en optionele Cloudflare Turnstile-validatie; de ongebruikte publieke JSON-mailroute wordt gesloten zodat er geen tweede ingang overblijft.

**Tech Stack:** PHP 8.3, PHPMailer, PHP sessions, file-locked JSON rate-limit storage, Cloudflare Turnstile Siteverify, Playwright Chromium.

## Global Constraints

- Verstuur tijdens tests uitsluitend mail naar `naoufal.exe@gmail.com`.
- Verstuur in geautomatiseerde tests geen echte mail; injecteer of onderdruk de transportstap.
- Push niets naar Vercel of productie; de gebruiker pusht zelf via git.
- Gebruik Playwright uitsluitend met Chromium voor browservalidatie.
- Bewaar de Turnstile-secret uitsluitend server-side in `POLITIEKPRAAT_TURNSTILE_SECRET_KEY`.
- Toon bots geen gedetailleerde afwijsreden; log alleen gehashte identifiers en eventcodes.

---

### Task 1: Server-side contactbeveiliging

**Files:**
- Create: `includes/contact_security.php`
- Test: `scripts/tests/test-contact-security.php`

**Interfaces:**
- Produces: `pp_contact_issue_challenge(): string`, `pp_contact_validate_submission(array $post): array`, `pp_contact_record_delivery(string $email, string $fingerprint): bool`, `pp_contact_turnstile_config(): array`.
- Consumes: `$_SESSION`, request servervariabelen en optionele Turnstile environmentvariabelen.

- [ ] **Step 1: Write failing tests for challenge and input controls**

```php
$challenge = pp_contact_issue_challenge(time() - 5);
assert_true(pp_contact_validate_submission(valid_post($challenge))['ok'] === true, 'normale inzending');
assert_true(pp_contact_validate_submission(valid_post($challenge, ['website' => 'https://spam.test']))['ok'] === false, 'honeypot');
assert_true(pp_contact_validate_submission(valid_post('forged'))['ok'] === false, 'vervalste challenge');
assert_true(pp_contact_validate_submission(valid_post($challenge, ['message' => str_repeat('https://x.test ', 4)]))['ok'] === false, 'linkspam');
```

- [ ] **Step 2: Run the focused test and confirm failure**

Run: `php scripts/tests/test-contact-security.php`
Expected: FAIL because `includes/contact_security.php` does not exist.

- [ ] **Step 3: Implement the guard**

```php
function pp_contact_validate_submission(array $post): array {
    // Validate and consume the signed session challenge, reject the honeypot,
    // enforce scalar type/length/URL limits, then validate Turnstile when configured.
    // Return ['ok' => bool, 'reason' => string, 'data' => array].
}
```

The implementation must use `hash_hmac` plus `hash_equals`, consume each challenge once, require a minimum form age, enforce a maximum age, use `flock(LOCK_EX)` for throttling, fail closed when production protection storage cannot be used, and never log raw IP addresses, e-mail addresses or message content.

- [ ] **Step 4: Run focused tests**

Run: `php scripts/tests/test-contact-security.php`
Expected: PASS for legitimate input, honeypot, forged/replayed/fast/expired challenge, oversize values, URL spam, duplicate fingerprint and rate-limit cases.

### Task 2: Protect the real form and close the duplicate mail endpoint

**Files:**
- Modify: `controllers/contact.php`
- Modify: `api/endpoints/contact.php`
- Modify: `api/index.php`

**Interfaces:**
- Consumes: the functions from `includes/contact_security.php`.
- Produces: a protected `/contact` POST flow and no public mail-sending `/api/contact` bypass.

- [ ] **Step 1: Add a controller regression check**

```php
assert_source_contains('controllers/contact.php', 'pp_contact_validate_submission');
assert_source_not_contains('api/endpoints/contact.php', 'mail(');
```

- [ ] **Step 2: Run it to verify the old controller fails the check**

Run: `php scripts/tests/test-contact-security.php`
Expected: FAIL because the real controller does not call the guard.

- [ ] **Step 3: Integrate before PHPMailer**

```php
$validation = pp_contact_validate_submission($_POST);
if (!$validation['ok']) {
    pp_contact_log_abuse($validation['reason']);
    $_SESSION['contact_error'] = 'Het bericht kon niet worden geverifieerd. Vernieuw de pagina en probeer opnieuw.';
    header('Location: ' . URLROOT . '/contact');
    exit();
}
```

Use validated raw values for PHPMailer, HTML-escape only at the HTML output boundary, enable TLS certificate verification, hide SMTP internals from visitors, and record a successful delivery fingerprint only after `$mail->send()` succeeds.

- [ ] **Step 4: Disable the unused JSON mail path**

Return HTTP 410 from `POST /api/contact`, remove its public documentation entry, and leave no `mail()` call reachable through that endpoint.

- [ ] **Step 5: Run regression tests**

Run: `php scripts/tests/test-contact-security.php`
Expected: PASS.

### Task 3: Browser form controls and managed challenge

**Files:**
- Modify: `controllers/contact.php`
- Test: `tests/contact-antispam.spec.js`
- Modify: `package.json`

**Interfaces:**
- Consumes: `pp_contact_issue_challenge()` and `pp_contact_turnstile_config()`.
- Produces: hidden `contact_challenge`, off-screen honeypot `website`, input maxlength attributes, and a Turnstile widget only when both keys are configured.

- [ ] **Step 1: Write Chromium checks**

```js
test('contact form exposes safe limits and bot controls', async ({ page }) => {
  await page.goto('/contact');
  await expect(page.locator('input[name="contact_challenge"]')).toHaveAttribute('value', /.+/);
  await expect(page.locator('input[name="website"]')).toHaveCount(1);
  await expect(page.locator('#message')).toHaveAttribute('maxlength', '5000');
});
```

- [ ] **Step 2: Add the controls to the rendered form**

```php
<input type="hidden" name="contact_challenge" value="<?= pp_e($contact_challenge) ?>">
<div class="sr-only" aria-hidden="true">
    <label for="website">Website</label>
    <input id="website" name="website" type="text" tabindex="-1" autocomplete="off">
</div>
```

Render Turnstile with action `contact` and validate the returned hostname/action server-side. When keys are absent, retain all local anti-spam layers and log one configuration warning in production.

- [ ] **Step 3: Run Chromium only**

Run: `npx playwright test tests/contact-antispam.spec.js --project=chromium`
Expected: PASS with no horizontal overflow at mobile and desktop viewports.

### Task 4: Full verification and operational handoff

**Files:**
- Modify: `README.md`
- Modify: `api/README.md`

**Interfaces:**
- Produces: exact environment setup and verification instructions.

- [ ] **Step 1: Document production keys**

```text
POLITIEKPRAAT_TURNSTILE_SITE_KEY=<public site key>
POLITIEKPRAAT_TURNSTILE_SECRET_KEY=<server-only secret>
POLITIEKPRAAT_TURNSTILE_HOSTNAMES=politiekpraat.nl,www.politiekpraat.nl
```

- [ ] **Step 2: Run syntax, build, security and Chromium checks**

Run: `find controllers includes api/endpoints -name '*.php' -print0 | xargs -0 -n1 php -l`
Expected: no syntax errors.

Run: `npm run build`
Expected: Tailwind build succeeds.

Run: `php scripts/tests/test-contact-security.php`
Expected: PASS.

Run: `npx playwright test tests/contact-antispam.spec.js --project=chromium`
Expected: PASS.

- [ ] **Step 3: Review the final diff**

Run: `git diff --check && git status --short`
Expected: no whitespace errors; only planned files are changed.
