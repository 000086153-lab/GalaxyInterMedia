<?php
declare(strict_types=1);

function db(): PDO
{
    static $pdo = null;

    if ($pdo instanceof PDO) {
        return $pdo;
    }

    $host = env_value('DB_HOST', env_value('MYSQLHOST', '127.0.0.1'));
    $port = env_value('DB_PORT', env_value('MYSQLPORT', '3306'));
    $name = env_value('DB_NAME', env_value('MYSQLDATABASE', 'galaxyintermedia'));
    $user = env_value('DB_USER', env_value('MYSQLUSER', 'root'));
    $password = env_value('DB_PASSWORD', env_value('MYSQLPASSWORD', ''));

    $dsn = sprintf(
        'mysql:host=%s;port=%s;dbname=%s;charset=utf8mb4',
        $host,
        $port,
        $name
    );

    $pdo = new PDO($dsn, $user, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
        PDO::ATTR_TIMEOUT => 8,
    ]);

    return $pdo;
}

function ensure_schema(PDO $pdo): void
{
    $pdo->exec(
        "CREATE TABLE IF NOT EXISTS registros (
            id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
            nombre VARCHAR(120) NOT NULL,
            email VARCHAR(190) NOT NULL,
            servicio VARCHAR(80) NOT NULL,
            mensaje TEXT NOT NULL,
            estado VARCHAR(30) NOT NULL DEFAULT 'Nuevo',
            created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            INDEX idx_registros_created_at (created_at),
            INDEX idx_registros_servicio (servicio),
            INDEX idx_registros_estado (estado),
            INDEX idx_registros_email (email)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci"
    );
}
