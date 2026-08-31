<?php
require_once appPath('servidor/actividades/detalle.php');

$pageTitle = 'Detalle de Actividad';
$pageStyles = ['cliente/assets/css/detalle_actividad.css'];
ob_start();
?>

<section class="container py-5">

    <div class="row justify-content-center">

        <div class="col-lg-8 col-xl-7">

            <!-- Encabezado -->
            <div class="text-center mb-4">

                <a href="<?= url('/detalle-evento?id=' . $actividad['id_evento']) ?>" class="badge bg-light text-primary border rounded-pill px-3 py-2 mb-2 event-link">
                    <i class="fa-solid fa-calendar-days me-1"></i>
                    <?= htmlspecialchars($actividad['nombre_evento']) ?>
                </a>

                <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-2 badge-tipo">
                    <i class="fa-solid fa-tag me-1"></i>
                    <?= htmlspecialchars($actividad['tipo_actividad']) ?>
                </span>

                <h2 class="fw-bold mt-3 mb-2">
                    <?= htmlspecialchars($actividad['nombre_actividad']) ?>
                </h2>

            </div>


            <!-- Detalle -->
            <div class="detail-card">

                <div class="card-body p-4">

                    <!-- Info Grid -->
                    <div class="row g-3 mb-4">

                        <div class="col-sm-6">
                            <div class="info-item">
                                <div class="icon-wrapper"><i class="fa-regular fa-calendar"></i></div>
                                <div>
                                    <div class="label">Fecha de Inicio</div>
                                    <div class="value"><?= !empty($actividad['fecha_inicio']) ? date('d/m/Y', strtotime($actividad['fecha_inicio'])) : 'Por definir' ?></div>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="info-item">
                                <div class="icon-wrapper"><i class="fa-regular fa-calendar-check"></i></div>
                                <div>
                                    <div class="label">Fecha de Fin</div>
                                    <div class="value"><?= !empty($actividad['fecha_fin']) ? date('d/m/Y', strtotime($actividad['fecha_fin'])) : 'Por definir' ?></div>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="info-item">
                                <div class="icon-wrapper"><i class="fa-regular fa-clock"></i></div>
                                <div>
                                    <div class="label">Horario</div>
                                    <div class="value"><?= !empty($actividad['hora_inicio']) ? date('H:i', strtotime($actividad['hora_inicio'])) : '--:--' ?> — <?= !empty($actividad['hora_fin']) ? date('H:i', strtotime($actividad['hora_fin'])) : '--:--' ?></div>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="info-item">
                                <div class="icon-wrapper"><i class="fa-solid fa-clock"></i></div>
                                <div>
                                    <div class="label">Duración</div>
                                    <div class="value"><?= htmlspecialchars($actividad['duracion']) ?></div>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="info-item">
                                <div class="icon-wrapper"><i class="fa-solid fa-calendar-week"></i></div>
                                <div>
                                    <div class="label">Días</div>
                                    <div class="value"><?= htmlspecialchars($actividad['dias_semana']) ?></div>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="info-item">
                                <div class="icon-wrapper"><i class="fa-solid fa-location-dot"></i></div>
                                <div>
                                    <div class="label">Lugar</div>
                                    <div class="value"><?= htmlspecialchars($actividad['lugar']) ?></div>
                                </div>
                            </div>
                        </div>

                    </div>


                    <!-- Descripción -->
                    <?php if (!empty($actividad['descripcion'])): ?>
                        <div class="mb-4">
                            <h5 class="fw-bold mb-2"><i class="fa-solid fa-align-left me-2 text-primary"></i>Descripción</h5>
                            <p class="text-secondary description-text"><?= nl2br(htmlspecialchars($actividad['descripcion'])) ?></p>
                        </div>
                    <?php endif; ?>

                    <!-- Requisitos -->
                    <?php if (!empty($actividad['requisitos'])): ?>
                        <div class="mb-4">
                            <h5 class="fw-bold mb-2"><i class="fa-solid fa-list-check me-2 text-primary"></i>Requisitos</h5>
                            <p class="text-secondary description-text"><?= nl2br(htmlspecialchars($actividad['requisitos'])) ?></p>
                        </div>
                    <?php endif; ?>

                    <!-- Inversión y Cupos -->
                    <div class="row g-3 mb-4">

                        <div class="col-sm-6">
                            <div class="price-box">
                                <span class="label">Inversión</span>
                                <?php if (empty($actividad['costo']) || $actividad['costo'] == 0): ?>
                                    <span class="price gratis">Gratis</span>
                                <?php else: ?>
                                    <span class="price">Bs. <?= number_format($actividad['costo'], 2) ?></span>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="cupo-box <?= $actividadAgotada ? 'agotado' : '' ?>">
                                <div class="cupo-bar">
                                    <div class="cupo-fill" style="width: <?= $actividad['cupo_maximo'] > 0 ? min(100, max(0, ($cupoDisponible / $actividad['cupo_maximo']) * 100)) : 0 ?>%"></div>
                                </div>
                                <span class="cupo-text">
                                    <?php if ($actividadAgotada): ?>
                                        <i class="fa-solid fa-circle-xmark text-danger me-1"></i> Cupos agotados
                                    <?php elseif ($cupoDisponible <= 3): ?>
                                        <i class="fa-solid fa-triangle-exclamation text-warning me-1"></i> Últimos <?= $cupoDisponible ?> cupos
                                    <?php else: ?>
                                        <i class="fa-solid fa-circle-check text-success me-1"></i> <?= $cupoDisponible ?> cupos disponibles
                                    <?php endif; ?>
                                </span>
                            </div>
                        </div>

                    </div>


                    <!-- Botón Inscripción -->
                    <div class="text-center">
                        <?php if ($actividadAgotada): ?>
                            <button class="btn btn-secondary btn-lg rounded-pill px-4" disabled>
                                <i class="fa-solid fa-ban me-2"></i> Cupos agotados
                            </button>
                        <?php else: ?>
                            <a href="<?= url('/inscripcion/registrar?id=' . $idActividad) ?>" class="btn btn-primary btn-lg rounded-pill px-4">
                                <i class="fa-solid fa-pen-to-square me-2"></i> Inscribirse
                            </a>
                        <?php endif; ?>
                    </div>

                </div>

            </div>

            <!-- Volver -->
            <div class="text-center mt-4">
                <a href="<?= url('/ver-actividades') ?>" class="btn btn-outline-secondary rounded-pill">
                    <i class="fa-solid fa-arrow-left me-2"></i> Volver a Actividades
                </a>
            </div>

        </div>

    </div>

</section>

<?php
$content = ob_get_clean();
require_once appPath('cliente/layouts/PublicLayout.php');