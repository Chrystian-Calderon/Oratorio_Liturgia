<?php
declare(strict_types=1);
require_once appPath('servidor/config/database.php');

$conexion = conectar();

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$persona = null;

if ($id > 0) {
  $sql = "SELECT p.*, us.rol,
                 CONCAT(u.nombre, ' - ', u.sigla) AS universidad_nombre
          FROM personas p
          LEFT JOIN usuarios_sistema us ON p.id_usuario = us.id_usuario
          LEFT JOIN universidades u ON p.id_universidad = u.id_universidad
          WHERE p.id_persona = ? LIMIT 1";
  $stmt = $conexion->prepare($sql);
  $stmt->bind_param('i', $id);
  $stmt->execute();
  $persona = $stmt->get_result()->fetch_assoc();
  $stmt->close();
}

// Universidades para el selector
$universidades = $conexion->query(
  "SELECT id_universidad, nombre, sigla
   FROM universidades
   WHERE estado = 'Activo'
   ORDER BY nombre ASC"
)->fetch_all(MYSQLI_ASSOC);

// Roles disponibles (desde usuarios_sistema)
$roles = $conexion->query(
  "SELECT DISTINCT rol FROM usuarios_sistema WHERE rol IS NOT NULL ORDER BY rol ASC"
)->fetch_all(MYSQLI_ASSOC);

$conexion->close();

pagina('cliente/pages/admin/personas/form.php', [
  'persona'       => $persona,
  'universidades' => $universidades,
  'roles'         => $roles,
]);