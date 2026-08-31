<?php
declare(strict_types=1);
require_once appPath('servidor/config/database.php');

$conexion = conectar();

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$inscripcion = null;

if ($id > 0) {
  $sql = "SELECT * FROM inscripcion WHERE id_inscripcion = ? LIMIT 1";
  $stmt = $conexion->prepare($sql);
  $stmt->bind_param('i', $id);
  $stmt->execute();
  $inscripcion = $stmt->get_result()->fetch_assoc();
  $stmt->close();
}

if (!$inscripcion) {
  header("Location: " . url('/inscripcion'));
  exit();
}

// Opciones para los selectores
$actividades = $conexion->query(
  "SELECT id_actividad, nombre_actividad
   FROM actividades
   ORDER BY nombre_actividad ASC"
)->fetch_all(MYSQLI_ASSOC);

$personas = $conexion->query(
  "SELECT id_persona, nombres, apellidos, ci
   FROM personas
   ORDER BY apellidos ASC"
)->fetch_all(MYSQLI_ASSOC);

$pagos = $conexion->query(
  "SELECT id_pago, concepto, monto, fecha_pago
   FROM pagos
   ORDER BY id_pago DESC"
)->fetch_all(MYSQLI_ASSOC);

$conexion->close();

pagina('cliente/pages/admin/inscripcion/form.php', [
  'inscripcion'  => $inscripcion,
  'actividades'  => $actividades,
  'personas'     => $personas,
  'pagos'        => $pagos,
]);
