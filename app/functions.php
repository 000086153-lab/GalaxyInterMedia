<?php
declare(strict_types=1);

function env_value(string $key, ?string $default = null): ?string
{
    $value = getenv($key);
    return $value === false || $value === '' ? $default : $value;
}

function e(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function is_post(): bool
{
    return ($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST';
}

function wants_json(): bool
{
    return str_contains(strtolower($_SERVER['HTTP_ACCEPT'] ?? ''), 'application/json');
}

function json_response(array $payload, int $status = 200): never
{
    http_response_code($status);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}

function redirect(string $path): never
{
    header('Location: ' . $path, true, 303);
    exit;
}

function allowed_services(): array
{
    return [
        'Producción de contenido',
        'Desarrollo de marca',
        'Marketing digital',
        'Proyecto integral',
    ];
}

function allowed_statuses(): array
{
    return ['Nuevo', 'Contactado', 'En seguimiento', 'Cerrado', 'Descartado'];
}

function text_input(string $key): string
{
    $value = $_POST[$key] ?? '';
    return is_string($value) ? trim($value) : '';
}

function flash_form(string $type, string $message): void
{
    $_SESSION['form_flash'] = ['type' => $type, 'message' => $message];
}

function csv_safe(string $value): string
{
    return preg_match('/^[=+\-@]/', $value) === 1 ? "'" . $value : $value;
}

