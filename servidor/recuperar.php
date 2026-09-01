<?php
require_once appPath('servidor/config/database.php');

$conexion = conectar();

// 1. Recibir correo
$correo = trim($_POST['correo'] ?? '');

// 2. Validar correo
if ($correo === '') {
    $_SESSION['recuperar'] = [
        'tipo'    => 'error',
        'mensaje' => 'El correo es obligatorio.',
    ];
    header('Location: ' . url('/recuperar-password'));
    exit;
}

// 3. Buscar usuario
$stmt = $conexion->prepare("SELECT * FROM personas WHERE correo = ?");
$stmt->bind_param("s", $correo);
$stmt->execute();
$result = $stmt->get_result();
$persona = $result->fetch_assoc();

if (!$persona) {
    $_SESSION['recuperar'] = [
        'tipo'    => 'error',
        'mensaje' => 'El correo ingresado no existe en el sistema.',
    ];
    header('Location: ' . url('/recuperar-password'));
    exit;
}

// 4. Verificar restricción de 45 días desde último cambio de contraseña
if ($persona['fecha_cambio_password'] !== null) {
    $fechaCambio = new DateTime($persona['fecha_cambio_password']);
    $ahora = new DateTime();
    $diferencia = $ahora->diff($fechaCambio)->days;

    if ($diferencia < 45) {
        $restantes = 45 - $diferencia;
        $_SESSION['recuperar'] = [
            'tipo'    => 'error',
            'mensaje' => "No puedes cambiar tu contraseña en este momento. Debes esperar {$restantes} días desde tu último cambio.",
        ];
        header('Location: ' . url('/recuperar-password'));
        exit;
    }
}

// 5. Crear token único
$token = bin2hex(random_bytes(16));

// 6. Fecha de expiración (1 hora)
$expira = date("Y-m-d H:i:s", strtotime("+1 hour"));

// 7. Guardar en BD
$update = $conexion->prepare(
    "UPDATE personas SET token=?, token_expira=? WHERE correo=?"
);
$update->bind_param("sss", $token, $expira, $correo);
$update->execute();

// 8. Guardar resultado para mostrarlo en la vista
$_SESSION['recuperar'] = [
    'tipo'    => 'success',
    'mensaje' => 'Haz clic en el botón para recuperar tu contraseña.',
    'link'    => url('/reset-password') . '?token=' . $token,
];

header('Location: ' . url('/recuperar-password'));
exit;