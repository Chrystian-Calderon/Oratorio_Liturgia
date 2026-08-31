<?php
declare(strict_types=1);
require_once appPath('servidor/config/database.php');
require_once appPath('servidor/helpers/respuesta.php');

$conexion = conectar();

try {
  $sacramento = trim($_GET['sacramento'] ?? '');
  $buscar = trim($_GET['buscar'] ?? '');

  $where = [];
  $parametros = [];
  $tipos = '';

  if ($sacramento !== '') {
    $where[] = 's.sacramento = ?';
    $parametros[] = $sacramento;
    $tipos .= 's';
  }
  if ($buscar !== '') {
    $where[] = '(s.nombre_solicitante LIKE ? OR s.email LIKE ? OR s.telefono LIKE ?)';
    $parametros[] = "%{$buscar}%";
    $parametros[] = "%{$buscar}%";
    $parametros[] = "%{$buscar}%";
    $tipos .= 'sss';
  }

  $whereSql = $where ? 'WHERE ' . implode(' AND ', $where) : '';

  $stmt = $conexion->prepare(
    "SELECT * FROM formulario_sacramentos s {$whereSql} ORDER BY s.fecha_registro DESC"
  );
  if ($parametros) {
    $stmt->bind_param($tipos, ...$parametros);
  }
  $stmt->execute();
  $sacramentos = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
  $stmt->close();

  $conexion->close();

  respuestaJson(true, 'Reporte de sacramentos.', ['sacramentos' => $sacramentos]);
} catch (Throwable $e) {
  error_log('Error reporte sacramentos: ' . $e->getMessage());
  respuestaJson(false, 'Error al obtener el reporte.', null, 500);
}