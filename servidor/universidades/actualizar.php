<?php
declare(strict_types=1);
require_once appPath('servidor/config/database.php');
require_once appPath('servidor/helpers/respuesta.php');

$conexion = conectar();

try {
  $datos = json_decode(file_get_contents('php://input'), true);

  $id = (int) ($datos['id_universidad'] ?? 0);
  $nombre = trim($datos['nombre'] ?? '');
  $sigla = trim($datos['sigla'] ?? '');
  $ciudad = trim($datos['ciudad'] ?? '');
  $direccion = trim($datos['direccion'] ?? '');
  $telefono = trim($datos['telefono'] ?? '');
  $correo = trim($datos['correo'] ?? '');
  $sitioWeb = trim($datos['sitio_web'] ?? '');
  $estado = trim($datos['estado'] ?? 'Activo');

  if ($id <= 0 || $nombre === '' || $ciudad === '' || $direccion === '') {
    respuestaJson(false, 'Complete los campos obligatorios (nombre, ciudad y dirección).', null, 422);
  }

  if (!in_array($estado, ['Activo', 'Inactivo'], true)) {
    $estado = 'Activo';
  }

  $stmt = $conexion->prepare("SELECT COUNT(*) AS n FROM universidades WHERE nombre = ? AND id_universidad <> ?");
  $stmt->bind_param('si', $nombre, $id);
  $stmt->execute();
  $existe = (int) $stmt->get_result()->fetch_assoc()['n'];
  $stmt->close();
  if ($existe > 0) {
    respuestaJson(false, 'Ya existe otra universidad con ese nombre.', null, 422);
  }

  $stmt = $conexion->prepare(
    "UPDATE universidades SET nombre = ?, sigla = ?, ciudad = ?, direccion = ?,
     telefono = ?, correo = ?, sitio_web = ?, estado = ? WHERE id_universidad = ?"
  );
  $stmt->bind_param('ssssssssi', $nombre, $sigla, $ciudad, $direccion, $telefono, $correo, $sitioWeb, $estado, $id);
  $stmt->execute();
  $stmt->close();

  respuestaJson(true, 'Universidad actualizada correctamente.');
} catch (Throwable $e) {
  error_log('Error al actualizar universidad: ' . $e->getMessage());
  respuestaJson(false, 'Error al actualizar la universidad.', null, 500);
} finally {
  $conexion->close();
}