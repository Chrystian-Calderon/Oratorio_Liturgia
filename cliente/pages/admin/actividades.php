<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Registro de Actividades</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-lg rounded-4">
                <div class="card-body p-4">
                    <form action="../servidor/validar_actividades.php" method="POST">
                        <h3>Registro de Actividades</h3>
                        <input type='hidden' name='action' value='registrar'>

                            <div class="col-md-6 mb-3">
                                <label>Nombre Actividad</label>
                                <input type="text" class="form-control" name="txtnombre_actividad" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label>Tipo Actividad</label>
                                <input type="text" class="form-control" name="txttipo_actividad" required>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label>Fecha Inicio</label>
                                <input type="date" class="form-control" name="txtfecha_inicio" required>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label>Fecha Fin</label>
                                <input type="date" class="form-control" name="txtfecha_fin" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <label>Días Semana</label>
                                <input type="text" class="form-control" name="txtdias_semana">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label>Hora Inicio</label>
                                <input type="time" class="form-control" name="txthora_inicio" required>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label>Hora Fin</label>
                                <input type="time" class="form-control" name="txthora_fin" required>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label>Duración</label>
                                <input type="text" class="form-control" name="txtduracion">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label>Requisitos</label>
                                <input type="text" class="form-control" name="txtrequisitos">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label>Costo</label>
                                <input type="number" class="form-control" name="txtcosto" min="0">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label>Cupo Máximo</label>
                                <input type="number" class="form-control" name="txtcupo_maximo">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <label>Cupo Disponible</label>
                                <input type="number" class="form-control" name="txtcupo_disponible">
                            </div>
                            <div class="col-md-9 mb-3">
                                <label>Descripción</label>
                                <textarea class="form-control" name="txtdescripcion" rows="2"></textarea>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label>ID Evento</label>
                                <input type="number" class="form-control" name="txtid_evento" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Estado</label>
                                <select class="form-select" name="txtestado" required>
                                    <option disabled selected>Seleccione</option>
                                    <option value="1">Activo</option>
                                    <option value="0">Inactivo</option>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label>Fecha Creación</label>
                                <input type="date" class="form-control" name="txtfecha_creacion" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Fecha Actualización</label>
                                <input type="date" class="form-control" name="txtfecha_actualizacion">
                            </div>
                        </div>

                        <div class="d-grid mt-3">
                            <button type="submit" class="btn btn-primary btn-lg mb-2">Registrar Actividad</button>
                            <a href="listarActividades.php" class="btn btn-primary btn-lg">Listar Actividades</a>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>
