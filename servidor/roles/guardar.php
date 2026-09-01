<?php
declare(strict_types=1);
require_once appPath('servidor/config/database.php');
require_once appPath('servidor/helpers/respuesta.php');
require_once appPath('servidor/helpers/audit.php');

$conexion = conectar();
establecerAuditUser($conexion);

try {
  $datos = json_decode(file_get_contents('php://input'), true);

  $rol = trim($datos['rol'] ?? '');
  $permisos = trim($datos['permisos'] ?? '');
  $estado = trim($datos['estado'] ?? 'Activo');

  $rolesValidos = ['Administrador', 'Coordinador', 'Estudiante', 'Docente', 'Voluntario', 'Sacerdote', 'Externo'];
  $estadosValidos = ['Activo', 'Inactivo', 'Suspendido'];

  if (!in_array($rol, $rolesValidos, true)) {
    respuestaJson(false, 'Seleccione un rol válido.', null, 422);
  }
  if (!in_array($estado, $estadosValidos, true)) {
    $estado = 'Activo';
  }

  $stmt = $conexion->prepare(
    "INSERT INTO usuarios_sistema (rol, permisos, estado, fecha_creacion, fecha_actualizacion)
     VALUES (?, ?, ?, NOW(), NOW())"
  );
  $stmt->bind_param('sss', $rol, $permisos, $estado);
  $stmt->execute();
  $id = $conexion->insert_id;
  $stmt->close();

  respuestaJson(true, 'Rol registrado correctamente.', ['id_usuario' => $id]);
} catch (Throwable $e) {
  error_log('Error al registrar rol: ' . $e->getMessage());
  respuestaJson(false, 'Error al registrar el rol.', null, 500);
} finally {
  $conexion->close();
}