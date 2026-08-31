<?php
require_once appPath('servidor/config/database.php');
$conexion = conectar();

// Recibir datos
$nombre = $_POST['txtnombre'];
$apellidos = $_POST['txtapellidos'];
$ci = $_POST['txtci'];
$correo = $_POST['txtcorreo'];
$password = $_POST['txtpassword'];

$errores = [];

if ($nombre === '') {
    $errores['txtnombre'] = 'El nombre es obligatorio.';
}
if ($apellidos === '') {
    $errores['txtapellidos'] = 'El apellido es obligatorio.';
}
if ($ci === '') {
    $errores['txtci'] = 'El carnet de identidad es obligatorio.';
}
if ($correo === '') {
    $errores['txtcorreo'] = 'El correo es obligatorio.';
} elseif (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
    $errores['txtcorreo'] = 'El correo no es válido.';
}
if ($password === '') {
    $errores['txtpassword'] = 'La contraseña es obligatoria.';
} elseif (strlen($password) < 8) {
    $errores['txtpassword'] = 'La contraseña debe tener al menos 8 caracteres.';
}

if (!empty($errores)) {
    $_SESSION['errors'] = $errores;
    $_SESSION['old'] = [
        'txtnombre' => $nombre,
        'txtapellidos' => $apellidos,
        'txtci' => $ci,
        'txtcorreo' => $correo,
    ];
    header('Location: ' . url('/registrarse'));
    exit;
}

// Encriptar contraseña
$passwordHash = password_hash($password, PASSWORD_BCRYPT);

// Verificar si el correo existe
$verificar = "SELECT * FROM personas WHERE correo='$correo'";

$resultado = mysqli_query($conexion, $verificar);

if(mysqli_num_rows($resultado) > 0){
    $_SESSION['errors'] = ['txtcorreo' => 'El correo ya existe.'];
    header('Location: ' . url('/registrarse'));
    exit();
}

// Insertar usuario
$sql = "INSERT INTO personas(nombres, apellidos, ci, correo, password)
VALUES('$nombre','$apellidos','$ci','$correo','$passwordHash')";

if(mysqli_query($conexion, $sql)){
    $_SESSION['notificacion'] = [
        'mensaje' => 'Usuario registrado exitosamente.',
        'tipo' => 'success'
    ];
    header('Location: ' . url('/login'));
    exit();
}else{
    $_SESSION['notificacion'] = [
        'mensaje' => 'Error al registrar el usuario. Por favor, inténtalo de nuevo.',
        'tipo' => 'error'
    ];
    header('Location: ' . url('/registrarse'));
    exit();
}

?>