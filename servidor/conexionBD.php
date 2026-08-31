<?php
$conexion = new mysqli(env('DB_HOST'), env('DB_USER'), env('DB_PASSWORD'), env('DB_NAME'));

if ($conexion->connect_error) {
die("Error de conexión: " . $conexion->connect_error);
}
?>



