<?php
declare(strict_types=1);
require_once appPath('servidor/config/database.php');
require_once appPath('servidor/helpers/respuesta.php');
require_once appPath('servidor/helpers/audit.php');

$conexion = conectar();
establecerAuditUser($conexion);

try {
  $datos = json_decode(file_get_contents('php://input'), true);

  $ci = trim($datos['ci'] ?? '');
  $nombres = trim($datos['nombres'] ?? '');
  $apellidos = trim($datos['apellidos'] ?? '');
  $genero = trim($datos['genero'] ?? '');
  $direccion = trim($datos['direccion'] ?? '');
  $telefono = trim($datos['telefono'] ?? '');
  $correo = trim($datos['correo'] ?? '');
  $password = $datos['password'] ?? '';
  $tipoPersona = trim($datos['tipo_persona'] ?? 'Estudiante');
  $idUniversidad = ($datos['id_universidad'] ?? '') !== '' ? (int) $datos['id_universidad'] : null;
  $rol = trim($datos['rol'] ?? '');
  $estado = trim($datos['estado'] ?? 'Activo');

  if ($ci === '' || $nombres === '' || $apellidos === '' || $correo === '' || $password === '') {
    respuestaJson(false, 'Complete los campos obligatorios (CI, nombres, apellidos, correo y contraseña).', null, 422);
  }

  $tiposPersona = ['Estudiante', 'Docente', 'Voluntario', 'Sacerdote', 'Administrativo', 'Externo'];
  $estadosValidos = ['Activo', 'Inactivo', 'Suspendido'];
  $generosValidos = ['Masculino', 'Femenino', 'Otro', 'Prefiero no decir'];

  if (!in_array($tipoPersona, $tiposPersona, true)) {
    $tipoPersona = 'Estudiante';
  }
  if (!in_array($estado, $estadosValidos, true)) {
    $estado = 'Activo';
  }
  if ($genero !== '' && !in_array($genero, $generosValidos, true)) {
    $genero = '';
  }

  // Validar CI / correo únicos
  $stmt = $conexion->prepare("SELECT COUNT(*) AS n FROM personas WHERE ci = ? OR correo = ?");
  $stmt->bind_param('ss', $ci, $correo);
  $stmt->execute();
  $existe = (int) $stmt->get_result()->fetch_assoc()['n'];
  $stmt->close();
  if ($existe > 0) {
    respuestaJson(false, 'El CI o correo ya se encuentra registrado.', null, 422);
  }

  $passwordHash = password_hash($password, PASSWORD_DEFAULT);

  // Crear usuarios_sistema si se eligió rol
  $idUsuario = null;
  if ($rol !== '') {
    $stmt = $conexion->prepare("INSERT INTO usuarios_sistema (rol, permisos, estado, fecha_creacion, fecha_actualizacion) VALUES (?, NULL, 'Activo', NOW(), NOW())");
    $stmt->bind_param('s', $rol);
    $stmt->execute();
    $idUsuario = $conexion->insert_id;
    $stmt->close();
  }

  $sql = "INSERT INTO personas
          (ci, nombres, apellidos, genero, direccion, telefono, correo, password,
           tipo_persona, fecha_registro, id_universidad, id_usuario, estado)
          VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), ?, ?, ?)";

  $stmt = $conexion->prepare($sql);
  $stmt->bind_param('sssssssssiis', $ci, $nombres, $apellidos, $genero, $direccion, $telefono,
    $correo, $passwordHash, $tipoPersona, $idUniversidad, $idUsuario, $estado);
  $stmt->execute();
  $id = $conexion->insert_id;
  $stmt->close();

  respuestaJson(true, 'Persona registrada correctamente.', ['id_persona' => $id]);
} catch (Throwable $e) {
  error_log('Error al registrar persona: ' . $e->getMessage());
  respuestaJson(false, 'Error al registrar la persona.', null, 500);
} finally {
  $conexion->close();
}