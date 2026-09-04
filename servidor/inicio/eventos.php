<?php

declare(strict_types=1);

require_once appPath('servidor/config/database.php');
require_once appPath('servidor/helpers/respuesta.php');

$conexion = conectar();

$stmt = $conexion->prepare(
    "SELECT id_evento, nombre_evento, descripcion, fecha_evento,
            hora_evento, lugar
     FROM eventos
     WHERE estado = 'Activo'
     ORDER BY
        CASE
            WHEN fecha_evento IS NULL OR fecha_evento < CURDATE() THEN 1
            ELSE 0
        END,
        fecha_evento ASC
     LIMIT 6"
);
$stmt->execute();
$eventos = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();
$conexion->close();

respuestaJson(true, 'Eventos obtenidos correctamente', $eventos);
