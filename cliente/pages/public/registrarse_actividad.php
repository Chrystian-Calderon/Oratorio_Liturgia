<?php
require_once appPath('servidor/inscripcion/registrar.php');

$pageTitle = 'Inscripción | ' . htmlspecialchars($actividad['nombre_actividad']);
$pageStyles = ['cliente/assets/css/registrarse_actividad.css'
];
?>

<?php ob_start(); ?>

<!-- Encabezado -->
<header class="page-header">
    <div class="container text-center">
        <span class="badge bg-light text-primary px-3 py-2 mb-3">
            <i class="fa-solid fa-user-plus me-1"></i>
            INSCRIPCIÓN
        </span>
        <h1 class="fw-bold">Inscribirme a la actividad</h1>
        <p class="mb-0">Completa tus datos para participar</p>
    </div>
</header>


<!-- Contenido -->
<main class="container registration-container">
    <div class="row justify-content-center">
        <div class="col-lg-9">

            <div class="card card-custom">
                <div class="card-body p-4 p-lg-5">

                    <!-- Resumen de actividad -->
                    <div class="activity-summary mb-4">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <span class="badge bg-primary-subtle text-primary">
                                <?= htmlspecialchars($actividad['tipo_actividad']) ?>
                            </span>
                            <?php if ($actividadAgotada): ?>
                                <span class="badge bg-danger">Cupos agotados</span>
                            <?php else: ?>
                                <span class="badge bg-success"><?= $cupoDisponible ?> cupos disponibles</span>
                            <?php endif; ?>
                        </div>

                        <h3 class="fw-bold mb-3"><?= htmlspecialchars($actividad['nombre_actividad']) ?></h3>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="info-item">
                                    <div class="info-icon"><i class="fa-regular fa-calendar"></i></div>
                                    <div>
                                        <small class="text-muted d-block">Fecha</small>
                                        <strong>
                                            <?= date('d/m/Y', strtotime($actividad['fecha_inicio'])) ?>
                                            <?php if ($actividad['fecha_inicio'] != $actividad['fecha_fin']): ?>
                                                - <?= date('d/m/Y', strtotime($actividad['fecha_fin'])) ?>
                                            <?php endif; ?>
                                        </strong>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-item">
                                    <div class="info-icon"><i class="fa-regular fa-clock"></i></div>
                                    <div>
                                        <small class="text-muted d-block">Horario</small>
                                        <strong>
                                            <?= date('H:i', strtotime($actividad['hora_inicio'])) ?>
                                            - <?= date('H:i', strtotime($actividad['hora_fin'])) ?>
                                        </strong>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-item">
                                    <div class="info-icon"><i class="fa-solid fa-calendar-week"></i></div>
                                    <div>
                                        <small class="text-muted d-block">Días</small>
                                        <strong><?= htmlspecialchars($actividad['dias_semana']) ?></strong>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-item">
                                    <div class="info-icon"><i class="fa-solid fa-money-bill"></i></div>
                                    <div>
                                        <small class="text-muted d-block">Costo</small>
                                        <strong>
                                            <?php if (empty($actividad['costo']) || $actividad['costo'] == 0): ?>
                                                <span class="text-success">Gratis</span>
                                            <?php else: ?>
                                                Bs. <?= number_format($actividad['costo'], 2) ?>
                                            <?php endif; ?>
                                        </strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <?php if (!$actividadAgotada): ?>

                    <!-- Formulario -->
                    <form action="<?= url('/validar-inscripcion') ?>" method="POST">

                        <input type="hidden" name="id_actividad" value="<?= $idActividad ?>">

                        <h4 class="fw-bold mb-4">
                            <i class="fa-solid fa-user text-primary me-2"></i>
                            Datos del participante
                        </h4>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="nombre" class="form-label fw-semibold">
                                    Nombre <span class="required">*</span>
                                </label>
                                <input type="text" class="form-control" id="nombre" name="nombre"
                                       value="<?= htmlspecialchars($nombre) ?>" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="apellidos" class="form-label fw-semibold">
                                    Apellidos <span class="required">*</span>
                                </label>
                                <input type="text" class="form-control" id="apellidos" name="apellidos"
                                       value="<?= htmlspecialchars($apellidos) ?>" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="correo" class="form-label fw-semibold">
                                    Correo electrónico <span class="required">*</span>
                                </label>
                                <input type="email" class="form-control" id="correo" name="correo"
                                       value="<?= htmlspecialchars($correo) ?>" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="ci" class="form-label fw-semibold">
                                    Cédula de Identidad <span class="required">*</span>
                                </label>
                                <input type="text" class="form-control" id="ci" name="ci"
                                       placeholder="Ej. 12345678" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="telefono" class="form-label fw-semibold">Teléfono</label>
                                <input type="tel" class="form-control" id="telefono" name="telefono"
                                       placeholder="Ej. 70000000">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="observacion" class="form-label fw-semibold">Observación</label>
                                <input type="text" class="form-control" id="observacion" name="observacion"
                                       placeholder="Información adicional">
                            </div>
                        </div>

                        <!-- Términos -->
                        <div class="form-check mb-4 mt-2">
                            <input class="form-check-input" type="checkbox" id="aceptar" required>
                            <label class="form-check-label" for="aceptar">
                                Confirmo que deseo inscribirme en esta actividad y que los datos proporcionados son correctos.
                            </label>
                        </div>

                        <!-- Botones -->
                        <div class="d-flex flex-column flex-md-row gap-2">
                            <button type="submit" class="btn btn-register flex-grow-1">
                                <i class="fa-solid fa-check me-2"></i> Confirmar inscripción
                            </button>
                            <button type="button" class="btn btn-outline-secondary px-4" onclick="history.back()">
                                <i class="fa-solid fa-arrow-left me-2"></i> Volver
                            </button>
                        </div>

                    </form>

                    <?php else: ?>

                        <div class="alert alert-danger text-center">
                            <i class="fa-solid fa-circle-exclamation me-2"></i>
                            Esta actividad ya no tiene cupos disponibles.
                        </div>
                        <div class="text-center">
                            <button type="button" class="btn btn-outline-secondary" onclick="history.back()">
                                <i class="fa-solid fa-arrow-left me-2"></i> Volver a actividades
                            </button>
                        </div>

                    <?php endif; ?>

                </div>
            </div>

        </div>
    </div>
</main>

<?php
$content = ob_get_clean();
require_once appPath('cliente/layouts/PublicLayout.php');