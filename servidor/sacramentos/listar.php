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
  $where = "WHERE nombre_solicitante LIKE ?";
  $parametros = ["%{$buscar}%"];
  $tipos = 's';
}

$stmt = $conexion->prepare("SELECT COUNT(*) AS total FROM formulario_sacramentos {$where}");
if ($parametros) $stmt->bind_param($tipos, ...$parametros);
$stmt->execute();
$total = (int) $stmt->get_result()->fetch_assoc()['total'];
$stmt->close();

$totalPaginas = max(1, (int) ceil($total / $porPagina));
if ($paginaActual > $totalPaginas) $paginaActual = $totalPaginas;
$inicio = ($paginaActual - 1) * $porPagina;

$stmt = $conexion->prepare(
  "SELECT * FROM formulario_sacramentos {$where} ORDER BY id_inscripcion DESC LIMIT ? OFFSET ?"
);
if ($parametros) {
  $stmt->bind_param($tipos . 'ii', ...array_merge($parametros, [$porPagina, $inicio]));
} else {
  $stmt->bind_param('ii', $porPagina, $inicio);
}
$stmt->execute();
$sacramentos = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

$conexion->close();

pagina('cliente/pages/admin/sacramentos/index.php', [
  'sacramentos'  => $sacramentos,
  'paginaActual' => $paginaActual,
  'totalPaginas' => $totalPaginas,
  'total'        => $total,
  'buscar'       => $buscar,
]);