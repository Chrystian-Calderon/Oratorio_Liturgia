<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Asistencias</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
        <!-- Tarjeta -->
        <div class="card shadow-lg rounded-4">
            <div class="card-body p-4">
            <h3 class="text-center text-primary mb-4">Registro de Asistencia</h3>

            <!-- Formulario -->
            <form action="../servidor/validar_asistencias.php" method="POST">
                

                <!-- ID Inscripción -->
                <div class="col-md-6 mb-3">
                    <label for="id_inscripcion" class="form-label fw-medium text-secondary fs-6">ID Inscripción</label>
                    <input type="number" class="form-control" id="id_inscripcion" name="txtid_inscripcion" placeholder="Ingrese ID de la inscripción" required>
                </div>
                </div>

                <div class="row">
                <!-- Fecha -->
                <div class="col-md-6 mb-3">
                    <label for="fecha" class="form-label fw-medium text-secondary fs-6">Fecha</label>
                    <input type="date" class="form-control" id="fecha" name="txtfecha" required>
                </div>

                <!-- Asistió -->
                <div class="col-md-6 mb-3">
                    <label for="asistio" class="form-label fw-medium text-secondary fs-6">Asistió</label>
                    <select id="asistio" name="txtasistio" class="form-select" required>
                    <option value="" selected disabled>Seleccione</option>
                    <option value="1">Sí</option>
                    <option value="0">No</option>
                    </select>
                </div>
                </div>

                <div class="row">
                <!-- Observaciones -->
                <div class="col-md-6 mb-3">
                    <label for="observaciones" class="form-label fw-medium text-secondary fs-6">Observaciones</label>
                    <textarea class="form-control" id="observaciones" name="txtobservaciones" rows="2" placeholder="Ingrese observaciones si aplica"></textarea>
                </div>
                <?php
                require_once appPath('servidor/conexionDB.php');
                $query="SELECT id_persona,nombres FROM personas";
                $resultado=$conexion->query($query);
                ?>
                <!-- Registrado Por -->
                <div class="col-md-6 mb-3">
                    <label for="registrado_por" class="form-label fw-medium text-secondary fs-6">Registrado Por</label>
                    <select type="text" class="form-control" id="registrado_por" name="txtregistrado_por" required>
                            <option value="">Seleccione</option>
                            <?php
                            while($filas=$resultado->fetch_assoc()){?>
                            <option value="<?php echo $filas['id_persona']; ?>"><?php echo $filas['nombres']; ?></option>
                            <?php }?>
                            
                    </select>
                    
                </div>
                </div>

                <div class="row">
                <!-- Fecha Registro -->
                <div class="col-md-6 mb-3">
                    <label for="fecha_registro" class="form-label fw-medium text-secondary fs-6">Fecha Registro</label>
                    <input type="date" class="form-control" id="fecha_registro" name="txtfecha_registro" required>
                </div>
                </div>

                <!-- Botón -->
                <div class="d-grid mt-3">
                <button type="submit" class="btn btn-primary btn-lg">Registrar Asistencia</button>
                </div>
            </form>

            </div>
        </div>
        </div>
    </div>
    </div>
</body>

</html>