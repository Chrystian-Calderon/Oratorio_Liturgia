<?php

$pageStyles = [
    'cliente/assets/css/pagina_inicio.css',
    'cliente/assets/css/carousel.css',
    'cliente/assets/css/inicio-eventos-wrapper.css',
];
ob_start();
?>
    <!-- Panel Izquierdo: Redes Sociales y Botón Subir -->
    <div class="social-floating">
        <!-- Tus redes sociales -->
        <a href="TU_LINK_FACEBOOK" target="_blank" title="Facebook"><i class="fab fa-facebook-f"></i></a>
        <a href="TU_LINK_INSTAGRAM" target="_blank" title="Instagram"><i class="fab fa-instagram"></i></a>
        <a href="TU_LINK_TIKTOK" target="_blank" title="TikTok"><i class="fab fa-tiktok"></i></a>

        <!-- Pequeña separación visual opcional -->
        <div style="height: 5px;"></div>

        <!-- Tu flecha de Subir Arriba -->
        <a href="#" class="scroll-top-btn" title="Volver arriba">
            <i class="fas fa-arrow-up"></i>
        </a>
    </div>

    <!-- BOTONES FLOTANTES DERECHOS (WhatsApp y Sugerencias - BAJADOS) -->
    <div class="floating-buttons">
        <!-- Botón de WhatsApp -->
        <a href="https://wa.me/59172060082?text=Hola%2C%20me%20gustar%C3%ADa%20obtener%20m%C3%A1s%20informaci%C3%B3n%20sobre%20Oratorio%20y%20Liturgia" target="_blank" class="floating-btn btn-whatsapp" title="WhatsApp">
            <i class="fab fa-whatsapp"></i>
        </a>

        <!-- Botón de Sugerencias -->
        <button type="button" class="floating-btn btn-suggestion" title="Sugerencias" data-bs-toggle="modal" data-bs-target="#sugerenciaModal">
            <i class="fas fa-lightbulb"></i>
        </button>
    </div>

    <!-- =========================================================
     CAROUSEL PRINCIPAL (DINÁMICO)
     ========================================================= -->
<?php
$carouselPath = appPath('servidor/data/carousel.json');
$carouselSlides = [];
if (file_exists($carouselPath)) {
    $carouselData = json_decode(file_get_contents($carouselPath), true);
    if ($carouselData && isset($carouselData['slides'])) {
        $carouselSlides = array_filter($carouselData['slides'], fn($s) => !empty($s['activo']) && !empty($s['imagen']));
        $carouselSlides = array_values($carouselSlides);
    }
}
if (!empty($carouselSlides)):
?>
    <section class="hero-carousel p-0">
        <div id="heroCarousel"
            class="carousel slide carousel-fade"
            data-bs-ride="carousel"
            data-bs-interval="5000">

            <!-- INDICADORES -->
            <div class="carousel-indicators">
                <?php foreach ($carouselSlides as $i => $slide): ?>
                <button type="button"
                    data-bs-target="#heroCarousel"
                    data-bs-slide-to="<?= $i ?>"
                    class="<?= $i === 0 ? 'active' : '' ?>"
                    aria-current="<?= $i === 0 ? 'true' : 'false' ?>"
                    aria-label="Slide <?= $i + 1 ?>">
                </button>
                <?php endforeach; ?>
            </div>

            <!-- SLIDES -->
            <div class="carousel-inner">
                <?php foreach ($carouselSlides as $i => $slide): ?>
                <div class="carousel-item <?= $i === 0 ? 'active' : '' ?> position-relative"
                    onmouseenter="this.querySelector('.btn-leer')?.classList.remove('d-none')"
                    onmouseleave="this.querySelector('.btn-leer')?.classList.add('d-none')">
                    <img src="<?= url('cliente/assets/img/carusel/' . htmlspecialchars($slide['imagen'])) ?>"
                        class="d-block w-100 hero-img"
                        alt="<?= htmlspecialchars($slide['titulo']) ?>">
                    <div class="carousel-caption custom-caption" style="left: 50%; transform:translateX(-50%)">
                        <!-- <h1 class="display-4 fw-bold"><?= htmlspecialchars($slide['titulo']) ?></h1>
                        <p class="lead"><?= htmlspecialchars($slide['subtitulo']) ?></p> -->
                        <a href="<?= url('/carousel-detalle?id=' . (int) $slide['id']) ?>" class="btn btn-warning btn-lg rounded-pill px-4 shadow-sm mt-3">
                            <i class="fas fa-book-open me-2"></i>Leer más
                        </a>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <!-- CONTROLES -->
            <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Anterior</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Siguiente</span>
            </button>
        </div>
    </section>
