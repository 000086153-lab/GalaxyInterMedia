<?php
declare(strict_types=1);

require_once dirname(__DIR__, 2) . '/app/bootstrap.php';

if (admin_is_authenticated()) {
    redirect('index.php');
}

$error = '';
$configurationMissing = (env_value('ADMIN_PASSWORD_HASH', '') ?? '') === ''
    && (env_value('ADMIN_PASSWORD', '') ?? '') === '';

if (is_post()) {
    $attempts = (int) ($_SESSION['login_attempts'] ?? 0);
    $lockedUntil = (int) ($_SESSION['login_locked_until'] ?? 0);

    if ($lockedUntil > time()) {
        $error = 'Demasiados intentos. Espera unos minutos antes de volver a intentar.';
    } elseif (!csrf_is_valid($_POST['csrf_token'] ?? null)) {
        $error = 'La sesión expiró. Recarga la página.';
    } else {
        $username = text_input('username');
        $password = text_input('password');

        if (verify_admin_credentials($username, $password)) {
            unset($_SESSION['login_attempts'], $_SESSION['login_locked_until']);
            authenticate_admin($username);
            redirect('index.php');
        }

        $attempts++;
        $_SESSION['login_attempts'] = $attempts;
        if ($attempts >= 5) {
            $_SESSION['login_locked_until'] = time() + 300;
            $_SESSION['login_attempts'] = 0;
        }
        $error = 'Usuario o contraseña incorrectos.';
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="robots" content="noindex, nofollow">
  <title>Acceso administrativo | GalaxyInterMedia</title>
  <link rel="stylesheet" href="../styles.css">
</head>
<body class="admin-body">
  <main class="login-shell">
    <section class="login-card" aria-labelledby="login-title">
      <a class="admin-brand" href="../index.php" aria-label="Volver a GalaxyInterMedia">
        <span class="brand-mark brand-fallback" aria-hidden="true">GI</span>
        <span>GalaxyInterMedia</span>
      </a>
      <span class="eyebrow">Panel privado</span>
      <h1 id="login-title">Acceso administrativo</h1>
      <p>Consulta y organiza las solicitudes recibidas desde la landing.</p>

      <?php if ($configurationMissing): ?>
        <div class="alert alert-warning" role="alert">El acceso todavía no está configurado en el servidor.</div>
      <?php endif; ?>

      <?php if ($error !== ''): ?>
        <div class="alert alert-error" role="alert"><?= e($error) ?></div>
      <?php endif; ?>

      <form method="post" class="login-form">
        <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
        <div class="field">
          <label for="username">Usuario</label>
          <input id="username" name="username" type="text" autocomplete="username" required autofocus>
        </div>
        <div class="field">
          <label for="password">Contraseña</label>
          <input id="password" name="password" type="password" autocomplete="current-password" required>
        </div>
        <button class="btn btn-primary" type="submit"<?= $configurationMissing ? ' disabled' : '' ?>>Entrar al dashboard</button>
      </form>
      <a class="back-link" href="../index.php">← Volver a la landing</a>
    </section>
  </main>
</body>
</html>
