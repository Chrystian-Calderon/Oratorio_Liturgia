<?php
declare(strict_types=1);
require_once appPath('servidor/config/database.php');

$conexion = conectar();

$idActividad = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($idActividad <= 0) {
    header("Location: " . url('/ver-actividades'));
    exit;
}

$stmt = $conexion->prepare("SELECT a.*, e.lugar, e.nombre_evento FROM actividades a JOIN eventos e ON a.id_evento = e.id_evento WHERE a.id_actividad = ? AND a.estado = 'Activo' LIMIT 1");
$stmt->bind_param("i", $idActividad);
$stmt->execute();
$actividad = $stmt->get_result()->fetch_assoc();
$stmt->close();
$conexion->close();

if (!$actividad) {
    header("Location: " . url('/ver-actividades'));
    exit;
}

$cupoDisponible = (int)($actividad['cupo_disponible'] ?? 0);
$actividadAgotada = $cupoDisponible <= 0;

$nombre = $_SESSION['nombre'] ?? '';
$apellidos = $_SESSION['apellidos'] ?? '';
$correo = $_SESSION['correo'] ?? '';

return compact('actividad', 'idActividad', 'cupoDisponible', 'actividadAgotada', 'nombre', 'apellidos', 'correo');