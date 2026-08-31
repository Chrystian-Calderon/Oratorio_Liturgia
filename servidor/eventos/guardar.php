<?php
declare(strict_types=1);
require_once appPath('servidor/config/database.php');
require_once appPath('servidor/helpers/respuesta.php');

$conexion = conectar();

try {
  $datos = json_decode(file_get_contents('php://input'), true);
  $nombre = trim($datos['nombre_evento'] ?? '');
  $descripcion = trim($datos['descripcion'] ?? '');
  $fecha = $datos['fecha_evento'] ?? null;
  $hora = $datos['hora_evento'] ?? null;
  $lugar = trim($datos['lugar'] ?? '');
  $estado = trim($datos['estado'] ?? 'Activo');

  if ($nombre === '') {
    respuestaJson(false, 'El nombre del evento es obligatorio.', null, 422);
  }

  if (!in_array($estado, ['Activo', 'Inactivo', 'Cancelado'], true)) {
    $estado = 'Activo';
  }

  $sql = "INSERT INTO eventos (nombre_evento, descripcion, fecha_evento, hora_evento, lugar, estado, fecha_creacion, fecha_actualizacion)
          VALUES (?, ?, ?, ?, ?, ?, NOW(), NOW())";
  $stmt = $conexion->prepare($sql);
  $stmt->bind_param('ssssss', $nombre, $descripcion, $fecha, $hora, $lugar, $estado);
  $stmt->execute();
  $id = $conexion->insert_id;
  $stmt->close();

  respuestaJson(true, 'Evento registrado correctamente.', ['id_evento' => $id]);
} catch (Throwable $e) {
  error_log('Error al registrar evento: ' . $e->getMessage());
  respuestaJson(false, 'Error al registrar el evento.', null, 500);
} finally {
  $conexion->close();
}
