<?php
declare(strict_types=1);
require_once appPath('servidor/config/database.php');

$conexion = conectar();

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$evento = null;

if ($id > 0) {
  $sql = "SELECT id_evento, nombre_evento, descripcion, fecha_evento, hora_evento, estado, lugar
          FROM eventos WHERE id_evento = ? LIMIT 1";
  $stmt = $conexion->prepare($sql);
  $stmt->bind_param('i', $id);
  $stmt->execute();
  $resultado = $stmt->get_result();
  $evento = $resultado->fetch_assoc();
  $stmt->close();
}

$conexion->close();

pagina('cliente/pages/admin/eventos/form.php', ['evento' => $evento]);
