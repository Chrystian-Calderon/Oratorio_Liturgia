<?php
require_once appPath('servidor/config/database.php');
$conexion = conectar();

$correo = $_POST['txtcorreo'];
$password = $_POST['txtpassword'];

$errores = [];

if ($correo === '') {
    $errores['txtemail'] = 'El correo es obligatorio.';
} elseif (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
    $errores['txtemail'] = 'El correo no es válido.';
}

if ($password === '') {
    $errores['txtpassword'] = 'La contraseña es obligatoria.';
} elseif (strlen($password) < 8) {
    $errores['txtpassword'] = 'La contraseña debe tener al menos 8 caracteres.';
}

if (!empty($errores)) {
    $_SESSION['errors'] = $errores;
    $_SESSION['old'] = [
        'txtemail' => $correo,
    ];
    header('Location: ' . url('/login-admin'));
    exit;
}

$sql = "SELECT * FROM personas WHERE correo='$correo'";

$resultado = mysqli_query($conexion, $sql);

if(mysqli_num_rows($resultado) > 0){

    $usuario = mysqli_fetch_assoc($resultado);

    // Verificar contraseña encriptada
    if(password_verify($password, $usuario['password'])){
        if($usuario['tipo_persona'] == 'Administrativo'){
            $nombres = $usuario['nombres'];
            $apellido = $usuario['apellidos'];
            $_SESSION['usuario'] = $nombres . ' ' . $apellido;
            $_SESSION['correo'] = $usuario['correo'];
            $_SESSION['tipo_persona'] = $usuario['tipo_persona'];
            $_SESSION['id_usuario'] = $usuario['id_persona'];

            // Redireccionar
            header("Location: " . url('/panel-eventos'));
            exit();

        }else{
            $_SESSION['errors'] = ['txtpassword' => 'No tienes permisos para ingresar al panel administrativo'];
            $_SESSION['old'] = [
                'txtemail' => $correo,
            ];
            header('Location: ' . url('/login-admin'));
            exit;
        }

    }else{
        $_SESSION['errors'] = ['txtpassword' => 'Contraseña incorrecta'];
        $_SESSION['old'] = [
            'txtemail' => $correo,
        ];
        header('Location: ' . url('/login-admin'));
        exit;
    }

}else{
    $_SESSION['errors'] = ['txtemail' => 'Usuario no existe'];
    $_SESSION['old'] = [
        'txtemail' => $correo,
    ];
    header('Location: ' . url('/login-admin'));
    exit;
}
?>