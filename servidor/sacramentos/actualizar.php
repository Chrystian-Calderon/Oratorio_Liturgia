<?php
declare(strict_types=1);
require_once appPath('servidor/config/database.php');
require_once appPath('servidor/helpers/respuesta.php');

$conexion = conectar();

try {
  $datos = json_decode(file_get_contents('php://input'), true);

  $id = (int) ($datos['id_inscripcion'] ?? 0);
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

  if ($id <= 0 || $sacramento === '' || $nombreSolicitante === '' || $fechaNacimiento === '' ||
      $lugarNacimiento === '' || $telefono === '' || $email === '') {
    respuestaJson(false, 'Complete los campos obligatorios (sacramento, solicitante, fecha y lugar de nacimiento, teléfono y correo).', null, 422);
  }

  $stmt = $conexion->prepare(
    "UPDATE formulario_sacramentos SET sacramento = ?, nombre_solicitante = ?, fecha_nacimiento = ?,
     lugar_nacimiento = ?, nombre_padre = ?, nombre_madre = ?, nombre_padrino = ?, nombre_madrina = ?,
     telefono = ?, email = ? WHERE id_inscripcion = ?"
  );
  $stmt->bind_param('ssssssssssi', $sacramento, $nombreSolicitante, $fechaNacimiento, $lugarNacimiento,
    $nombrePadre, $nombreMadre, $nombrePadrino, $nombreMadrina, $telefono, $email, $id);
  $stmt->execute();
  $stmt->close();

  respuestaJson(true, 'Inscripción sacramental actualizada correctamente.');
} catch (Throwable $e) {
  error_log('Error al actualizar sacramento: ' . $e->getMessage());
  respuestaJson(false, 'Error al actualizar la inscripción.', null, 500);
} finally {
  $conexion->close();
}