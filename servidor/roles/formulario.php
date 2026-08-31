<?php
declare(strict_types=1);
require_once appPath('servidor/config/database.php');

$conexion = conectar();

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$rol = null;

if ($id > 0) {
  $stmt = $conexion->prepare("SELECT * FROM usuarios_sistema WHERE id_usuario = ? LIMIT 1");
  $stmt->bind_param('i', $id);
  $stmt->execute();
  $rol = $stmt->get_result()->fetch_assoc();
  $stmt->close();
}

$conexion->close();

pagina('cliente/pages/admin/roles/form.php', [
  'rol' => $rol,
]);