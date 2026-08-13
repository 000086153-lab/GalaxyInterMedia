<?php
declare(strict_types=1);

require_once dirname(__DIR__) . '/app/bootstrap.php';

if (!is_post()) {
    http_response_code(405);
    header('Allow: POST');
    exit('Método no permitido.');
}

if (!csrf_is_valid($_POST['csrf_token'] ?? null)) {
    if (wants_json()) {
        json_response(['ok' => false, 'message' => 'La sesión expiró. Recarga la página e inténtalo de nuevo.'], 419);
    }
    flash_form('error', 'La sesión expiró. Recarga la página e inténtalo de nuevo.');
    redirect('index.php#contacto');
}

if (text_input('website') !== '') {
    if (wants_json()) {
        json_response(['ok' => true, 'message' => 'Solicitud recibida.'], 201);
    }
    flash_form('success', 'Solicitud recibida.');
    redirect('index.php#contacto');
}

$lastSubmission = (int) ($_SESSION['last_form_submission'] ?? 0);
if ($lastSubmission > 0 && time() - $lastSubmission < 5) {
    if (wants_json()) {
        json_response(['ok' => false, 'message' => 'Espera unos segundos antes de enviar otra solicitud.'], 429);
    }
    flash_form('error', 'Espera unos segundos antes de enviar otra solicitud.');
    redirect('index.php#contacto');
}

$nombre = text_input('nombre');
$email = text_input('email');
$servicio = text_input('servicio');
$mensaje = text_input('mensaje');
$errors = [];

if (mb_strlen($nombre) < 2 || mb_strlen($nombre) > 120) {
    $errors['nombre'] = 'Escribe un nombre de entre 2 y 120 caracteres.';
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL) || mb_strlen($email) > 190) {
    $errors['email'] = 'Escribe un correo electrónico válido.';
}

if (!in_array($servicio, allowed_services(), true)) {
    $errors['servicio'] = 'Selecciona un servicio válido.';
}

if (mb_strlen($mensaje) < 15 || mb_strlen($mensaje) > 3000) {
    $errors['mensaje'] = 'Describe tu proyecto con entre 15 y 3000 caracteres.';
}

if ($errors !== []) {
    if (wants_json()) {
        json_response([
            'ok' => false,
            'message' => 'Revisa los campos marcados antes de continuar.',
            'errors' => $errors,
        ], 422);
    }
    flash_form('error', 'Revisa los datos del formulario e inténtalo de nuevo.');
    redirect('index.php#contacto');
}

try {
    $pdo = db();
    ensure_schema($pdo);

    $statement = $pdo->prepare(
        'INSERT INTO registros (nombre, email, servicio, mensaje) VALUES (:nombre, :email, :servicio, :mensaje)'
    );
    $statement->execute([
        ':nombre' => $nombre,
        ':email' => strtolower($email),
        ':servicio' => $servicio,
        ':mensaje' => $mensaje,
    ]);

    $_SESSION['last_form_submission'] = time();

    if (wants_json()) {
        json_response([
            'ok' => true,
            'message' => '¡Solicitud enviada! Revisaremos tu proyecto y nos pondremos en contacto contigo.',
        ], 201);
    }

    flash_form('success', '¡Solicitud enviada! Revisaremos tu proyecto y nos pondremos en contacto contigo.');
    redirect('index.php#contacto');
} catch (Throwable $exception) {
    error_log('No fue posible guardar el registro: ' . $exception->getMessage());

    if (wants_json()) {
        json_response([
            'ok' => false,
            'message' => 'No pudimos guardar tu solicitud en este momento. Inténtalo nuevamente más tarde.',
        ], 503);
    }

    flash_form('error', 'No pudimos guardar tu solicitud en este momento. Inténtalo nuevamente más tarde.');
    redirect('index.php#contacto');
}