<?php endif; ?>


    <section class="py-5 bg-light overflow-hidden">
        <div class="container">
            <!-- HERO PRINCIPAL -->
            <div class="row align-items-center g-5 mb-5">
                <div class="col-lg-6">
                    <h1 class="display-3 fw-bold text-dark lh-sm mb-4">
                        <span class="text-primary">Comunidad Pastoral Universitaria</span>
                    </h1>

                    <p class="lead text-secondary mb-4">
                        Un espacio de fe, formación y servicio donde jóvenes y familias
                        viven experiencias que transforman vidas y fortalecen la comunidad.
                    </p>

                    <!-- Botones -->
                    <div class="d-flex flex-wrap gap-3">
                        <a href="<?= url('/calendario') ?>" class="btn btn-danger btn-lg rounded-pill px-4 shadow-sm">
                            <i class="fas fa-calendar-alt me-2"></i>
                            Ver actividades
                        </a>

                        <a href="https://www.youtube.com/watch?v=RpIq4r9UJtw" class="btn btn-outline-success btn-lg rounded-pill px-4 shadow-sm">
                            <i class="fas fa-circle-play me-2"></i>
                            Conócenos

                        </a>

                        <!--Ver Noticias -->
                        <a href="<?= url('/noticias') ?>" class="btn btn-primary btn-lg rounded-pill px-4 shadow-sm">
                            <i class="fas fa-newspaper me-2"></i>
                            Ver Noticias
                        </a>
                    </div>


                    <!-- Mini estadísticas -->
                    <div class="row mt-5 g-4 text-center">
                        <div class="col-4">
                            <h2 class="fw-bold text-primary mb-0 counter" data-target="150">0</h2>
                            <small class="text-muted">Años formando jóvenes</small>
                        </div>

                        <div class="col-4">
                            <h2 class="fw-bold text-primary mb-0 counter" data-target="30">0</h2>
                            <small class="text-muted">Miembros activos</small>
                        </div>

                        <div class="col-4">
                            <h2 class="fw-bold text-primary mb-0 counter" data-target="30">0</h2>
                            <small class="text-muted">Actividades anuales</small>
                        </div>
                    </div>
                </div>

                <!-- Slider -->
                <div class="col-lg-6">
                    <div class="position-relative">
                        <div id="sliderModern"
                            class="carousel slide shadow rounded-4 overflow-hidden"
                            data-bs-ride="carousel"
                            data-bs-interval="4000">
                            <!-- Slides -->
                            <div class="carousel-inner">
                                <!-- Slide 1 -->
                                <div class="carousel-item active position-relative">
                                    <img src="<?= url('cliente/assets/img/comunidad-2.jpeg') ?>"
                                        class="d-block w-100"
                                        alt="Comunidad"
                                        style="height: 620px; object-fit: cover;">
                                    <!-- Overlay -->
                                    <div class="position-absolute top-0 start-0 w-100 h-100"
                                        style="background: rgba(0,0,0,0.35);">
                                    </div>
                                    <div class="carousel-caption text-start pb-5">
                                        <span class="badge bg-primary px-3 py-2 rounded-pill mb-3">
                                            Comunidad
                                        </span>
                                        <h2 class="fw-bold display-6">
                                            Unidos en la fe
                                        </h2>
                                        <p class="small">
                                            Jóvenes compartiendo experiencias y crecimiento espiritual.
                                        </p>
                                    </div>
                                </div>

                                <!-- Slide 2 -->
                                <div class="carousel-item position-relative">
                                    <img src="<?= url('cliente/assets/img/comunidad-1.jpeg') ?>"
                                        class="d-block w-100"
                                        alt="Juventud"
                                        style="height: 620px; object-fit: cover;">
                                    <!-- Overlay -->
                                    <div class="position-absolute top-0 start-0 w-100 h-100"
                                        style="background: rgba(0,0,0,0.35);">
                                    </div>
                                    <div class="carousel-caption text-start pb-5">
                                        <span class="badge bg-success px-3 py-2 rounded-pill mb-3">
                                            Juventud
                                        </span>
                                        <h2 class="fw-bold display-6">
                                            Formación integral
                                        </h2>
                                        <p class="small">
                                            Espacios de aprendizaje y acompañamiento permanente.
                                        </p>
                                    </div>
                                </div>

                                <!-- Slide 3 -->
                                <div class="carousel-item position-relative">
                                    <img src="<?= url('cliente/assets/img/comunidad-3.jpeg') ?>"
                                        class="d-block w-100"
                                        alt="Servicio"
                                        style="height: 620px; object-fit: cover;">
                                    <!-- Overlay -->
                                    <div class="position-absolute top-0 start-0 w-100 h-100"
                                        style="background: rgba(0,0,0,0.35);">
                                    </div>
                                    <div class="carousel-caption text-start pb-5">
                                        <span class="badge bg-warning text-dark px-3 py-2 rounded-pill mb-3">
                                            Servicio
                                        </span>
                                        <h2 class="fw-bold display-6">
                                            Evangelio en acción
                                        </h2>
                                        <p class="small">
                                            Comprometidos con el servicio y la solidaridad.
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Flecha izquierda -->
                            <button class="carousel-control-prev"
                                type="button"
                                data-bs-target="#sliderModern"
                                data-bs-slide="prev">
                                <span class="carousel-control-prev-icon"></span>
                            </button>
                            <!-- Flecha derecha -->
                            <button class="carousel-control-next"
                                type="button"
                                data-bs-target="#sliderModern"
                                data-bs-slide="next">
                                <span class="carousel-control-next-icon"></span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- descubre tu lugar en la comunidad -->
    <section class="py-5 bg-light overflow-hidden container">
        <div class="row mb-4 text-center">
            <div class="col-12">
                <h2 class="display-5 fw-bold">Descubre tu lugar en la Comunidad</h2>
                <p class="text-muted lead">
                    Conoce nuestros pilares y sé parte de esta gran familia.
                </p>
            </div>
        </div>

        <!-- TARJETAS MODERNAS (SIN BOTONES) -->
        <div class="row g-4 mb-5">
            <div class="col-md-4">
                <div class="card border-0 shadow-sm rounded-5 h-100">
                    <img src="https://images.unsplash.com/photo-1517486808906-6ca8b3f04846?w=900"
                        class="card-img-top"
                        style="height: 250px; object-fit: cover;"
                        alt="Oración">
                    <div class="card-body p-4 text-center">
                        <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex p-3 mb-3">
                            <i class="fas fa-praying-hands text-primary fs-4"></i>
                        </div>
                        <h4 class="fw-bold">Espacios de oración</h4>
                        <p class="text-muted mb-0">
                            Momentos de reflexión, espiritualidad y encuentro con Dios.
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card border-0 shadow-sm rounded-5 h-100">
                    <img src="https://images.unsplash.com/photo-1522202176988-66273c2fd55f?w=900"
                        class="card-img-top"
                        style="height: 250px; object-fit: cover;"
                        alt="Formación">
                    <div class="card-body p-4 text-center">
                        <div class="bg-success bg-opacity-10 rounded-circle d-inline-flex p-3 mb-3">
                            <i class="fas fa-users text-success fs-4"></i>
                        </div>
                        <h4 class="fw-bold">Formación juvenil</h4>
                        <p class="text-muted mb-0">
                            Actividades dinámicas para el crecimiento humano y cristiano.
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card border-0 shadow-sm rounded-5 h-100">
                    <img src="https://images.unsplash.com/photo-1509099836639-18ba1795216d?w=900"
                        class="card-img-top"
                        style="height: 250px; object-fit: cover;"
                        alt="Servicio">
                    <div class="card-body p-4 text-center">
                        <div class="bg-warning bg-opacity-10 rounded-circle d-inline-flex p-3 mb-3">
                            <i class="fas fa-hand-holding-heart text-warning fs-4"></i>
                        </div>
                        <h4 class="fw-bold">Servicio comunitario</h4>
                        <p class="text-muted mb-0">
                            Compartimos esperanza mediante acciones solidarias.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- BOTÓN GIGANTE DE PARTICIPACIÓN -->
        <div class="row mb-5 text-center">
            <div class="col-12">
                <a href="<?= url('/participar') ?>" class="btn btn-primary btn-lg rounded-pill px-5 py-3 shadow-lg fs-5 fw-bold transition-all hover-scale">
                    <i class="fas fa-rocket me-2"></i> ¡Quiero Participar!
                </a>
            </div>
        </div>
    </section>

    <!-- video -->
    <section class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-5">
                <h2 class="display-5 fw-bold mb-4">
                    Vive la experiencia del Oratorio
                </h2>
                <p class="text-secondary lead">
                    Reflexiones, celebraciones y momentos especiales compartidos
                    con toda la comunidad.
                </p>
                <div class="d-flex flex-column gap-3 mt-4">
                    <div class="d-flex align-items-center gap-3">
                        <div class="bg-primary bg-opacity-10 rounded-circle p-3">
                            <i class="fas fa-check text-primary"></i>
                        </div>
                        <span>Eventos y celebraciones</span>
                    </div>

                    <div class="d-flex align-items-center gap-3">
                        <div class="bg-success bg-opacity-10 rounded-circle p-3">
                            <i class="fas fa-check text-success"></i>
                        </div>
                        <span>Formación espiritual</span>
                    </div>

                    <div class="d-flex align-items-center gap-3">
                        <div class="bg-success bg-opacity-10 rounded-circle p-3">
                            <i class="fas fa-check text-success"></i>
                        </div>
                        <span>Testimonios y experiencias</span>
                    </div>
                </div>
            </div>

            <div class="col-lg-7">
                <div class="ratio ratio-16x9 rounded-5 overflow-hidden shadow-lg">
                    <iframe src="https://www.youtube.com/embed/CkU66thoYRs?si=CveXfUIPDuchqHeZ"
                        title="Video"
                        allowfullscreen>
                    </iframe>
                </div>
            </div>
        </div>
    </section>

    <!-- 1. PRÓXIMOS EVENTOS (Sentido de Urgencia) -->
    <section id="eventos" class="py-5">
        <div class="container">
            <h2 class="text-center fw-bold border-bottom border-primary pb-2 mb-5">Próximos Eventos</h2>
            <p class="text-center mb-5 lead">No te pierdas nuestras próximas actividades y celebraciones especiales.</p>
            <div class="eventos-wrapper">
                <button class="btn btn-primary eventos-control eventos-prev" type="button" aria-label="Evento anterior"><i class="bi bi-chevron-left"></i></button>
                <div class="eventos-scroll" id="eventosScroll" data-url="<?= url('/inicio-eventos-data') ?>">
                    <div class="eventos-cargando text-center w-100 py-4">
                        <div class="spinner-border text-primary" role="status"></div>
                    </div>
                </div>
                <button class="btn btn-primary eventos-control eventos-next" type="button" aria-label="Siguiente evento"><i class="bi bi-chevron-right"></i></button>
            </div>
        </div>
    </section>

    <!-- 2. ACTIVIDADES REGULARES (Prueba Social / Constancia) -->
    <section id="actividades" class="py-5 bg-light">
        <div class="container">
            <h2 class="text-center fw-bold border-bottom border-primary pb-2 mb-5">Actividades Regulares</h2>
            <p class="text-center mb-5 lead">Participa en nuestras actividades semanales diseñadas para todas las edades.</p>
            <div class="row g-4">
                <div class="col-md-6">
                    <div class="card mb-4 border-0 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="card-title mb-0">Misa Dominical</h5><span class="badge bg-primary">Todos los Domingos</span>
                            </div>
                            <p class="card-text"><i class="bi bi-clock me-2"></i>8:00 AM, 10:00 AM, 12:00 PM</p>
                            <p class="card-text">Celebración eucarística para toda la comunidad. Incluye música en vivo y homilía especial para niños en el horario de las 10:00 AM.</p>
                            <div class="progress mt-3" style="height: 8px;">
                                <div class="progress-bar" role="progressbar" style="width: 85%;"></div>
                            </div>
                            <small class="text-muted">85% de capacidad regular</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card mb-4 border-0 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="card-title mb-0">Grupo de Jóvenes</h5><span class="badge bg-success">Todos los Viernes</span>
                            </div>
                            <p class="card-text"><i class="bi bi-clock me-2"></i>7:00 PM - 9:00 PM</p>
                            <p class="card-text">Encuentros para jóvenes de 15 a 25 años con dinámicas, charlas, oración y actividades sociales.</p>
                            <div class="progress mt-3" style="height: 8px;">
                                <div class="progress-bar bg-success" role="progressbar" style="width: 65%;"></div>
                            </div>
                            <small class="text-muted">65% de capacidad regular</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card mb-4 border-0 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="card-title mb-0">Catequesis Familiar</h5><span class="badge bg-info">Todos los Sábados</span>
                            </div>
                            <p class="card-text"><i class="bi bi-clock me-2"></i>10:00 AM - 12:00 PM</p>
                            <p class="card-text">Formación en la fe para niños y sus familias, preparación para los sacramentos.</p>
                            <div class="progress mt-3" style="height: 8px;">
                                <div class="progress-bar bg-info" role="progressbar" style="width: 90%;"></div>
                            </div>
                            <small class="text-muted">90% de capacidad regular</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card mb-4 border-0 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="card-title mb-0">Adoración Eucarística</h5><span class="badge bg-purple" style="background-color: #6f42c1;">Primer Viernes de mes</span>
                            </div>
                            <p class="card-text"><i class="bi bi-clock me-2"></i>7:00 PM - 8:00 PM</p>
                            <p class="card-text">Momento de oración silenciosa ante el Santísimo Sacramento.</p>
                            <div class="progress mt-3" style="height: 8px;">
                                <div class="progress-bar" style="background-color: #6f42c1; width: 75%;"></div>
                            </div>
                            <small class="text-muted">75% de capacidad regular</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 3. SERVICIOS ESPIRITUALES (Profundidad Institucional) -->
    <section id="servicios-espirituales" class="py-5">
        <div class="container">
            <h2 class="text-center fw-bold border-bottom border-primary pb-2 mb-5">Nuestros Servicios Espirituales</h2>
            <p class="text-center mb-5 lead">Ofrecemos una variedad de servicios espirituales para fortalecer tu fe y conexión con Dios.</p>
            <div class="row g-4">
                <div class="col-lg-4 col-md-6">
                    <div class="card h-100 shadow-lg border-0 rounded-4 overflow-hidden">
                        <div class="position-relative" style="height: 250px; overflow: hidden;">
                            <img src="<?= url('cliente/assets/img/img_eucaristía.jpg') ?>" alt="Misas" class="w-100 h-100 object-fit-cover transition-transform" style="transition: transform 0.5s ease;">
                            <span class="position-absolute top-0 start-0 bg-primary text-white px-3 py-1 m-3 rounded-pill">Nuevo</span>
                        </div>
                        <div class="card-body d-flex flex-column p-4">
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-primary bg-opacity-10 p-2 rounded me-3">
                                    <i class="bi bi-calendar-check text-primary fs-4"></i>
                                </div>
                                <h5 class="card-title fw-bold mb-0">Celebraciones Eucarísticas</h5>
                            </div>
                            <p class="card-text flex-grow-1 text-muted">Participa en nuestras misas diarias y dominicales, así como en celebraciones especiales durante el año litúrgico.</p>
                            <div class="mt-auto pt-3">
                                <button type="button" class="btn btn-primary w-100 py-2 fw-semibold" data-bs-toggle="modal" data-bs-target="#modalEucaristia">Ver Horarios <i class="bi bi-clock ms-1"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="card h-100 shadow-lg border-0 rounded-4 overflow-hidden">
                        <div class="position-relative" style="height: 250px; overflow: hidden;">
                            <img src="<?= url('cliente/assets/img/Sacramentos.jpg') ?>" alt="Sacramentos" class="w-100 h-100 object-fit-cover transition-transform" style="transition: transform 0.5s ease;">
                            <span class="position-absolute top-0 start-0 bg-success text-white px-3 py-1 m-3 rounded-pill">Destacado</span>
                        </div>
                        <div class="card-body d-flex flex-column p-4">
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-success bg-opacity-10 p-2 rounded me-3">
                                    <i class="bi bi-heart-fill text-success fs-4"></i>
                                </div>
                                <h5 class="card-title fw-bold mb-0">Sacramentos</h5>
                            </div>
                            <p class="card-text flex-grow-1 text-muted">Celebramos todos los sacramentos: bautismo, primera comunión, confirmación, reconciliación, unción de los enfermos y matrimonio.</p>
                            <div class="mt-auto pt-3">
                                <button type="button" class="btn btn-success w-100 py-2 fw-semibold" data-bs-toggle="modal" data-bs-target="#modalSacramentos">Más Información <i class="bi bi-info-circle ms-1"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="card h-100 shadow-lg border-0 rounded-4 overflow-hidden">
                        <div class="position-relative" style="height: 250px; overflow: hidden;">
                            <img src="<?= url('cliente/assets/img/oracion.jpg') ?>" alt="Grupos de oración" class="w-100 h-100 object-fit-cover transition-transform" style="transition: transform 0.5s ease;">
                            <span class="position-absolute top-0 start-0 bg-warning text-dark px-3 py-1 m-3 rounded-pill">Comunidad</span>
                        </div>
                        <div class="card-body d-flex flex-column p-4">
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-warning bg-opacity-10 p-2 rounded me-3">
                                    <i class="bi bi-people-fill text-warning fs-4"></i>
                                </div>
                                <h5 class="card-title fw-bold mb-0">Grupos de Oración</h5>
                            </div>
                            <p class="card-text flex-grow-1 text-muted">Únete a nuestros grupos de oración para compartir y crecer juntos en la fe a través de la oración comunitaria y el estudio bíblico.</p>
                            <div class="mt-auto pt-3">
                                <button type="button" class="btn btn-warning text-dark w-100 py-2 fw-semibold" data-bs-toggle="modal" data-bs-target="#modalOracion">Unirse <i class="bi bi-person-plus ms-1"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 4. TESTIMONIOS -->
    <section id="testimonios" class="py-5 bg-light">
        <div class="container">
            <h2 class="text-center fw-bold border-bottom border-primary pb-2 mb-5">Testimonios</h2>
            <p class="text-center mb-5 lead">Lo que dicen los miembros de nuestra comunidad sobre su experiencia.</p>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body text-center p-4">
                            <div class="mb-4"><img src="https://randomuser.me/api/portraits/women/45.jpg" alt="María González" class="rounded-circle border border-3 border-primary" width="80"></div>
                            <h5 class="card-title">María González</h5>
                            <p class="text-muted mb-3">Miembro desde 2018</p>
                            <p class="card-text">"Encontré en esta comunidad un hogar espiritual donde puedo crecer en mi fe. Las celebraciones litúrgicas son especialmente significativas para mí y mi familia."</p>
                            <div class="mt-3 text-warning"><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body text-center p-4">
                            <div class="mb-4"><img src="https://randomuser.me/api/portraits/men/32.jpg" alt="Carlos Rodríguez" class="rounded-circle border border-3 border-primary" width="80"></div>
                            <h5 class="card-title">Carlos Rodríguez</h5>
                            <p class="text-muted mb-3">Miembro desde 2015</p>
                            <p class="card-text">"Los grupos de oración han transformado mi vida espiritual. La comunidad es acogedora y me ha ayudado a profundizar mi relación con Dios de manera significativa."</p>
                            <div class="mt-3 text-warning"><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-half"></i></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body text-center p-4">
                            <div class="mb-4"><img src="https://randomuser.me/api/portraits/women/68.jpg" alt="Ana Martínez" class="rounded-circle border border-3 border-primary" width="80"></div>
                            <h5 class="card-title">Ana Martínez</h5>
                            <p class="text-muted mb-3">Miembro desde 2020</p>
                            <p class="card-text">"Las actividades para jóvenes han sido fundamentales para que mis hijos mantengan su fe. Estoy muy agradecida por el trabajo que hacen con las nuevas generaciones."</p>
                            <div class="mt-3 text-warning"><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star"></i></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contacto -->
    <section id="contacto" class="py-5 bg-primary text-white">
        <div class="container">
            <h2 class="text-center fw-bold border-bottom border-white pb-2 mb-5">Contáctanos</h2>
            <p class="text-center mb-5 lead">Estamos aquí para responder tus preguntas y ayudarte en tu camino espiritual.</p>
            <div class="row g-4">
                <div class="col-md-4 text-center">
                    <div class="p-4 bg-primary bg-opacity-25 rounded-3 h-100"><i class="bi bi-geo-alt fs-1 mb-3"></i>
                        <h5>Dirección</h5>
                        <p>Av.Chacaltaya Nro.1258, Zona Achachicala. #123<br>Ciudad de La Paz</p>
                    </div>
                </div>
                <div class="col-md-4 text-center">
                    <div class="p-4 bg-primary bg-opacity-25 rounded-3 h-100"><i class="bi bi-telephone fs-1 mb-3"></i>
                        <h5>Teléfono</h5>
                        <p>Whatsapp: (591)72060082 Celular:(591)72002192<br>Lunes a Viernes: 8:30AM - 4:30PM</p>
                    </div>
                </div>
                <div class="col-md-4 text-center">
                    <div class="p-4 bg-primary bg-opacity-25 rounded-3 h-100"><i class="bi bi-envelope fs-1 mb-3"></i>
                        <h5>Correo Electrónico</h5>
                        <p>www.usalesiana.edu.bo<br>contacto@oratorioliturgia.org</p>
                    </div>
                </div>
            </div>
            <div class="row mt-5">
                <div class="col-lg-8 mx-auto">
                    <div class="bg-white text-dark rounded-3 p-4 shadow">
                        <h4 class="text-center mb-4">Envíanos un mensaje</h4>
                        <form id="formContactoInicio">
                            <div class="row g-3">
                                <div class="col-md-6"><input type="text" class="form-control" id="cNombre" placeholder="Tu nombre" required></div>
                                <div class="col-md-6"><input type="email" class="form-control" id="cEmail" placeholder="Tu correo electrónico" required></div>
                                <div class="col-12"><input type="text" class="form-control" id="cAsunto" placeholder="Asunto"></div>
                                <div class="col-12"><textarea class="form-control" id="cMensaje" rows="4" placeholder="Tu mensaje" required></textarea></div>
                                <div class="col-12"><button type="submit" class="btn btn-primary w-100 py-2" id="btnEnviarContactoInicio"><i class="fas fa-paper-plane me-2"></i>Enviar Mensaje</button></div>
                            </div>
                        </form>
                        <div id="contactoInicioAlert" class="mt-3" style="display: none;"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Modal para inscripción rápida -->
    <div class="modal fade" id="inscripcionModal" tabindex="-1" aria-labelledby="inscripcionModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="inscripcionModalLabel">Inscripción a Evento</h5><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p id="modalEventoDesc">¿Deseas inscribirte en este evento?</p>
                    <form>
                        <div class="mb-3"><label for="modalNombre" class="form-label">Nombre completo</label><input type="text" class="form-control" id="modalNombre" required></div>
                        <div class="mb-3"><label for="modalEmail" class="form-label">Correo electrónico</label><input type="email" class="form-control" id="modalEmail" required></div>
                    </form>
                </div>
                <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button><button type="button" class="btn btn-primary">Confirmar Inscripción</button></div>
            </div>
        </div>
    </div>

    <!-- MODALES (Ocultos a nivel visual, se activan con los botones) -->
    <!-- Modal Eucaristía -->
    <div class="modal fade" id="modalEucaristia" tabindex="-1" aria-labelledby="modalEucaristiaLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content rounded-4 overflow-hidden">
                <div class="modal-header bg-primary text-white">
                    <h2 class="modal-title fw-bold" id="modalEucaristiaLabel"><i class="bi bi-calendar-check me-2"></i> Horarios de Misas</h2>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-0">
                    <div class="row">
                        <div class="col-md-6 p-4">
                            <h4 class="fw-bold mb-4">Horarios Regulares</h4>
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item d-flex justify-content-between"><span><strong>Domingos:</strong></span><span>7:00 AM, 9:00 AM, 11:00 AM, 6:00 PM</span></li>
                                <li class="list-group-item d-flex justify-content-between"><span><strong>Lunes a Viernes:</strong></span><span>7:00 AM, 12:00 PM</span></li>
                                <li class="list-group-item d-flex justify-content-between"><span><strong>Sábados:</strong></span><span>7:00 AM, 5:00 PM</span></li>
                                <li class="list-group-item d-flex justify-content-between"><span><strong>Primeros Viernes:</strong></span><span>7:00 PM (Adoración)</span></li>
                            </ul>
                        </div>
                        <div class="col-md-6 bg-light p-4">
                            <h4 class="fw-bold mb-4">Próximas Celebraciones</h4>
                            <div class="mb-3">
                                <h6 class="text-primary fw-bold">Navidad</h6>
                                <p class="mb-1">24 de Diciembre: Misa de Gallo - 10:00 PM</p>
                                <p>25 de Diciembre: Misas - 7:00 AM, 9:00 AM, 11:00 AM</p>
                            </div>
                            <div class="mb-3">
                                <h6 class="text-primary fw-bold">Semana Santa</h6>
                                <p class="mb-1">Jueves Santo: 7:00 PM</p>
                                <p>Viernes Santo: 3:00 PM</p>
                            </div>
                            <div class="alert alert-info mt-4"><i class="bi bi-info-circle me-2"></i><strong>Nota:</strong> Los horarios pueden cambiar en días festivos.</div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary"><i class="bi bi-download me-1"></i> Descargar Horarios</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Sacramentos -->
    <div class="modal fade" id="modalSacramentos" tabindex="-1" aria-labelledby="modalSacramentosLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content rounded-4 overflow-hidden">
                <div class="modal-header bg-success text-white">
                    <h2 class="modal-title fw-bold" id="modalSacramentosLabel"><i class="bi bi-heart-fill me-2"></i> Sacramentos</h2>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <h4 class="fw-bold mb-3">Información General</h4>
                            <p>Ofrecemos todos los sacramentos de la Iglesia Católica. Para cada sacramento se requiere preparación previa y documentación específica.</p>
                            <div class="accordion" id="accordionSacramentos">
                                <div class="accordion-item">
                                    <h2 class="accordion-header"><button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne">Bautismo</button></h2>
                                    <div id="collapseOne" class="accordion-collapse collapse show" data-bs-parent="#accordionSacramentos">
                                        <div class="accordion-body">
                                            <p>Se realiza el primer sábado de cada mes a las 10:00 AM. Se requiere inscripción previa y asistencia a charlas de preparación.</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="accordion-item">
                                    <h2 class="accordion-header"><button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo">Matrimonio</button></h2>
                                    <div id="collapseTwo" class="accordion-collapse collapse" data-bs-parent="#accordionSacramentos">
                                        <div class="accordion-body">
                                            <p>Se requiere aviso de 6 meses mínimo. Es necesario asistir al curso prematrimonial y presentar documentación requerida.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h4 class="fw-bold mb-3">Contacto para Sacramentos</h4>
                            <div class="card border-success">
                                <div class="card-body">
                                    <p class="card-text"><strong>Encargada:</strong> María González</p>
                                    <p class="card-text"><strong>Teléfono:</strong> (555) 123-4567</p>
                                    <p class="card-text"><strong>Email:</strong> sacramentos@parroquiaejemplo.org</p>
                                    <p class="card-text"><strong>Horario de atención:</strong> Lunes a Viernes 9:00 AM - 1:00 PM</p>
                                    <hr>
                                    <p class="card-text">Para solicitar información sobre cualquier sacramento, puede acudir a la oficina parroquial o contactarnos por teléfono/email.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <a href="#" class="btn btn-success"><i class="bi bi-telephone me-1"></i> Contactar</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Oración -->
    <div class="modal fade" id="modalOracion" tabindex="-1" aria-labelledby="modalOracionLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4 overflow-hidden">
                <div class="modal-header bg-warning text-dark">
                    <h2 class="modal-title fw-bold" id="modalOracionLabel"><i class="bi bi-people-fill me-2"></i> Unirse a Grupos de Oración</h2>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <h4 class="fw-bold mb-3">Formulario de Inscripción</h4>
                    <p>Complete el siguiente formulario para unirse a uno de nuestros grupos de oración. Nos contactaremos con usted en breve.</p>
                    <form action="https://formspree.io/f/maqqoeby" method="POST">
                        <input type="hidden" name="_subject" value="Inscripción a Grupos de Oración">
                        <div class="mb-3"><label for="nombreOracion" class="form-label">Nombre completo *</label><input type="text" class="form-control" id="nombreOracion" name="nombre_completo" required></div>
                        <div class="mb-3"><label for="emailOracion" class="form-label">Correo electrónico *</label><input type="email" class="form-control" id="emailOracion" name="email" required></div>
                        <div class="mb-3"><label for="telefonoOracion" class="form-label">Teléfono</label><input type="tel" class="form-control" id="telefonoOracion" name="telefono"></div>
                        <div class="mb-3"><label for="grupo" class="form-label">Grupo de interés *</label>
                            <select class="form-select" id="grupo" name="grupo_interes" required>
                                <option value="" selected disabled>Seleccione un grupo</option>
                                <option value="jovenes">Grupo de Jóvenes (18-30 años)</option>
                                <option value="adultos">Grupo de Adultos (30-60 años)</option>
                                <option value="tercera_edad">Grupo de la Tercera Edad</option>
                                <option value="matrimonios">Grupo de Matrimonios</option>
                                <option value="estudio">Grupo de Estudio Bíblico</option>
                            </select>
                        </div>
                        <div class="mb-3"><label for="mensajeOracion" class="form-label">Mensaje adicional</label><textarea class="form-control" id="mensajeOracion" name="mensaje_adicional" rows="3"></textarea></div>
                        <div class="form-check mb-3"><input class="form-check-input" type="checkbox" id="privacidadOracion" name="acepta_privacidad" required><label class="form-check-label" for="privacidadOracion">Acepto la política de privacidad y el tratamiento de mis datos *</label></div>
                        <div class="modal-footer pt-3 pb-0 px-0"><button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button><button type="submit" class="btn btn-warning fw-bold">Enviar Inscripción</button></div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Sugerencias -->
    <div class="modal fade" id="sugerenciaModal" tabindex="-1" aria-labelledby="sugerenciaModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4 overflow-hidden">
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title fw-bold" id="sugerenciaModalLabel"><i class="fas fa-lightbulb me-2"></i>Enviar Sugerencia</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <p class="text-muted mb-3">Tu opinión nos ayuda a mejorar. Comparte tus sugerencias con nosotros.</p>
                    <form id="formSugerencia">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="sugNombre" class="form-label">Nombre *</label>
                                <input type="text" class="form-control" id="sugNombre" required>
                            </div>
                            <div class="col-md-6">
                                <label for="sugApellido" class="form-label">Apellido *</label>
                                <input type="text" class="form-control" id="sugApellido" required>
                            </div>
                            <div class="col-md-6">
                                <label for="sugCorreo" class="form-label">Correo electrónico *</label>
                                <input type="email" class="form-control" id="sugCorreo" required>
                            </div>
                            <div class="col-md-6">
                                <label for="sugTelefono" class="form-label">Teléfono</label>
                                <input type="tel" class="form-control" id="sugTelefono">
                            </div>
                            <div class="col-12">
                                <label for="sugAsunto" class="form-label">Asunto *</label>
                                <input type="text" class="form-control" id="sugAsunto" required>
                            </div>
                            <div class="col-12">
                                <label for="sugMensaje" class="form-label">Mensaje *</label>
                                <textarea class="form-control" id="sugMensaje" rows="4" required></textarea>
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-warning fw-bold w-100 py-2">
                                    <i class="fas fa-paper-plane me-2"></i>Enviar Sugerencia
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="<?= url('cliente/assets/js/mini_estadisticas.js') ?>"></script>
    <script src="<?= url('cliente/assets/js/inicio-eventos-wrapper.js') ?>"></script>
    <!-- CHATBOT-->
    <script>
        (function() {
            if (!window.chatbase || window.chatbase("getState") !== "initialized") {
                window.chatbase = (...arguments) => {
                    if (!window.chatbase.q) {
                        window.chatbase.q = []
                    }
                    window.chatbase.q.push(arguments)
                };
                window.chatbase = new Proxy(window.chatbase, {
                    get(target, prop) {
                        if (prop === "q") {
                            return target.q
                        }
                        return (...args) => target(prop, ...args)
                    }
                })
            }
            const onLoad = function() {
                const script = document.createElement("script");
                script.src = "https://www.chatbase.co/embed.min.js";
                script.id = "0MJJ4vCJqVVjsqSb4KmAi";
                script.domain = "www.chatbase.co";
                document.body.appendChild(script)
            };
            if (document.readyState === "complete") {
                onLoad()
            } else {
                window.addEventListener("load", onLoad)
            }
        })();

    </script>

    <script src="<?= url('cliente/assets/js/navbar.js') ?>"></script>
    <script src="<?= url('cliente/assets/js/carousel.js') ?>"></script>

    <script>
        // Script para el modal de inscripción rápida
        const inscripcionModal = document.getElementById('inscripcionModal');
        if (inscripcionModal) {
            inscripcionModal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                const evento = button.getAttribute('data-evento');
                const modalTitle = inscripcionModal.querySelector('.modal-title');
                const modalEventoDesc = inscripcionModal.querySelector('#modalEventoDesc');
                modalTitle.textContent = 'Inscripción: ' + evento;
                modalEventoDesc.textContent = '¿Deseas inscribirte en ' + evento + '?';
            });
        }

        // Animación para las cards al hacer scroll
        document.addEventListener('DOMContentLoaded', function() {
            const cards = document.querySelectorAll('.card');
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.style.opacity = '1';
                        entry.target.style.transform = 'translateY(0)';
                        observer.unobserve(entry.target);
                    }
                });
            }, {
                threshold: 0.1
            });

            cards.forEach(card => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
                card.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
                observer.observe(card);
            });

            // Formulario de sugerencias
            var formSugerencia = document.getElementById('formSugerencia');
            if (formSugerencia) {
                formSugerencia.addEventListener('submit', function(e) {
                    e.preventDefault();
                    var btn = this.querySelector('button[type="submit"]');
                    var originalText = btn.innerHTML;
                    btn.disabled = true;
                    btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Enviando...';

                    var data = {
                        nombre: document.getElementById('sugNombre').value.trim(),
                        apellido: document.getElementById('sugApellido').value.trim(),
                        correo: document.getElementById('sugCorreo').value.trim(),
                        telefono: document.getElementById('sugTelefono').value.trim(),
                        asunto: document.getElementById('sugAsunto').value.trim(),
                        mensaje: document.getElementById('sugMensaje').value.trim()
                    };

                    fetch('<?= url('/sugerencias/guardar') ?>', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify(data)
                    })
                    .then(function(r) { return r.json(); })
                    .then(function(res) {
                        if (res.success) {
                            var modal = bootstrap.Modal.getInstance(document.getElementById('sugerenciaModal'));
                            modal.hide();
                            formSugerencia.reset();
                            showToast('Sugerencia enviada exitosamente.', 'success');
                        } else {
                            showToast(res.message || 'Error al enviar la sugerencia.', 'error');
                        }
                    })
                    .catch(function() {
                        showToast('Error de conexión. Intente nuevamente.', 'error');
                    })
                    .finally(function() {
                        btn.disabled = false;
                        btn.innerHTML = originalText;
                    });
                });
            }

            function showToast(msg, type) {
                var el = document.createElement('div');
                el.className = 'alert alert-' + (type === 'error' ? 'danger' : 'success') + ' position-fixed top-0 end-0 m-3 shadow';
                el.style.zIndex = '9999';
                el.style.minWidth = '280px';
                el.innerHTML = msg;
                document.body.appendChild(el);
                setTimeout(function() { el.remove(); }, 4000);
            }

            // Formulario de contacto en inicio
            var formContactoInicio = document.getElementById('formContactoInicio');
            if (formContactoInicio) {
                formContactoInicio.addEventListener('submit', function(e) {
                    e.preventDefault();
                    var btn = document.getElementById('btnEnviarContactoInicio');
                    var originalText = btn.innerHTML;
                    btn.disabled = true;
                    btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Enviando...';

                    var data = {
                        nombre: document.getElementById('cNombre').value.trim(),
                        apellido: '-',
                        correo: document.getElementById('cEmail').value.trim(),
                        telefono: '',
                        asunto: document.getElementById('cAsunto').value.trim() || 'Contacto desde inicio',
                        mensaje: document.getElementById('cMensaje').value.trim()
                    };

                    fetch('<?= url('/contacto/guardar') ?>', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify(data)
                    })
                    .then(function(r) { return r.json(); })
                    .then(function(res) {
                        var alertDiv = document.getElementById('contactoInicioAlert');
                        alertDiv.style.display = 'block';
                        if (res.success) {
                            alertDiv.className = 'alert alert-success';
                            alertDiv.textContent = res.message;
                            formContactoInicio.reset();
                        } else {
                            alertDiv.className = 'alert alert-danger';
                            alertDiv.textContent = res.message || 'Error al enviar el mensaje.';
                        }
                        setTimeout(function() { alertDiv.style.display = 'none'; }, 5000);
                    })
                    .catch(function() {
                        var alertDiv = document.getElementById('contactoInicioAlert');
                        alertDiv.style.display = 'block';
                        alertDiv.className = 'alert alert-danger';
                        alertDiv.textContent = 'Error de conexión. Intente nuevamente.';
                        setTimeout(function() { alertDiv.style.display = 'none'; }, 5000);
                    })
                    .finally(function() {
                        btn.disabled = false;
                        btn.innerHTML = originalText;
                    });
                });
            }
        });
    </script>
<?php
$content = ob_get_clean();

require appPath('cliente/layouts/PublicLayout.php');