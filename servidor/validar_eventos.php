<?php
include("conexionBD.php");
//Registrar
if (isset($_POST['action']) && $_POST['action'] == 'registrar') {
    $nombre_evento = $_POST['txtnombre_evento'];
    $descripcion = $_POST['txtdescripcion'];
    $fecha_evento = $_POST['txtfecha_evento'];
    $hora_evento = $_POST['txthora_evento'];
    $lugar = $_POST['txtlugar'];
    $estado = $_POST['txtestado'];
    $fecha_creacion = $_POST['txtfecha_creacion'];
    $fecha_actualizacion = $_POST['txtfecha_actualizacion'];

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
    '$fecha_creacion',
    '$fecha_actualizacion'
)";
    $resultado = mysqli_query($conexion, $consulta);

    if ($resultado) {
        echo "<script>
            alert('¡Registro exitoso!');
            window.location='../cliente/PaginaInicio.php';
        </script>";
    } else {
        echo "Error al insertar: " . mysqli_error($conexion);
    }
}

//ELIMINAR
if (isset($_GET['eliminar'])) {
    $id = intval($_GET['eliminar']); // seguridad
    $sql = "DELETE FROM eventos WHERE id_evento = $id";
    if ($conexion->query($sql) === TRUE) {
        header("Location: ../cliente/listarEventos.php");
        exit;
    } else {
        echo "Error al eliminar: " . $conexion->error;
    }
}

//EDITAR
if (isset($_POST['action']) && $_POST['action'] == 'editar') {
    $id = $_POST['txtid'];
    $nombre = $_POST['txtnombre'];
    $descripcion = $_POST['txtdescripcion'];
    $estado = $_POST['txtestado'];

    $sql = "UPDATE eventos
SET
    nombre_evento='$nombre',
    descripcion='$descripcion',
    fecha_evento='$fecha_evento',
    hora_evento='$hora_evento',
    lugar='$lugar',
    estado='$estado',
    fecha_actualizacion=NOW()
WHERE id_evento=$id";

    if ($conexion->query($sql)) {
        header("Location: ../cliente/listarEventos.php");
        exit;
    } else {
        echo "Error al actualizar: " . $conexion->error;
    }
}
