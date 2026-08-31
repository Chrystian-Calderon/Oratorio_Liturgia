<?php
declare(strict_types=1);

require_once appPath('servidor/config/database.php');
require_once appPath('servidor/helpers/respuesta.php');

$conexion = conectar();

try {

  // ===== Estadísticas =====
  $total = (int) $conexion->query(
    "SELECT COUNT(*) AS total FROM actividades"
  )->fetch_assoc()['total'];

  $proximas = (int) $conexion->query(
    "SELECT COUNT(*) AS total FROM actividades
     WHERE fecha_inicio IS NULL OR fecha_inicio >= CURDATE()"
  )->fetch_assoc()['total'];

  $realizadas = (int) $conexion->query(
    "SELECT COUNT(*) AS total FROM actividades
     WHERE fecha_inicio IS NOT NULL AND fecha_inicio < CURDATE()"
  )->fetch_assoc()['total'];

  $inscripciones = (int) $conexion->query(
    "SELECT COUNT(*) AS total FROM inscripcion"
  )->fetch_assoc()['total'];

  // ===== Actividades por Mes =====
  $meses = [
    "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio",
    "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre",
  ];
  $actividadesPorMes = array_fill(0, 12, 0);

  $resultado = $conexion->query(
    "SELECT MONTH(fecha_inicio) AS mes, COUNT(*) AS total
       FROM actividades
      WHERE fecha_inicio IS NOT NULL
      GROUP BY MONTH(fecha_inicio)
      ORDER BY mes"
  );
  while ($fila = $resultado->fetch_assoc()) {
    $actividadesPorMes[(int) $fila['mes'] - 1] = (int) $fila['total'];
  }
  $resultado->free();

  // ===== Inscripciones por Actividad =====
  $inscripcionesPorActividad = $conexion->query(
    "SELECT a.nombre_actividad AS actividad,
            COUNT(i.id_inscripcion) AS total
       FROM actividades a
       LEFT JOIN inscripcion i ON i.id_actividad = a.id_actividad
      GROUP BY a.id_actividad, a.nombre_actividad
      ORDER BY total DESC"
  )->fetch_all(MYSQLI_ASSOC);

  // ===== Próximas Actividades =====
  $proximasActividades = $conexion->query(
    "SELECT id_actividad, nombre_actividad AS nombre, fecha_inicio AS fecha
       FROM actividades
      WHERE fecha_inicio IS NULL OR fecha_inicio >= CURDATE()
      ORDER BY fecha_inicio ASC
      LIMIT 6"
  )->fetch_all(MYSQLI_ASSOC);

  $conexion->close();

  respuestaJson(true, 'Datos del panel de actividades obtenidos.', [
    'estadisticas'             => [
      'total'       => $total,
      'proximas'    => $proximas,
      'realizadas'  => $realizadas,
      'inscripciones' => $inscripciones,
    ],
    'graficos'                 => [
      'meses'                     => $meses,
      'actividades_por_mes'       => $actividadesPorMes,
      'inscripciones_por_actividad' => $inscripcionesPorActividad,
    ],
    'proximas_actividades'     => $proximasActividades,
  ]);

} catch (Throwable $e) {
  error_log('Error al obtener datos del panel de actividades: ' . $e->getMessage());
  respuestaJson(false, 'Error al obtener los datos del panel de actividades.', null, 500);
}