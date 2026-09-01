<?php
declare(strict_types=1);
require_once appPath('servidor/config/database.php');
require_once appPath('servidor/helpers/respuesta.php');
require_once appPath('servidor/helpers/audit.php');

$conexion = conectar();
establecerAuditUser($conexion);

try {
  $datos = json_decode(file_get_contents('php://input'), true);
  $id = (int) ($datos['id_usuario'] ?? 0);

  if ($id <= 0) {
    respuestaJson(false, 'Identificador de rol no válido.', null, 422);
  }

  $stmt = $conexion->prepare("DELETE FROM usuarios_sistema WHERE id_usuario = ?");
  $stmt->bind_param('i', $id);
  $stmt->execute();
  $afectadas = $stmt->affected_rows;
  $stmt->close();

  if ($afectadas <= 0) {
    respuestaJson(false, 'No se encontró el rol a eliminar.', null, 404);
  }

  respuestaJson(true, 'Rol eliminado correctamente.');
} catch (mysqli_sql_exception $e) {
  error_log('Error al eliminar rol: ' . $e->getMessage());
  $mensaje = (strpos($e->getMessage(), 'foreign key') !== false || strpos($e->getMessage(), 'constraint') !== false)
    ? 'No se puede eliminar: el rol está siendo usado por una o más personas.'
    : 'Error al eliminar el rol.';
  respuestaJson(false, $mensaje, null, 500);
} catch (Throwable $e) {
  error_log('Error al eliminar rol: ' . $e->getMessage());
  respuestaJson(false, 'Error al eliminar el rol.', null, 500);
} finally {
  $conexion->close();
}