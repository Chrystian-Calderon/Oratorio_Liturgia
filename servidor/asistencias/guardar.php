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

    $idInscripcion = (int) ($datos['id_inscripcion'] ?? 0);
    $fecha = trim($datos['fecha'] ?? '');
    $asistio = trim($datos['asistio'] ?? '');
    $observaciones = trim($datos['observaciones'] ?? '');

    if ($idInscripcion <= 0) {
        respuestaJson(false, 'Inscripción no válida.', null, 422);
    }

    if ($fecha === '' || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $fecha)) {
        respuestaJson(false, 'Fecha no válida.', null, 422);
    }

    if (!in_array($asistio, ['Si', 'No', 'Justificado'], true)) {
        respuestaJson(false, 'Estado de asistencia no válido.', null, 422);
    }

    // Obtener ID de persona (registrado_por) desde la sesión
    $correo = $_SESSION['correo'];
    $stmtPersona = $conexion->prepare("SELECT id_persona FROM personas WHERE correo = ?");
    $stmtPersona->bind_param("s", $correo);
    $stmtPersona->execute();
    $fila = $stmtPersona->get_result()->fetch_assoc();
    $stmtPersona->close();

    if (!$fila) {
        respuestaJson(false, 'Usuario no encontrado.', null, 404);
    }

    $registradoPor = (int) $fila['id_persona'];

    // Verificar si ya existe una asistencia para esta inscripción en esta fecha
    $check = $conexion->prepare("SELECT id_asistencia FROM asistencias WHERE id_inscripcion = ? AND fecha = ?");
    $check->bind_param("is", $idInscripcion, $fecha);
    $check->execute();
    $existente = $check->get_result()->fetch_assoc();
    $check->close();

    if ($existente) {
        // Actualizar
        $stmt = $conexion->prepare(
            "UPDATE asistencias SET asistio = ?, observaciones = ? WHERE id_asistencia = ?"
        );
        $stmt->bind_param("ssi", $asistio, $observaciones, $existente['id_asistencia']);
    } else {
        // Insertar
        $stmt = $conexion->prepare(
            "INSERT INTO asistencias (id_inscripcion, fecha, asistio, observaciones, registrado_por) VALUES (?, ?, ?, ?, ?)"
        );
        $stmt->bind_param("isssi", $idInscripcion, $fecha, $asistio, $observaciones, $registradoPor);
    }

    $stmt->execute();
    $stmt->close();

    $conexion->close();

    respuestaJson(true, 'Asistencia registrada correctamente.');
} catch (Throwable $e) {
    error_log('Error al guardar asistencia: ' . $e->getMessage());
    if (isset($conexion) && $conexion) {
        $conexion->close();
    }
    respuestaJson(false, 'Error al guardar la asistencia.', null, 500);
}
