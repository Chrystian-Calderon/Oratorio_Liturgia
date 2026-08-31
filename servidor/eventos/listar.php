<?php
declare(strict_types=1);
require_once appPath('servidor/config/database.php');

$conexion = conectar();

$porPagina = 10;

$paginaActual = isset($_GET['pagina']) ? (int) $_GET['pagina'] : 1;
if ($paginaActual < 1) {
  $paginaActual = 1;
}

// Filtros
$buscar = trim($_GET['buscar'] ?? '');
$mes = (int) ($_GET['mes'] ?? 0);

// WHERE dinámico con parámetros
$condiciones = [];
$parametros = [];
$tipos = '';

if ($buscar !== '') {
  $condiciones[] = "nombre_evento LIKE ?";
  $parametros[] = "%{$buscar}%";
  $tipos .= 's';
}

if ($mes >= 1 && $mes <= 12) {
  $condiciones[] = "MONTH(fecha_evento) = ?";
  $parametros[] = $mes;
  $tipos .= 'i';
}

$where = $condiciones ? 'WHERE ' . implode(' AND ', $condiciones) : '';

// Total con filtros
$stmt = $conexion->prepare("SELECT COUNT(*) AS total FROM eventos {$where}");
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

// Datos con filtros y paginación
$sql = "SELECT id_evento, nombre_evento, descripcion, fecha_evento, hora_evento, lugar, estado
        FROM eventos
        {$where}
        ORDER BY id_evento DESC
        LIMIT {$porPagina} OFFSET {$inicio}";

$stmt = $conexion->prepare($sql);
if ($parametros) {
  $stmt->bind_param($tipos, ...$parametros);
}
$stmt->execute();
$eventos = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

$conexion->close();

pagina('cliente/pages/admin/eventos/index.php', [
  'eventos'      => $eventos,
  'paginaActual' => $paginaActual,
  'totalPaginas' => $totalPaginas,
  'total'        => $total,
  'buscar'       => $buscar,
  'mes'          => $mes,
  'meses'        => [
    1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',
    5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',
    9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre',
  ],
]);
