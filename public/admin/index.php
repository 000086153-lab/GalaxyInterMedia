<?php
declare(strict_types=1);

require_once dirname(__DIR__, 2) . '/app/bootstrap.php';
require_admin();

$pdo = db();
ensure_schema($pdo);

$q = trim((string) ($_GET['q'] ?? ''));
$servicio = trim((string) ($_GET['servicio'] ?? ''));
$estado = trim((string) ($_GET['estado'] ?? ''));
$page = max(1, (int) ($_GET['page'] ?? 1));
$perPage = 20;
$notice = $_SESSION['admin_notice'] ?? null;
unset($_SESSION['admin_notice']);

if (!in_array($servicio, allowed_services(), true)) {
    $servicio = '';
}
if (!in_array($estado, allowed_statuses(), true)) {
    $estado = '';
}

if (is_post()) {
    if (!csrf_is_valid($_POST['csrf_token'] ?? null)) {
        $_SESSION['admin_notice'] = ['type' => 'error', 'message' => 'La sesión expiró. Intenta nuevamente.'];
    } else {
        $id = filter_var($_POST['id'] ?? null, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
        $newStatus = text_input('estado');

        if ($id !== false && in_array($newStatus, allowed_statuses(), true)) {
            $update = $pdo->prepare('UPDATE registros SET estado = :estado WHERE id = :id');
            $update->execute([':estado' => $newStatus, ':id' => $id]);
            $_SESSION['admin_notice'] = ['type' => 'success', 'message' => 'Estado actualizado.'];
        } else {
            $_SESSION['admin_notice'] = ['type' => 'error', 'message' => 'No fue posible actualizar ese registro.'];
        }
    }

    $returnFilters = [
        'q' => (string) ($_POST['return_q'] ?? ''),
        'servicio' => (string) ($_POST['return_servicio'] ?? ''),
        'estado' => (string) ($_POST['return_estado'] ?? ''),
        'page' => max(1, (int) ($_POST['return_page'] ?? 1)),
    ];
    redirect('index.php?' . http_build_query(array_filter($returnFilters, static fn ($value) => $value !== '')));
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
$countStatement = $pdo->prepare('SELECT COUNT(*) FROM registros' . $whereSql);
$countStatement->execute($params);
$total = (int) $countStatement->fetchColumn();
$pages = max(1, (int) ceil($total / $perPage));
$page = min($page, $pages);
$offset = ($page - 1) * $perPage;

$listStatement = $pdo->prepare(
    'SELECT id, nombre, email, servicio, mensaje, estado, created_at, updated_at '
    . 'FROM registros' . $whereSql . ' ORDER BY created_at DESC LIMIT :limit OFFSET :offset'
);
foreach ($params as $key => $value) {
    $listStatement->bindValue($key, $value, PDO::PARAM_STR);
}
$listStatement->bindValue(':limit', $perPage, PDO::PARAM_INT);
$listStatement->bindValue(':offset', $offset, PDO::PARAM_INT);
$listStatement->execute();
$records = $listStatement->fetchAll();

$metrics = $pdo->query(
    "SELECT COUNT(*) AS total,
            COALESCE(SUM(estado = 'Nuevo'), 0) AS nuevos,
            COALESCE(SUM(estado = 'En seguimiento'), 0) AS seguimiento,
            COALESCE(SUM(DATE(created_at) = CURRENT_DATE()), 0) AS hoy
     FROM registros"
)->fetch();

$exportQuery = http_build_query(array_filter([
    'q' => $q,
    'servicio' => $servicio,
    'estado' => $estado,
], static fn ($value) => $value !== ''));

function status_class(string $status): string
{
    return match ($status) {
        'Nuevo' => 'status-new',
        'Contactado' => 'status-contacted',
        'En seguimiento' => 'status-followup',
        'Cerrado' => 'status-closed',
        'Descartado' => 'status-rejected',
        default => '',
    };
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="robots" content="noindex, nofollow">
  <title>Dashboard de registros | GalaxyInterMedia</title>
  <link rel="stylesheet" href="../styles.css">
</head>
<body class="admin-body">
  <header class="admin-header">
    <div class="admin-container admin-nav">
      <a class="admin-brand" href="index.php">
        <span class="brand-mark brand-fallback" aria-hidden="true">GI</span>
        <span>GalaxyInterMedia <small>Dashboard</small></span>
      </a>
      <div class="admin-actions">
        <a class="btn btn-secondary btn-small" href="../index.php">Ver landing</a>
        <form action="logout.php" method="post" class="inline-form">
          <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
          <button class="btn btn-secondary btn-small" type="submit">Cerrar sesión</button>
        </form>
      </div>
    </div>
  </header>

  <main class="admin-container dashboard-main">
    <div class="dashboard-title">
      <div>
        <span class="eyebrow">Prospectos</span>
        <h1>Registros del formulario</h1>
        <p>Consulta, filtra y da seguimiento a las solicitudes recibidas.</p>
      </div>
      <a class="btn btn-primary" href="export.php<?= $exportQuery !== '' ? '?' . e($exportQuery) : '' ?>">Exportar CSV</a>
    </div>

    <?php if (is_array($notice)): ?>
      <div class="alert alert-<?= e((string) ($notice['type'] ?? 'info')) ?>" role="status"><?= e((string) ($notice['message'] ?? '')) ?></div>
    <?php endif; ?>

    <section class="metric-grid" aria-label="Resumen de registros">
      <article class="metric-card"><span>Total</span><strong><?= (int) ($metrics['total'] ?? 0) ?></strong></article>
      <article class="metric-card"><span>Nuevos</span><strong><?= (int) ($metrics['nuevos'] ?? 0) ?></strong></article>
      <article class="metric-card"><span>En seguimiento</span><strong><?= (int) ($metrics['seguimiento'] ?? 0) ?></strong></article>
      <article class="metric-card"><span>Recibidos hoy</span><strong><?= (int) ($metrics['hoy'] ?? 0) ?></strong></article>
    </section>

    <form method="get" class="filters" aria-label="Filtros de registros">
      <div class="field">
        <label for="q">Buscar</label>
        <input id="q" name="q" type="search" value="<?= e($q) ?>" placeholder="Nombre, correo o mensaje">
      </div>
      <div class="field">
        <label for="servicio">Servicio</label>
        <select id="servicio" name="servicio">
          <option value="">Todos</option>
          <?php foreach (allowed_services() as $option): ?>
            <option value="<?= e($option) ?>"<?= $servicio === $option ? ' selected' : '' ?>><?= e($option) ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="field">
        <label for="estado">Estado</label>
        <select id="estado" name="estado">
          <option value="">Todos</option>
          <?php foreach (allowed_statuses() as $option): ?>
            <option value="<?= e($option) ?>"<?= $estado === $option ? ' selected' : '' ?>><?= e($option) ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <button class="btn btn-primary" type="submit">Filtrar</button>
      <a class="btn btn-secondary" href="index.php">Limpiar</a>
    </form>

    <section class="table-panel" aria-labelledby="results-title">
      <div class="table-heading">
        <h2 id="results-title">Resultados</h2>
        <span><?= $total ?> registro<?= $total === 1 ? '' : 's' ?></span>
      </div>

      <?php if ($records === []): ?>
        <div class="empty-state">
          <strong>No hay registros que coincidan.</strong>
          <p>Cuando alguien complete el formulario, aparecerá aquí.</p>
        </div>
      <?php else: ?>
        <div class="table-scroll">
          <table>
            <thead>
              <tr>
                <th>ID</th>
                <th>Contacto</th>
                <th>Servicio</th>
                <th>Mensaje</th>
                <th>Fecha</th>
                <th>Estado</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($records as $record): ?>
                <tr>
                  <td>#<?= (int) $record['id'] ?></td>
                  <td>
                    <strong><?= e((string) $record['nombre']) ?></strong>
                    <a href="mailto:<?= e((string) $record['email']) ?>"><?= e((string) $record['email']) ?></a>
                  </td>
                  <td><?= e((string) $record['servicio']) ?></td>
                  <td class="message-cell">
                    <details>
                      <summary>Ver mensaje</summary>
                      <p><?= nl2br(e((string) $record['mensaje'])) ?></p>
                    </details>
                  </td>
                  <td><time datetime="<?= e((string) $record['created_at']) ?>"><?= e(date('d/m/Y H:i', strtotime((string) $record['created_at']))) ?></time></td>
                  <td>
                    <span class="status-badge <?= e(status_class((string) $record['estado'])) ?>"><?= e((string) $record['estado']) ?></span>
                    <form method="post" class="status-form">
                      <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
                      <input type="hidden" name="id" value="<?= (int) $record['id'] ?>">
                      <input type="hidden" name="return_q" value="<?= e($q) ?>">
                      <input type="hidden" name="return_servicio" value="<?= e($servicio) ?>">
                      <input type="hidden" name="return_estado" value="<?= e($estado) ?>">
                      <input type="hidden" name="return_page" value="<?= $page ?>">
                      <label class="sr-only" for="status-<?= (int) $record['id'] ?>">Cambiar estado</label>
                      <select id="status-<?= (int) $record['id'] ?>" name="estado" onchange="this.form.submit()">
                        <?php foreach (allowed_statuses() as $option): ?>
                          <option value="<?= e($option) ?>"<?= $record['estado'] === $option ? ' selected' : '' ?>><?= e($option) ?></option>
                        <?php endforeach; ?>
                      </select>
                    </form>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      <?php endif; ?>
    </section>

    <?php if ($pages > 1): ?>
      <nav class="pagination" aria-label="Paginación">
        <?php for ($i = 1; $i <= $pages; $i++): ?>
          <?php $pageQuery = http_build_query(array_filter(['q' => $q, 'servicio' => $servicio, 'estado' => $estado, 'page' => $i], static fn ($value) => $value !== '')); ?>
          <a href="?<?= e($pageQuery) ?>"<?= $i === $page ? ' aria-current="page" class="active"' : '' ?>><?= $i ?></a>
        <?php endfor; ?>
      </nav>
    <?php endif; ?>
  </main>
</body>
</html>
