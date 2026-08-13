<?php
declare(strict_types=1);

function admin_is_authenticated(): bool
{
    return ($_SESSION['admin_authenticated'] ?? false) === true;
}

function verify_admin_credentials(string $username, string $password): bool
{
    $expectedUser = env_value('ADMIN_USER', env_value('ADMIN_EMAIL', 'admin')) ?? 'admin';
    $passwordHash = env_value('ADMIN_PASSWORD_HASH', '') ?? '';
    $plainPassword = env_value('ADMIN_PASSWORD', '') ?? '';

    if (!hash_equals($expectedUser, $username)) {
        return false;
    }

    if ($passwordHash !== '') {
        return password_verify($password, $passwordHash);
    }

    return $plainPassword !== '' && hash_equals($plainPassword, $password);
}

function authenticate_admin(string $username): void
{
    session_regenerate_id(true);
    $_SESSION['admin_authenticated'] = true;
    $_SESSION['admin_user'] = $username;
    $_SESSION['admin_last_activity'] = time();
}

function require_admin(): void
{
    $lastActivity = (int) ($_SESSION['admin_last_activity'] ?? 0);
    $expired = $lastActivity > 0 && time() - $lastActivity > 7200;

    if (!admin_is_authenticated() || $expired) {
        logout_admin();
        redirect('login.php');
    }

    $_SESSION['admin_last_activity'] = time();
}

function logout_admin(): void
{
    unset(
        $_SESSION['admin_authenticated'],
        $_SESSION['admin_user'],
        $_SESSION['admin_last_activity']
    );
    session_regenerate_id(true);
}
