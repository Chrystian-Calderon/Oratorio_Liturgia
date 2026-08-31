<?php
declare(strict_types=1);
require_once appPath('servidor/config/database.php');

$conexion = conectar();

$roles = $conexion->query(
  "SELECT * FROM usuarios_sistema ORDER BY id_usuario ASC"
)->fetch_all(MYSQLI_ASSOC);

$conexion->close();

pagina('cliente/pages/admin/roles/index.php', [
  'roles' => $roles,
]);