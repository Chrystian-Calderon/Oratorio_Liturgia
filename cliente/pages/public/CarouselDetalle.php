<?php
$pageTitle = 'Detalle';
$pageStyles = [
    'cliente/assets/css/carousel-detalle.css',
];
ob_start();

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$slide = null;

$carouselPath = appPath('servidor/data/carousel.json');
if (file_exists($carouselPath)) {
    $carouselData = json_decode(file_get_contents($carouselPath), true);
    foreach (($carouselData['slides'] ?? []) as $s) {
        if ((int) $s['id'] === $id) {
            $slide = $s;
            break;
        }
    }
}

if (!$slide):
?>
    <div class="container py-5 text-center">
        <div class="empty-state mx-auto" style="max-width: 480px;">
            <i class="fas fa-image mb-3" style="font-size: 3rem; color: #0340A0;"></i>
            <h2 class="fw-bold mb-3">Contenido no encontrado</h2>
            <p class="text-secondary mb-4">No encontramos el contenido que buscas. Puede haber sido desactivado o eliminado.</p>
            <a href="<?= url('/') ?>" class="btn btn-primary rounded-pill px-4"><i class="fas fa-arrow-left me-2"></i>Volver al inicio</a>
        </div>
    </div>
<?php else: ?>
    <article class="carousel-detalle">
        <div class="detalle-banner">
            <img src="<?= url('cliente/assets/img/carusel/' . htmlspecialchars($slide['imagen'])) ?>" class="detalle-banner-img w-100" alt="<?= htmlspecialchars($slide['titulo']) ?>">
            <div class="detalle-banner-overlay"></div>
            <div class="container detalle-banner-content">
                <span class="detalle-badge d-inline-block px-4 py-2 rounded-pill mb-3">
                    <i class="fa-regular fa-newspaper me-2"></i> NOTICIAS
                </span>
                <h1 class="display-4 fw-bold mb-2"><?= htmlspecialchars($slide['titulo']) ?></h1>
                <p class="lead mb-0"><?= htmlspecialchars($slide['subtitulo']) ?></p>
            </div>
        </div>

        <div class="container py-5">
            <div class="row justify-content-center">
                <div class="col-12 col-md-10 col-lg-8">
                    <p class="fs-5 lh-lg detalle-texto"><?= nl2br(htmlspecialchars($slide['descripcion'])) ?></p>
                    <div class="mt-4 pt-3 border-top d-flex flex-wrap gap-2">
                        <a href="<?= url('/noticias') ?>" class="btn btn-outline-secondary rounded-pill px-4"><i class="fas fa-arrow-left me-2"></i>Ver todas las noticias</a>
                        <a href="<?= url('/') ?>" class="btn btn-primary rounded-pill px-4"><i class="fas fa-home me-2"></i>Ir al inicio</a>
                    </div>
                </div>
            </div>
        </div>
    </article>
<?php endif; ?>

<?php
$content = ob_get_clean();
require appPath('cliente/layouts/PublicLayout.php');
