<?php
declare(strict_types=1);
require_once appPath('servidor/config/database.php');
require_once appPath('servidor/helpers/respuesta.php');

$conexion = conectar();

try {
  $datos = json_decode(file_get_contents('php://input'), true);
  $id = (int) ($datos['id_evento'] ?? 0);
  $nombre = trim($datos['nombre_evento'] ?? '');
  $descripcion = trim($datos['descripcion'] ?? '');
  $fecha = $datos['fecha_evento'] ?? null;
  $hora = $datos['hora_evento'] ?? null;
  $lugar = trim($datos['lugar'] ?? '');
  $estado = trim($datos['estado'] ?? 'Activo');

  if ($id <= 0 || $nombre === '') {
    respuestaJson(false, 'Datos incompletos para actualizar el evento.', null, 422);
  }

  if (!in_array($estado, ['Activo', 'Inactivo', 'Cancelado'], true)) {
    $estado = 'Activo';
  }

  $sql = "UPDATE eventos
          SET nombre_evento = ?, descripcion = ?, fecha_evento = ?, hora_evento = ?, estado = ?, lugar = ?, fecha_actualizacion = NOW()
          WHERE id_evento = ?";
  $stmt = $conexion->prepare($sql);
  $stmt->bind_param('sssssss', $nombre, $descripcion, $fecha, $hora, $estado, $lugar, $id);
  $stmt->execute();
  $afectadas = $stmt->affected_rows;
  $stmt->close();

  if ($afectadas < 0) {
    respuestaJson(false, 'Error al actualizar el evento.', null, 500);
  }

  respuestaJson(true, 'Evento actualizado correctamente.');
} catch (Throwable $e) {
  error_log('Error al actualizar evento: ' . $e->getMessage());
  respuestaJson(false, 'Error al actualizar el evento.', null, 500);
} finally {
  $conexion->close();
}
