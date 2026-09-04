<?php
declare(strict_types=1);
require_once appPath('servidor/config/database.php');
require_once appPath('servidor/helpers/respuesta.php');

$conexion = conectar();

try {
    $data = json_decode(file_get_contents('php://input'), true);

    if (!$data) {
        respuestaJson(false, 'Datos no válidos.', null, 400);
    }

    $nombre    = trim($data['nombre'] ?? '');
    $apellido  = trim($data['apellido'] ?? '');
    $correo    = trim($data['correo'] ?? '');
    $telefono  = trim($data['telefono'] ?? '');
    $asunto    = trim($data['asunto'] ?? '');
    $mensaje   = trim($data['mensaje'] ?? '');

    if ($nombre === '' || $apellido === '' || $correo === '' || $asunto === '' || $mensaje === '') {
        respuestaJson(false, 'Todos los campos obligatorios deben ser completados.', null, 400);
    }

    if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        respuestaJson(false, 'El correo electrónico no es válido.', null, 400);
    }

    $stmt = $conexion->prepare(
        "INSERT INTO sugerencias (nombre, apellido, correo, telefono, asunto, mensaje)
         VALUES (?, ?, ?, ?, ?, ?)"
    );
    $stmt->bind_param('ssssss', $nombre, $apellido, $correo, $telefono, $asunto, $mensaje);
    $stmt->execute();
    $stmt->close();

    $conexion->close();

    respuestaJson(true, 'Sugerencia registrada exitosamente.');
} catch (Throwable $e) {
    error_log('Error guardar sugerencia: ' . $e->getMessage());
    respuestaJson(false, 'Error al guardar la sugerencia.', null, 500);
}
