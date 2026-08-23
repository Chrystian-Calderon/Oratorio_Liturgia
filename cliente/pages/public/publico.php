<?php
require_once appPath('cliente/components/Navbar.php');
ob_start();
?>
<div>
  servicios
  estadistas
  <?php renderNavbar(); ?>
</div>

<?php
$content = ob_get_clean();
require_once appPath('cliente/layouts/PublicLayout.php');