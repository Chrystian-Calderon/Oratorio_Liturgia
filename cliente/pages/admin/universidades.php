<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Universidades</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css">
</head>
    <body>
    <div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
        <!-- Tarjeta -->
        <div class="card shadow-lg rounded-4">
            <div class="card-body p-4">
            <h3 class="text-center text-primary mb-4">Registro de Universidad</h3>

            <!-- Formulario -->
            <form action="../servidor/validar_universidades.php" method="POST">
                <div class="row">
                <!-- ID Universidad -->
                <div class="col-md-6 mb-3">
                    <label for="id_universidad" class="form-label fw-medium text-secondary fs-6">ID Universidad</label>
                    <input type="number" class="form-control" id="id_universidad" name="txtid_universidad" placeholder="Ingrese ID de la universidad" required>
                </div>

                <!-- Nombre -->
                <div class="col-md-6 mb-3">
                    <label for="nombre" class="form-label fw-medium text-secondary fs-6">Nombre</label>
                    <input type="text" class="form-control" id="nombre" name="txtnombre" placeholder="Ingrese nombre de la universidad" required>
                </div>
                </div>

                <div class="row">
                <!-- Sigla -->
                <div class="col-md-3 mb-3">
                    <label for="sigla" class="form-label fw-medium text-secondary fs-6">Sigla</label>
                    <input type="text" class="form-control" id="sigla" name="txtsigla" placeholder="Ej: USAL" required>
                </div>

                <!-- Ciudad -->
                <div class="col-md-3 mb-3">
                    <label for="ciudad" class="form-label fw-medium text-secondary fs-6">Ciudad</label>
                    <input type="text" class="form-control" id="ciudad" name="txtciudad" placeholder="Ingrese ciudad" required>
                </div>

                <!-- Dirección -->
                <div class="col-md-3 mb-3">
                    <label for="direccion" class="form-label fw-medium text-secondary fs-6">Dirección</label>
                    <input type="text" class="form-control" id="direccion" name="txtdireccion"  placeholder="Ingrese dirección" required>
                </div>

                <!-- Teléfono -->
                <div class="col-md-3 mb-3">
                    <label for="telefono" class="form-label fw-medium text-secondary fs-6">Teléfono</label>
                    <input type="text" class="form-control" id="telefono" name="txttelefono" placeholder="Ingrese teléfono" required>
                </div>
                </div>

                <div class="row">
                <!-- Correo -->
                <div class="col-md-6 mb-3">
                    <label for="correo" class="form-label fw-medium text-secondary fs-6">Correo</label>
                    <input type="email" class="form-control" id="correo" name="txtcorreo" placeholder="Ingrese correo electrónico" required>
                </div>

                <!-- Sitio Web -->
                <div class="col-md-6 mb-3">
                    <label for="sitio_web" class="form-label fw-medium text-secondary fs-6">Sitio Web</label>
                    <input type="url" class="form-control" id="sitio_web" name="txtsitio_web" placeholder="Ingrese URL del sitio web">
                </div>
                </div>

                <div class="row">
                <!-- Estado -->
                <div class="col-md-6 mb-3">
                    <label for="estado" class="form-label fw-medium text-secondary fs-6">Estado</label>
                    <select id="estado" name="txtestado" class="form-select" required>
                    <option value="" selected disabled>Seleccione</option>
                    <option value="activo">Activo</option>
                    <option value="inactivo">Inactivo</option>
                    </select>
                </div>

                <!-- Fecha Creación -->
                <div class="col-md-6 mb-3">
                    <label for="fecha_creacion" class="form-label fw-medium text-secondary fs-6">Fecha Creación</label>
                    <input type="date" class="form-control" id="fecha_creacion" name="txtfecha_creacion" required>
                </div>
                </div>

                <!-- Botón -->
                <div class="d-grid mt-3">
                <button type="submit" class="btn btn-primary btn-lg">Registrar Universidad</button>
                </div>
            </form>

            </div>
        </div>
        </div>
    </div>
    </div>
</body>

</html>