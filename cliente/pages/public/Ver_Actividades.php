<?php
$pageTitle = 'Actividades';
$pageStyles = ['cliente/assets/css/ver_actividades.css'];
ob_start();

$datos = require appPath('servidor/actividades/ver.php');
extract($datos);

$limpiarFiltros = !empty($filtroBusqueda) || !empty($filtroTipo);
?>
<header class="page-header">
    <div class="container">
        <div class="text-center">
            <span class="badge-header d-inline-block px-4 py-2 rounded-pill mb-3">
                <i class="fa-solid fa-calendar-days me-2"></i> ACTIVIDADES
            </span>
            <h1 class="display-4 fw-bold mb-2">Descubre tu próxima actividad</h1>
            <p class="fs-5 mb-0 mx-auto" style="max-width: 500px;">Encuentra una actividad y forma parte de nuestra comunidad</p>
        </div>
    </div>
</header>

<main class="container pb-5">
    <section class="filters-section">
        <form method="GET" action="<?= url('/ver-actividades') ?>" class="row g-3 align-items-end">
            <div class="col-12 col-md-5">
                <label class="form-label fw-semibold small text-secondary"><i class="fa-solid fa-search me-1"></i> Buscar</label>
                <input type="text" name="buscar" class="form-control" placeholder="Nombre o descripción..." value="<?= htmlspecialchars($filtroBusqueda) ?>">
            </div>
            <div class="col-12 col-md-4">
                <label class="form-label fw-semibold small text-secondary"><i class="fa-solid fa-tag me-1"></i> Tipo</label>
                <select name="tipo" class="form-select">
                    <option value="">Todos los tipos</option>
                    <?php foreach ($tiposActividades as $tipo): ?>
                        <option value="<?= htmlspecialchars($tipo) ?>" <?= $filtroTipo === $tipo ? 'selected' : '' ?>><?= htmlspecialchars($tipo) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-12 col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary w-100 rounded-3"><i class="fa-solid fa-filter me-2"></i> Filtrar</button>
                <?php if ($limpiarFiltros): ?>
                    <a href="<?= url('/ver-actividades') ?>" class="btn btn-outline-secondary rounded-3"><i class="fa-solid fa-times"></i></a>
                <?php endif; ?>
            </div>
        </form>
    </section>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div><span class="fw-semibold"><?= $totalRegistros ?></span> <span class="text-secondary">actividades disponibles</span></div>
        <?php if ($totalPaginas > 1): ?>
            <small class="text-secondary">Página <?= $paginaActual ?> de <?= $totalPaginas ?></small>
        <?php endif; ?>
    </div>

    <div class="row g-4">
        <?php if (!empty($actividades)): ?>
            <?php foreach ($actividades as $a):
                $cupo = (int)$a['cupo_disponible'];
                $max = (int)$a['cupo_maximo'];
                if ($cupo <= 0) { $colorC = 'danger'; $textC = 'Agotado'; $iconC = 'fa-circle-exclamation'; }
                elseif ($cupo <= 3) { $colorC = 'warning'; $textC = "Últimos $cupo cupos"; $iconC = 'fa-triangle-exclamation'; }
                else { $colorC = 'success'; $textC = "$cupo disponibles"; $iconC = 'fa-circle-check'; }
                $fI = date('d M Y', strtotime($a['fecha_inicio']));
                $fF = date('d M Y', strtotime($a['fecha_fin']));
                $hI = date('H:i', strtotime($a['hora_inicio']));
                $hF = date('H:i', strtotime($a['hora_fin']));
                $desc = strlen($a['descripcion'] ?? '') > 110 ? substr($a['descripcion'], 0, 110) . '...' : ($a['descripcion'] ?? '');
                $costo = (empty($a['costo']) || $a['costo'] == 0) ? '<span class="text-success">Gratis</span>' : 'Bs. ' . number_format($a['costo'], 2);
            ?>
            <div class="col-12 col-md-6 col-xl-4">
                <article class="activity-card">
                    <div class="card-body d-flex flex-column">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="badge-type"><i class="fa-solid fa-tag"></i> <?= htmlspecialchars($a['tipo_actividad']) ?></span>
                            <span class="badge-status bg-<?= $colorC ?> bg-opacity-10 text-<?= $colorC ?>"><i class="fa-regular <?= $iconC ?>"></i> <?= $textC ?></span>
                        </div>
                        <h4 class="fw-bold mb-1"><?= htmlspecialchars($a['nombre_actividad']) ?></h4>
                        <p class="text-secondary small mb-3"><?= htmlspecialchars($desc) ?></p>
                        <div class="bg-light rounded-3 p-3 mb-3">
                            <div class="info-item">
                                <div class="icon-wrapper"><i class="fa-regular fa-calendar"></i></div>
                                <div><div class="label">Fecha</div><div class="value"><?= $fI ?><?= $fI !== $fF ? " — $fF" : '' ?></div></div>
                            </div>
                            <div class="info-item">
                                <div class="icon-wrapper"><i class="fa-regular fa-clock"></i></div>
                                <div><div class="label">Horario</div><div class="value"><?= $hI ?> — <?= $hF ?></div></div>
                            </div>
                            <div class="info-item mb-0">
                                <div class="icon-wrapper"><i class="fa-solid fa-calendar-week"></i></div>
                                <div><div class="label">Días</div><div class="value"><?= htmlspecialchars($a['dias_semana']) ?></div></div>
                            </div>
                        </div>
                        <div class="row g-2 mb-3">
                            <div class="col-6">
                                <div class="metric-box">
                                    <div class="metric-label"><i class="fa-solid fa-hourglass-half me-1"></i> Duración</div>
                                    <div class="metric-value"><?= htmlspecialchars($a['duracion']) ?></div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="metric-box">
                                    <div class="metric-label"><i class="fa-solid fa-users me-1"></i> Cupos</div>
                                    <div class="metric-value text-<?= $colorC ?>"><?= $textC ?></div>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div><small class="text-secondary text-uppercase fw-semibold" style="font-size:.65rem;letter-spacing:.5px">Inversión</small><div class="fs-5 fw-bold"><?= $costo ?></div></div>
                            <div class="text-end"><small class="text-secondary text-uppercase fw-semibold" style="font-size:.65rem;letter-spacing:.5px">Cupo máximo</small><div class="fw-semibold"><?= $max ?> personas</div></div>
                        </div>
                        <a href="<?= url('/detalle-actividad?id=' . $a['id_actividad']) ?>" class="btn-detail mt-auto">Ver actividad <i class="fa-solid fa-arrow-right"></i></a>
                    </div>
                </article>
            </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12">
                <div class="empty-state">
                    <div class="empty-icon"><i class="fa-regular fa-calendar-xmark"></i></div>
                    <h4 class="fw-bold mb-2">No hay actividades disponibles</h4>
                    <p class="text-secondary mb-0">
                        <?php if ($limpiarFiltros): ?>
                            No encontramos actividades que coincidan con tu búsqueda.<br>
                            <a href="<?= url('/ver-actividades') ?>" class="btn btn-link text-primary"><i class="fa-solid fa-arrow-left me-1"></i> Ver todas</a>
                        <?php else: ?>
                            En este momento no existen actividades disponibles.<br>Vuelve a consultar más tarde.
                        <?php endif; ?>
                    </p>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <?php if ($totalPaginas > 1): ?>
    <nav class="mt-5">
        <ul class="pagination justify-content-center pagination-custom">
            <?php if ($paginaActual > 1): ?>
                <li class="page-item"><a class="page-link" href="?pagina=<?= $paginaActual - 1 ?>&buscar=<?= urlencode($filtroBusqueda) ?>&tipo=<?= urlencode($filtroTipo) ?>"><i class="fa-solid fa-chevron-left"></i></a></li>
            <?php endif; ?>
            <?php
            $rango = 2; $inicio = max(1, $paginaActual - $rango); $fin = min($totalPaginas, $paginaActual + $rango);
            if ($inicio > 1) {
                echo '<li class="page-item"><a class="page-link" href="?pagina=1&buscar=' . urlencode($filtroBusqueda) . '&tipo=' . urlencode($filtroTipo) . '">1</a></li>';
                if ($inicio > 2) echo '<li class="page-item disabled"><span class="page-link">…</span></li>';
            }
            for ($i = $inicio; $i <= $fin; $i++): ?>
                <li class="page-item <?= $i === $paginaActual ? 'active' : '' ?>"><a class="page-link" href="?pagina=<?= $i ?>&buscar=<?= urlencode($filtroBusqueda) ?>&tipo=<?= urlencode($filtroTipo) ?>"><?= $i ?></a></li>
            <?php endfor;
            if ($fin < $totalPaginas) {
                if ($fin < $totalPaginas - 1) echo '<li class="page-item disabled"><span class="page-link">…</span></li>';
                echo '<li class="page-item"><a class="page-link" href="?pagina=' . $totalPaginas . '&buscar=' . urlencode($filtroBusqueda) . '&tipo=' . urlencode($filtroTipo) . '">' . $totalPaginas . '</a></li>';
            }
            ?>
            <?php if ($paginaActual < $totalPaginas): ?>
                <li class="page-item"><a class="page-link" href="?pagina=<?= $paginaActual + 1 ?>&buscar=<?= urlencode($filtroBusqueda) ?>&tipo=<?= urlencode($filtroTipo) ?>"><i class="fa-solid fa-chevron-right"></i></a></li>
            <?php endif; ?>
        </ul>
    </nav>
    <?php endif; ?>
</main>
<?php
$content = ob_get_clean();
require_once appPath('cliente/layouts/PublicLayout.php');