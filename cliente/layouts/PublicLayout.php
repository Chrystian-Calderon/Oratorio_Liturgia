<?php
$pageTitle = $pageTitle ?? 'Oratorio y Liturgia';
require_once appPath('cliente/components/Navbar.php');
require_once appPath('cliente/components/footer/FooterIndex.php');
require_once appPath('cliente/components/footer/FooterPublic.php');
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <title>
      <?= htmlspecialchars($pageTitle) ?>
    </title>
    <link rel="shortcut icon" href="../assets/img/logo.jpg">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <!-- Font Awesome (para íconos de redes sociales) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="<?= url('cliente/assets/css/navbar.css') ?>">
    <?php if (!empty($pageStyles)): ?>
      <?php foreach ($pageStyles as $style): ?>
          <link
              rel="stylesheet"
              href="<?= url($style) ?>"
          >
      <?php endforeach; ?>
    <?php endif; ?>
</head>

<body>
  <?php renderNavbar(); ?>
  <main>
    <?= $content ?>
  </main>
  <?php
    $ruta = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    if ($ruta === '/inicio' || $ruta === '/') {
        renderFooterIndex();
    } else {
      if ($ruta !== '/contacto' && $ruta !== '/calendario') {
          renderFooterPublic();
      }
    }
  ?>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
  <script src="<?= url('cliente/assets/js/navbar.js') ?>"></script>
</body>
</html>