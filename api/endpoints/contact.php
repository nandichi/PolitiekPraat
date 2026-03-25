<?php
/**
 * Contact API Endpoint
 * Handles contact form submissions
 */

class ContactAPI {
    private $db;

    private const CONTACT_WINDOW_SECONDS = 600; // 10 minuten
    private const CONTACT_MAX_REQUESTS_PER_IP = 5;
    private const CONTACT_MAX_REQUESTS_PER_EMAIL = 3;
    private const CONTACT_MIN_SUBMIT_SECONDS = 3;
    private const CONTACT_RETRY_AFTER_SECONDS = 120;

    public function __construct() {
        $this->db = new Database();
    }

    public function handle($method, $segments) {
        switch ($method) {
            case 'POST':
                $this->submitContact();
                break;

            default:
                sendApiError('Method niet toegestaan. Alleen POST is beschikbaar.', 405);
        }
    }

    private function submitContact() {
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            if (!is_array($data)) {
                sendApiError('Ongeldige JSON payload', 400);
                return;
            }

            if (!$this->passesBotChecks($data)) {
                return;
            }

            // Validate required fields
            $required = ['name', 'email', 'subject', 'message'];
            foreach ($required as $field) {
                if (!isset($data[$field]) || empty(trim((string) $data[$field]))) {
                    sendApiError("Veld '{$field}' is verplicht", 400);
                    return;
                }
            }

            // Validate email
            if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                sendApiError('Ongeldig email adres', 400);
                return;
            }

            if (!$this->passesContactRateLimit((string) $data['email'])) {
                return;
            }

            // Sanitize input
            $name = htmlspecialchars(trim((string) $data['name']), ENT_QUOTES, 'UTF-8');
            $email = filter_var(trim((string) $data['email']), FILTER_SANITIZE_EMAIL);
            $subject = htmlspecialchars(trim((string) $data['subject']), ENT_QUOTES, 'UTF-8');
            $message = htmlspecialchars(trim((string) $data['message']), ENT_QUOTES, 'UTF-8');

            // Send email (if configured)
            $to = 'naoufal.exe@gmail.com';
            $emailSubject = "Contact formulier: {$subject}";
            $emailBody = "Naam: {$name}\n";
            $emailBody .= "Email: {$email}\n\n";
            $emailBody .= "Bericht:\n{$message}";

            $headers = "From: {$email}\r\n";
            $headers .= "Reply-To: {$email}\r\n";
            $headers .= 'X-Mailer: PHP/' . phpversion();

            // Note: mail() function may not work on all servers
            // Consider using a proper email service like SendGrid, Mailgun, etc.
            $mailSent = mail($to, $emailSubject, $emailBody, $headers);

