<?php
/**
 * Helper de Auditoría
 *
 * Proporciona la función establecerAuditUser() que configura
 * la variable de sesión @audit_user_id de MariaDB para que
 * los triggers de auditoría conozcan el id_usuario de PHP.
 *
 * Uso: llamar ANTES de cada INSERT/UPDATE/DELETE en tablas auditadas.
 */

/**
 * Establece la variable @audit_user_id en la sesión de MariaDB.
 * Esto permite que los triggers de auditoría registren qué usuario
 * de la aplicación realizó cada operación.
 *
 * @param mysqli $conexion Conexión activa a la base de datos
 */
function establecerAuditUser(mysqli $conexion): void
{
    $idUsuario = $_SESSION['id_usuario'] ?? null;

    $stmt = $conexion->prepare("SET @audit_user_id = ?");
    if ($idUsuario !== null) {
        $stmt->bind_param("i", $idUsuario);
    } else {
        $null = null;
        $stmt->bind_param("i", $null);
    }
    $stmt->execute();
    $stmt->close();
}
