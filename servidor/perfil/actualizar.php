<?php
declare(strict_types=1);
require_once appPath('servidor/config/database.php');
require_once appPath('servidor/helpers/respuesta.php');

if ($_SERVER['REQUEST_METHOD'] !== 'PUT') {
    respuestaJson(false, 'Método no permitido.', null, 405);
}

if (!isset($_SESSION['correo'])) {
    respuestaJson(false, 'No hay sesión activa.', null, 401);
}

$input = json_decode(file_get_contents('php://input'), true);

if (!$input) {
    respuestaJson(false, 'No se recibieron datos.', null, 400);
}

$conexion = conectar();

$correoActual = $_SESSION['correo'];

// Obtener persona actual
$stmt = $conexion->prepare("SELECT * FROM personas WHERE correo = ?");
$stmt->bind_param("s", $correoActual);
$stmt->execute();
$persona = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$persona) {
    $conexion->close();
    respuestaJson(false, 'Usuario no encontrado.', null, 404);
}

// Datos a actualizar
$nombres = trim($input['nombres'] ?? $persona['nombres']);
$apellidos = trim($input['apellidos'] ?? $persona['apellidos']);
$telefono = trim($input['telefono'] ?? $persona['telefono']);
$direccion = trim($input['direccion'] ?? $persona['direccion']);
$nuevoCorreo = trim($input['correo'] ?? $persona['correo']);
$password = $input['password'] ?? '';

// Validaciones
if ($nombres === '') {
    respuestaJson(false, 'Los nombres son obligatorios.', null, 400);
}
if ($apellidos === '') {
    respuestaJson(false, 'Los apellidos son obligatorios.', null, 400);
}
if ($nuevoCorreo === '' || !filter_var($nuevoCorreo, FILTER_VALIDATE_EMAIL)) {
    respuestaJson(false, 'El correo no es válido.', null, 400);
}

// Verificar si el correo ya existe en otro registro
if ($nuevoCorreo !== $correoActual) {
    $check = $conexion->prepare("SELECT id_persona FROM personas WHERE correo = ? AND id_persona != ?");
    $check->bind_param("si", $nuevoCorreo, $persona['id_persona']);
    $check->execute();
    $existe = $check->get_result()->num_rows > 0;
    $check->close();
    if ($existe) {
        respuestaJson(false, 'El correo ya está registrado por otro usuario.', null, 400);
    }
}

// Actualizar contraseña solo si se proporcionó
if ($password !== '') {
    if (strlen($password) < 8) {
        respuestaJson(false, 'La contraseña debe tener al menos 8 caracteres.', null, 400);
    }
    $hash = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $conexion->prepare("UPDATE personas SET nombres=?, apellidos=?, telefono=?, direccion=?, correo=?, password=? WHERE id_persona=?");
    $stmt->bind_param("ssssssi", $nombres, $apellidos, $telefono, $direccion, $nuevoCorreo, $hash, $persona['id_persona']);
} else {
    $stmt = $conexion->prepare("UPDATE personas SET nombres=?, apellidos=?, telefono=?, direccion=?, correo=? WHERE id_persona=?");
    $stmt->bind_param("sssssi", $nombres, $apellidos, $telefono, $direccion, $nuevoCorreo, $persona['id_persona']);
}

$stmt->execute();
$stmt->close();
$conexion->close();

// Actualizar sesión
$_SESSION['usuario'] = $nombres . ' ' . $apellidos;
$_SESSION['correo'] = $nuevoCorreo;

respuestaJson(true, 'Perfil actualizado correctamente.', [
    'nombres' => $nombres,
    'apellidos' => $apellidos,
    'correo' => $nuevoCorreo,
    'telefono' => $telefono,
    'direccion' => $direccion,
]);