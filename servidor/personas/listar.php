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
$rol = trim($_GET['rol'] ?? '');

$where = [];
$parametros = [];
$tipos = '';

if ($buscar !== '') {
  $where[] = "(p.nombres LIKE ? OR p.apellidos LIKE ? OR CONCAT(p.nombres, ' ', p.apellidos) LIKE ? OR p.ci LIKE ?)";
  array_push($parametros, "%{$buscar}%", "%{$buscar}%", "%{$buscar}%", "%{$buscar}%");
  $tipos .= 'ssss';
}

if ($rol !== '') {
  $where[] = 'us.rol = ?';
  $parametros[] = $rol;
  $tipos .= 's';
}

$whereSql = empty($where) ? '' : 'WHERE ' . implode(' AND ', $where);

$stmt = $conexion->prepare(
  "SELECT COUNT(*) AS total
   FROM personas p
   LEFT JOIN usuarios_sistema us ON p.id_usuario = us.id_usuario
   {$whereSql}"
);
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

$stmt = $conexion->prepare(
  "SELECT p.id_persona, p.ci, p.nombres, p.apellidos, p.genero,
          p.direccion, p.telefono, p.correo, p.tipo_persona,
          p.id_universidad, p.id_usuario, p.estado,
          u.sigla AS universidad_sigla, u.nombre AS universidad_nombre,
          us.rol
   FROM personas p
   LEFT JOIN universidades u ON p.id_universidad = u.id_universidad
   LEFT JOIN usuarios_sistema us ON p.id_usuario = us.id_usuario
   {$whereSql}
   ORDER BY p.id_persona DESC
   LIMIT {$porPagina} OFFSET {$inicio}"
);
if ($parametros) {
  $stmt->bind_param($tipos, ...$parametros);
}
$stmt->execute();
$personas = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// Roles disponibles (desde usuarios_sistema)
$roles = $conexion->query(
  "SELECT DISTINCT rol FROM usuarios_sistema WHERE rol IS NOT NULL ORDER BY rol ASC"
)->fetch_all(MYSQLI_ASSOC);

$conexion->close();

pagina('cliente/pages/admin/personas/index.php', [
  'personas'      => $personas,
  'paginaActual'  => $paginaActual,
  'totalPaginas'  => $totalPaginas,
  'total'         => $total,
  'buscar'        => $buscar,
  'rol'           => $rol,
  'roles'         => $roles,
]);