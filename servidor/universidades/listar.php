<?php
declare(strict_types=1);
require_once appPath('servidor/config/database.php');

$conexion = conectar();

$porPagina = 10;
$paginaActual = isset($_GET['pagina']) ? (int) $_GET['pagina'] : 1;
if ($paginaActual < 1) $paginaActual = 1;

$buscar = trim($_GET['buscar'] ?? '');

$where = '';
$parametros = [];
$tipos = '';

if ($buscar !== '') {
  $where = "WHERE nombre LIKE ?";
  $parametros = ["%{$buscar}%"];
  $tipos = 's';
}

$stmt = $conexion->prepare("SELECT COUNT(*) AS total FROM universidades {$where}");
if ($parametros) $stmt->bind_param($tipos, ...$parametros);
$stmt->execute();
$total = (int) $stmt->get_result()->fetch_assoc()['total'];
$stmt->close();

$totalPaginas = max(1, (int) ceil($total / $porPagina));
if ($paginaActual > $totalPaginas) $paginaActual = $totalPaginas;
$inicio = ($paginaActual - 1) * $porPagina;

$stmt = $conexion->prepare(
  "SELECT * FROM universidades {$where} ORDER BY id_universidad DESC LIMIT ? OFFSET ?"
);
if ($parametros) {
  $stmt->bind_param($tipos . 'ii', ...array_merge($parametros, [$porPagina, $inicio]));
} else {
  $stmt->bind_param('ii', $porPagina, $inicio);
}
$stmt->execute();
$universidades = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

$conexion->close();

pagina('cliente/pages/admin/universidades/index.php', [
  'universidades' => $universidades,
  'paginaActual'  => $paginaActual,
  'totalPaginas'  => $totalPaginas,
  'total'         => $total,
  'buscar'        => $buscar,
]);