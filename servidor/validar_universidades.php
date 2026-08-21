<?php
include("conexionBD.php");
$nombre=$_POST['txtnombre'];
$sigla=$_POST['txtsigla'];
$ciudad=$_POST['txtciudad'];
$direccion=$_POST['txtdireccion'];
$telefono=$_POST['txttelefono'];
$correo=$_POST['txtcorreo'];
$sitio_web=$_POST['txtsitio_web'];
$estado=$_POST['txtestado'];
$fecha_creacion=$_POST['txtfecha_creacion'];
$consulta ="INSERT INTO universidades(nombre,sigla,ciudad,direccion,telefono,correo,sitio_web,estado,fecha_creacion) VALUES('$nombre','$sigla','$ciudad','$direccion','$telefono','$correo','$sitio_web','$estado','$fecha_creacion')";
$resultado=mysqli_query($conexion,$consulta);
if ($resultado) {
    echo "<script>
            alert('¡Registro exitoso!');
            window.location='../cliente/universidades.php';
        </script>";
} else {
    echo "Error al insertar: " . mysqli_error($conexion);
}
?>