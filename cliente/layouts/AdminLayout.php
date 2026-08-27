<?php
$pageTitle = $pageTitle ?? 'Panel Administrativo';
require_once appPath('cliente/components/Sidebar.php');
require_once appPath('cliente/components/NavbarAdmin.php');

$sidebarVisible = $_SESSION['sidebar_visible'] ?? true;
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title><?= htmlspecialchars($pageTitle) ?></title>

  <!-- Bootstrap, FontAwesome, ChartJS, Export libs -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="<?= url('cliente/assets/css/sidebar.css') ?>" />
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.28/jspdf.plugin.autotable.min.js"></script>
    <?php if (!empty($pageStyles)): ?>
      <?php foreach ($pageStyles as $style): ?>
          <link
              rel="stylesheet"
              href="<?= url($style) ?>"
          >
      <?php endforeach; ?>
    <?php endif; ?>
  <style>
    .grid {
      display: grid;
      grid-template-columns: 280px 1fr;
      min-height: 100dvh;
      transition: grid-template-columns 0.3s ease;
    }

    .grid > section {
      min-width: 0;
      overflow-x: hidden;
      background: #eef1f6;
    }

    body.dark .grid > section {
      background: #0b0d0f;
    }
  </style>
</head>

<body>
  <div id="sidebarOverlay"></div>
  <main class="grid <?= $sidebarVisible ? '' : 'sidebar-hidden' ?>">
    <?= renderSidebar() ?>
    <section>
      <?= renderNavbarAdmin($sidebarVisible, $pageTitle) ?>
      <?= $content ?>
    </section>
  </main>
</body>
</html>