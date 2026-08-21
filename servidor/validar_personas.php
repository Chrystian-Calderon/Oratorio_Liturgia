<?php
include("conexionBD.php");

// Recibir datos del formulario
$ci = trim($_POST['txtci']);
$nombres = trim($_POST['txtnombres']);
$apellidos = trim($_POST['txtapellidos']);
$genero = $_POST['txtgenero'];
$direccion = trim($_POST['txtdireccion']);
$telefono = trim($_POST['txttelefono']);
$correo = trim($_POST['txtcorreo']);

$password = password_hash($_POST['txtpassword'], PASSWORD_DEFAULT);

$tipo_persona = $_POST['txttipo_persona'];

$id_universidad = $_POST['id_universidad'];

// Verificar si el CI ya existe
$sql_ci = "SELECT id_persona FROM personas WHERE ci = '$ci'";
$resultado_ci = mysqli_query($conexion, $sql_ci);

if (mysqli_num_rows($resultado_ci) > 0) {
    echo "<script>
        alert('El CI ya se encuentra registrado.');
        window.history.back();
    </script>";
    exit();
}

// Verificar si el correo ya existe
$sql_correo = "SELECT id_persona FROM personas WHERE correo = '$correo'";
$resultado_correo = mysqli_query($conexion, $sql_correo);

if (mysqli_num_rows($resultado_correo) > 0) {
    echo "<script>
        alert('El correo electrónico ya está registrado.');
        window.history.back();
    </script>";
    exit();
}

// Consulta SQL
$consulta = "INSERT INTO personas
(
    ci,
    nombres,
    apellidos,
    genero,
    direccion,
    telefono,
    correo,
    password,
    tipo_persona,
    id_universidad
)
VALUES
(
    '$ci',
    '$nombres',
    '$apellidos',
    '$genero',
    '$direccion',
    '$telefono',
    '$correo',
    '$password',
    '$tipo_persona',
    '$id_universidad'
)";

$resultado = mysqli_query($conexion, $consulta);

if ($resultado) {

    echo "<script>
        alert('Persona registrada correctamente');
        window.location='../cliente/personas.php';
    </script>";

} else {

    echo "Error: " . mysqli_error($conexion);

}
?>