<?php
declare(strict_types=1);
require_once appPath('servidor/config/database.php');
require_once appPath('servidor/helpers/respuesta.php');

$conexion = conectar();

try {
  $estado = trim($_GET['estado'] ?? '');
  $buscar = trim($_GET['buscar'] ?? '');

  $where = [];
  $parametros = [];
  $tipos = '';

  if ($estado !== '') {
    $where[] = 'e.estado = ?';
    $parametros[] = $estado;
    $tipos .= 's';
  }
  if ($buscar !== '') {
    $where[] = '(e.nombre_evento LIKE ? OR e.descripcion LIKE ?)';
    $parametros[] = "%{$buscar}%";
    $parametros[] = "%{$buscar}%";
    $tipos .= 'ss';
  }

  $whereSql = $where ? 'WHERE ' . implode(' AND ', $where) : '';

  $stmt = $conexion->prepare(
    "SELECT e.id_evento, e.nombre_evento, e.descripcion, e.estado, e.fecha_evento,
            e.fecha_creacion, e.fecha_actualizacion,
            (SELECT COUNT(*) FROM actividades a WHERE a.id_evento = e.id_evento) AS total_actividades,
            (SELECT COUNT(*) FROM actividades a2
             JOIN inscripcion i ON i.id_actividad = a2.id_actividad
             WHERE a2.id_evento = e.id_evento) AS total_inscripciones
     FROM eventos e
     {$whereSql}
     ORDER BY e.fecha_creacion DESC"
  );
  if ($parametros) {
    $stmt->bind_param($tipos, ...$parametros);
  }
  $stmt->execute();
  $eventos = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
  $stmt->close();

  $conexion->close();

  respuestaJson(true, 'Reporte de eventos.', ['eventos' => $eventos]);
} catch (Throwable $e) {
  error_log('Error reporte eventos: ' . $e->getMessage());
  respuestaJson(false, 'Error al obtener el reporte.', null, 500);
}