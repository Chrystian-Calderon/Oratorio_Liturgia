<?php
declare(strict_types=1);
require_once appPath('servidor/config/database.php');
require_once appPath('servidor/helpers/respuesta.php');

$conexion = conectar();

try {
  $evento = (int) ($_GET['evento'] ?? 0);
  $estado = trim($_GET['estado'] ?? '');
  $buscar = trim($_GET['buscar'] ?? '');

  $where = [];
  $parametros = [];
  $tipos = '';

  if ($evento > 0) {
    $where[] = 'a.id_evento = ?';
    $parametros[] = $evento;
    $tipos .= 'i';
  }
  if ($estado !== '') {
    $where[] = 'a.estado = ?';
    $parametros[] = $estado;
    $tipos .= 's';
  }
  if ($buscar !== '') {
    $where[] = '(a.nombre_actividad LIKE ? OR a.tipo_actividad LIKE ?)';
    $parametros[] = "%{$buscar}%";
    $parametros[] = "%{$buscar}%";
    $tipos .= 'ss';
  }

  $whereSql = $where ? 'WHERE ' . implode(' AND ', $where) : '';

  $stmt = $conexion->prepare(
    "SELECT a.id_actividad, a.nombre_actividad, a.tipo_actividad, a.fecha_inicio, a.fecha_fin,
            a.hora_inicio, a.hora_fin, a.duracion, a.costo, a.cupo_maximo, a.cupo_disponible,
            a.descripcion, a.estado, a.id_evento,
            e.nombre_evento,
            (SELECT COUNT(*) FROM inscripcion i WHERE i.id_actividad = a.id_actividad) AS total_inscripciones,
            (SELECT COALESCE(SUM(p.monto), 0) FROM inscripcion i2
             JOIN pagos p ON p.id_pago = i2.id_pago
             WHERE i2.id_actividad = a.id_actividad) AS total_pagos
     FROM actividades a
     JOIN eventos e ON a.id_evento = e.id_evento
     {$whereSql}
     ORDER BY a.fecha_inicio DESC"
  );
  if ($parametros) {
    $stmt->bind_param($tipos, ...$parametros);
  }
  $stmt->execute();
  $actividades = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
  $stmt->close();

  $eventos = $conexion->query("SELECT id_evento, nombre_evento FROM eventos ORDER BY nombre_evento")->fetch_all(MYSQLI_ASSOC);

  $conexion->close();

  respuestaJson(true, 'Reporte de actividades.', [
    'actividades' => $actividades,
    'eventos'     => $eventos,
  ]);
} catch (Throwable $e) {
  error_log('Error reporte actividades: ' . $e->getMessage());
  respuestaJson(false, 'Error al obtener el reporte.', null, 500);
}