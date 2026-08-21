<?php
$conexion = new mysqli("localhost", "root", "1234", "oratorio");

if ($conexion->connect_error) {
die("Error de conexión: " . $conexion->connect_error);
}
?>



