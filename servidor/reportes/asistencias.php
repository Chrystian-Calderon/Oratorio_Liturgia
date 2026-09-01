<?php
declare(strict_types=1);
require_once appPath('servidor/config/database.php');
require_once appPath('servidor/helpers/respuesta.php');

if (!isset($_SESSION['usuario']) || !in_array($_SESSION['tipo_persona'], ['Administrativo', 'Encargado'])) {
  respuestaJson(false, 'No autorizado.', null, 401);
}

$conexion = conectar();

try {
  $buscar = trim($_GET['buscar'] ?? '');

  $where = '';
  $parametros = [];
  $tipos = '';

  if ($buscar !== '') {
    $where = 'WHERE (CONCAT(p.nombres, " ", p.apellidos) LIKE ? OR a.nombre_actividad LIKE ?)';
    $parametros = ["%{$buscar}%", "%{$buscar}%"];
    $tipos = 'ss';
  }

  $stmt = $conexion->prepare(
    "SELECT at.id_asistencia, at.fecha, at.asistio, at.observaciones, at.fecha_registro,
            p.ci, p.nombres, p.apellidos,
            a.nombre_actividad,
            reg.nombres AS registrado_por_nombre, reg.apellidos AS registrado_por_apellidos
     FROM asistencias at
     JOIN inscripcion i ON i.id_inscripcion = at.id_inscripcion
     JOIN personas p ON p.id_persona = i.id_persona
     JOIN actividades a ON a.id_actividad = i.id_actividad
     JOIN personas reg ON reg.id_persona = at.registrado_por
     {$where}
     ORDER BY at.fecha DESC"
  );
  if ($parametros) {
    $stmt->bind_param($tipos, ...$parametros);
  }
  $stmt->execute();
  $asistencias = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
  $stmt->close();

  $conexion->close();

  respuestaJson(true, 'Reporte de asistencias.', ['asistencias' => $asistencias]);
} catch (Throwable $e) {
  error_log('Error reporte asistencias: ' . $e->getMessage());
  respuestaJson(false, 'Error al obtener el reporte.', null, 500);
}