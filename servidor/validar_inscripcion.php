<?php
require_once appPath('servidor/config/database.php');

session_start();

$conexion = conectar();

// ==========================================================
// 1. RECIBIR DATOS
// ==========================================================

$id_actividad = isset($_POST['id_actividad']) ? (int)$_POST['id_actividad'] : 0;
$nombre = trim($_POST['nombre'] ?? '');
$apellidos = trim($_POST['apellidos'] ?? '');
$correo = trim($_POST['correo'] ?? '');
$ci = trim($_POST['ci'] ?? '');
$telefono = trim($_POST['telefono'] ?? '');
$observacion = trim($_POST['observacion'] ?? '');

// ==========================================================
// 2. VALIDAR DATOS
// ==========================================================

if ($id_actividad <= 0) {
    $_SESSION['notificacion'] = ['mensaje' => 'Actividad no válida.', 'tipo' => 'error'];
    header("Location: " . url('/ver-actividades'));
    exit;
}

if ($nombre === '' || $apellidos === '' || $correo === '' || $ci === '') {
    $_SESSION['notificacion'] = ['mensaje' => 'Complete todos los campos obligatorios.', 'tipo' => 'error'];
    header("Location: " . url('/inscripcion/registrar?id=' . $id_actividad));
    exit;
}

if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['notificacion'] = ['mensaje' => 'El correo electrónico no es válido.', 'tipo' => 'error'];
    header("Location: " . url('/inscripcion/registrar?id=' . $id_actividad));
    exit;
}

// ==========================================================
// 3. VERIFICAR ACTIVIDAD
// ==========================================================

$stmtActividad = $conexion->prepare("SELECT id_actividad, nombre_actividad, cupo_disponible, costo, estado FROM actividades WHERE id_actividad = ? LIMIT 1");
$stmtActividad->bind_param("i", $id_actividad);
$stmtActividad->execute();
$actividad = $stmtActividad->get_result()->fetch_assoc();
$stmtActividad->close();

if (!$actividad) {
    $_SESSION['notificacion'] = ['mensaje' => 'La actividad no existe.', 'tipo' => 'error'];
    header("Location: " . url('/ver-actividades'));
    exit;
}

if ($actividad['estado'] !== 'Activo') {
    $_SESSION['notificacion'] = ['mensaje' => 'La actividad no está disponible.', 'tipo' => 'error'];
    header("Location: " . url('/detalle-actividad?id=' . $id_actividad));
    exit;
}

// ==========================================================
// 4. VERIFICAR CUPOS
// ==========================================================

$cupoDisponible = (int)$actividad['cupo_disponible'];

if ($cupoDisponible <= 0) {
    $_SESSION['notificacion'] = ['mensaje' => 'Lo sentimos, los cupos para esta actividad están agotados.', 'tipo' => 'error'];
    header("Location: " . url('/detalle-actividad?id=' . $id_actividad));
    exit;
}

// ==========================================================
// 5. BUSCAR PERSONA POR CI
// ==========================================================

$stmtCI = $conexion->prepare("SELECT id_persona, nombres, apellidos, ci, correo, telefono FROM personas WHERE ci = ? LIMIT 1");
$stmtCI->bind_param("s", $ci);
$stmtCI->execute();
$personaPorCI = $stmtCI->get_result()->fetch_assoc();
$stmtCI->close();

if ($personaPorCI) {
    $id_persona = (int)$personaPorCI['id_persona'];
} else {
    // Buscar por correo
    $stmtCorreo = $conexion->prepare("SELECT id_persona, nombres, apellidos, ci, correo, telefono FROM personas WHERE correo = ? LIMIT 1");
    $stmtCorreo->bind_param("s", $correo);
    $stmtCorreo->execute();
    $personaPorCorreo = $stmtCorreo->get_result()->fetch_assoc();
    $stmtCorreo->close();

    if ($personaPorCorreo) {
        $id_persona = (int)$personaPorCorreo['id_persona'];
    } else {
        // Crear persona nueva
        $passwordHash = password_hash(bin2hex(random_bytes(8)), PASSWORD_DEFAULT);

        $stmtInsert = $conexion->prepare("INSERT INTO personas (nombres, apellidos, ci, correo, telefono, password) VALUES (?, ?, ?, ?, ?, ?)");
        $stmtInsert->bind_param("ssssss", $nombre, $apellidos, $ci, $correo, $telefono, $passwordHash);

        if (!$stmtInsert->execute()) {
            $_SESSION['notificacion'] = ['mensaje' => 'Error al registrar la información. Inténtalo de nuevo.', 'tipo' => 'error'];
            header("Location: " . url('/inscripcion/registrar?id=' . $id_actividad));
            exit;
        }

        $id_persona = $conexion->insert_id;
        $stmtInsert->close();
    }
}

// ==========================================================
// 6. VERIFICAR INSCRIPCIÓN DUPLICADA
// ==========================================================

$stmtExiste = $conexion->prepare("SELECT id_inscripcion FROM inscripcion WHERE id_actividad = ? AND id_persona = ? LIMIT 1");
$stmtExiste->bind_param("ii", $id_actividad, $id_persona);
$stmtExiste->execute();
$inscripcionExistente = $stmtExiste->get_result()->fetch_assoc();
$stmtExiste->close();

if ($inscripcionExistente) {
    $_SESSION['notificacion'] = ['mensaje' => 'Ya te encuentras inscrito en esta actividad.', 'tipo' => 'warning'];
    header("Location: " . url('/detalle-actividad?id=' . $id_actividad));
    exit;
}

// ==========================================================
// 7. INSERTAR INSCRIPCIÓN
// ==========================================================

$estado = 'Inscrito';
$cumple_requisitos = 'Si';
$asistencia = 1;
$calificacion = null;
$fecha_inscripcion = date('Y-m-d H:i:s');
$fecha_actualizacion = date('Y-m-d H:i:s');
$id_pago = null;

$stmtIns = $conexion->prepare("INSERT INTO inscripcion (id_actividad, id_persona, id_pago, cumple_requisitos, estado, fecha_inscripcion, fecha_actualizacion, observaciones, asistencia, calificacion) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
$stmtIns->bind_param("iiisssssis", $id_actividad, $id_persona, $id_pago, $cumple_requisitos, $estado, $fecha_inscripcion, $fecha_actualizacion, $observacion, $asistencia, $calificacion);

if (!$stmtIns->execute()) {
    $_SESSION['notificacion'] = ['mensaje' => 'Error al registrar la inscripción. Inténtalo de nuevo.', 'tipo' => 'error'];
    header("Location: " . url('/inscripcion/registrar?id=' . $id_actividad));
    exit;
}
$stmtIns->close();

// ==========================================================
// 8. REDUCIR CUPO
// ==========================================================

$stmtCupo = $conexion->prepare("UPDATE actividades SET cupo_disponible = cupo_disponible - 1 WHERE id_actividad = ? AND cupo_disponible > 0");
if ($stmtCupo) {
    $stmtCupo->bind_param("i", $id_actividad);
    $stmtCupo->execute();
    $stmtCupo->close();
}

$conexion->close();

// ==========================================================
// 9. FINALIZAR
// ==========================================================

$_SESSION['notificacion'] = ['mensaje' => '¡Inscripción realizada con éxito!', 'tipo' => 'success'];
header("Location: " . url('/detalle-actividad?id=' . $id_actividad));
exit;