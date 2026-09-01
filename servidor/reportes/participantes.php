<?php
declare(strict_types=1);
require_once appPath('servidor/config/database.php');
require_once appPath('servidor/helpers/respuesta.php');

if (!isset($_SESSION['usuario']) || !in_array($_SESSION['tipo_persona'], ['Administrativo', 'Encargado'])) {
  respuestaJson(false, 'No autorizado.', null, 401);
}

$conexion = conectar();

try {
  $actividad = (int) ($_GET['actividad'] ?? 0);
  $estado = trim($_GET['estado'] ?? '');
  $buscar = trim($_GET['buscar'] ?? '');

  $where = [];
  $parametros = [];
  $tipos = '';

  if ($actividad > 0) {
    $where[] = 'i.id_actividad = ?';
    $parametros[] = $actividad;
    $tipos .= 'i';
  }
  if ($estado !== '') {
    $where[] = 'i.estado = ?';
    $parametros[] = $estado;
    $tipos .= 's';
  }
  if ($buscar !== '') {
    $where[] = '(CONCAT(p.nombres, " ", p.apellidos) LIKE ? OR p.ci LIKE ?)';
    $parametros[] = "%{$buscar}%";
    $parametros[] = "%{$buscar}%";
    $tipos .= 'ss';
  }

  $whereSql = $where ? 'WHERE ' . implode(' AND ', $where) : '';

  $stmt = $conexion->prepare(
    "SELECT i.id_inscripcion, i.fecha_inscripcion, i.cumple_requisitos, i.estado,
            i.observaciones, i.asistencia, i.calificacion,
            p.ci, p.nombres, p.apellidos, p.correo, p.telefono, p.tipo_persona,
            a.nombre_actividad, a.fecha_inicio, a.fecha_fin,
            pg.monto, pg.metodo_pago, pg.estado AS estado_pago
     FROM inscripcion i
     JOIN personas p ON p.id_persona = i.id_persona
     JOIN actividades a ON a.id_actividad = i.id_actividad
     LEFT JOIN pagos pg ON pg.id_pago = i.id_pago
     {$whereSql}
     ORDER BY i.fecha_inscripcion DESC"
  );
  if ($parametros) {
    $stmt->bind_param($tipos, ...$parametros);
  }
  $stmt->execute();
  $participantes = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
  $stmt->close();

  $actividades = $conexion->query("SELECT id_actividad, nombre_actividad FROM actividades ORDER BY nombre_actividad")->fetch_all(MYSQLI_ASSOC);

  $conexion->close();

  respuestaJson(true, 'Reporte de participantes.', [
    'participantes' => $participantes,
    'actividades'   => $actividades,
  ]);
} catch (Throwable $e) {
  error_log('Error reporte participantes: ' . $e->getMessage());
  respuestaJson(false, 'Error al obtener el reporte.', null, 500);
}