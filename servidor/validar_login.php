<?php

include("conexionBD.php");

session_start();

// =====================================================
// RECIBIR DATOS DEL FORMULARIO
// =====================================================

$email = trim($_POST['txtemail'] ?? '');
$password = $_POST['txtpassword'] ?? '';

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

        // Regenerar sesión por seguridad
        session_regenerate_id(true);

        // =================================================
        // GUARDAR DATOS DEL USUARIO EN LA SESIÓN
        // =================================================

        $_SESSION['correo'] = $usuario['correo'];

        $_SESSION['nombre'] = $usuario['nombres'];

        $_SESSION['apellidos'] = $usuario['apellidos'];

        $_SESSION['tipo_persona'] = $usuario['tipo_persona'];

        // =================================================
        // REDIRECCIONAR
        // =================================================

        header("Location: ../cliente/PaginaInicio.php");
        exit();

    } else {

        echo "<script>
                alert('Contraseña incorrecta');
                window.location='../cliente/login.php';
              </script>";
        exit();
    }

} else {

    echo "<script>
            alert('Usuario no existe');
            window.location='../cliente/login.php';
          </script>";
    exit();
}

$stmt->close();
$conexion->close();

?>