<?php
declare(strict_types=1);
require_once appPath('servidor/config/database.php');
require_once appPath('servidor/helpers/respuesta.php');

if (!isset($_SESSION['usuario']) || !in_array($_SESSION['tipo_persona'], ['Administrativo', 'Encargado'])) {
    respuestaJson(false, 'No autorizado.', null, 401);
}

$conexion = conectar();

try {
    $sugerenciasNuevas = (int) $conexion->query(
        "SELECT COUNT(*) AS total FROM sugerencias WHERE estado = 'Nuevo'"
    )->fetch_assoc()['total'];

    $contactoNuevos = (int) $conexion->query(
        "SELECT COUNT(*) AS total FROM contacto WHERE estado = 'Nuevo'"
    )->fetch_assoc()['total'];

    $conexion->close();

    respuestaJson(true, 'Conteo obtenido.', [
        'sugerencias_nuevas' => $sugerenciasNuevas,
        'contacto_nuevos'    => $contactoNuevos,
        'total'              => $sugerenciasNuevas + $contactoNuevos,
    ]);
} catch (Throwable $e) {
    error_log('Error contador sugerencias/contacto: ' . $e->getMessage());
    respuestaJson(false, 'Error al obtener el conteo.', null, 500);
}
