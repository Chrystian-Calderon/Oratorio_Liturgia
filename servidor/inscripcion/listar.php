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
  $where = "WHERE i.id_persona = ?
            OR p.nombres LIKE ?
            OR p.apellidos LIKE ?
            OR CONCAT(p.nombres, ' ', p.apellidos) LIKE ?";
  $parametros = [(int) $buscar, "%{$buscar}%", "%{$buscar}%", "%{$buscar}%"];
  $tipos = 'isss';
}

$stmt = $conexion->prepare("SELECT COUNT(*) AS total FROM inscripcion i LEFT JOIN personas p ON i.id_persona = p.id_persona {$where}");
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

$sql = "SELECT i.id_inscripcion, i.id_actividad, i.id_persona, i.id_pago,
               i.cumple_requisitos, i.estado, i.observaciones,
               i.asistencia, i.calificacion, i.fecha_inscripcion,
               CONCAT(p.nombres, ' ', p.apellidos) AS persona_nombre,
               p.nombres AS persona_nombres, p.apellidos AS persona_apellidos,
               a.nombre_actividad,
               pa.concepto AS pago_concepto, pa.monto AS pago_monto
        FROM inscripcion i
        LEFT JOIN personas p ON i.id_persona = p.id_persona
        LEFT JOIN actividades a ON i.id_actividad = a.id_actividad
        LEFT JOIN pagos pa ON i.id_pago = pa.id_pago
        {$where}
        ORDER BY i.id_inscripcion DESC
        LIMIT {$porPagina} OFFSET {$inicio}";

$stmt = $conexion->prepare($sql);
if ($parametros) {
  $stmt->bind_param($tipos, ...$parametros);
}
$stmt->execute();
$inscripciones = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

$conexion->close();

pagina('cliente/pages/admin/inscripcion/index.php', [
  'inscripciones' => $inscripciones,
  'paginaActual'  => $paginaActual,
  'totalPaginas'  => $totalPaginas,
  'total'         => $total,
  'buscar'        => $buscar,
]);
