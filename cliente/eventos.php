<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Eventos</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

</head>

<body>

    <div class="container"> <br>
        <!-- Formulario -->
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card shadow-lg rounded-4">
                    <div class="card-body p-4">
                        <form action="../servidor/validar_eventos.php" method="POST">
                            <h3>Registro de Eventos</h3>
                            <input type='hidden' name='action' value='registrar'>
                            <div class="row">
                                <div class="col-md-6 mb-3">

                                    <!--<label for="id_evento" class="form-label">ID Evento</label>
                                <input type="hidden" class="form-control" id="id_evento" name="txtid_evento" placeholder="Ingrese ID del evento" required>
                            </div>  -->
                                    <div class="col-md-6 mb-3">
                                        <label for="nombre_evento" class="form-label">Nombre Evento</label>
                                        <input type="text" class="form-control" id="nombre_evento" name="txtnombre_evento" placeholder="Ingrese el nombre del evento" required>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                        <label for="descripcion" class="form-label">Descripción</label>
                                        <textarea class="form-control" id="descripcion" name="txtdescripcion" rows="3" placeholder="Ingrese descripción del evento"></textarea>
                                    </div>
                                </div>

                                <div class="row">

                                    <div class="col-md-4 mb-3">
                                        <label for="fecha_evento" class="form-label">Fecha del Evento</label>
                                        <input type="date"
                                            class="form-control"
                                            id="fecha_evento"
                                            name="txtfecha_evento"
                                            required>
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label for="hora_evento" class="form-label">Hora del Evento</label>
                                        <input type="time"
                                            class="form-control"
                                            id="hora_evento"
                                            name="txthora_evento"
                                            required>
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label for="lugar" class="form-label">Lugar</label>
                                        <input type="text"
                                            class="form-control"
                                            id="lugar"
                                            name="txtlugar"
                                            placeholder="Ej.: Salón Principal"
                                            required>
                                    </div>

                                </div>



                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="estado" class="form-label">Estado</label>
                                        <select id="estado" name="txtestado" class="form-select" required>
                                            <option value="" selected disabled>Seleccione</option>
                                            <option value="1">Activo</option>
                                            <option value="0">Inactivo</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="fecha_creacion" class="form-label">Fecha Creación</label>
                                        <input type="date" class="form-control" id="fecha_creacion" name="txtfecha_creacion" required>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="fecha_actualizacion" class="form-label">Fecha Actualización</label>
                                        <input type="date" class="form-control" id="fecha_actualizacion" name="txtfecha_actualizacion">
                                    </div>
                                </div>

                                <div class="d-grid mt-3">
                                    <button type="submit" class="btn btn-primary btn-lg mb-2">Registrar Evento</button>
                                    <a href="./listarEventos.php" class="btn btn-primary btn-lg">Listar Eventos</a>
                                </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>

</html>