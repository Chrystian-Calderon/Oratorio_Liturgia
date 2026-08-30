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
                                <tr>
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
                                            <a
                                                href="../servidor/eliminar_usuario.php?id=<?= $fila['id_usuario']; ?>"
                                                class="btn btn-danger btn-sm"
                                                onclick="return confirm('¿Desea eliminar este usuario?');">
                                                <i class="fas fa-trash"></i>
                                                Eliminar
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <!-- Modal Editar Usuario -->
                                <div class="modal fade" id="editar<?= $fila['id_usuario']; ?>" tabindex="-1">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <form action="../servidor/actualizar_usuario.php" method="POST">
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
                                                            <input
                                                                type="text"
                                                                class="form-control"
                                                                name="rol"
                                                                value="<?= $fila['rol']; ?>"
                                                                required>
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
    </script>
<?php
$content = ob_get_clean();
require_once appPath('cliente/layouts/AdminLayout.php');