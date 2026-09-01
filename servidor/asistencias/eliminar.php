<?php
declare(strict_types=1);
require_once appPath('servidor/config/database.php');
require_once appPath('servidor/helpers/respuesta.php');

if (!isset($_SESSION['correo'])) {
    respuestaJson(false, 'No hay sesión activa.', null, 401);
}

$conexion = conectar();

try {
    $datos = json_decode(file_get_contents('php://input'), true);

    if (!$datos) {
        respuestaJson(false, 'No se recibieron datos.', null, 400);
    }

    $id = (int) ($datos['id_asistencia'] ?? 0);

    if ($id <= 0) {
        respuestaJson(false, 'ID de asistencia no válido.', null, 422);
    }

    $stmt = $conexion->prepare("DELETE FROM asistencias WHERE id_asistencia = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $afectadas = $stmt->affected_rows;
    $stmt->close();

    $conexion->close();

    if ($afectadas <= 0) {
        respuestaJson(false, 'No se encontró la asistencia.', null, 404);
    }

    respuestaJson(true, 'Asistencia eliminada correctamente.');
} catch (Throwable $e) {
    error_log('Error al eliminar asistencia: ' . $e->getMessage());
    if (isset($conexion) && $conexion) {
        $conexion->close();
    }
    respuestaJson(false, 'Error al eliminar la asistencia.', null, 500);
}
