<?php
declare(strict_types=1);
require_once appPath('servidor/config/database.php');
require_once appPath('servidor/helpers/respuesta.php');
require_once appPath('servidor/helpers/audit.php');

$conexion = conectar();
establecerAuditUser($conexion);

try {
  $datos = json_decode(file_get_contents('php://input'), true);

  $id = (int) ($datos['id_inscripcion'] ?? 0);
  $idActividad = (int) ($datos['id_actividad'] ?? 0);
  $idPersona = (int) ($datos['id_persona'] ?? 0);
  $idPago = ($datos['id_pago'] ?? '') !== '' ? (int) $datos['id_pago'] : null;
  $cumpleRequisitos = trim($datos['cumple_requisitos'] ?? 'En revisión');
  $estado = trim($datos['estado'] ?? 'Pre-inscrito');
  $observaciones = trim($datos['observaciones'] ?? '');
  $asistencia = (int) ($datos['asistencia'] ?? 0);
  $calificacion = ($datos['calificacion'] ?? '') !== '' ? (int) $datos['calificacion'] : null;

  if ($id <= 0 || $idActividad <= 0 || $idPersona <= 0) {
    respuestaJson(false, 'Debe seleccionar la actividad y la persona.', null, 422);
  }

  if (!in_array($cumpleRequisitos, ['Si', 'No', 'En revisión'], true)) {
    $cumpleRequisitos = 'En revisión';
  }

  if (!in_array($estado, ['Pre-inscrito', 'Inscrito', 'En espera', 'Cancelado', 'Completado'], true)) {
    $estado = 'Pre-inscrito';
  }

  if ($calificacion !== null && ($calificacion < 0 || $calificacion > 100)) {
    $calificacion = null;
  }

  $sql = "UPDATE inscripcion
          SET id_actividad = ?, id_persona = ?, id_pago = ?,
              cumple_requisitos = ?, estado = ?, observaciones = ?,
              asistencia = ?, calificacion = ?, fecha_actualizacion = NOW()
          WHERE id_inscripcion = ?";

  $stmt = $conexion->prepare($sql);
  $stmt->bind_param('iiisssiii', $idActividad, $idPersona, $idPago,
    $cumpleRequisitos, $estado, $observaciones, $asistencia, $calificacion, $id);
  $stmt->execute();
  $afectadas = $stmt->affected_rows;
  $stmt->close();

  if ($afectadas <= 0) {
    respuestaJson(false, 'Error al actualizar la inscripción.', null, 500);
  }

  respuestaJson(true, 'Inscripción actualizada correctamente.');
} catch (Throwable $e) {
  error_log('Error al actualizar inscripción: ' . $e->getMessage());
  respuestaJson(false, 'Error al actualizar la inscripción.', null, 500);
} finally {
  $conexion->close();
}
