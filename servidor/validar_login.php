<?php
require_once appPath('servidor/config/database.php');

$conexion = conectar();

$email = trim($_POST['txtemail'] ?? '');
$password = trim($_POST['txtpassword'] ?? '');

$errores = [];

if ($email === '') {
    $errores['txtemail'] = 'El correo es obligatorio.';
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
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
        'txtemail' => $email,
    ];
    header('Location: ' . url('/login'));
    exit;
}

// BUSCAR USUARIO
$stmt = $conexion->prepare(
    "SELECT * FROM personas WHERE correo = ?"
);
$stmt->bind_param("s", $email);
$stmt->execute();
$resultado = $stmt->get_result();

if ($usuario = $resultado->fetch_assoc()) {
    if (password_verify($password, $usuario['password'])) {
        session_regenerate_id(true);
        $_SESSION['correo'] = $usuario['correo'];
        $_SESSION['nombre'] = $usuario['nombres'];
        $_SESSION['apellidos'] = $usuario['apellidos'];
        $_SESSION['tipo_persona'] = $usuario['tipo_persona'];

        header("Location: " . url('/inicio'));
        exit();

    } else {
        $_SESSION['errors'] = ['txtpassword' => 'Credenciales incorrectas'];
        $_SESSION['old'] = [
            'txtemail' => $email,
        ];
        header('Location: ' . url('/login'));
        exit;
    }

} else {
    $_SESSION['errors'] = ['txtemail' => 'Usuario no existe'];
    $_SESSION['old'] = [
        'txtemail' => $email,
    ];
    header('Location: ' . url('/login'));
    exit;
}
?>