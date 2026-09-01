<?php
declare(strict_types=1);
require_once appPath('servidor/config/database.php');

$conexion = conectar();

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$sacramento = null;

try {
  if ($id > 0) {
    $stmt = $conexion->prepare("SELECT * FROM formulario_sacramentos WHERE id_inscripcion = ? LIMIT 1");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $sacramento = $stmt->get_result()->fetch_assoc();
    $stmt->close();
  }
} catch (Throwable $e) {
  error_log('Error al cargar sacramento: ' . $e->getMessage());
  $sacramento = null;
} finally {
  $conexion->close();
}

pagina('cliente/pages/admin/sacramentos/form.php', [
  'sacramento' => $sacramento,
]);
