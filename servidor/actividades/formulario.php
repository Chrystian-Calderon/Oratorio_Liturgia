<?php
declare(strict_types=1);
require_once appPath('servidor/config/database.php');

$conexion = conectar();

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$actividad = null;

if ($id > 0) {
  $sql = "SELECT * FROM actividades WHERE id_actividad = ? LIMIT 1";
  $stmt = $conexion->prepare($sql);
  $stmt->bind_param('i', $id);
  $stmt->execute();
  $actividad = $stmt->get_result()->fetch_assoc();
  $stmt->close();
}

// Eventos disponibles para el selector
$eventos = $conexion->query(
  "SELECT id_evento, nombre_evento, fecha_evento
   FROM eventos
   ORDER BY nombre_evento ASC"
)->fetch_all(MYSQLI_ASSOC);

$conexion->close();

pagina('cliente/pages/admin/actividades/form.php', [
  'actividad' => $actividad,
  'eventos'   => $eventos,
  'diasSemana' => ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'],
]);
