<?php
declare(strict_types=1);
require_once appPath('servidor/config/database.php');

$conexion = conectar();

$registrosPorPagina = 6;
$paginaActual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$offset = ($paginaActual - 1) * $registrosPorPagina;

$filtroEstado = trim($_GET['estado'] ?? '');
$filtroBusqueda = trim($_GET['buscar'] ?? '');
$filtroFecha = trim($_GET['fecha'] ?? '');

$sqlBase = "FROM eventos WHERE 1=1";
$params = [];
$tipos = '';

if ($filtroEstado !== '') {
    $sqlBase .= " AND estado = ?";
    $params[] = $filtroEstado;
    $tipos .= 's';
}
if ($filtroBusqueda !== '') {
    $sqlBase .= " AND (nombre_evento LIKE ? OR descripcion LIKE ? OR lugar LIKE ?)";
    $params[] = "%{$filtroBusqueda}%";
    $params[] = "%{$filtroBusqueda}%";
    $params[] = "%{$filtroBusqueda}%";
    $tipos .= 'sss';
}
if ($filtroFecha !== '') {
    $sqlBase .= " AND DATE(fecha_evento) = ?";
    $params[] = $filtroFecha;
    $tipos .= 's';
}

$stmtCount = $conexion->prepare("SELECT COUNT(*) as total $sqlBase");
if ($params) $stmtCount->bind_param($tipos, ...$params);
$stmtCount->execute();
$totalRegistros = $stmtCount->get_result()->fetch_assoc()['total'];
$stmtCount->close();
$totalPaginas = ceil($totalRegistros / $registrosPorPagina);

$sql = "SELECT id_evento, nombre_evento, descripcion, fecha_evento, hora_evento,
               lugar, estado, fecha_creacion, fecha_actualizacion
        $sqlBase
        ORDER BY
            CASE
                WHEN estado = 'Activo' THEN 1
                WHEN estado = 'Inactivo' THEN 2
                WHEN estado = 'Cancelado' THEN 3
            END,
            fecha_evento DESC
        LIMIT $offset, $registrosPorPagina";

$stmt = $conexion->prepare($sql);
if ($params) $stmt->bind_param($tipos, ...$params);
$stmt->execute();
$eventos = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();
$conexion->close();

$estadosEventos = ['Activo', 'Inactivo', 'Cancelado'];

return compact('eventos', 'estadosEventos', 'totalRegistros', 'totalPaginas', 'paginaActual', 'filtroEstado', 'filtroBusqueda', 'filtroFecha');