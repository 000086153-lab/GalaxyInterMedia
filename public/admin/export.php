<?php
declare(strict_types=1);

require_once dirname(__DIR__, 2) . '/app/bootstrap.php';
require_admin();

$pdo = db();
ensure_schema($pdo);

$q = trim((string) ($_GET['q'] ?? ''));
$servicio = trim((string) ($_GET['servicio'] ?? ''));
$estado = trim((string) ($_GET['estado'] ?? ''));

if (!in_array($servicio, allowed_services(), true)) {
    $servicio = '';
}
if (!in_array($estado, allowed_statuses(), true)) {
    $estado = '';
}

$where = [];
$params = [];
if ($q !== '') {
    $where[] = '(nombre LIKE :q_nombre OR email LIKE :q_email OR mensaje LIKE :q_mensaje)';
    $search = '%' . mb_substr($q, 0, 120) . '%';
    $params[':q_nombre'] = $search;
    $params[':q_email'] = $search;
    $params[':q_mensaje'] = $search;
}
if ($servicio !== '') {
    $where[] = 'servicio = :servicio';
    $params[':servicio'] = $servicio;
}
if ($estado !== '') {
    $where[] = 'estado = :estado';
    $params[':estado'] = $estado;
}

$whereSql = $where === [] ? '' : ' WHERE ' . implode(' AND ', $where);
$statement = $pdo->prepare(
    'SELECT id, nombre, email, servicio, mensaje, estado, created_at, updated_at '
    . 'FROM registros' . $whereSql . ' ORDER BY created_at DESC'
);
$statement->execute($params);

$filename = 'registros-galaxyintermedia-' . date('Y-m-d-His') . '.csv';
header('Content-Type: text/csv; charset=UTF-8');
header('Content-Disposition: attachment; filename="' . $filename . '"');
header('Cache-Control: no-store, no-cache, must-revalidate');

$output = fopen('php://output', 'wb');
if ($output === false) {
    http_response_code(500);
    exit;
}

fwrite($output, "\xEF\xBB\xBF");
fputcsv($output, ['ID', 'Nombre', 'Correo', 'Servicio', 'Mensaje', 'Estado', 'Fecha de registro', 'Última actualización']);

while ($record = $statement->fetch()) {
    fputcsv($output, [
        (string) $record['id'],
        csv_safe((string) $record['nombre']),
        csv_safe((string) $record['email']),
        csv_safe((string) $record['servicio']),
        csv_safe((string) $record['mensaje']),
        csv_safe((string) $record['estado']),
        (string) $record['created_at'],
        (string) $record['updated_at'],
    ]);
}

fclose($output);
exit;

