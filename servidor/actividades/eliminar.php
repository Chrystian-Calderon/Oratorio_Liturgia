<?php
declare(strict_types=1);
require_once appPath('servidor/config/database.php');
require_once appPath('servidor/helpers/respuesta.php');

$conexion = conectar();

try {
  $datos = json_decode(file_get_contents('php://input'), true);
  $id = (int) ($datos['id_actividad'] ?? 0);

  if ($id <= 0) {
    respuestaJson(false, 'Identificador de actividad no válido.', null, 422);
  }

  $sql = "DELETE FROM actividades WHERE id_actividad = ?";
  $stmt = $conexion->prepare($sql);
  $stmt->bind_param('i', $id);
  $stmt->execute();
  $afectadas = $stmt->affected_rows;
  $stmt->close();

  if ($afectadas <= 0) {
    respuestaJson(false, 'No se encontró la actividad a eliminar.', null, 404);
  }

  respuestaJson(true, 'Actividad eliminada correctamente.');
} catch (Throwable $e) {
  error_log('Error al eliminar actividad: ' . $e->getMessage());
  respuestaJson(false, 'Error al eliminar la actividad.', null, 500);
} finally {
  $conexion->close();
}
