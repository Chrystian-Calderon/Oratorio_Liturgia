<?php
declare(strict_types=1);
require_once appPath('servidor/config/database.php');
require_once appPath('servidor/helpers/respuesta.php');

$conexion = conectar();

try {
  $datos = json_decode(file_get_contents('php://input'), true);

  $id = (int) ($datos['id_usuario'] ?? 0);
  $rol = trim($datos['rol'] ?? '');
  $permisos = trim($datos['permisos'] ?? '');
  $estado = trim($datos['estado'] ?? 'Activo');

  $rolesValidos = ['Administrador', 'Coordinador', 'Estudiante', 'Docente', 'Voluntario', 'Sacerdote', 'Externo'];
  $estadosValidos = ['Activo', 'Inactivo', 'Suspendido'];

  if ($id <= 0) {
    respuestaJson(false, 'Identificador de rol no válido.', null, 422);
  }
  if (!in_array($rol, $rolesValidos, true)) {
    respuestaJson(false, 'Seleccione un rol válido.', null, 422);
  }
  if (!in_array($estado, $estadosValidos, true)) {
    $estado = 'Activo';
  }

  $stmt = $conexion->prepare(
    "UPDATE usuarios_sistema SET rol = ?, permisos = ?, estado = ?, fecha_actualizacion = NOW() WHERE id_usuario = ?"
  );
  $stmt->bind_param('sssi', $rol, $permisos, $estado, $id);
  $stmt->execute();
  $stmt->close();

  respuestaJson(true, 'Rol actualizado correctamente.');
} catch (Throwable $e) {
  error_log('Error al actualizar rol: ' . $e->getMessage());
  respuestaJson(false, 'Error al actualizar el rol.', null, 500);
} finally {
  $conexion->close();
}