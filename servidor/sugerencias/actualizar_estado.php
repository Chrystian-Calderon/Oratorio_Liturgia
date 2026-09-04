<?php
declare(strict_types=1);
require_once appPath('servidor/config/database.php');
require_once appPath('servidor/helpers/respuesta.php');

if (!isset($_SESSION['usuario']) || !in_array($_SESSION['tipo_persona'], ['Administrativo', 'Encargado'])) {
    respuestaJson(false, 'No autorizado.', null, 401);
}

$conexion = conectar();

try {
    $data = json_decode(file_get_contents('php://input'), true);

    if (!$data) {
        respuestaJson(false, 'Datos no válidos.', null, 400);
    }

    $tipo   = trim($data['tipo'] ?? '');
    $id     = (int) ($data['id'] ?? 0);
    $estado = trim($data['estado'] ?? '');

    if ($id <= 0 || $tipo === '' || $estado === '') {
        respuestaJson(false, 'Parámetros incompletos.', null, 400);
    }

    if (!in_array($estado, ['Nuevo', 'Leido', 'Respondido'])) {
        respuestaJson(false, 'Estado no válido.', null, 400);
    }

    $tabla = ($tipo === 'contacto') ? 'contacto' : 'sugerencias';
    $idCampo = ($tipo === 'contacto') ? 'id_contacto' : 'id_sugerencia';

    $stmt = $conexion->prepare("UPDATE {$tabla} SET estado = ? WHERE {$idCampo} = ?");
    $stmt->bind_param('si', $estado, $id);
    $stmt->execute();

    if ($stmt->affected_rows === 0) {
        $stmt->close();
        $conexion->close();
        respuestaJson(false, 'Registro no encontrado.', null, 404);
    }

    $stmt->close();
    $conexion->close();

    respuestaJson(true, 'Estado actualizado.');
} catch (Throwable $e) {
    error_log('Error actualizar estado sugerencias/contacto: ' . $e->getMessage());
    respuestaJson(false, 'Error al actualizar el estado.', null, 500);
}
