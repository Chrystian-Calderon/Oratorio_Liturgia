<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscripción Sacramental</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card p-4 shadow">
                    <div class="mb-3">
                        <a href="../cliente/Participar.php" class="text-decoration-none text-secondary">
                            <i class="bi bi-arrow-left"></i> Volver al panel
                        </a>
                    </div>
                    <h2 class="card-title text-center mb-4">Formulario de Inscripción Sacramental</h2>
                    
                    <form action="../servidor/sacramentos_db.php" method="POST">
                        
                        <div class="mb-3">
                            <label for="sacramento" class="form-label">Sacramento a Recibir:</label>
                            <select id="sacramento" name="sacramento" class="form-select" required>
                                <option value="">Seleccione uno</option>
                                <option value="Bautizo">Bautizo</option>
                                <option value="Primera Comunión">Primera Comunión</option>
                                <option value="Confirmación">Confirmación</option>
                            </select>
                        </div>

                        <h3 class="mt-4">Datos del Solicitante</h3>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="nombre_solicitante" class="form-label">Nombre Completo:</label>
                                <input type="text" id="nombre_solicitante" name="nombre_solicitante" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="fecha_nacimiento" class="form-label">Fecha de Nacimiento:</label>
                                <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" class="form-control" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="lugar_nacimiento" class="form-label">Lugar de Nacimiento:</label>
                            <input type="text" id="lugar_nacimiento" name="lugar_nacimiento" class="form-control" required>
                        </div>

                        <h3 class="mt-4">Datos de Padres y Padrinos</h3>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="nombre_padre" class="form-label">Nombre del Padre:</label>
                                <input type="text" id="nombre_padre" name="nombre_padre" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="nombre_madre" class="form-label">Nombre de la Madre:</label>
                                <input type="text" id="nombre_madre" name="nombre_madre" class="form-control" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="nombre_padrino" class="form-label">Nombre del Padrino:</label>
                                <input type="text" id="nombre_padrino" name="nombre_padrino" class="form-control">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="nombre_madrina" class="form-label">Nombre de la Madrina:</label>
                                <input type="text" id="nombre_madrina" name="nombre_madrina" class="form-control">
                            </div>
                        </div>

                        <h3 class="mt-4">Información de Contacto</h3>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="telefono" class="form-label">Teléfono:</label>
                                <input type="tel" id="telefono" name="telefono" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email:</label>
                                <input type="email" id="email" name="email" class="form-control" required>
                            </div>
                        </div>

                        <div class="d-grid mt-4">
                            <button type="submit" class="btn btn-primary btn-lg">Enviar Inscripción</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>