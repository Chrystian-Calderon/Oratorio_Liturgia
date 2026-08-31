<?php
declare(strict_types=1);
require_once appPath('servidor/config/database.php');

$conexion = conectar();

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: " . url('/ver-eventos'));
    exit;
}

$idEvento = (int)$_GET['id'];

$stmt = $conexion->prepare("SELECT id_evento, nombre_evento, descripcion, fecha_evento, hora_evento, lugar, estado, fecha_creacion, fecha_actualizacion FROM eventos WHERE id_evento = ? LIMIT 1");
$stmt->bind_param("i", $idEvento);
$stmt->execute();
$evento = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$evento) {
    header("Location: " . url('/ver-eventos'));
    exit;
}

$stmtAct = $conexion->prepare("SELECT id_actividad, nombre_actividad, tipo_actividad, fecha_inicio, fecha_fin, dias_semana, hora_inicio, hora_fin, duracion, requisitos, costo, cupo_maximo, cupo_disponible, descripcion, id_evento, estado FROM actividades WHERE id_evento = ? AND estado = 'Activo' ORDER BY fecha_inicio ASC, hora_inicio ASC");
$stmtAct->bind_param("i", $idEvento);
$stmtAct->execute();
$actividades = $stmtAct->get_result()->fetch_all(MYSQLI_ASSOC);
$stmtAct->close();
$conexion->close();

$totalActividades = count($actividades);

return compact('evento', 'actividades', 'totalActividades');