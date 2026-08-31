<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscripción</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
        <!-- Tarjeta -->
        <div class="card shadow-lg rounded-4">
            <div class="card-body p-4">
            <h3 class="text-center text-primary mb-4">Registro de Inscripción</h3>

            <!-- Formulario -->
            <form action="../servidor/validar_inscripcion.php" method="POST">
                <div class="row">
                <!-- ID Inscripción -->
                <div class="col-md-6 mb-3">
                    <label for="id_inscripcion" class="form-label fw-medium text-secondary fs-6">ID Inscripción</label>
                    <input type="number" class="form-control" id="id_inscripcion" name="txtid_inscripcion" placeholder="Ingrese ID de la inscripción" required>
                </div>

                <!-- ID Actividad -->
                <div class="col-md-6 mb-3">
                    <label for="id_actividad" class="form-label fw-medium text-secondary fs-6">ID Actividad</label>
                    <input type="number" class="form-control" id="id_actividad" name="txtid_actividad" placeholder="Ingrese ID de la actividad" required>
                </div>
                </div>

                <div class="row">
                <!-- ID Persona -->
                <div class="col-md-6 mb-3">
                    <label for="id_persona" class="form-label fw-medium text-secondary fs-6">ID Persona</label>
                    <input type="number" class="form-control" id="id_persona" name="txtid_persona" placeholder="Ingrese ID de la persona" required>
                </div>

                <!-- ID Pago -->
                <div class="col-md-6 mb-3">
                    <label for="id_pago" class="form-label fw-medium text-secondary fs-6">ID Pago</label>
                    <input type="number" class="form-control" id="id_pago" name="txtid_pago" placeholder="Ingrese ID del pago">
                </div>
                </div>

                <div class="row">
                <!-- Cumple Requisitos -->
                <div class="col-md-6 mb-3">
                    <label for="cumple_requisitos" class="form-label fw-medium text-secondary fs-6">Cumple Requisitos</label>
                    <select id="cumple_requisitos" name="txtcumple_requisitos" class="form-select" required>
                    <option value="" selected disabled>Seleccione</option>
                    <option value="1">Sí</option>
                    <option value="0">No</option>
                    </select>
                </div>

                <!-- Estado -->
                <div class="col-md-6 mb-3">
                    <label for="estado" class="form-label fw-medium text-secondary fs-6">Estado</label>
                    <select id="estado" name="txtestado" class="form-select" required>
                    <option value="" selected disabled>Seleccione</option>
                    <option value="preinscrito">Pre-inscrito</option>
                    <option value="inscrito">Inscrito</option>
                    </select>
                </div>
                </div>

                <div class="row">
                <!-- Fecha Inscripción -->
                <div class="col-md-6 mb-3">
                    <label for="fecha_inscripcion" class="form-label fw-medium text-secondary fs-6">Fecha Inscripción</label>
                    <input type="date" class="form-control" id="fecha_inscripcion" name="txtfecha_inscripcion" required>
                </div>

                <!-- Fecha Actualización -->
                <div class="col-md-6 mb-3">
                    <label for="fecha_actualizacion" class="form-label fw-medium text-secondary fs-6">Fecha Actualización</label>
                    <input type="date" class="form-control" id="fecha_actualizacion" name="txtfecha_actualizacion">
                </div>
                </div>

                <div class="row">
                <!-- Observaciones -->
                <div class="col-md-6 mb-3">
                    <label for="observaciones" class="form-label fw-medium text-secondary fs-6">Observaciones</label>
                    <textarea class="form-control" id="observaciones" name="txtobservaciones" rows="2" placeholder="Ingrese observaciones si aplica"></textarea>
                </div>

                <!-- Asistencia -->
                <div class="col-md-3 mb-3">
                    <label for="asistencia" class="form-label fw-medium text-secondary fs-6">Asistencia</label>
                    <input type="number" class="form-control" id="asistencia" name="txtasistencia" placeholder="Número de asistencias">
                </div>

                <!-- Calificación -->
                <div class="col-md-3 mb-3">
                    <label for="calificacion" class="form-label fw-medium text-secondary fs-6">Calificación</label>
                    <input type="number" class="form-control" id="calificacion" name="txtcalificacion" placeholder="Calificación obtenida" min="0" max="100">
                </div>
                </div>

            <!-- Botón -->
                <div class="d-grid mt-3">
                <button type="submit" class="btn btn-primary btn-lg">Registrar Inscripción</button>
                </div>
            </form>

            </div>
        </div>
        </div>
    </div>
    </div>
</body>

</html>