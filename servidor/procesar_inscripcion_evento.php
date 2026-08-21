<?php
session_start();

// 1. Verificamos que el usuario tenga una sesión activa
if (!isset($_SESSION['usuario_correo'])) {
    header("Location: ../cliente/login.php");
    exit();
}

require_once 'conexionBD.php';

// 2. Capturamos los datos enviados por el formulario
$id_evento = $_POST['id_evento'] ?? null;
$observaciones = $_POST['observaciones'] ?? '';
$correo_usuario = $_SESSION['usuario_correo'];

// Validamos de seguridad: que no intenten forzar el botón sin elegir evento
if (!$id_evento) {
    die("Error: No se seleccionó ningún evento válido.");
}

// 3. Traducimos el correo de la sesión a su 'id_persona' real
$stmt_persona = $conexion->prepare("SELECT id_persona FROM personas WHERE correo = ?");
$stmt_persona->bind_param("s", $correo_usuario);
$stmt_persona->execute();
$resultado_persona = $stmt_persona->get_result();

if ($resultado_persona->num_rows > 0) {
    $fila_persona = $resultado_persona->fetch_assoc();
    $id_persona = $fila_persona['id_persona'];
} else {
    die("Error crítico: Usuario no encontrado en el registro.");
}
$stmt_persona->close();

// 4. Verificamos si el joven ya se había inscrito antes para no duplicar datos
$stmt_check = $conexion->prepare("SELECT id_inscripcion_evento FROM inscripcion_eventos WHERE id_persona = ? AND id_evento = ?");
$stmt_check->bind_param("ii", $id_persona, $id_evento);
$stmt_check->execute();
$resultado_check = $stmt_check->get_result();

if ($resultado_check->num_rows > 0) {
    // Si ya existe, le avisamos
    $mensaje = "Ya te encontrabas inscrito en este evento. ¡Te esperamos!";
    $tipo_alerta = "warning";
    $icono = "fa-exclamation-triangle";
} else {
    // 5. Inyectamos la inscripción en la base de datos
    $estado_inicial = 'Pre-inscrito'; 
    $stmt_insert = $conexion->prepare("INSERT INTO inscripcion_eventos (id_persona, id_evento, observaciones, estado) VALUES (?, ?, ?, ?)");
    $stmt_insert->bind_param("iiss", $id_persona, $id_evento, $observaciones, $estado_inicial);

    if ($stmt_insert->execute()) {
        $mensaje = "¡Inscripción al evento confirmada con éxito!";
        $tipo_alerta = "success";
        $icono = "fa-check-circle";
    } else {
        $mensaje = "Ups, hubo un error al procesar tu inscripción: " . $conexion->error;
        $tipo_alerta = "danger";
        $icono = "fa-times-circle";
    }
    $stmt_insert->close();
}
$stmt_check->close();
$conexion->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estado de Inscripción</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        body { background-color: #f4f6f9; }
    </style>
</head>
<body>
<div class="container mt-5">
    <div class="row justify-content-center mt-5">
        <div class="col-md-6">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-body text-center p-5">
                    <i class="fas <?php echo $icono; ?> fa-5x text-<?php echo $tipo_alerta; ?> mb-4"></i>
                    <h2 class="fw-bold mb-3"><?php echo $mensaje; ?></h2>
                    <p class="text-muted mb-4">Tu lugar en el evento ha sido procesado por el sistema de la Pastoral.</p>
                    <a href="../cliente/eventos.php" class="btn btn-outline-<?php echo $tipo_alerta; ?> btn-lg px-5 rounded-pill fw-bold">Volver al panel</a>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>