            sendApiResponse([
                'message' => 'Contactbericht succesvol verzonden',
                'email_sent' => $mailSent,
                'note' => $mailSent ? null : 'Email verzending kan gefaald zijn. Neem rechtstreeks contact op als u geen antwoord ontvangt.',
                'rate_limit' => [
                    'window_seconds' => self::CONTACT_WINDOW_SECONDS,
                    'max_requests_per_ip' => self::CONTACT_MAX_REQUESTS_PER_IP,
                    'max_requests_per_email' => self::CONTACT_MAX_REQUESTS_PER_EMAIL,
                ],
            ]);

        } catch (Exception $e) {
            sendApiError('Interne serverfout', 500, ['exception' => $e->getMessage()]);
        }
    }

    private function passesBotChecks(array $data): bool {
        // Honeypot: bots vullen vaak hidden velden
        $honeypot = trim((string) ($data['website'] ?? ''));
        if ($honeypot !== '') {
            $this->logAbuse('honeypot_triggered', [
                'ip_hash' => $this->hashIp(get_rate_limit_client_ip()),
            ]);

            header('Retry-After: ' . self::CONTACT_RETRY_AFTER_SECONDS);
            sendApiError('Te veel verzoeken. Probeer het later opnieuw.', 429);
            return false;
        }

        // Timestamp-check: formulier mag niet "instant" verzonden zijn
        $submittedAt = isset($data['submitted_at']) ? (int) $data['submitted_at'] : 0;
        if ($submittedAt > 0) {
            $age = time() - $submittedAt;
            if ($age >= 0 && $age < self::CONTACT_MIN_SUBMIT_SECONDS) {
                $this->logAbuse('submit_too_fast', [
                    'ip_hash' => $this->hashIp(get_rate_limit_client_ip()),
                    'age_seconds' => $age,
                ]);

                header('Retry-After: ' . self::CONTACT_RETRY_AFTER_SECONDS);
                sendApiError('Te veel verzoeken. Probeer het later opnieuw.', 429);
                return false;
            }
        }

        return true;
    }

    private function passesContactRateLimit(string $email): bool {
        $ip = get_rate_limit_client_ip();
        $emailKey = mb_strtolower(trim($email));

        $storageDir = dirname(__DIR__, 2) . '/cache/rate_limit';
        if (!is_dir($storageDir) && !mkdir($storageDir, 0775, true) && !is_dir($storageDir)) {
            return true;
        }

        $storagePath = $storageDir . '/contact_rate_limits.json';
        $now = time();
        $windowStart = $now - self::CONTACT_WINDOW_SECONDS;

        $fp = fopen($storagePath, 'c+');
        if (!$fp) {
            return true;
        }

        if (!flock($fp, LOCK_EX)) {
            fclose($fp);
            return true;
        }

        $raw = stream_get_contents($fp);
        $state = json_decode((string) $raw, true);
        if (!is_array($state)) {
            $state = [];
        }

        $state = $this->pruneRateLimitState($state, $windowStart);

        $ipKey = 'ip:' . hash('sha256', $ip);
        $emailBucketKey = 'email:' . hash('sha256', $emailKey);

        $ipCount = isset($state[$ipKey]) ? count($state[$ipKey]) : 0;
        $emailCount = isset($state[$emailBucketKey]) ? count($state[$emailBucketKey]) : 0;

        $isBlocked = $ipCount >= self::CONTACT_MAX_REQUESTS_PER_IP || $emailCount >= self::CONTACT_MAX_REQUESTS_PER_EMAIL;

        if (!$isBlocked) {
            $state[$ipKey][] = $now;
            $state[$emailBucketKey][] = $now;
        }

        ftruncate($fp, 0);
        rewind($fp);
        fwrite($fp, json_encode($state, JSON_UNESCAPED_UNICODE));
        fflush($fp);
        flock($fp, LOCK_UN);
        fclose($fp);

        if ($isBlocked) {
            $this->logAbuse('rate_limit_exceeded', [
                'ip_hash' => $this->hashIp($ip),
                'email_hash' => hash('sha256', $emailKey),
                'ip_count' => $ipCount,
                'email_count' => $emailCount,
                'window_seconds' => self::CONTACT_WINDOW_SECONDS,
            ]);

            header('Retry-After: ' . self::CONTACT_RETRY_AFTER_SECONDS);
            sendApiError('Te veel verzoeken. Probeer het later opnieuw.', 429);
            return false;
        }

        return true;
    }

    private function pruneRateLimitState(array $state, int $windowStart): array {
        foreach ($state as $key => $timestamps) {
            if (!is_array($timestamps)) {
                unset($state[$key]);
                continue;
            }

            $state[$key] = array_values(array_filter($timestamps, static function ($ts) use ($windowStart) {
                return is_int($ts) && $ts >= $windowStart;
            }));

            if (count($state[$key]) === 0) {
                unset($state[$key]);
            }
        }

        return $state;
    }

    private function hashIp(string $ip): string {
        return hash('sha256', $ip);
    }

    private function logAbuse(string $event, array $context = []): void {
        $logContext = array_merge([
            'event' => $event,
            'request_id' => function_exists('api_get_request_id') ? api_get_request_id() : null,
            'uri' => $_SERVER['REQUEST_URI'] ?? '',
            'method' => $_SERVER['REQUEST_METHOD'] ?? '',
        ], $context);

        error_log('[CONTACT ABUSE] ' . json_encode($logContext, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
    }
}
