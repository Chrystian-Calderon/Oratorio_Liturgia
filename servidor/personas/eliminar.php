<?php
declare(strict_types=1);
require_once appPath('servidor/config/database.php');
require_once appPath('servidor/helpers/respuesta.php');
require_once appPath('servidor/helpers/audit.php');

$conexion = conectar();
establecerAuditUser($conexion);

try {
  $datos = json_decode(file_get_contents('php://input'), true);
  $id = (int) ($datos['id_persona'] ?? 0);

  if ($id <= 0) {
    respuestaJson(false, 'Identificador de persona no válido.', null, 422);
  }

  $sql = "DELETE FROM personas WHERE id_persona = ?";
  $stmt = $conexion->prepare($sql);
  $stmt->bind_param('i', $id);
  $stmt->execute();
  $afectadas = $stmt->affected_rows;
  $stmt->close();

  if ($afectadas <= 0) {
    respuestaJson(false, 'No se encontró la persona a eliminar.', null, 404);
  }

  respuestaJson(true, 'Persona eliminada correctamente.');
} catch (mysqli_sql_exception $e) {
  error_log('Error al eliminar persona: ' . $e->getMessage());
  $mensaje = (strpos($e->getMessage(), 'foreign key') !== false || strpos($e->getMessage(), 'constraint') !== false)
    ? 'No se puede eliminar: la persona tiene registros relacionados (inscripciones, pagos, etc.).'
    : 'Error al eliminar la persona.';
  respuestaJson(false, $mensaje, null, 500);
} catch (Throwable $e) {
  error_log('Error al eliminar persona: ' . $e->getMessage());
  respuestaJson(false, 'Error al eliminar la persona.', null, 500);
} finally {
  $conexion->close();
}