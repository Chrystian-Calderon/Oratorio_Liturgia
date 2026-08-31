<?php
declare(strict_types=1);
require_once appPath('servidor/config/database.php');

$conexion = conectar();

$registrosPorPagina = 9;
$paginaActual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$offset = ($paginaActual - 1) * $registrosPorPagina;

$filtroTipo = trim($_GET['tipo'] ?? '');
$filtroBusqueda = trim($_GET['buscar'] ?? '');

$sqlBase = "FROM actividades WHERE estado = 'Activo'";
$params = [];
$tipos = '';

if ($filtroTipo !== '') {
    $sqlBase .= " AND tipo_actividad = ?";
    $params[] = $filtroTipo;
    $tipos .= 's';
}
if ($filtroBusqueda !== '') {
    $sqlBase .= " AND (nombre_actividad LIKE ? OR descripcion LIKE ?)";
    $params[] = "%{$filtroBusqueda}%";
    $params[] = "%{$filtroBusqueda}%";
    $tipos .= 'ss';
}

$stmtCount = $conexion->prepare("SELECT COUNT(*) as total $sqlBase");
if ($params) $stmtCount->bind_param($tipos, ...$params);
$stmtCount->execute();
$totalRegistros = $stmtCount->get_result()->fetch_assoc()['total'];
$stmtCount->close();
$totalPaginas = ceil($totalRegistros / $registrosPorPagina);

$sql = "SELECT id_actividad, nombre_actividad, tipo_actividad, fecha_inicio, fecha_fin,
               dias_semana, hora_inicio, hora_fin, duracion, requisitos, costo,
               cupo_maximo, cupo_disponible, descripcion, id_evento, estado
        $sqlBase
        ORDER BY fecha_inicio ASC
        LIMIT $offset, $registrosPorPagina";

$stmt = $conexion->prepare($sql);
if ($params) $stmt->bind_param($tipos, ...$params);
$stmt->execute();
$actividades = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

$stmtTipos = $conexion->prepare("SELECT DISTINCT tipo_actividad FROM actividades WHERE estado = 'Activo' ORDER BY tipo_actividad");
$stmtTipos->execute();
$tiposActividades = [];
$rTipos = $stmtTipos->get_result();
while ($row = $rTipos->fetch_assoc()) {
    $tiposActividades[] = $row['tipo_actividad'];
}
$stmtTipos->close();
$conexion->close();

return compact('actividades', 'tiposActividades', 'totalRegistros', 'totalPaginas', 'paginaActual', 'filtroTipo', 'filtroBusqueda');