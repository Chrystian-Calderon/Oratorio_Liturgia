<?php
session_start();
include("conexionBD.php"); // Asegúrate de que la ruta sea correcta

// 1. Verificamos que el usuario esté logueado y que los datos lleguen por POST
if (!isset($_SESSION['usuario_correo']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../cliente/IniciarSesion.php");
    exit();
}

// 2. Recogemos los datos que nos envió el formulario oculto y el textarea
$correo_usuario = $_SESSION['usuario_correo'];
$id_actividad   = $_POST['id_actividad'] ?? null;
$observaciones  = trim($_POST['txtobservaciones'] ?? '');

// 3. Validamos que haya seleccionado una actividad
if (empty($id_actividad)) {
    echo "<script>alert('Error: No seleccionaste ninguna actividad.'); window.location.href='../cliente/actividades.php';</script>";
    exit();
}

// 4. Primero, necesitamos buscar el ID del usuario basándonos en su correo actual
$stmt_usuario = $conexion->prepare("SELECT id_persona FROM personas WHERE correo = ?"); // Asegúrate de que se llame 'id_persona' en tu base de datos
$stmt_usuario->bind_param("s", $correo_usuario);
$stmt_usuario->execute();
$resultado_usuario = $stmt_usuario->get_result();

if ($fila_usuario = $resultado_usuario->fetch_assoc()) {
    $id_persona = $fila_usuario['id_persona'];

    // 5. Opcional pero recomendado: Verificar si la persona ya estaba inscrita
    $stmt_check = $conexion->prepare("SELECT id_inscripcion FROM inscripcion WHERE id_persona = ? AND id_actividad = ?");
    $stmt_check->bind_param("ii", $id_persona, $id_actividad);
    $stmt_check->execute();
    if ($stmt_check->get_result()->num_rows > 0) {
        echo "<script>alert('¡Ya estás inscrito en esta actividad!'); window.location.href='../cliente/actividades.php';</script>";
        exit();
    }

    // 6. ¡Todo en orden! Insertamos la inscripción en la base de datos
    // estado = 'Pre-inscrito' por defecto, asistencia = 0
    $estado_inicial = 'Pre-inscrito';
    $fecha_actual = date('Y-m-d H:i:s');
    $asistencia_inicial = 0;

    $stmt_insert = $conexion->prepare("INSERT INTO inscripcion (id_actividad, id_persona, cumple_requisitos, estado, fecha_inscripcion, observaciones, asistencia) VALUES (?, ?, 'No evaluado', ?, ?, ?, ?)");
    $stmt_insert->bind_param("iisssi", $id_actividad, $id_persona, $estado_inicial, $fecha_actual, $observaciones, $asistencia_inicial);
    
    if ($stmt_insert->execute()) {
        echo "<script>alert('¡Inscripción realizada con éxito! Pronto serás contactado.'); window.location.href='../cliente/Participar.php';</script>";
    } else {
        echo "<script>alert('Ocurrió un error al intentar inscribirte. Intenta de nuevo.'); window.location.href='../cliente/actividades.php';</script>";
    }

} else {
    // Si por alguna razón el correo de la sesión no existe en la BD
    echo "<script>alert('Error de validación de usuario.'); window.location.href='../cliente/logout.php';</script>";
}

$conexion->close();
?>