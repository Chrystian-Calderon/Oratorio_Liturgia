<?php
declare(strict_types=1);
require_once appPath('servidor/config/database.php');

$conexion = conectar();
$resultado = $conexion->query(
    "SELECT id_evento, nombre_evento, descripcion, fecha_evento, hora_evento,
            lugar, estado
     FROM eventos
     WHERE estado = 'Activo'
     ORDER BY fecha_evento ASC"
);
$eventos = $resultado->fetch_all(MYSQLI_ASSOC);
$conexion->close();

return $eventos;
