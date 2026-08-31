<?php
declare(strict_types=1);
require_once appPath('servidor/config/database.php');
require_once appPath('servidor/helpers/respuesta.php');

$conexion = conectar();

try {
  $datos = json_decode(file_get_contents('php://input'), true);

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
  $costo = $datos['costo'] ?? 0.00;
  $cupoMaximo = ($datos['cupo_maximo'] ?? '') !== '' ? (int) $datos['cupo_maximo'] : null;
  $cupoDisponible = ($datos['cupo_disponible'] ?? '') !== '' ? (int) $datos['cupo_disponible'] : null;
  $descripcion = trim($datos['descripcion'] ?? '');
  $idEvento = (int) ($datos['id_evento'] ?? 0);
  $estado = trim($datos['estado'] ?? 'Activo');

  if ($nombre === '' || $fechaInicio === null || $fechaFin === null ||
      $horaInicio === null || $horaFin === null || $duracion === '' || $idEvento <= 0) {
    respuestaJson(false, 'Complete los campos obligatorios.', null, 422);
  }

  $costo = (float) $costo;

  if (!in_array($estado, ['Activo', 'Cancelado', 'Completado', 'En espera'], true)) {
    $estado = 'Activo';
  }

  $sql = "INSERT INTO actividades
          (nombre_actividad, tipo_actividad, fecha_inicio, fecha_fin, dias_semana,
           hora_inicio, hora_fin, duracion, requisitos, costo, cupo_maximo,
           cupo_disponible, descripcion, id_evento, estado, fecha_creacion, fecha_actualizacion)
          VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())";

  $stmt = $conexion->prepare($sql);
  $stmt->bind_param('sssssssssdiisis', $nombre, $tipo, $fechaInicio, $fechaFin, $diasSemana,
    $horaInicio, $horaFin, $duracion, $requisitos, $costo, $cupoMaximo,
    $cupoDisponible, $descripcion, $idEvento, $estado);
  $stmt->execute();
  $id = $conexion->insert_id;
  $stmt->close();

  respuestaJson(true, 'Actividad registrada correctamente.', ['id_actividad' => $id]);
} catch (Throwable $e) {
  error_log('Error al registrar actividad: ' . $e->getMessage());
  respuestaJson(false, 'Error al registrar la actividad.', null, 500);
} finally {
  $conexion->close();
}
