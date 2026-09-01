<?php
declare(strict_types=1);

function obtenerPerfil(string $correo): ?array
{
    $conexion = null;
    $persona = null;

    try {
        require_once appPath('servidor/config/database.php');
        $conexion = conectar();
        $stmt = $conexion->prepare(
            "SELECT nombres, apellidos, ci, genero, telefono, correo, direccion, tipo_persona, estado, fecha_registro
             FROM personas WHERE correo = ?"
        );
        $stmt->bind_param("s", $correo);
        $stmt->execute();
        $persona = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        $conexion->close();
    } catch (Throwable $e) {
        error_log('Error obtener perfil: ' . $e->getMessage());
        if ($conexion) {
            $conexion->close();
        }
    }

    return $persona;
}
