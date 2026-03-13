<?php

declare(strict_types=1);

$basePath = dirname(__DIR__);
$queueFile = $basePath . '/storage/queues/sitemap-regenerate.json';
$lockFile = $basePath . '/storage/queues/sitemap-regenerate.lock';
$logDir = $basePath . '/logs';
$logFile = $logDir . '/sitemap-worker.log';

if (!is_dir($logDir)) {
    @mkdir($logDir, 0755, true);
}

$log = static function (string $message) use ($logFile): void {
    $line = sprintf("[%s] %s\n", date('c'), $message);
    @file_put_contents($logFile, $line, FILE_APPEND);
    error_log('sitemap-worker: ' . $message);
};

if (!file_exists($queueFile)) {
    $log('Geen sitemap-taak in queue, stop.');
    exit(0);
}

$lockFp = @fopen($lockFile, 'c+');
if ($lockFp === false) {
    $log('Kon lockfile niet openen.');
    exit(1);
}

if (!flock($lockFp, LOCK_EX | LOCK_NB)) {
    $log('Worker draait al; deze run stopt.');
    fclose($lockFp);
    exit(0);
}

try {
    $payloadRaw = @file_get_contents($queueFile);
    $payload = $payloadRaw ? json_decode($payloadRaw, true) : [];
    $queuedAt = $payload['queued_at'] ?? 'unknown';

    $log('Start sitemap-regeneratie. queued_at=' . $queuedAt);

    $cmd = 'cd ' . escapeshellarg($basePath) . ' && php generate-sitemap.php > sitemap.xml 2>&1';
    $output = [];
    $exitCode = 0;
    exec($cmd, $output, $exitCode);

    if ($exitCode !== 0) {
        $log('Sitemap generatie faalde met exitcode ' . $exitCode . '. Queue blijft staan voor retry.');
        exit(1);
    }

    $sitemapUrl = urlencode('https://politiekpraat.nl/sitemap.xml');
    $pingUrl = 'https://www.google.com/ping?sitemap=' . $sitemapUrl;

    $context = stream_context_create([
        'http' => [
            'timeout' => 5,
            'ignore_errors' => true,
        ],
    ]);

    @file_get_contents($pingUrl, false, $context);

    @unlink($queueFile);
    $log('Sitemap-regeneratie klaar en queue-item verwijderd.');
    exit(0);
} catch (Throwable $e) {
    $log('Onverwachte fout: ' . $e->getMessage());
    exit(1);
} finally {
    flock($lockFp, LOCK_UN);
    fclose($lockFp);
}
