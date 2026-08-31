<?php
declare(strict_types=1);
require_once appPath('servidor/config/database.php');

$conexion = conectar();

$porPagina = 10;

$paginaActual = isset($_GET['pagina']) ? (int) $_GET['pagina'] : 1;
if ($paginaActual < 1) {
  $paginaActual = 1;
}

$buscar = trim($_GET['buscar'] ?? '');

$where = '';
$parametros = [];
$tipos = '';

if ($buscar !== '') {
  $where = 'WHERE a.nombre_actividad LIKE ?';
  $parametros[] = "%{$buscar}%";
  $tipos = 's';
}

$stmt = $conexion->prepare("SELECT COUNT(*) AS total FROM actividades a {$where}");
if ($parametros) {
  $stmt->bind_param($tipos, ...$parametros);
}
$stmt->execute();
$total = (int) $stmt->get_result()->fetch_assoc()['total'];
$stmt->close();

$totalPaginas = max(1, (int) ceil($total / $porPagina));

if ($paginaActual > $totalPaginas) {
  $paginaActual = $totalPaginas;
}

$inicio = ($paginaActual - 1) * $porPagina;

$sql = "SELECT a.id_actividad, a.nombre_actividad, a.tipo_actividad,
               a.fecha_inicio, a.fecha_fin, a.hora_inicio, a.hora_fin,
               a.duracion, a.costo, a.cupo_maximo, a.cupo_disponible,
               a.estado, e.nombre_evento
        FROM actividades a
        LEFT JOIN eventos e ON a.id_evento = e.id_evento
        {$where}
        ORDER BY a.id_actividad DESC
        LIMIT {$porPagina} OFFSET {$inicio}";

$stmt = $conexion->prepare($sql);
if ($parametros) {
  $stmt->bind_param($tipos, ...$parametros);
}
$stmt->execute();
$actividades = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

$conexion->close();

pagina('cliente/pages/admin/actividades/index.php', [
  'actividades'  => $actividades,
  'paginaActual' => $paginaActual,
  'totalPaginas' => $totalPaginas,
  'total'        => $total,
  'buscar'       => $buscar,
]);
