<?php

declare(strict_types=1);

require_once appPath('servidor/config/database.php');
require_once appPath('servidor/helpers/respuesta.php');

$datos = json_decode(
    file_get_contents('php://input'),
    true
);

$idUsuario = (int) ($datos['id_usuario'] ?? 0);

if ($idUsuario <= 0) {
    respuestaJson(
        false,
        'El usuario seleccionado no es válido.',
        null,
        422
    );
}

$conexion = conectar();

try {
    $conexion->begin_transaction();
    $stmt = $conexion->prepare("
        DELETE FROM usuarios_sistema
        WHERE id_usuario = ?
    ");
    $stmt->bind_param('i', $idUsuario);
    $stmt->execute();

    if ($stmt->affected_rows === 0) {
        throw new RuntimeException('Usuario no encontrado.');
    }

    $stmt->close();
    $conexion->commit();
    respuestaJson(
        true,
        'Usuario eliminado correctamente.'
    );
} catch (Throwable $e) {
    $conexion->rollback();
    error_log('Error al eliminar usuario: '. $e->getMessage());
    respuestaJson(
        false,
        'No se pudo eliminar el usuario.',
        null,
        500
    );
} finally {
    $conexion->close();
}