<?php
session_start();

include("conexionBD.php");

$correo = $_POST['txtcorreo'];
$password = $_POST['txtpassword'];

$sql = "SELECT * FROM personas WHERE correo='$correo'";

$resultado = mysqli_query($conexion, $sql);

if(mysqli_num_rows($resultado) > 0){

    $usuario = mysqli_fetch_assoc($resultado);

    // Verificar contraseña encriptada
    if(password_verify($password, $usuario['password'])){

        // VALIDAR SI ES ADMINISTRATIVO
        if($usuario['tipo_persona'] == 'Administrativo'){

            // Crear sesión
            $_SESSION['usuario'] = $usuario['nombres'];
            $_SESSION['correo'] = $usuario['correo'];
            $_SESSION['tipo_persona'] = $usuario['tipo_persona'];

            // Redireccionar
            header("Location: ../cliente/Panel_Eventos.php");
            exit();

        }else{

            echo "
            <script>
                alert('No tienes permisos para ingresar al panel administrativo');
                window.location.href='../cliente/IniciarSesion.php';
            </script>
            ";

        }

    }else{

        echo "
        <script>
            alert('Contraseña incorrecta');
            window.location.href='../cliente/IniciarSesion.php';
        </script>
        ";

    }

}else{

    echo "
    <script>
        alert('Correo no encontrado');
        window.location.href='../cliente/IniciarSesion.php';
    </script>
    ";

}
?>