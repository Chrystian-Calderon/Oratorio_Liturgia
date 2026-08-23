<?php
require_once appPath('servidor/conexionBD.php');

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

// Verificar que no estén vacíos
if (empty($email) || empty($password)) {
    echo "<script>
            alert('Debe ingresar correo y contraseña');
            window.location='../cliente/login.php';
          </script>";
    exit();
}

// =====================================================
// BUSCAR USUARIO
// =====================================================

$stmt = $conexion->prepare(
    "SELECT * FROM personas WHERE correo = ?"
);

$stmt->bind_param("s", $email);

$stmt->execute();

$resultado = $stmt->get_result();

// =====================================================
// VERIFICAR SI EXISTE EL USUARIO
// =====================================================

if ($usuario = $resultado->fetch_assoc()) {

    // =================================================
    // VERIFICAR CONTRASEÑA
    // =================================================

    if (password_verify($password, $usuario['password'])) {

        $_SESSION['correo'] = $usuario['correo'];
        // Regenerar sesión por seguridad
        session_regenerate_id(true);

        // =================================================
        // GUARDAR DATOS DEL USUARIO EN LA SESIÓN
        // =================================================

        $_SESSION['correo'] = $usuario['correo'];

        $_SESSION['nombre'] = $usuario['nombres'];
        $_SESSION['apellidos'] = $usuario['apellidos'];

        $_SESSION['apellidos'] = $usuario['apellidos'];

        $_SESSION['tipo_persona'] = $usuario['tipo_persona'];

        // =================================================
        // REDIRECCIONAR
        // =================================================

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