<?php
declare(strict_types=1);

require_once __DIR__ . '/functions.php';

date_default_timezone_set(env_value('APP_TIMEZONE', 'America/Mexico_City') ?? 'America/Mexico_City');

$isHttps = ($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https'
    || (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_name('gim_session');
    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/',
        'secure' => $isHttps,
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
    session_start();
}

if (!headers_sent()) {
    header('X-Content-Type-Options: nosniff');
    header('X-Frame-Options: SAMEORIGIN');
    header('Referrer-Policy: strict-origin-when-cross-origin');
    header("Permissions-Policy: camera=(), microphone=(), geolocation=()");
}

require_once __DIR__ . '/csrf.php';
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/auth.php';

