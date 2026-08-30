<?php
declare(strict_types=1);
$pageTitle = "Usuarios del Sistema";
ob_start();
?>
    <div class="container py-4">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="fas fa-users"></i>
                        Usuarios del Sistema
                    </h4>
                </div>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-4">
                        <input
                            type="text"
                            id="buscar"
                            class="form-control"
                            placeholder="Buscar usuario...">
                    </div>
                </div>
                <div class="table-responsive shadow rounded">
                    <?php if (empty($usuarios)): ?>
                        <div class="alert alert-info text-center">
                            No se encontraron usuarios en el sistema.
                        </div>
                    <?php else: ?>
                    <table class="table table-hover table-striped-bordered align-middle text-center mb-0" id="tablaUsuarios">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Rol</th>
                                <th>Permisos</th>
                                <th>Estado</th>
                                <th>Creado</th>
                                <th>Actualizado</th>
                                <th width="170">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($usuarios as $fila) { ?>
                                <tr id="usuario-<?= $fila['id_usuario']; ?>">
                                    <td><?= $fila['id_usuario']; ?></td>
                                    <td><?= $fila['rol']; ?></td>
                                    <td><?= $fila['permisos']; ?></td>
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
                                    <td><?= $fila['fecha_creacion']; ?></td>
                                    <td><?= $fila['fecha_actualizacion']; ?></td>
                                    <td class="text-center">
                                        <div class="d-flex flex-column flex-md-row gap-2 justify-content-center">
                                            <button
                                                class="btn btn-warning btn-sm"
                                                data-bs-toggle="modal"
                                                data-bs-target="#editar<?= $fila['id_usuario']; ?>">
                                                <i class="fas fa-edit"></i>
                                                Editar
                                            </button>
                                            <button
                                                type="button"
                                                class="btn btn-danger btn-sm"
                                                data-bs-toggle="modal"
                                                data-bs-target="#eliminar<?= $fila['id_usuario']; ?>">
                                                <i class="fas fa-trash"></i>
                                                Eliminar
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <!-- Modal Editar Usuario -->
                                <div class="modal fade" id="editar<?= $fila['id_usuario']; ?>" tabindex="-1">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <form class="formEditarUsuario">
                                                <input
                                                    type="hidden"
                                                    name="id_usuario"
                                                    value="<?= $fila['id_usuario']; ?>">

                                                <div class="modal-header bg-warning">
                                                    <h5 class="modal-title">
                                                        <i class="fas fa-user-edit"></i>
                                                        Editar Usuario
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
                                                            <label class="form-label">
                                                                Rol
                                                            </label>
                                                            <select name="rol" id="rol" class="form-select" required>
                                                                <option value="Administrador" <?= ($fila['rol'] === 'Administrador') ? 'selected' : ''; ?>>Administrador</option>
                                                                <option value="Coordinador" <?= ($fila['rol'] === 'Coordinador') ? 'selected' : ''; ?>>Coordinador</option>
                                                                <option value="Estudiante" <?= ($fila['rol'] === 'Estudiante') ? 'selected' : ''; ?>>Estudiante</option>
                                                                <option value="Docente" <?= ($fila['rol'] === 'Docente') ? 'selected' : ''; ?>>Docente</option>
                                                                <option value="Voluntario" <?= ($fila['rol'] === 'Voluntario') ? 'selected' : ''; ?>>Voluntario</option>
                                                                <option value="Sacerdote" <?= ($fila['rol'] === 'Sacerdote') ? 'selected' : ''; ?>>Sacerdote</option>
                                                                <option value="Externo" <?= ($fila['rol'] === 'Externo') ? 'selected' : ''; ?>>Externo</option>
                                                            </select>
                                                        </div>

                                                        <div class="col-md-6 mb-3">
                                                            <label class="form-label">
                                                                Permisos
                                                            </label>
                                                            <input
                                                                type="text"
                                                                class="form-control"
                                                                name="permisos"
                                                                value="<?= $fila['permisos']; ?>"
                                                                required>
                                                        </div>

                                                        <div class="col-md-6 mb-3">
                                                            <label class="form-label">
                                                                Estado
                                                            </label>
                                                            <select
                                                                class="form-select"
                                                                name="estado">
                                                                <option value="Activo"
                                                                    <?= $fila['estado'] == "Activo" ? "selected" : ""; ?>>
                                                                    Activo
                                                                </option>

                                                                <option value="Inactivo"
                                                                    <?= $fila['estado'] == "Inactivo" ? "selected" : ""; ?>>

                                                                    Inactivo
                                                                </option>

                                                                <option value="Suspendido"
                                                                    <?= $fila['estado'] == "Suspendido" ? "selected" : ""; ?>>

                                                                    Suspendido
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
                                <!-- Modal Eliminar Usuario -->
                                <div
                                    class="modal fade"
                                    id="eliminar<?= $fila['id_usuario']; ?>"
                                    tabindex="-1"
                                    aria-labelledby="tituloEliminar<?= $fila['id_usuario']; ?>"
                                    aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header bg-danger text-white">
                                                <h5
                                                    class="modal-title"
                                                    id="tituloEliminar<?= $fila['id_usuario']; ?>">
                                                    <i class="fas fa-trash"></i>
                                                    Eliminar Usuario
                                                </h5>
                                                <button
                                                    type="button"
                                                    class="btn-close btn-close-white"
                                                    data-bs-dismiss="modal"
                                                    aria-label="Cerrar">
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <p class="mb-0">
                                                    ¿Está seguro de que desea eliminar este usuario?
                                                </p>
                                                <p class="text-muted mb-0 mt-2">
                                                    Esta acción no se puede deshacer.
                                                </p>
                                            </div>
                                            <div class="modal-footer">
                                                <button
                                                    type="button"
                                                    class="btn btn-secondary"
                                                    data-bs-dismiss="modal">
                                                    Cancelar
                                                </button>
                                                <button
                                                    type="button"
                                                    class="btn btn-danger btnEliminarUsuario"
                                                    data-id="<?= $fila['id_usuario']; ?>">
                                                    <i class="fas fa-trash"></i>
                                                    Eliminar
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                        </tbody>
                    </table>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <script>
        const buscar = document.getElementById("buscar");
        buscar.addEventListener("keyup", function() {
            let texto = this.value.toLowerCase();
            let filas = document.querySelectorAll("#tablaUsuarios tbody tr");
            filas.forEach(function(fila) {
                let contenido = fila.textContent.toLowerCase();
                fila.style.display = contenido.includes(texto) ? "" : "none";

            });

        });


        const formsEdits = document.querySelectorAll(".formEditarUsuario");
        formsEdits.forEach(formEditarUsuario => {
            formEditarUsuario.addEventListener("submit", async (event) => {
                event.preventDefault();
                const formData = new FormData(formEditarUsuario);
                const datos = {
                    id_usuario: Number(formData.get("id_usuario")),
                    rol: formData.get("rol"),
                    permisos: formData.get("permisos"),
                    estado: formData.get("estado")
                }
                
                try {
                    const response = await fetch("<?= url('/usuarios') ?>", {
                        method: "PUT",
                        headers: {
                            "Content-Type": "application/json"
                        },
                        body: JSON.stringify(datos)
                    });

                    const result = await response.json();
                    if (!result.success) {
                        mostrarNotificacion(result.message, "error");
                        return;
                    }
                    actualizarFilaUsuario(datos);
                    mostrarNotificacion(result.message, "success");
                    const modalEditar = document.getElementById(`editar${datos.id_usuario}`);
                    const modal = bootstrap.Modal.getOrCreateInstance(modalEditar);
                    modal.hide();

                    formEditarUsuario.reset();
                    return;
                } catch (error) {
                    console.error("Error al actualizar el usuario:", error);
                    mostrarNotificacion("Error al actualizar el usuario.", "error");
                }
            });
        });

        function actualizarFilaUsuario(usuario) {
            const fila = document.getElementById(`usuario-${usuario.id_usuario}`);

            if (!fila) {
                return;
            }

            fila.querySelector('.usuario-nombre').textContent = usuario.usuario;
            fila.querySelector('.usuario-rol').textContent = usuario.rol;
            fila.querySelector('.usuario-estado').textContent = usuario.estado;
        }

        const botonesEliminar = document.querySelectorAll('.btnEliminarUsuario');

        botonesEliminar.forEach((boton) => {
            boton.addEventListener('click', async () => {
                    const idUsuario = Number(boton.dataset.id);
                    try {
                        const response = await fetch("<?= url('/usuarios') ?>",
                            {
                                method: 'DELETE',
                                headers: {
                                    'Content-Type':
                                        'application/json'
                                },
                                body: JSON.stringify({
                                    id_usuario: idUsuario
                                })
                            }
                        );

                        const result = await response.json();
                        if (!result.success) {
                            mostrarNotificacion(
                                result.message,
                                'error'
                            );
                            return;
                        }
                        const fila = document.getElementById(`usuario-${idUsuario}`);
                        if (fila) {
                            fila.remove();
                        }

                        mostrarNotificacion(
                            result.message,
                            'success'
                        );
                        const modalEliminar = document.getElementById(`eliminar${idUsuario}`);
                        const modal = bootstrap.Modal.getOrCreateInstance(modalEliminar);
                        modal.hide();
                        return;

                    } catch (error) {
                        console.error(
                            'Error al eliminar el usuario:',
                            error
                        );
                        mostrarNotificacion(
                            'Error al eliminar el usuario.',
                            'error'
                        );
                    }
                }
            );
        });
    </script>
<?php
$content = ob_get_clean();
require_once appPath('cliente/layouts/AdminLayout.php');