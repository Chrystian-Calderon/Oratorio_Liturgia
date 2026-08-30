<?php
require_once appPath("servidor/config/database.php");
require_once appPath("servidor/helpers/respuesta.php");
$conexion = conectar();

try {
  $conexion->begin_transaction();

  $datos = json_decode(file_get_contents('php://input'), true);
  $id = (int) ($datos['id_usuario'] ?? 0);
  $rol = trim($datos['rol'] ?? '');
  $permisos = trim($datos['permisos'] ?? '');
  $estado = trim($datos['estado'] ?? '');
  $sql = "UPDATE usuarios_sistema SET rol=?, permisos=?, estado=?, fecha_actualizacion=NOW() WHERE id_usuario=?";

  $smtm = $conexion->prepare($sql);
  $smtm->bind_param("sssi", $rol, $permisos, $estado, $id);
  $smtm->execute();
  $smtm->close();
  $conexion->commit();
  respuestaJson(true, 'Usuario actualizado correctamente.');
} catch (Throwable $e) {
  $conexion->rollback();
  error_log("Error al actualizar el usuario: " . $e->getMessage());
  respuestaJson(false, 'Error al actualizar el usuario.');
} finally {
  $conexion->close();
}
