<?php
require_once appPath('servidor/config/database.php');
$conexion = conectar();

// ======================================
// OBTENER TOKEN (POST o GET)
// ======================================
$token = trim($_POST['token'] ?? $_GET['token'] ?? '');

// ======================================
// VERIFICAR TOKEN
// ======================================
$stmt = $conexion->prepare(
    "SELECT * FROM personas WHERE token = ? AND token_expira > NOW()"
);
$stmt->bind_param("s", $token);
$stmt->execute();
$result = $stmt->get_result();
$tokenValido = (bool) $result->fetch_assoc();

// ======================================
// ACTUALIZAR CONTRASEÑA
// ======================================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $password = trim($_POST['password'] ?? '');

    if ($password === '') {
        header('Location: ' . url('/reset-password') . '?token=' . urlencode($token));
        exit;
    }

    if (!$tokenValido) {
        $_SESSION['reset'] = [
            'tipo'    => 'error',
            'mensaje' => 'El enlace expiró o ya fue utilizado.',
        ];
        header('Location: ' . url('/reset-password'));
        exit;
    }

    $nueva = password_hash($password, PASSWORD_BCRYPT);

    $update = $conexion->prepare(
        "UPDATE personas SET password=?, token=NULL, token_expira=NULL WHERE token=?"
    );
    $update->bind_param("ss", $nueva, $token);
    $update->execute();

    $_SESSION['reset'] = ['tipo' => 'success'];

    header('Location: ' . url('/reset-password'));
    exit;
}

// ======================================
// VALIDAR TOKEN (GET): dejar estado para la vista
// ======================================
if (!isset($_SESSION['reset'])) {

    if ($token === '' || !$tokenValido) {
        $_SESSION['reset'] = [
            'tipo'    => 'error',
            'mensaje' => 'El enlace expiró o ya fue utilizado.',
        ];
    } else {
        $_SESSION['reset'] = ['tipo' => 'form'];
    }
}