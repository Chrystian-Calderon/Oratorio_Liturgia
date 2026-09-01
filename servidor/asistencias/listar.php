<?php
declare(strict_types=1);
require_once appPath('servidor/config/database.php');

$conexion = conectar();

$actividad = (int) ($_GET['actividad'] ?? 0);
$fecha = trim($_GET['fecha'] ?? date('Y-m-d'));

$actividades = [];
$inscripciones = [];

// Cargar actividades disponibles
$stmtActividades = $conexion->prepare(
    "SELECT id_actividad, nombre_actividad FROM actividades WHERE estado = 'Activo' ORDER BY nombre_actividad ASC"
);
$stmtActividades->execute();
$actividades = $stmtActividades->get_result()->fetch_all(MYSQLI_ASSOC);
$stmtActividades->close();

// Si se seleccionó una actividad, cargar inscritos con asistencia
if ($actividad > 0) {
    $stmt = $conexion->prepare(
        "SELECT i.id_inscripcion, p.nombres, p.apellidos, p.ci,
                a.nombre_actividad,
                ast.id_asistencia, ast.asistio, ast.observaciones
         FROM inscripcion i
         JOIN personas p ON i.id_persona = p.id_persona
         JOIN actividades a ON i.id_actividad = a.id_actividad
         LEFT JOIN asistencias ast ON ast.id_inscripcion = i.id_inscripcion AND ast.fecha = ?
         WHERE i.id_actividad = ? AND i.estado IN ('Inscrito', 'Pre-inscrito')
         ORDER BY p.apellidos ASC"
    );
    $stmt->bind_param("si", $fecha, $actividad);
    $stmt->execute();
    $inscripciones = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
}

$conexion->close();

pagina('cliente/pages/admin/asistencias/index.php', [
    'actividades'   => $actividades,
    'inscripciones' => $inscripciones,
    'actividad'     => $actividad,
    'fecha'         => $fecha,
]);
