<?php
declare(strict_types=1);
require_once appPath('servidor/config/database.php');
require_once appPath('servidor/helpers/respuesta.php');

$conexion = conectar();

try {
  $datos = json_decode(file_get_contents('php://input'), true);

  $sacramento = trim($datos['sacramento'] ?? '');
  $nombreSolicitante = trim($datos['nombre_solicitante'] ?? '');
  $fechaNacimiento = trim($datos['fecha_nacimiento'] ?? '');
  $lugarNacimiento = trim($datos['lugar_nacimiento'] ?? '');
  $nombrePadre = trim($datos['nombre_padre'] ?? '');
  $nombreMadre = trim($datos['nombre_madre'] ?? '');
  $nombrePadrino = trim($datos['nombre_padrino'] ?? '');
  $nombreMadrina = trim($datos['nombre_madrina'] ?? '');
  $telefono = trim($datos['telefono'] ?? '');
  $email = trim($datos['email'] ?? '');

  if ($sacramento === '' || $nombreSolicitante === '' || $fechaNacimiento === '' ||
      $lugarNacimiento === '' || $telefono === '' || $email === '') {
    respuestaJson(false, 'Complete los campos obligatorios (sacramento, solicitante, fecha y lugar de nacimiento, teléfono y correo).', null, 422);
  }

  $stmt = $conexion->prepare(
    "INSERT INTO formulario_sacramentos
     (sacramento, nombre_solicitante, fecha_nacimiento, lugar_nacimiento,
      nombre_padre, nombre_madre, nombre_padrino, nombre_madrina, telefono, email)
     VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
  );
  $stmt->bind_param('ssssssssss', $sacramento, $nombreSolicitante, $fechaNacimiento, $lugarNacimiento,
    $nombrePadre, $nombreMadre, $nombrePadrino, $nombreMadrina, $telefono, $email);
  $stmt->execute();
  $id = $conexion->insert_id;
  $stmt->close();

  respuestaJson(true, 'Inscripción sacramental registrada correctamente.', ['id_inscripcion' => $id]);
} catch (Throwable $e) {
  error_log('Error al registrar sacramento: ' . $e->getMessage());
  respuestaJson(false, 'Error al registrar la inscripción.', null, 500);
} finally {
  $conexion->close();
}