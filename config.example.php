<?php
declare(strict_types=1);

/*
 * Referencia local únicamente. La aplicación desplegada lee estas claves desde
 * variables de entorno; no copies credenciales reales dentro del repositorio.
 */
return [
    'DB_HOST' => getenv('DB_HOST') ?: getenv('MYSQLHOST') ?: '127.0.0.1',
    'DB_PORT' => getenv('DB_PORT') ?: getenv('MYSQLPORT') ?: '3306',
    'DB_NAME' => getenv('DB_NAME') ?: getenv('MYSQLDATABASE') ?: 'galaxyintermedia',
    'DB_USER' => getenv('DB_USER') ?: getenv('MYSQLUSER') ?: 'root',
    'DB_PASSWORD' => getenv('DB_PASSWORD') ?: getenv('MYSQLPASSWORD') ?: '',
    'ADMIN_USER' => getenv('ADMIN_USER') ?: getenv('ADMIN_EMAIL') ?: 'admin',
    'ADMIN_PASSWORD_HASH' => getenv('ADMIN_PASSWORD_HASH') ?: '',
    'APP_TIMEZONE' => getenv('APP_TIMEZONE') ?: 'America/Mexico_City',
];

