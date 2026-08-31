<?php
declare(strict_types=1);
require_once appPath('servidor/config/database.php');
require_once appPath('servidor/helpers/respuesta.php');

$conexion = conectar();

try {
  $datos = json_decode(file_get_contents('php://input'), true);

  $id = (int) ($datos['id_persona'] ?? 0);
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

  if ($id <= 0 || $ci === '' || $nombres === '' || $apellidos === '' || $correo === '') {
    respuestaJson(false, 'Complete los campos obligatorios (CI, nombres, apellidos y correo).', null, 422);
  }

  // Cargar persona actual (password e id_usuario)
  $stmt = $conexion->prepare("SELECT password, id_usuario FROM personas WHERE id_persona = ?");
  $stmt->bind_param('i', $id);
  $stmt->execute();
  $actual = $stmt->get_result()->fetch_assoc();
  $stmt->close();

  if (!$actual) {
    respuestaJson(false, 'No se encontró la persona.', null, 404);
  }

  // Validar CI / correo únicos (excluyendo la persona actual)
  $stmt = $conexion->prepare("SELECT COUNT(*) AS n FROM personas WHERE (ci = ? OR correo = ?) AND id_persona <> ?");
  $stmt->bind_param('ssi', $ci, $correo, $id);
  $stmt->execute();
  $existe = (int) $stmt->get_result()->fetch_assoc()['n'];
  $stmt->close();
  if ($existe > 0) {
    respuestaJson(false, 'El CI o correo ya se encuentra registrado por otra persona.', null, 422);
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

  $passwordHash = $actual['password'];
  if ($password !== '') {
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);
  }

  // Gestión del rol (usuarios_sistema)
  $idUsuario = $actual['id_usuario'] !== null ? (int) $actual['id_usuario'] : null;
  if ($rol !== '') {
    if ($idUsuario !== null) {
      // Actualizar rol del usuario existente
      $stmt = $conexion->prepare("UPDATE usuarios_sistema SET rol = ?, fecha_actualizacion = NOW() WHERE id_usuario = ?");
      $stmt->bind_param('si', $rol, $idUsuario);
      $stmt->execute();
      $stmt->close();
    } else {
      // Crear usuario de sistema con el rol elegido
      $stmt = $conexion->prepare("INSERT INTO usuarios_sistema (rol, permisos, estado, fecha_creacion, fecha_actualizacion) VALUES (?, NULL, 'Activo', NOW(), NOW())");
      $stmt->bind_param('s', $rol);
      $stmt->execute();
      $idUsuario = $conexion->insert_id;
      $stmt->close();
    }
  }

  $sql = "UPDATE personas
          SET ci = ?, nombres = ?, apellidos = ?, genero = ?, direccion = ?,
              telefono = ?, correo = ?, password = ?, tipo_persona = ?,
              id_universidad = ?, id_usuario = ?, estado = ?
          WHERE id_persona = ?";

  $stmt = $conexion->prepare($sql);
  $stmt->bind_param('sssssssssiisi', $ci, $nombres, $apellidos, $genero, $direccion, $telefono,
    $correo, $passwordHash, $tipoPersona, $idUniversidad, $idUsuario, $estado, $id);
  $stmt->execute();
  $stmt->close();

  respuestaJson(true, 'Persona actualizada correctamente.');
} catch (Throwable $e) {
  error_log('Error al actualizar persona: ' . $e->getMessage());
  respuestaJson(false, 'Error al actualizar la persona.', null, 500);
} finally {
  $conexion->close();
}