<?php
declare(strict_types=1);
require_once appPath('servidor/config/database.php');
require_once appPath('servidor/helpers/respuesta.php');
require_once appPath('servidor/helpers/audit.php');

$conexion = conectar();
establecerAuditUser($conexion);

try {
  $datos = json_decode(file_get_contents('php://input'), true);

  $id = (int) ($datos['id_actividad'] ?? 0);
  $nombre = trim($datos['nombre_actividad'] ?? '');
  $tipo = trim($datos['tipo_actividad'] ?? '');
  $fechaInicio = $datos['fecha_inicio'] ?? null;
  $fechaFin = $datos['fecha_fin'] ?? null;
  $diasSemana = is_array($datos['dias_semana'] ?? null)
    ? implode(',', array_map('trim', $datos['dias_semana']))
    : '';
  $horaInicio = $datos['hora_inicio'] ?? null;
  $horaFin = $datos['hora_fin'] ?? null;
  $duracion = trim($datos['duracion'] ?? '');
  $requisitos = trim($datos['requisitos'] ?? '');
  $costo = (float) ($datos['costo'] ?? 0.00);
  $cupoMaximo = ($datos['cupo_maximo'] ?? '') !== '' ? (int) $datos['cupo_maximo'] : null;
  $cupoDisponible = ($datos['cupo_disponible'] ?? '') !== '' ? (int) $datos['cupo_disponible'] : null;
  $descripcion = trim($datos['descripcion'] ?? '');
  $idEvento = (int) ($datos['id_evento'] ?? 0);
  $estado = trim($datos['estado'] ?? 'Activo');

  if ($id <= 0 || $nombre === '' || $fechaInicio === null || $fechaFin === null ||
      $horaInicio === null || $horaFin === null || $duracion === '' || $idEvento <= 0) {
    respuestaJson(false, 'Complete los campos obligatorios.', null, 422);
  }

  if (!in_array($estado, ['Activo', 'Cancelado', 'Completado', 'En espera'], true)) {
    $estado = 'Activo';
  }

  $sql = "UPDATE actividades
          SET nombre_actividad = ?, tipo_actividad = ?, fecha_inicio = ?, fecha_fin = ?,
              dias_semana = ?, hora_inicio = ?, hora_fin = ?, duracion = ?, requisitos = ?,
              costo = ?, cupo_maximo = ?, cupo_disponible = ?, descripcion = ?,
              id_evento = ?, estado = ?, fecha_actualizacion = NOW()
          WHERE id_actividad = ?";

  $stmt = $conexion->prepare($sql);
  $stmt->bind_param('sssssssssdiisisi', $nombre, $tipo, $fechaInicio, $fechaFin, $diasSemana,
    $horaInicio, $horaFin, $duracion, $requisitos, $costo, $cupoMaximo,
    $cupoDisponible, $descripcion, $idEvento, $estado, $id);
  $stmt->execute();
  $stmt->close();

  respuestaJson(true, 'Actividad actualizada correctamente.');
} catch (Throwable $e) {
  error_log('Error al actualizar actividad: ' . $e->getMessage());
  respuestaJson(false, 'Error al actualizar la actividad.', null, 500);
} finally {
  $conexion->close();
}
