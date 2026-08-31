<?php
declare(strict_types=1);

require_once appPath('servidor/config/database.php');
require_once appPath('servidor/helpers/respuesta.php');

$conexion = conectar();

try {

  // ===== Estadísticas =====
  $totalEventos = (int) $conexion->query(
    "SELECT COUNT(*) AS total FROM eventos"
  )->fetch_assoc()['total'];

  $proximos = (int) $conexion->query(
    "SELECT COUNT(*) AS total FROM eventos
     WHERE fecha_evento IS NULL OR fecha_evento >= CURDATE()"
  )->fetch_assoc()['total'];

  $realizados = (int) $conexion->query(
    "SELECT COUNT(*) AS total FROM eventos
     WHERE fecha_evento IS NOT NULL AND fecha_evento < CURDATE()"
  )->fetch_assoc()['total'];

  $totalInscripciones = (int) $conexion->query(
    "SELECT COUNT(*) AS total FROM inscripcion"
  )->fetch_assoc()['total'];

  // ===== Eventos por Mes =====
  $meses = [
    "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio",
    "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre",
  ];
  $eventosPorMes = array_fill(0, 12, 0);

  $resultado = $conexion->query(
    "SELECT MONTH(fecha_evento) AS mes, COUNT(*) AS total
       FROM eventos
      WHERE fecha_evento IS NOT NULL
      GROUP BY MONTH(fecha_evento)
      ORDER BY mes"
  );
  while ($fila = $resultado->fetch_assoc()) {
    $eventosPorMes[(int) $fila['mes'] - 1] = (int) $fila['total'];
  }
  $resultado->free();

  // ===== Participación por Evento =====
  $participacionPorEvento = $conexion->query(
    "SELECT e.nombre_evento AS evento,
            COUNT(i.id_inscripcion) AS total
       FROM eventos e
       LEFT JOIN actividades a ON a.id_evento = e.id_evento
       LEFT JOIN inscripcion i ON i.id_actividad = a.id_actividad
      GROUP BY e.id_evento, e.nombre_evento
      HAVING total > 0
      ORDER BY total DESC"
  )->fetch_all(MYSQLI_ASSOC);

  // ===== Próximos Eventos =====
  $proximosEventos = $conexion->query(
    "SELECT id_evento, nombre_evento AS nombre, fecha_evento AS fecha
       FROM eventos
      WHERE fecha_evento IS NULL OR fecha_evento >= CURDATE()
      ORDER BY fecha_evento ASC
      LIMIT 6"
  )->fetch_all(MYSQLI_ASSOC);

  $conexion->close();

  respuestaJson(true, 'Datos del panel de eventos obtenidos.', [
    'estadisticas'        => [
      'total_eventos'       => $totalEventos,
      'proximos'            => $proximos,
      'realizados'          => $realizados,
      'total_inscripciones' => $totalInscripciones,
    ],
    'graficos'            => [
      'meses'                   => $meses,
      'eventos_por_mes'         => $eventosPorMes,
      'participacion_por_evento'=> $participacionPorEvento,
    ],
    'proximos_eventos'    => $proximosEventos,
  ]);

} catch (Throwable $e) {
  error_log('Error al obtener datos del panel de eventos: ' . $e->getMessage());
  respuestaJson(false, 'Error al obtener los datos del panel de eventos.', null, 500);
}