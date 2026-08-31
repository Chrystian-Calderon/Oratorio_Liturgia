<?php
require_once appPath("servidor/conexionBD.php");

$sql = "SELECT
            p.id_persona,
            p.ci,
            p.nombres,
            p.apellidos,
            p.genero,
            p.direccion,
            p.telefono,
            p.correo,
            p.tipo_persona,
            p.estado,
            u.sigla AS universidad,
            us.rol
        FROM personas p
        LEFT JOIN universidades u
            ON p.id_universidad = u.id_universidad
        LEFT JOIN usuarios_sistema us
            ON p.id_usuario = us.id_usuario
        ORDER BY p.id_persona ASC";

$resultado = mysqli_query($conexion, $sql);
$sqlUniversidades = "SELECT id_universidad, sigla
                        FROM universidades
                        WHERE estado='Activo'
                        ORDER BY sigla ASC";
$universidades = mysqli_query($conexion, $sqlUniversidades);
?>

<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Personas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">

</head>

<body class="bg-light">
    <div class="container py-4">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">

                <h4>
                    <i class="fas fa-users"></i>
                    Personas Registradas
                </h4>
            </div>
            <?php if (isset($_GET['mensaje'])) { ?>

                <?php if ($_GET['mensaje'] == "actualizado") { ?>

                    <div class="alert alert-success alert-dismissible fade show m-3" role="alert">
                        <i class="fas fa-check-circle"></i>
                        <strong>¡Éxito!</strong> La persona fue actualizada correctamente.
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>

                <?php } ?>

                <?php if ($_GET['mensaje'] == "eliminado") { ?>

                    <div class="alert alert-danger alert-dismissible fade show m-3" role="alert">
                        <i class="fas fa-trash"></i>
                        <strong>¡Éxito!</strong> La persona fue eliminada correctamente.
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>

                <?php } ?>

                <?php if ($_GET['mensaje'] == "error") { ?>

                    <div class="alert alert-warning alert-dismissible fade show m-3" role="alert">
                        <i class="fas fa-exclamation-triangle"></i>
                        <strong>Atención:</strong> No se pudo eliminar la persona.
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>

                <?php } ?>

        
            <?php } ?>
            <!--Buscador-->
            <div class="row mb-3">

                <div class="col-md-6">
                    <input
                        type="text"
                        id="buscar"
                        class="form-control"
                        placeholder="Buscar por CI, nombre, tipo o rol...">
                </div>

                

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover table-striped table-bordered align-middle text-center">

                        <thead class="table-dark text-center align-middle">
                            <tr>
                                <th width="60">ID</th>
                                <th width="90">CI</th>
                                <th>Nombre Completo</th>
                                <th>Tipo</th>
                                <th>Universidad</th>
                                <th>Rol</th>
                                <th width="100">Estado</th>
                                <th width="170">Acciones</th>
                            </tr>
                        </thead>

                        <tbody>

                            <?php while ($fila = mysqli_fetch_assoc($resultado)) { ?>

                                <tr>
                                    <td><?= $fila['id_persona']; ?></td>
                                    <td><?= $fila['ci']; ?></td>
                                    <td class="text-start">
                                        <?= $fila['nombres'] . " " . $fila['apellidos']; ?>
                                    </td>

                                    <td><?= $fila['tipo_persona']; ?></td>
                                    <td>
                                        <?= empty($fila['universidad']) ? "No registrada" : $fila['universidad']; ?>
                                    </td>

                                    <td>
                                        <?= empty($fila['rol']) ? "Sin usuario" : $fila['rol']; ?>
                                    </td>

                                    <td>
                                        <?php
                                        if ($fila['estado'] == "Activo") {

                                        ?>
                                            <span class="badge bg-success">
                                                Activo

                                            </span>
                                        <?php
                                        } elseif ($fila['estado'] == "Suspendido") {

                                        ?>
                                            <span class="badge bg-warning text-dark">
                                                Suspendido
                                            </span>
                                        <?php

                                        } else {

                                        ?>
                                            <span class="badge bg-danger">
                                                Inactivo
                                            </span>
                                        <?php
                                        }
                                        ?>
                                    </td>

                                    <!-- Botón Editar con modal -->
                                    <td class="text-center">
                                        <div class="d-flex flex-column flex-md-row gap-2 justify-content-center">

                                            <button
                                                class="btn btn-warning btn-sm"
                                                data-bs-toggle="modal"
                                                data-bs-target="#editar<?= $fila['id_persona']; ?>">
                                                <i class="fas fa-edit"></i>
                                                Editar
                                            </button>

                                            <button
                                                class="btn btn-danger btn-sm"
                                                data-bs-toggle="modal"
                                                data-bs-target="#eliminar<?= $fila['id_persona']; ?>">
                                                <i class="fas fa-trash"></i>
                                                Eliminar
                                            </button>
                                        </div>
                                    </td>
                                </tr>

                                <!-- Modal Editar Persona -->
                                <div class="modal fade" id="editar<?= $fila['id_persona']; ?>" tabindex="-1">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">

                                            <form action="../servidor/actualizar_personas1.php" method="POST">

                                                <input
                                                    type="hidden"
                                                    name="id_persona"
                                                    value="<?= $fila['id_persona']; ?>">

                                                <div class="modal-header bg-warning">
                                                    <h5 class="modal-title">
                                                        <i class="fas fa-user-edit"></i>
                                                        Editar Persona
                                                    </h5>

                                                    <button
                                                        type="button"
                                                        class="btn-close"
                                                        data-bs-dismiss="modal">
                                                    </button>
                                                </div>

                                                <div class="modal-body">

                                                    <div class="row">

                                                        <div class="col-md-6 mb-3">
                                                            <label class="form-label">CI</label>
                                                            <input
                                                                type="text"
                                                                class="form-control"
                                                                name="ci"
                                                                value="<?= $fila['ci']; ?>">
                                                        </div>

                                                        <div class="col-md-6 mb-3">
                                                            <label class="form-label">Nombres</label>
                                                            <input
                                                                type="text"
                                                                class="form-control"
                                                                name="nombres"
                                                                value="<?= $fila['nombres']; ?>">
                                                        </div>

                                                        <div class="col-md-6 mb-3">
                                                            <label class="form-label">Apellidos</label>
                                                            <input
                                                                type="text"
                                                                class="form-control"
                                                                name="apellidos"
                                                                value="<?= $fila['apellidos']; ?>">
                                                        </div>
                                                        <div class="col-md-6 mb-3">
                                                            <label class="form-label">Género</label>
                                                            <select class="form-select" name="genero">
                                                                <option value="Masculino" <?= $fila['genero'] == "Masculino" ? "selected" : ""; ?>>
                                                                    Masculino
                                                                </option>

                                                                <option value="Femenino" <?= $fila['genero'] == "Femenino" ? "selected" : ""; ?>>
                                                                    Femenino
                                                                </option>
                                                            </select>
                                                        </div>

                                                        <div class="col-md-6 mb-3">
                                                            <label class="form-label">Dirección</label>
                                                            <input
                                                                type="text"
                                                                class="form-control"
                                                                name="direccion"
                                                                value="<?= $fila['direccion']; ?>">
                                                        </div>

                                                        <div class="col-md-6 mb-3">
                                                            <label class="form-label">Teléfono</label>
                                                            <input
                                                                type="text"
                                                                class="form-control"
                                                                name="telefono"
                                                                value="<?= $fila['telefono']; ?>">
                                                        </div>

                                                        <div class="col-md-6 mb-3">
                                                            <label class="form-label">Correo</label>
                                                            <input
                                                                type="email"
                                                                class="form-control"
                                                                name="correo"
                                                                value="<?= $fila['correo']; ?>">
                                                        </div>

                                                        <div class="col-md-6 mb-3">
                                                            <label class="form-label">Tipo de Persona</label>

                                                            <input
                                                                type="text"
                                                                class="form-control"
                                                                name="tipo_persona"
                                                                value="<?= $fila['tipo_persona']; ?>">
                                                        </div>

                                                        <div class="col-md-6 mb-3">
                                                            <label class="form-label">Estado</label>

                                                            <select
                                                                class="form-select"
                                                                name="estado">

                                                                <option value="Activo"
                                                                    <?= $fila['estado'] == "Activo" ? "selected" : ""; ?>>
                                                                    Activo
                                                                </option>

                                                                <option value="Suspendido"
                                                                    <?= $fila['estado'] == "Suspendido" ? "selected" : ""; ?>>
                                                                    Suspendido
                                                                </option>

                                                                <option value="Inactivo"
                                                                    <?= $fila['estado'] == "Inactivo" ? "selected" : ""; ?>>
                                                                    Inactivo
                                                                </option>

                                                            </select>
                                                        </div>

                                                    </div>

                                                </div>

                                                <div class="modal-footer">

                                                    <button
                                                        type="button"
                                                        class="btn btn-secondary"
                                                        data-bs-dismiss="modal">
                                                        Cancelar
                                                    </button>

                                                    <button
                                                        type="submit"
                                                        class="btn btn-success">
                                                        <i class="fas fa-save"></i>
                                                        Guardar Cambios
                                                    </button>

                                                </div>

                                            </form>

                                        </div>
                                    </div>
                                </div>

                                <!-- Modal Eliminar Persona -->
                                <div class="modal fade" id="eliminar<?= $fila['id_persona']; ?>" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">

                                            <div class="modal-header bg-danger text-white">
                                                <h5 class="modal-title">
                                                    <i class="fas fa-trash"></i>
                                                    Eliminar Persona
                                                </h5>

                                                <button
                                                    type="button"
                                                    class="btn-close btn-close-white"
                                                    data-bs-dismiss="modal">
                                                </button>
                                            </div>

                                            <div class="modal-body">

                                                <p>¿Está seguro de eliminar la siguiente persona?</p>

                                                <div class="alert alert-warning">
                                                    <strong>
                                                        <?= $fila['nombres'] . " " . $fila['apellidos']; ?>
                                                    </strong><br>

                                                    CI: <?= $fila['ci']; ?>

                                                </div>

                                            </div>

                                            <div class="modal-footer">

                                                <button
                                                    type="button"
                                                    class="btn btn-secondary"
                                                    data-bs-dismiss="modal">
                                                    Cancelar
                                                </button>

                                                <a
                                                    href="../servidor/eliminar_personas1.php?id=<?= $fila['id_persona']; ?>"
                                                    class="btn btn-danger">
                                                    <i class="fas fa-trash"></i>
                                                    Eliminar
                                                </a>

                                            </div>

                                        </div>
                                    </div>
                                </div>
                            <?php } ?>


                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <?php if (isset($_GET['mensaje'])) { ?>

        <script>
            <?php if ($_GET['mensaje'] == "actualizado") { ?>

                Swal.fire({
                    icon: 'success',
                    title: '¡Actualizado!',
                    text: 'La persona fue actualizada correctamente.',
                    confirmButtonColor: '#198754'
                });

            <?php } ?>

            <?php if ($_GET['mensaje'] == "eliminado") { ?>

                Swal.fire({
                    icon: 'success',
                    title: '¡Eliminado!',
                    text: 'La persona fue eliminada correctamente.',
                    confirmButtonColor: '#dc3545'
                });

            <?php } ?>

            <?php if ($_GET['mensaje'] == "error") { ?>

                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'No se pudo eliminar la persona.',
                    confirmButtonColor: '#dc3545'
                });

            <?php } ?>
        </script>

    <?php } ?>




    <script>
        const buscar = document.getElementById("buscar");
        buscar.addEventListener("keyup", function() {
            let texto = this.value.toLowerCase();
            let filas = document.querySelectorAll("tbody tr");
            filas.forEach(function(fila) {
                let contenido = fila.textContent.toLowerCase();
                fila.style.display = contenido.includes(texto) ?
                    "" :
                    "none";
            });
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>

</html>