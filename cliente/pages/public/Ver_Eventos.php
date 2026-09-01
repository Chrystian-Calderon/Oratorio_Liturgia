<?php
$pageTitle = 'Eventos';
$pageStyles = ['cliente/assets/css/ver_eventos.css'];
ob_start();

$datos = require appPath('servidor/eventos/ver.php');
extract($datos);

$limpiarFiltros = !empty($filtroBusqueda) || !empty($filtroEstado) || !empty($filtroFecha);
?>
<header class="page-header">
    <div class="container">
        <div class="text-center">
            <span class="badge-header d-inline-block px-4 py-2 rounded-pill mb-3">
                <i class="fa-regular fa-calendar-plus me-2"></i> EVENTOS
            </span>
            <h1 class="display-4 fw-bold mb-2">Calendario de eventos</h1>
            <p class="fs-5 mb-0 mx-auto" style="max-width: 550px;">Participa en nuestros eventos y vive experiencias únicas en comunidad</p>
        </div>
    </div>
</header>

<main class="container pb-5">
    <section class="filters-section">
        <form method="GET" action="<?= url('/ver-eventos') ?>" class="row g-3 align-items-end">
            <div class="col-12 col-md-4">
                <label class="form-label"><i class="fa-solid fa-search me-1"></i> Buscar</label>
                <input type="text" name="buscar" class="form-control" placeholder="Nombre, descripción o lugar..." value="<?= htmlspecialchars($filtroBusqueda) ?>">
            </div>
            <div class="col-12 col-md-3">
                <label class="form-label"><i class="fa-solid fa-circle me-1"></i> Estado</label>
                <select name="estado" class="form-select">
                    <option value="">Todos los estados</option>
                    <?php foreach ($estadosEventos as $e): ?>
                        <option value="<?= $e ?>" <?= $filtroEstado === $e ? 'selected' : '' ?>><?= $e ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-12 col-md-3">
                <label class="form-label"><i class="fa-regular fa-calendar me-1"></i> Fecha</label>
                <input type="date" name="fecha" class="form-control" value="<?= htmlspecialchars($filtroFecha) ?>">
            </div>
            <div class="col-12 col-md-2">
                <button type="submit" class="btn-primary-custom w-100"><i class="fa-solid fa-sliders me-2"></i> Filtrar</button>
            </div>
        </form>
        <?php if ($limpiarFiltros): ?>
            <div class="mt-3 pt-3 border-top">
                <a href="<?= url('/ver-eventos') ?>" class="text-decoration-none small"><i class="fa-solid fa-times-circle me-1"></i> Limpiar filtros</a>
            </div>
        <?php endif; ?>
    </section>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div><span class="fw-semibold"><?= $totalRegistros ?></span> <span class="text-secondary">eventos encontrados</span></div>
        <?php if ($totalPaginas > 1): ?>
            <small class="text-secondary">Página <?= $paginaActual ?> de <?= $totalPaginas ?></small>
        <?php endif; ?>
    </div>

    <div class="row g-4">
        <?php if (!empty($eventos)): ?>
            <?php foreach ($eventos as $ev):
                $ec = strtolower(str_replace(' ', '-', $ev['estado']));
                $ei = ['Activo'=>'fa-play-circle','Inactivo'=>'fa-circle-xmark','Cancelado'=>'fa-circle-xmark'][$ev['estado']] ?? 'fa-circle';
                $fE = !empty($ev['fecha_evento']) ? date('d/m/Y', strtotime($ev['fecha_evento'])) : 'Fecha no definida';
                $hE = !empty($ev['hora_evento']) ? date('H:i', strtotime($ev['hora_evento'])) : 'Hora no definida';
                $desc = strlen($ev['descripcion'] ?? '') > 120 ? substr($ev['descripcion'], 0, 120) . '...' : ($ev['descripcion'] ?? '');
            ?>
            <div class="col-12 col-md-6 col-xl-4">
                <article class="event-card">
                    <div class="status-indicator <?= $ec ?>"></div>
                    <div class="card-body d-flex flex-column">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <span class="status-badge <?= $ec ?>"><i class="fa-regular <?= $ei ?>"></i> <?= htmlspecialchars($ev['estado']) ?></span>
                        </div>
                        <h4 class="fw-bold mb-2"><?= htmlspecialchars($ev['nombre_evento']) ?></h4>
                        <p class="text-secondary small mb-3"><?= htmlspecialchars($desc) ?></p>
                        <div class="bg-light rounded-3 p-3 mb-3">
                            <div class="event-info">
                                <div class="icon-wrapper"><i class="fa-regular fa-calendar"></i></div>
                                <div><div class="label">Fecha</div><div class="value"><?= $fE ?></div></div>
                            </div>
                            <div class="event-info">
                                <div class="icon-wrapper"><i class="fa-regular fa-clock"></i></div>
                                <div><div class="label">Hora</div><div class="value"><?= $hE ?></div></div>
                            </div>
                            <div class="event-info mb-0">
                                <div class="icon-wrapper"><i class="fa-solid fa-location-dot"></i></div>
                                <div><div class="label">Lugar</div><div class="value"><span class="event-location"><i class="fa-solid fa-map-pin text-primary"></i> <?= htmlspecialchars($ev['lugar'] ?: 'Lugar no definido') ?></span></div></div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <small class="text-secondary"><i class="fa-regular fa-calendar-plus me-1"></i> Creado: <?= date('d M Y', strtotime($ev['fecha_creacion'])) ?></small>
                        </div>
                        <a href="<?= url('/detalle-evento?id=' . $ev['id_evento']) ?>" class="btn-detail-event mt-auto">Ver evento <i class="fa-solid fa-arrow-right"></i></a>
                    </div>
                </article>
            </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12">
                <div class="empty-state">
                    <div class="empty-icon"><i class="fa-regular fa-calendar-xmark"></i></div>
                    <h4 class="fw-bold mb-2">No hay eventos disponibles</h4>
                    <p class="text-secondary mb-0">
                        <?php if ($limpiarFiltros): ?>
                            No encontramos eventos que coincidan con tu búsqueda.<br>
                            <a href="<?= url('/ver-eventos') ?>" class="btn btn-link text-primary"><i class="fa-solid fa-arrow-left me-1"></i> Ver todos</a>
                        <?php else: ?>
                            Pronto tendremos nuevos eventos para ti.<br>¡No olvides visitarnos nuevamente!
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
                <li class="page-item"><a class="page-link" href="?pagina=<?= $paginaActual - 1 ?>&buscar=<?= urlencode($filtroBusqueda) ?>&estado=<?= urlencode($filtroEstado) ?>&fecha=<?= urlencode($filtroFecha) ?>"><i class="fa-solid fa-chevron-left"></i></a></li>
            <?php endif; ?>
            <?php
            $paginas = [];
            if ($paginaActual <= 2) {
                for ($i = 1; $i <= min(3, $totalPaginas); $i++) $paginas[] = $i;
            } else {
                $paginas[] = $paginaActual;
                if ($paginaActual + 1 <= $totalPaginas) $paginas[] = $paginaActual + 1;
            }
            if ($totalPaginas > 2) {
                if (!in_array($totalPaginas - 1, $paginas)) $paginas[] = $totalPaginas - 1;
                if (!in_array($totalPaginas, $paginas)) $paginas[] = $totalPaginas;
            }
            $paginas = array_unique($paginas);
            sort($paginas);
            $ultimaMostrada = 0;
            foreach ($paginas as $p):
                if ($ultimaMostrada > 0 && $p > $ultimaMostrada + 1):
                    echo '<li class="page-item disabled"><span class="page-link">…</span></li>';
                endif;
                ?>
                <li class="page-item <?= $p === $paginaActual ? 'active' : '' ?>"><a class="page-link" href="?pagina=<?= $p ?>&buscar=<?= urlencode($filtroBusqueda) ?>&estado=<?= urlencode($filtroEstado) ?>&fecha=<?= urlencode($filtroFecha) ?>"><?= $p ?></a></li>
                <?php $ultimaMostrada = $p;
            endforeach; ?>
            <?php if ($paginaActual < $totalPaginas): ?>
                <li class="page-item"><a class="page-link" href="?pagina=<?= $paginaActual + 1 ?>&buscar=<?= urlencode($filtroBusqueda) ?>&estado=<?= urlencode($filtroEstado) ?>&fecha=<?= urlencode($filtroFecha) ?>"><i class="fa-solid fa-chevron-right"></i></a></li>
            <?php endif; ?>
        </ul>
    </nav>
    <?php endif; ?>
</main>
<?php
$content = ob_get_clean();
require_once appPath('cliente/layouts/PublicLayout.php');