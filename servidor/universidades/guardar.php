<?php
declare(strict_types=1);
require_once appPath('servidor/config/database.php');
require_once appPath('servidor/helpers/respuesta.php');
require_once appPath('servidor/helpers/audit.php');

$conexion = conectar();
establecerAuditUser($conexion);

try {
  $datos = json_decode(file_get_contents('php://input'), true);

  $nombre = trim($datos['nombre'] ?? '');
  $sigla = trim($datos['sigla'] ?? '');
  $ciudad = trim($datos['ciudad'] ?? '');
  $direccion = trim($datos['direccion'] ?? '');
  $telefono = trim($datos['telefono'] ?? '');
  $correo = trim($datos['correo'] ?? '');
  $sitioWeb = trim($datos['sitio_web'] ?? '');
  $estado = trim($datos['estado'] ?? 'Activo');

  if ($nombre === '' || $ciudad === '' || $direccion === '') {
    respuestaJson(false, 'Complete los campos obligatorios (nombre, ciudad y dirección).', null, 422);
  }

  if (!in_array($estado, ['Activo', 'Inactivo'], true)) {
    $estado = 'Activo';
  }

  $stmt = $conexion->prepare("SELECT COUNT(*) AS n FROM universidades WHERE nombre = ?");
  $stmt->bind_param('s', $nombre);
  $stmt->execute();
  $existe = (int) $stmt->get_result()->fetch_assoc()['n'];
  $stmt->close();
  if ($existe > 0) {
    respuestaJson(false, 'Ya existe una universidad con ese nombre.', null, 422);
  }

  $stmt = $conexion->prepare(
    "INSERT INTO universidades (nombre, sigla, ciudad, direccion, telefono, correo, sitio_web, estado)
     VALUES (?, ?, ?, ?, ?, ?, ?, ?)"
  );
  $stmt->bind_param('ssssssss', $nombre, $sigla, $ciudad, $direccion, $telefono, $correo, $sitioWeb, $estado);
  $stmt->execute();
  $id = $conexion->insert_id;
  $stmt->close();

  respuestaJson(true, 'Universidad registrada correctamente.', ['id_universidad' => $id]);
} catch (Throwable $e) {
  error_log('Error al registrar universidad: ' . $e->getMessage());
  respuestaJson(false, 'Error al registrar la universidad.', null, 500);
} finally {
  $conexion->close();
}