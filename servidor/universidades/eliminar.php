<?php
declare(strict_types=1);
require_once appPath('servidor/config/database.php');
require_once appPath('servidor/helpers/respuesta.php');
require_once appPath('servidor/helpers/audit.php');

$conexion = conectar();
establecerAuditUser($conexion);

try {
  $datos = json_decode(file_get_contents('php://input'), true);
  $id = (int) ($datos['id_universidad'] ?? 0);

  if ($id <= 0) {
    respuestaJson(false, 'Identificador de universidad no válido.', null, 422);
  }

  $stmt = $conexion->prepare("DELETE FROM universidades WHERE id_universidad = ?");
  $stmt->bind_param('i', $id);
  $stmt->execute();
  $afectadas = $stmt->affected_rows;
  $stmt->close();

  if ($afectadas <= 0) {
    respuestaJson(false, 'No se encontró la universidad a eliminar.', null, 404);
  }

  respuestaJson(true, 'Universidad eliminada correctamente.');
} catch (mysqli_sql_exception $e) {
  error_log('Error al eliminar universidad: ' . $e->getMessage());
  $mensaje = (strpos($e->getMessage(), 'foreign key') !== false || strpos($e->getMessage(), 'constraint') !== false)
    ? 'No se puede eliminar: la universidad tiene personas asociadas.'
    : 'Error al eliminar la universidad.';
  respuestaJson(false, $mensaje, null, 500);
} catch (Throwable $e) {
  error_log('Error al eliminar universidad: ' . $e->getMessage());
  respuestaJson(false, 'Error al eliminar la universidad.', null, 500);
} finally {
  $conexion->close();
}