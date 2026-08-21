<?php
include("conexionBD.php");

$rol = $_POST['txtrol'];
$permisos = "";

if (isset($_POST['permisos'])) {

    $permisos = implode(",", $_POST['permisos']);
}


$consulta = " INSERT INTO usuarios_sistema (rol,permisos) VALUES ('$rol','$permisos')";

$resultado = mysqli_query($conexion, $consulta);

if ($resultado) {
    echo "<script>
            alert('¡Registro exitoso!');
            window.location='../cliente/usuarios_sistema.php';
        </script>";
} else {
    echo "Error al insertar: " . mysqli_error($conexion);
}
