<?php
declare(strict_types=1);
require_once appPath('servidor/config/database.php');

$conexion = conectar();

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$universidad = null;

if ($id > 0) {
  $stmt = $conexion->prepare("SELECT * FROM universidades WHERE id_universidad = ? LIMIT 1");
  $stmt->bind_param('i', $id);
  $stmt->execute();
  $universidad = $stmt->get_result()->fetch_assoc();
  $stmt->close();
}

$conexion->close();

pagina('cliente/pages/admin/universidades/form.php', [
  'universidad' => $universidad,
]);