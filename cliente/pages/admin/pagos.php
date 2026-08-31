<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pagos</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css">
</head>

<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="mb-3">
                    <a href="../cliente/Participar.php" class="text-decoration-none text-secondary">
                        <i class="bi bi-arrow-left"></i> Volver al panel
                    </a>
                </div>
                <!-- Tarjeta -->
                <div class="card shadow-lg rounded-4">
                    <div class="card-body p-4">
                        <h3 class="text-center text-primary mb-4">Registro de Pagos</h3>

                        <!-- Formulario -->
                        <form action="../servidor/validar_pagos.php" method="POST">
                            <div class="row">
                                <!-- ID Pago -->
                                <div class="col-md-6 mb-3">
                                    <label for="id_pago" class="form-label fw-medium text-secondary fs-6">ID Pago</label>
                                    <input type="number" class="form-control" id="id_pago" name="txtid_pago" placeholder="Ingrese ID del pago" required>
                                </div>

                                <!-- ID Persona -->
                                <div class="col-md-6 mb-3">
                                    <label for="id_persona" class="form-label fw-medium text-secondary fs-6">ID Persona</label>
                                    <input type="number" class="form-control" id="id_persona" name="txtid_persona" placeholder="Ingrese ID de la persona" required>
                                </div>
                            </div>

                            <div class="row">
                                <!-- Concepto -->
                                <div class="col-md-6 mb-3">
                                    <label for="concepto" class="form-label fw-medium text-secondary fs-6">Concepto</label>
                                    <input type="text" class="form-control" id="concepto" name="txtconcepto" placeholder="Ingrese concepto del pago" required>
                                </div>

                                <!-- Monto -->
                                <div class="col-md-3 mb-3">
                                    <label for="monto" class="form-label fw-medium text-secondary fs-6">Monto</label>
                                    <input type="number" class="form-control" id="monto" name="txtmonto" placeholder="Ej: 100" min="0" required>
                                </div>

                                <!-- Fecha Pago -->
                                <div class="col-md-3 mb-3">
                                    <label for="fecha_pago" class="form-label fw-medium text-secondary fs-6">Fecha Pago</label>
                                    <input type="date" class="form-control" id="fecha_pago" name="fecha_pago" required>
                                </div>
                            </div>

                            <div class="row">
                                <!-- Método de Pago -->
                                <div class="col-md-6 mb-3">
                                    <label for="metodo_pago" class="form-label fw-medium text-secondary fs-6">Método de Pago</label>
                                    <select id="metodo_pago" name="txtmetodo_pago" class="form-select" required>
                                        <option value="" selected disabled>Seleccione</option>
                                        <option value="efectivo">Efectivo</option>
                                        <option value="transferencia">Transferencia</option>
                                        <option value="tarjeta">Tarjeta</option>
                                    </select>
                                </div>

                                <!-- Comprobante -->
                                <div class="col-md-6 mb-3">
                                    <label for="comprobante" class="form-label fw-medium text-secondary fs-6">Comprobante</label>
                                    <input type="text" class="form-control" id="comprobante" name="txtcomprobante" placeholder="Número o código del comprobante">
                                </div>
                            </div>

                            <div class="row">
                                <!-- Estado -->
                                <div class="col-md-6 mb-3">
                                    <label for="estado" class="form-label fw-medium text-secondary fs-6">Estado</label>
                                    <select class="form-select" id="estado" name="txtestado" required>
                                        <option value="" disabled selected>Seleccione un estado</option>
                                        <option value="Activo">Activo</option>
                                        <option value="Pendiente">Pendiente</option>
                                        <option value="Inactivo">Inactivo</option>
                                    </select>
                                </div>
                            </div>


                            <!-- Observaciones -->
                            <div class="col-md-6 mb-3">
                                <label for="observaciones" class="form-label fw-medium text-secondary fs-6">Observaciones</label>
                                <textarea class="form-control" id="observaciones" name="txtobservaciones" rows="2" placeholder="Ingrese observaciones si aplica"></textarea>
                            </div>
                    </div>

                    <div class="row">
                        <!-- Fecha Creación -->
                        <div class="col-md-6 mb-3">
                            <label for="fecha_creacion" class="form-label fw-medium text-secondary fs-6"> Fecha Creación</label>
                            <input type="date" class="form-control" id="fecha_creacion" name="txtfecha_creacion" required>
                        </div>
                    </div>

                    <!-- Botón -->
                    <div class="d-grid mt-3">
                        <button type="submit" class="btn btn-primary btn-lg">Registrar Pago</button>
                    </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
    </div>
</body>

</html>