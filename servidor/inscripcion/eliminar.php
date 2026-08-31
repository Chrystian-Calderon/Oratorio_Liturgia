<?php
declare(strict_types=1);
require_once appPath('servidor/config/database.php');
require_once appPath('servidor/helpers/respuesta.php');

$conexion = conectar();

try {
  $datos = json_decode(file_get_contents('php://input'), true);
  $id = (int) ($datos['id_inscripcion'] ?? 0);

  if ($id <= 0) {
    respuestaJson(false, 'Identificador de inscripción no válido.', null, 422);
  }

  $sql = "DELETE FROM inscripcion WHERE id_inscripcion = ?";
  $stmt = $conexion->prepare($sql);
  $stmt->bind_param('i', $id);
  $stmt->execute();
  $afectadas = $stmt->affected_rows;
  $stmt->close();

  if ($afectadas <= 0) {
    respuestaJson(false, 'No se encontró la inscripción a eliminar.', null, 404);
  }

  respuestaJson(true, 'Inscripción eliminada correctamente.');
} catch (Throwable $e) {
  error_log('Error al eliminar inscripción: ' . $e->getMessage());
  respuestaJson(false, 'Error al eliminar la inscripción.', null, 500);
} finally {
  $conexion->close();
}
