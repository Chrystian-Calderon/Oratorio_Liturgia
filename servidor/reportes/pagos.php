<?php
declare(strict_types=1);
require_once appPath('servidor/config/database.php');
require_once appPath('servidor/helpers/respuesta.php');

if (!isset($_SESSION['usuario']) || !in_array($_SESSION['tipo_persona'], ['Administrativo', 'Encargado'])) {
  respuestaJson(false, 'No autorizado.', null, 401);
}

$conexion = conectar();

try {
  $estado = trim($_GET['estado'] ?? '');
  $metodo = trim($_GET['metodo'] ?? '');
  $buscar = trim($_GET['buscar'] ?? '');

  $where = [];
  $parametros = [];
  $tipos = '';

  if ($estado !== '') {
    $where[] = 'pg.estado = ?';
    $parametros[] = $estado;
    $tipos .= 's';
  }
  if ($metodo !== '') {
    $where[] = 'pg.metodo_pago = ?';
    $parametros[] = $metodo;
    $tipos .= 's';
  }
  if ($buscar !== '') {
    $where[] = '(CONCAT(p.nombres, " ", p.apellidos) LIKE ? OR pg.concepto LIKE ? OR p.ci LIKE ?)';
    $parametros[] = "%{$buscar}%";
    $parametros[] = "%{$buscar}%";
    $parametros[] = "%{$buscar}%";
    $tipos .= 'sss';
  }

  $whereSql = $where ? 'WHERE ' . implode(' AND ', $where) : '';

  $stmt = $conexion->prepare(
    "SELECT pg.id_pago, pg.concepto, pg.monto, pg.fecha_pago, pg.metodo_pago,
            pg.comprobante, pg.estado, pg.observaciones, pg.fecha_creacion,
            p.ci, p.nombres, p.apellidos, p.correo
     FROM pagos pg
     JOIN personas p ON p.id_persona = pg.id_persona
     {$whereSql}
     ORDER BY pg.fecha_pago DESC"
  );
  if ($parametros) {
    $stmt->bind_param($tipos, ...$parametros);
  }
  $stmt->execute();
  $pagos = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
  $stmt->close();

  // Totales
  $total = count($pagos);
  $totalMonto = array_sum(array_map(fn($p) => (float) $p['monto'], $pagos));
  $totalCompletados = count(array_filter($pagos, fn($p) => $p['estado'] === 'Completado'));
  $totalPendientes = count(array_filter($pagos, fn($p) => $p['estado'] === 'Pendiente'));

  $conexion->close();

  respuestaJson(true, 'Reporte de pagos.', [
    'pagos'            => $pagos,
    'total'            => $total,
    'total_monto'      => $totalMonto,
    'total_completados'=> $totalCompletados,
    'total_pendientes' => $totalPendientes,
  ]);
} catch (Throwable $e) {
  error_log('Error reporte pagos: ' . $e->getMessage());
  respuestaJson(false, 'Error al obtener el reporte.', null, 500);
}