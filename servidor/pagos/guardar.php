<?php
declare(strict_types=1);
require_once appPath('servidor/config/database.php');
require_once appPath('servidor/helpers/respuesta.php');
require_once appPath('servidor/helpers/audit.php');

$conexion = conectar();
establecerAuditUser($conexion);

try {
  $datos = json_decode(file_get_contents('php://input'), true);

  $idPersona = (int) ($datos['id_persona'] ?? 0);
  $concepto = trim($datos['concepto'] ?? '');
  $monto = (float) ($datos['monto'] ?? 0);
  $fechaPago = $datos['fecha_pago'] ?? null;
  $metodoPago = trim($datos['metodo_pago'] ?? '');
  $comprobante = trim($datos['comprobante'] ?? '');
  $estado = trim($datos['estado'] ?? 'Pendiente');
  $observaciones = trim($datos['observaciones'] ?? '');

  $metodosValidos = ['Efectivo', 'Transferencia', 'Tarjeta de Crédito', 'Tarjeta de Débito', 'Depósito Bancario', 'Cheque'];
  $estadosValidos = ['Pendiente', 'Completado', 'Rechazado', 'Reembolsado'];

  if ($idPersona <= 0 || $concepto === '' || $monto < 0 || $fechaPago === null) {
    respuestaJson(false, 'Complete los campos obligatorios del pago (persona, concepto, monto y fecha).', null, 422);
  }

  if (!in_array($metodoPago, $metodosValidos, true)) {
    respuestaJson(false, 'Método de pago no válido.', null, 422);
  }

  if (!in_array($estado, $estadosValidos, true)) {
    $estado = 'Pendiente';
  }

  $sql = "INSERT INTO pagos
          (id_persona, concepto, monto, fecha_pago, metodo_pago, comprobante, estado, observaciones, fecha_creacion)
          VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())";

  $stmt = $conexion->prepare($sql);
  $stmt->bind_param('isdsssss', $idPersona, $concepto, $monto, $fechaPago, $metodoPago, $comprobante, $estado, $observaciones);
  $stmt->execute();
  $id = $conexion->insert_id;
  $stmt->close();

  respuestaJson(true, 'Pago registrado correctamente.', ['id_pago' => $id]);
} catch (Throwable $e) {
  error_log('Error al registrar pago: ' . $e->getMessage());
  respuestaJson(false, 'Error al registrar el pago.', null, 500);
} finally {
  $conexion->close();
}