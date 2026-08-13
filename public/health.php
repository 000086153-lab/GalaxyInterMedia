<?php
declare(strict_types=1);

require_once dirname(__DIR__) . '/app/bootstrap.php';

try {
    $pdo = db();
    ensure_schema($pdo);
    $pdo->query('SELECT 1');
    json_response(['status' => 'ok', 'database' => 'connected']);
} catch (Throwable $exception) {
    error_log('Healthcheck falló: ' . $exception->getMessage());
    json_response(['status' => 'degraded', 'database' => 'unavailable'], 503);
}

