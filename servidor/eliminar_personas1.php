<?php
require_once("conexionBD.php");

if (isset($_GET['id'])) {

    $id_persona = $_GET['id'];

    $sql = "DELETE FROM personas WHERE id_persona = ?";

    $stmt = mysqli_prepare($conexion, $sql);

    mysqli_stmt_bind_param($stmt, "i", $id_persona);

    if (mysqli_stmt_execute($stmt)) {

        mysqli_stmt_close($stmt);
        mysqli_close($conexion);

        header("Location: ../cliente/personas1.php?mensaje=eliminado");
        exit();

    } else {

        mysqli_stmt_close($stmt);
        mysqli_close($conexion);

        header("Location: ../cliente/personas1.php?mensaje=error");
        exit();

    }
}
?>