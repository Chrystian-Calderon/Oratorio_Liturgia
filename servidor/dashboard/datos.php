<?php
declare(strict_types=1);

require_once appPath('servidor/config/database.php');
require_once appPath('servidor/helpers/respuesta.php');

$conexion = conectar();

try {

  // ===== Resumen =====
  $personas = (int) $conexion->query(
    "SELECT COUNT(*) AS total FROM personas"
  )->fetch_assoc()['total'];

  $eventos = (int) $conexion->query(
    "SELECT COUNT(*) AS total FROM eventos"
  )->fetch_assoc()['total'];

  $actividades = (int) $conexion->query(
    "SELECT COUNT(*) AS total FROM actividades"
  )->fetch_assoc()['total'];

  $inscripciones = (int) $conexion->query(
    "SELECT COUNT(*) AS total FROM inscripcion"
  )->fetch_assoc()['total'];

  // ===== Próximos Eventos =====
  $proximosEventos = $conexion->query(
    "SELECT nombre_evento AS nombre, fecha_evento AS fecha
       FROM eventos
      WHERE fecha_evento IS NULL OR fecha_evento >= CURDATE()
      ORDER BY fecha_evento ASC
      LIMIT 5"
  )->fetch_all(MYSQLI_ASSOC);

  // ===== Próximas Actividades =====
  $proximasActividades = $conexion->query(
    "SELECT nombre_actividad AS nombre, fecha_inicio AS fecha
       FROM actividades
      WHERE (fecha_inicio IS NULL OR fecha_inicio >= CURDATE())
        AND estado = 'Activo'
      ORDER BY fecha_inicio ASC
      LIMIT 5"
  )->fetch_all(MYSQLI_ASSOC);

  // ===== Últimas Personas Registradas =====
  $ultimasPersonas = $conexion->query(
    "SELECT p.ci,
            CONCAT(p.nombres, ' ', p.apellidos) AS nombre,
            COALESCE(u.nombre, '—') AS universidad,
            p.estado
       FROM personas p
       LEFT JOIN universidades u ON p.id_universidad = u.id_universidad
      ORDER BY p.fecha_registro DESC
      LIMIT 5"
  )->fetch_all(MYSQLI_ASSOC);

  $conexion->close();

  respuestaJson(true, 'Datos del dashboard obtenidos.', [
    'resumen' => [
      'personas'      => $personas,
      'eventos'       => $eventos,
      'actividades'   => $actividades,
      'inscripciones' => $inscripciones,
    ],
    'proximos_eventos'     => $proximosEventos,
    'proximas_actividades' => $proximasActividades,
    'ultimas_personas'     => $ultimasPersonas,
  ]);

} catch (Throwable $e) {
  error_log('Error al obtener datos del dashboard: ' . $e->getMessage());
  respuestaJson(false, 'Error al obtener los datos del dashboard.', null, 500);
}
