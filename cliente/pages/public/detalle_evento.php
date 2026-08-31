<?php
require_once appPath('servidor/eventos/detalle.php');

$pageTitle = htmlspecialchars($evento['nombre_evento']);
$pageStyles = ['cliente/assets/css/detalle_evento.css'];
?>

<?php ob_start(); ?>

<section class="container py-5">

    <div class="row justify-content-center">

        <div class="col-lg-10">

            <!-- Encabezado del evento -->
            <div class="event-header text-center mb-5">

                <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-2 mb-3 badge-estado">
                    <i class="fa-solid fa-calendar-days me-1"></i>
                    <?= htmlspecialchars($evento['estado']) ?>
                </span>

                <h1 class="fw-bold mb-3"><?= htmlspecialchars($evento['nombre_evento']) ?></h1>

                <p class="text-secondary mb-4 event-desc"><?= htmlspecialchars($evento['descripcion']) ?></p>

                <div class="row g-3 justify-content-center">

                    <div class="col-auto">
                        <span class="info-chip">
                            <i class="fa-regular fa-calendar me-2"></i>
                            <?= date('d/m/Y', strtotime($evento['fecha_evento'])) ?>
                        </span>
                    </div>

                    <div class="col-auto">
                        <span class="info-chip">
                            <i class="fa-regular fa-clock me-2"></i>
                            <?= date('H:i', strtotime($evento['hora_evento'])) ?>
                        </span>
                    </div>

                    <div class="col-auto">
                        <span class="info-chip">
                            <i class="fa-solid fa-location-dot me-2"></i>
                            <?= htmlspecialchars($evento['lugar']) ?>
                        </span>
                    </div>

                </div>

            </div>

            <!-- Actividades del evento -->
            <div class="d-flex justify-content-between align-items-center mb-4">

                <div>
                    <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-2 badge-titulo">
                        <i class="fa-solid fa-list-check me-1"></i>
                        ACTIVIDADES
                    </span>
                    <h3 class="fw-bold mt-2 mb-1">Actividades de este evento</h3>
                    <p class="text-secondary mb-0">Participa en las actividades disponibles para este evento.</p>
                </div>

                <span class="badge bg-light text-dark border rounded-pill px-3 py-2 contador">
                    <?= $totalActividades ?> <?= $totalActividades == 1 ? 'actividad' : 'actividades' ?>
                </span>

            </div>

            <?php if ($totalActividades > 0): ?>

                <div class="row g-4">
                    <?php foreach ($actividades as $act): ?>
                        <?php
                        $cupo = (int)($act['cupo_disponible'] ?? 0);
                        if ($cupo <= 0) {
                            $colorCupo = 'danger';
                            $textoCupo = 'Cupos agotados';
                            $iconoCupo = 'fa-circle-xmark';
                        } elseif ($cupo <= 3) {
                            $colorCupo = 'warning';
                            $textoCupo = "Últimos {$cupo} cupos";
                            $iconoCupo = 'fa-triangle-exclamation';
                        } else {
                            $colorCupo = 'success';
                            $textoCupo = "{$cupo} cupos disponibles";
                            $iconoCupo = 'fa-circle-check';
                        }

                        $fechaInicio = !empty($act['fecha_inicio']) ? date('d/m/Y', strtotime($act['fecha_inicio'])) : 'Fecha no definida';
                        $fechaFin = !empty($act['fecha_fin']) ? date('d/m/Y', strtotime($act['fecha_fin'])) : '';
                        $horaInicio = !empty($act['hora_inicio']) ? date('H:i', strtotime($act['hora_inicio'])) : '--:--';
                        $horaFin = !empty($act['hora_fin']) ? date('H:i', strtotime($act['hora_fin'])) : '--:--';

                        $desc = $act['descripcion'] ?? '';
                        if (strlen($desc) > 110) {
                            $desc = substr($desc, 0, 110) . '...';
                        }
                        ?>

                        <div class="col-12 col-md-6 col-xl-4">
                            <article class="activity-card">
                                <div class="card-body d-flex flex-column">

                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <span class="badge bg-light text-primary rounded-pill px-3 py-2 badge-tipo-sm">
                                            <i class="fa-solid fa-tag me-1"></i>
                                            <?= htmlspecialchars($act['tipo_actividad']) ?>
                                        </span>
                                        <span class="badge bg-<?= $colorCupo ?>-subtle text-<?= $colorCupo ?> rounded-pill px-3 py-2 badge-cupo">
                                            <i class="fa-solid <?= $iconoCupo ?> me-1"></i>
                                            <?= $textoCupo ?>
                                        </span>
                                    </div>

                                    <h4 class="fw-bold mb-2"><?= htmlspecialchars($act['nombre_actividad']) ?></h4>

                                    <p class="small activity-description"><?= htmlspecialchars($desc) ?></p>

                                    <div class="activity-info mb-3">
                                        <div class="info-item">
                                            <div class="icon-wrapper"><i class="fa-regular fa-calendar"></i></div>
                                            <div>
                                                <div class="label">Fecha</div>
                                                <div class="value"><?= $fechaInicio ?><?= ($fechaInicio !== $fechaFin && !empty($fechaFin)) ? " — {$fechaFin}" : '' ?></div>
                                            </div>
                                        </div>
                                        <div class="info-item">
                                            <div class="icon-wrapper"><i class="fa-regular fa-clock"></i></div>
                                            <div>
                                                <div class="label">Horario</div>
                                                <div class="value"><?= $horaInicio ?> — <?= $horaFin ?></div>
                                            </div>
                                        </div>
                                        <div class="info-item">
                                            <div class="icon-wrapper"><i class="fa-solid fa-calendar-week"></i></div>
                                            <div>
                                                <div class="label">Días</div>
                                                <div class="value"><?= htmlspecialchars($act['dias_semana']) ?></div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <div>
                                            <small class="activity-label">Inversión</small>
                                            <div class="activity-price">
                                                <?php if (empty($act['costo']) || $act['costo'] == 0): ?>
                                                    <span class="gratis">Gratis</span>
                                                <?php else: ?>
                                                    Bs. <?= number_format($act['costo'], 2) ?>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <div class="text-end">
                                            <small class="activity-label">Duración</small>
                                            <div class="activity-duration"><?= htmlspecialchars($act['duracion']) ?></div>
                                        </div>
                                    </div>

                                    <a href="<?= url('/detalle-actividad?id=' . $act['id_actividad']) ?>" class="btn btn-primary activity-button mt-auto">
                                        Ver actividad <i class="fa-solid fa-arrow-right ms-2"></i>
                                    </a>

                                </div>
                            </article>
                        </div>

                    <?php endforeach; ?>
                </div>

            <?php else: ?>

                <div class="text-center empty-activities">
                    <div class="empty-icon">
                        <i class="fa-regular fa-calendar-xmark"></i>
                    </div>
                    <h4 class="fw-bold">No hay actividades disponibles</h4>
                    <p class="text-secondary mb-0">Este evento todavía no tiene actividades registradas.</p>
                </div>

            <?php endif; ?>

            <!-- Volver -->
            <div class="text-center mt-4">
                <a href="<?= url('/ver-eventos') ?>" class="btn btn-outline-secondary rounded-pill">
                    <i class="fa-solid fa-arrow-left me-2"></i> Volver a Eventos
                </a>
            </div>

        </div>

    </div>

</section>

<?php
$content = ob_get_clean();
require_once appPath('cliente/layouts/PublicLayout.php');