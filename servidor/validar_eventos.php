<?php

include("conexionBD.php");


/* ============================================================
   REGISTRAR EVENTO
   ============================================================ */

if (isset($_POST['action']) && $_POST['action'] == 'registrar') {

    $nombre_evento = $_POST['txtnombre_evento'];
    $descripcion = $_POST['txtdescripcion'];
    $fecha_evento = $_POST['txtfecha_evento'];
    $hora_evento = $_POST['txthora_evento'];
    $lugar = $_POST['txtlugar'];
    $estado = $_POST['txtestado'];

    /*
     * La fecha de creación se genera automáticamente
     * mediante NOW().
     */

    $consulta = "INSERT INTO eventos
    (
        nombre_evento,
        descripcion,
        fecha_evento,
        hora_evento,
        lugar,
        estado,
        fecha_creacion,
        fecha_actualizacion
    )
    VALUES
    (
        '$nombre_evento',
        '$descripcion',
        '$fecha_evento',
        '$hora_evento',
        '$lugar',
        '$estado',
        NOW(),
        NOW()
    )";


    $resultado = mysqli_query($conexion, $consulta);


    if ($resultado) {

        echo "<script>

            alert('¡Evento registrado correctamente!');

            window.location='../cliente/PaginaInicio.php';

        </script>";

    } else {

        echo "Error al insertar: "
            . mysqli_error($conexion);

    }
}



/* ============================================================
   ELIMINAR EVENTO
   ============================================================ */

if (isset($_GET['eliminar'])) {

    $id = intval($_GET['eliminar']);

    $sql = "
        DELETE FROM eventos
        WHERE id_evento = $id
    ";


    if ($conexion->query($sql) === TRUE) {

        header(
            "Location: ../cliente/listarEventos.php"
        );

        exit;

    } else {

        echo "Error al eliminar: "
            . $conexion->error;

    }
}



/* ============================================================
   EDITAR EVENTO
   ============================================================ */

if (
    isset($_POST['action']) &&
    $_POST['action'] == 'editar'
) {

    $id = intval($_POST['txtid']);

    $nombre = $_POST['txtnombre'];

    $descripcion = $_POST['txtdescripcion'];

    $fecha_evento = $_POST['txtfecha_evento'];

    $hora_evento = $_POST['txthora_evento'];

    $lugar = $_POST['txtlugar'];

    $estado = $_POST['txtestado'];


    /*
     * NO modificamos fecha_creacion.
     *
     * fecha_actualizacion se actualiza
     * automáticamente con NOW().
     */

    $sql = "UPDATE eventos
            SET
                nombre_evento = '$nombre',
                descripcion = '$descripcion',
                fecha_evento = '$fecha_evento',
                hora_evento = '$hora_evento',
                lugar = '$lugar',
                estado = '$estado',
                fecha_actualizacion = NOW()

            WHERE id_evento = $id";


    if ($conexion->query($sql)) {

        header(
            "Location: ../cliente/listarEventos.php"
        );

        exit;

    } else {

        echo "Error al actualizar: "
            . $conexion->error;

    }
}

?>

