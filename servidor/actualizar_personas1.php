<?php
require_once("conexionBD.php");

$id_persona = $_POST['id_persona'];
$ci = $_POST['ci'];
$nombres = $_POST['nombres'];
$apellidos = $_POST['apellidos'];
$genero = $_POST['genero'];
$direccion = $_POST['direccion'];
$telefono = $_POST['telefono'];
$correo = $_POST['correo'];
$tipo_persona = $_POST['tipo_persona'];
$estado = $_POST['estado'];

$sql = "UPDATE personas SET
            ci = ?,
            nombres = ?,
            apellidos = ?,
            genero = ?,
            direccion = ?,
            telefono = ?,
            correo = ?,
            tipo_persona = ?,
            estado = ?
        WHERE id_persona = ?";

$stmt = mysqli_prepare($conexion, $sql);

mysqli_stmt_bind_param(
    $stmt,
    "sssssssssi",
    $ci,
    $nombres,
    $apellidos,
    $genero,
    $direccion,
    $telefono,
    $correo,
    $tipo_persona,
    $estado,
    $id_persona
);

if (mysqli_stmt_execute($stmt)) {
    header("Location: ../cliente/personas1.php?mensaje=actualizado");
    exit();
} else {
    echo "Error al actualizar: " . mysqli_error($conexion);
}

mysqli_stmt_close($stmt);
mysqli_close($conexion);
?>