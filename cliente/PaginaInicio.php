<?php

session_start();

// =====================================================
// VERIFICAR SI EL USUARIO INICIÓ SESIÓN
// =====================================================

if (!isset($_SESSION['correo'])) {
    header("Location: login.php");
    exit();
}

// =====================================================
// VERIFICAR ROL
// SOLO ADMINISTRADOR Y ENCARGADO
// =====================================================



// =====================================================
// OBTENER DATOS DEL USUARIO
// =====================================================

$nombre = $_SESSION['nombre'] ?? '';
$apellido = $_SESSION['apellidos'] ?? '';
$correo_usuario = $_SESSION['correo'] ?? '';

// =====================================================
// ARMAR NOMBRE COMPLETO
// =====================================================

$nombres_completos = trim($nombre . ' ' . $apellido);

if (empty($nombres_completos)) {
    $nombres_completos = 'Integrante';
}

// =====================================================
// PROTEGER DATOS
// =====================================================

$usuario_nombre = htmlspecialchars(
    $nombres_completos,
    ENT_QUOTES,
    'UTF-8'
);

$usuario_apellido = htmlspecialchars(
    $apellido,
    ENT_QUOTES,
    'UTF-8'
);

$usuario_correo = htmlspecialchars(
    $correo_usuario,
    ENT_QUOTES,
    'UTF-8'
);

?>


<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <title>Oratorio y Liturgia</title>
    <link rel="shortcut icon" href="../assets/img/logo.jpg">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <!-- Font Awesome (para íconos de redes sociales) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="../css/carousel.css">
    <link rel="stylesheet" href="../css/navbar.css">
    <style>
        /* ============================================================
        0. VARIABLES GLOBALES (Fácil mantenimiento)
        ============================================================ */
        :root {
            --bg-puro: #ffffff;
            --texto-blanco: #ffffff;
            --texto-gris-claro: #f8f9fa;
            --sombra-fuerte: 2px 2px 6px rgba(0, 0, 0, 0.8);
            --sombra-suave: 1px 1px 4px rgba(0, 0, 0, 0.6);
            --degradado-inferior: linear-gradient(to bottom, transparent 40%, rgba(0, 0, 0, 0.85) 100%);
        }

        /* ============================================================
        1. LIMPIEZA DE CONTENEDORES (Adiós al gris de Bootstrap)
        ============================================================ */
        .hero-carousel,
        .hero-carousel .carousel-inner,
        .hero-carousel .carousel-item,
        .carousel:not(.hero-carousel),
        .carousel:not(.hero-carousel) .carousel-inner,
        .carousel:not(.hero-carousel) .carousel-item {
            background-color: var(--bg-puro) !important;
            border: none;
        }

        /* ============================================================
        2. PRIMER CARRUSEL (Hero Principal - PC Póster Gigante)
        ============================================================ */
        .hero-carousel .hero-img {
            width: 100% !important;
            /* ¡El secreto del póster! La altura dicta sus propias reglas en PC */
            height: auto !important;
            min-height: 97vh !important;
            object-fit: cover !important;
            object-position: center top;
        }

        .hero-carousel .carousel-item::after {
            content: "";
            position: absolute;
            inset: 0;
            background: var(--degradado-inferior);
            z-index: 1;
            pointer-events: none;
        }

        .hero-carousel .carousel-caption {
            position: absolute;
            bottom: 5%;
            left: 0;
            right: 0;
            background: transparent;
            text-align: center;
            padding: 15px;
            z-index: 2;
        }

        .hero-carousel .custom-caption h1 {
            font-size: clamp(1.4rem, 4vw, 2.5rem);
            color: var(--texto-blanco);
            margin-bottom: 6px;
            text-shadow: var(--sombra-fuerte);
        }

        .hero-carousel .custom-caption .lead {
            font-size: clamp(0.85rem, 2vw, 1.1rem);
            color: var(--texto-gris-claro);
            margin-bottom: 12px;
            text-shadow: var(--sombra-suave);
        }

        .hero-carousel .custom-caption .btn {
            font-size: clamp(0.8rem, 1.5vw, 1rem);
            padding: 8px 16px;
        }

        /* ============================================================
        3. SEGUNDO CARRUSEL (Afiches Limpios y sin bordes grises)
        ============================================================ */
        .carousel:not(.hero-carousel),
        .carousel:not(.hero-carousel) .carousel-inner,
        .carousel:not(.hero-carousel) .carousel-item {
            background: transparent !important;
            background-color: transparent !important;
            border: none !important;
            box-shadow: none !important;
            text-align: center;
        }

        .carousel:not(.hero-carousel) .carousel-item img {
            width: auto !important;
            max-width: 100% !important;
            height: auto !important;
            max-height: clamp(350px, 60vh, 650px) !important;
            object-fit: contain !important;
            margin: 0 auto !important;
            border-radius: 8px !important;
            background: transparent !important;
        }

        .carousel:not(.hero-carousel) .carousel-indicators {
            display: none !important;
        }

        /* ============================================================
        4. INTERFAZ FLOTANTE (Redes y Botones)
        ============================================================ */
        .social-floating {
            position: fixed;
            left: 20px;
            bottom: 100px;
            display: flex;
            flex-direction: column;
            gap: 12px;
            z-index: 1040;
        }

        .social-floating a {
            width: clamp(38px, 4vw, 45px);
            height: clamp(38px, 4vw, 45px);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: rgba(0, 0, 0, 0.7);
            color: white;
            text-decoration: none;
            transition: all 0.3s ease;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }

        .social-floating a:hover {
            transform: scale(1.1) translateX(5px);
            background-color: white;
        }

        .social-floating a:hover .fa-facebook-f {
            color: #1877f2;
        }

        .social-floating a:hover .fa-instagram {
            color: #e4405f;
        }

        .social-floating a:hover .fa-tiktok {
            color: #000000;
        }

        .social-floating i {
            font-size: clamp(16px, 2vw, 22px);
        }

        .floating-buttons {
            position: fixed;
            bottom: 100px;
            right: 20px;
            display: flex;
            flex-direction: column;
            gap: 15px;
            z-index: 1040;
        }

        .floating-btn {
            width: clamp(45px, 5vw, 55px);
            height: clamp(45px, 5vw, 55px);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            transition: all 0.3s ease;
            border: none;
        }

        .floating-btn i {
            font-size: clamp(20px, 2.5vw, 28px);
            color: white;
        }

        .btn-whatsapp {
            background-color: #25D366;
        }

        .btn-whatsapp:hover {
            background-color: #128C7E;
            transform: scale(1.1);
        }

        .btn-suggestion {
            background-color: #FF9800;
        }

        .btn-suggestion:hover {
            background-color: #F57C00;
            transform: scale(1.1);
        }

        /* ============================================================
        5. REGLAS EXCLUSIVAS PARA MÓVILES (Cambios de Estructura)
        ============================================================ */
        @media (max-width: 768px) {

            /* --- AQUÍ VIVE EL TAMAÑO COMPACTO PARA CELULARES --- */
            .hero-carousel .hero-img {
                height: 230px !important;
                min-height: unset !important;
                border-radius: 10px !important;
            }

            .carousel-indicators {
                display: none !important;
            }

            .hero-carousel,
            .carousel,
            .carousel-inner {
                padding-top: 0 !important;
                margin-top: 0 !important;
            }

            section {
                padding-top: 30px !important;
                padding-bottom: 30px !important;
            }

            section:first-of-type {
                padding-top: 0 !important;
            }

            .scroll-top-btn {
                width: clamp(38px, 4vw, 45px);
                height: clamp(38px, 4vw, 45px);
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                background-color: #0d6efd;
                color: white;
                text-decoration: none;
                transition: all 0.3s ease;
                box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            }

            .scroll-top-btn:hover {
                background-color: #0b5ed7;
                transform: translateY(-5px);
                color: white;
            }

            .floating-buttons {
                right: 10px;
                bottom: 80px;
                gap: 10px;
            }
        }
    </style>

</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark navbar-modern sticky-top">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center fw-bold" href="#">
                <i class="bi bi-church text-warning me-2"></i>
                <span>Oratorio y Liturgia</span>
            </a>

            <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-lg-center">
                    <li class="nav-item">
                        <a class="nav-link nav-hover active" href="#">Inicio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-hover" href="../cliente/Servicios.php">Servicios</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-hover" href="../cliente/AcercaNosotros.php">Nosotros</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-hover" href="../cliente/Contacto.php">Contacto</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-hover" href="../cliente/Calendario.php">Calendario</a>
                    </li>
                    <li class="nav-item d-flex gap-2 ms-lg-3 mt-2 mt-lg-0">
                        <?php
                        if (in_array($_SESSION['tipo_persona'] ?? '', ['Administrativo', 'Encargado'])) {
                        ?>
                            <a class="btn btn-outline-light btn-sm px-3 rounded-pill" href="../cliente/IniciarSesion.php">
                                Iniciar Sesión
                            </a>
                        <?php
                        }
                        ?>
                        <a class="btn btn-light btn-sm px-3 rounded-pill fw-semibold text-dark" href="../cliente/Participar.php">
                            Registrarse
                        </a>
                    </li>

                    <php>

                        <li class="nav-item dropdown ms-lg-3 mt-3 mt-lg-0">
                            <a class="nav-link dropdown-toggle user-box text-white d-flex align-items-center gap-2" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-person-circle text-warning fs-4"></i>
                                <div class="d-flex flex-column text-start" style="line-height: 1.2;">
                                    <span class="fw-semibold user-name" style="font-size: 0.85rem;"><?php echo $usuario_nombre; ?></span>
                                    <small class="text-white-50" style="font-size: 0.70rem;"><?php echo $usuario_correo; ?></small>
                                </div>
                            </a>

                            <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2">
                                <li>
                                    <h6 class="dropdown-header text-muted text-center small"><?php echo $usuario_correo; ?></h6>
                                </li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li>
                                    <a class="dropdown-item text-danger d-flex align-items-center gap-2" href="../cliente/logout.php">
                                        <i class="bi bi-box-arrow-right"></i> Salir
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <php>

                </ul>
            </div>
        </div>
    </nav>

    <!-- Panel Izquierdo: Redes Sociales y Botón Subir -->
    <div class="social-floating">
        <!-- Tus redes sociales -->
        <a href="TU_LINK_FACEBOOK" target="_blank" title="Facebook"><i class="fab fa-facebook-f"></i></a>
        <a href="TU_LINK_INSTAGRAM" target="_blank" title="Instagram"><i class="fab fa-instagram"></i></a>
        <a href="TU_LINK_TIKTOK" target="_blank" title="TikTok"><i class="fab fa-tiktok"></i></a>

        <!-- Pequeña separación visual opcional -->
        <div style="height: 5px;"></div>

        <!-- Tu flecha de Subir Arriba -->
        <!-- Al usar href="#" el navegador automáticamente salta al inicio de la página -->
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
        <button type="button" class="floating-btn btn-suggestion" title="Danos tus sugerencias" data-bs-toggle="modal" data-bs-target="#suggestionModal">
            <i class="fas fa-comment-dots"></i>
        </button>
    </div>

    <!-- MODAL DE SUGERENCIAS -->
    <div class="modal fade suggestion-modal" id="suggestionModal" tabindex="-1" aria-labelledby="suggestionModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title fw-bold" id="suggestionModalLabel">
                        <i class="fas fa-lightbulb me-2"></i>Danos tus sugerencias
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-3">Tu opinión es muy importante para nosotros. Compártenos tus sugerencias para mejorar nuestra comunidad.</p>
                    <form id="suggestionForm">
                        <div class="mb-3">
                            <label for="suggestionName" class="form-label">Nombre (opcional)</label>
                            <input type="text" class="form-control" id="suggestionName" placeholder="Tu nombre">
                        </div>
                        <div class="mb-3">
                            <label for="suggestionEmail" class="form-label">Correo electrónico (opcional)</label>
                            <input type="email" class="form-control" id="suggestionEmail" placeholder="correo@ejemplo.com">
                        </div>
                        <div class="mb-3">
                            <label for="suggestionMessage" class="form-label">Tu sugerencia *</label>
                            <textarea class="form-control" id="suggestionMessage" rows="4" placeholder="Escribe aquí tu sugerencia..." required></textarea>
                        </div>
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="suggestionAnonymous" checked>
                            <label class="form-check-label" for="suggestionAnonymous">Enviar de forma anónima</label>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-warning fw-bold" id="sendSuggestionBtn">
                        <i class="fas fa-paper-plane me-1"></i> Enviar sugerencia
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!--CAROUSEL - MEJORADO PARA MÓVILES Y EXPANSIBLE EN PC -->
    <section class="hero-carousel container-fluid p-0">
        <div id="heroCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel" data-bs-interval="5000">
            <div class="carousel-indicators">
                <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="0" class="active"></button>
                <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="1"></button>
                <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="2"></button>
                <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="3"></button>
            </div>
            <div class="carousel-inner">

                <!-- SLIDE 1 -->
                <div class="carousel-item active">

                    <img src="../portafolio/img/carousel/img1.jpg"
                        class="d-block w-100 hero-img"
                        alt="Oratorio">

                    <div class="carousel-caption custom-caption" style="left: 50%; transform:translateX(-50%)">

                    <h1 class="display-4 fw-bold">Este espacio es para ti</h1>
                    <p class="lead">Un espacio de fe, comunidad y crecimiento espiritual.</p>

                        <!-- BOTÓN CENTRADO ABAJO -->
                        <a href="../cliente/Contacto.php"
                            class="btn btn-success rounded-pill px-4 position-absolute start-50 translate-middle-x">Leer Más</a>
                    </div>
                </div>


                <!-- SLIDE 2 -->
                <div class="carousel-item">
                    <img src="../portafolio/img/carousel/img2.jpg" class="d-block w-100 hero-img" alt="Eventos">
                    <div class="carousel-caption custom-caption" style="left: 50%; transform:translateX(-50%)">
                        <h1 class="display-4 fw-bold">Reuniones Comunitarias</h1>
                        <p class="lead">Participa en encuentros de fe y amistad.</p>
                        <a href="#eventos" class="btn btn-warning rounded-pill px-4">Ver Eventos</a>
                    </div>
                </div>

                <!-- SLIDE 3 -->
                <div class="carousel-item">
                    <img src="../portafolio/img/carousel/img1.jpg" class="d-block w-100 hero-img" alt="Formación">
                    <div class="carousel-caption custom-caption" style="left: 50%; transform:translateX(-50%)">
                        <h1 class="display-4 fw-bold">Formación Sacramental</h1>
                        <p class="lead">Fortalece tu vida espiritual.</p>
                        <a href="#formacion" class="btn btn-danger rounded-pill px-4">Más Información</a>
                    </div>
                </div>
                <!-- SLIDE 4 -->
                <div class="carousel-item">
                    <img src="../portafolio/img/carousel/img3.jpg" class="d-block w-100 hero-img" alt="Cultura">
                    <div class="carousel-caption custom-caption" style="left: 50%; transform:translateX(-50%)">
                        <h1 class="display-4 fw-bold">Eventos Culturales</h1>
                        <p class="lead">Vive nuestras tradiciones y cultura.</p>
                        <a href="#cultura" class="btn btn-info rounded-pill px-4">Explorar</a>
                    </div>
                </div>
            </div>
            <!-- CONTROLES / FLECHAS -->
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


    <section class="py-5 bg-light overflow-hidden">
        <div class="container">
            <!-- HERO PRINCIPAL -->
            <div class="row align-items-center g-5 mb-5">

                <!-- Texto -->
                <div class="col-lg-6">
                    <h1 class="display-3 fw-bold text-dark lh-sm mb-4">
                        <span class="text-primary">Comunidad Pastoral <center>Universitaria</center></span>
                    </h1>

                    <p class="lead text-secondary mb-4">
                        Un espacio de fe, formación y servicio donde jóvenes y familias
                        viven experiencias que transforman vidas y fortalecen la comunidad.
                    </p>

                    <!-- Botones -->
                    <div class="d-flex flex-wrap gap-3">
                        <a href="../cliente/Calendario.php" class="btn btn-danger btn-lg rounded-pill px-4 shadow-sm">
                            <i class="fas fa-calendar-alt me-2"></i>
                            Ver actividades
                        </a>

                        <a href="https://www.youtube.com/watch?v=RpIq4r9UJtw" class="btn btn-outline-success btn-lg rounded-pill px-4 shadow-sm">
                            <i class="fas fa-circle-play me-2"></i>
                            Conócenos
                        </a>

                        <!--Ver Noticias -->
                        <a href="#" class="btn btn-primary btn-lg rouded-pill px-4 shadow-sm"
                            class="btn btn-outline-primary btn-lg rounded-pill px-4">

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
                            <!-- Indicadores -->
                            <div class="carousel-indicators mb-3">
                                <button type="button"
                                    data-bs-target="#sliderModern"
                                    data-bs-slide-to="0"
                                    class="active"
                                    aria-current="true"
                                    aria-label="Slide 1">
                                </button>

                                <button type="button"
                                    data-bs-target="#sliderModern"
                                    data-bs-slide-to="1"
                                    aria-label="Slide 2">
                                </button>

                                <button type="button"
                                    data-bs-target="#sliderModern"
                                    data-bs-slide-to="2"
                                    aria-label="Slide 3">
                                </button>
                            </div>
                            <!-- Slides -->
                            <div class="carousel-inner">
                                <!-- Slide 1 -->
                                <div class="carousel-item active position-relative">
                                    <img src="../portafolio/img/comunidad-1.jpeg"
                                        class="d-block w-100"
                                        alt="Comunidad"
                                        style="height: 620px; object-fit: cover;">
                                    <!-- Overlay -->
                                    <div class="position-absolute top-0 start-0 w-100 h-100"
                                        style="background: rgba(0,0,0,0.35);">
                                    </div>
                                    <!-- Texto -->
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

                                    <img src="../portafolio/img/comunidad-2.jpeg"
                                        class="d-block w-100"
                                        alt="Juventud"
                                        style="height: 620px; object-fit: cover;">

                                    <!-- Overlay -->
                                    <div class="position-absolute top-0 start-0 w-100 h-100"
                                        style="background: rgba(0,0,0,0.35);">
                                    </div>

                                    <!-- Texto -->
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

                                    <img src="../portafolio/img/comunidad-3.jpeg"
                                        class="d-block w-100"
                                        alt="Servicio"
                                        style="height: 620px; object-fit: cover;">

                                    <!-- Overlay -->
                                    <div class="position-absolute top-0 start-0 w-100 h-100"
                                        style="background: rgba(0,0,0,0.35);">
                                    </div>

                                    <!-- Texto -->
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

            <!-- SECCIÓN: PILARES Y LLAMADO A LA ACCIÓN -->
            <div class="row mb-4 text-center">
                <div class="col-12">
                    <h2 class="display-5 fw-bold">
                        Descubre tu lugar en la Comunidad
                    </h2>
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
                    <a href="../cliente/Participar.php" class="btn btn-primary btn-lg rounded-pill px-5 py-3 shadow-lg fs-5 fw-bold transition-all hover-scale">
                        <i class="fas fa-rocket me-2"></i> ¡Quiero Participar!
                    </a>
                </div>
            </div>

            <!-- VIDEOS MODERNOS -->
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
                            <div class="bg-warning bg-opacity-10 rounded-circle p-3">
                                <i class="fas fa-check text-warning"></i>
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

        </div>
    </section>

    <!-- 1. PRÓXIMOS EVENTOS (Sentido de Urgencia) -->
    <section id="eventos" class="py-5">
        <div class="container">
            <h2 class="text-center fw-bold border-bottom border-primary pb-2 mb-5">Próximos Eventos</h2>
            <p class="text-center mb-5 lead">No te pierdas nuestras próximas actividades y celebraciones especiales.</p>
            <div class="row g-4">
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 border-0 shadow">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">Retiro Espiritual</h5>
                        </div>
                        <div class="card-body d-flex flex-column">
                            <p class="card-text"><i class="bi bi-calendar-event me-2"></i>15-17 de Noviembre, 2023</p>
                            <p class="card-text"><i class="bi bi-clock me-2"></i>Viernes 6:00 PM - Domingo 2:00 PM</p>
                            <p class="card-text"><i class="bi bi-geo-alt me-2"></i>Casa de Retiros San José</p>
                            <p class="card-text flex-grow-1">Un fin de semana dedicado a la reflexión, oración y renovación espiritual. Incluye charlas, momentos de silencio y celebración eucarística.</p>
                            <div class="mt-auto">
                                <div class="d-grid"><button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#inscripcionModal" data-evento="Retiro Espiritual">Inscribirse</button></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 border-0 shadow">
                        <div class="card-header bg-success text-white">
                            <h5 class="mb-0">Concierto de Música Sacra</h5>
                        </div>
                        <div class="card-body d-flex flex-column">
                            <p class="card-text"><i class="bi bi-calendar-event me-2"></i>25 de Noviembre, 2023</p>
                            <p class="card-text"><i class="bi bi-clock me-2"></i>7:00 PM - 9:00 PM</p>
                            <p class="card-text"><i class="bi bi-geo-alt me-2"></i>Iglesia Principal</p>
                            <p class="card-text flex-grow-1">Disfruta de un concierto especial con coros locales interpretando música sacra clásica y contemporánea. Entrada gratuita.</p>
                            <div class="mt-auto">
                                <div class="d-grid"><button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#inscripcionModal" data-evento="Concierto de Música Sacra">Confirmar Asistencia</button></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 border-0 shadow">
                        <div class="card-header bg-warning text-dark">
                            <h5 class="mb-0">Taller de Liturgia</h5>
                        </div>
                        <div class="card-body d-flex flex-column">
                            <p class="card-text"><i class="bi bi-calendar-event me-2"></i>2 de Diciembre, 2026</p>
                            <p class="card-text"><i class="bi bi-clock me-2"></i>9:00 AM - 1:00 PM</p>
                            <p class="card-text"><i class="bi bi-geo-alt me-2"></i>Salón Parroquial</p>
                            <p class="card-text flex-grow-1">Taller formativo para ministros de liturgia, lectores y todos aquellos interesados en profundizar en el significado de la liturgia.</p>
                            <div class="mt-auto">
                                <div class="d-grid"><button class="btn btn-warning text-dark" data-bs-toggle="modal" data-bs-target="#inscripcionModal" data-evento="Taller de Liturgia">Inscribirse</button></div>
                            </div>
                        </div>
                    </div>
                </div>
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
                            <img src="../portafolio/img/Celebraciones_eucaristicas.jpg" alt="Misas" class="w-100 h-100 object-fit-cover transition-transform" style="transition: transform 0.5s ease;">
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
                            <img src="../portafolio/img/Sacramentos.jpg" alt="Sacramentos" class="w-100 h-100 object-fit-cover transition-transform" style="transition: transform 0.5s ease;">
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
                            <img src="../portafolio/img/oracion.jpg" alt="Grupos de oración" class="w-100 h-100 object-fit-cover transition-transform" style="transition: transform 0.5s ease;">
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
                        <form>
                            <div class="row g-3">
                                <div class="col-md-6"><input type="text" class="form-control" placeholder="Tu nombre" required></div>
                                <div class="col-md-6"><input type="email" class="form-control" placeholder="Tu correo electrónico" required></div>
                                <div class="col-12"><input type="text" class="form-control" placeholder="Asunto"></div>
                                <div class="col-12"><textarea class="form-control" rows="4" placeholder="Tu mensaje" required></textarea></div>
                                <div class="col-12"><button type="submit" class="btn btn-primary w-100 py-2">Enviar Mensaje</button></div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-dark py-5 text-white">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-4 mb-lg-0">
                    <h4 class="mb-4"><i class="bi bi-church me-2"></i>Oratorio y Liturgia</h4>
                    <p>Una comunidad dedicada a fortalecer la fe a través de la oración, la liturgia y el servicio comunitario.</p>
                    <div class="d-flex mt-4">
                        <a href="#" class="text-white me-3"><i class="bi bi-facebook fs-4"></i></a>
                        <a href="#" class="text-white me-3"><i class="bi bi-twitter fs-4"></i></a>
                        <a href="#" class="text-white me-3"><i class="bi bi-instagram fs-4"></i></a>
                        <a href="#" class="text-white"><i class="bi bi-youtube fs-4"></i></a>
                    </div>
                </div>
                <div class="col-lg-2 col-md-6 mb-4 mb-md-0">
                    <h5 class="mb-4">Enlaces Rápidos</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="#" class="text-white text-decoration-none">Inicio</a></li>
                        <li class="mb-2"><a href="#" class="text-white text-decoration-none">Servicios</a></li>
                        <li class="mb-2"><a href="#" class="text-white text-decoration-none">Eventos</a></li>
                        <li class="mb-2"><a href="#" class="text-white text-decoration-none">Actividades</a></li>
                        <li><a href="#" class="text-white text-decoration-none">Contacto</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-6 mb-4 mb-md-0">
                    <h5 class="mb-4">Horarios</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2">Misas Dominicales: 8AM, 10AM, 12PM</li>
                        <li class="mb-2">Misas Diarias: 7AM, 6PM</li>
                        <li class="mb-2">Confesiones: Sábados 4-5PM</li>
                        <li>Oficina: Lunes-Viernes 9AM-5PM</li>
                    </ul>
                </div>
                <div class="col-lg-3">
                    <h5 class="mb-4">Boletín Informativo</h5>
                    <p>Suscríbete para recibir noticias y actualizaciones.</p>
                    <form>
                        <div class="input-group"><input type="email" class="form-control" placeholder="Tu correo electrónico"><button class="btn btn-warning" type="button">Suscribir</button></div>
                    </form>
                </div>
            </div>
            <hr class="my-5">
            <div class="row align-items-center">
                <div class="col-md-6 text-center text-md-start">
                    <p class="mb-0">&copy; 2026 Oratorio y Liturgia. Todos los derechos reservados.</p>
                </div>
                <div class="col-md-6 text-center text-md-end"><a href="#" class="text-white text-decoration-none me-3">Política de Privacidad</a><a href="#" class="text-white text-decoration-none">Términos de Uso</a></div>
            </div>
        </div>
    </footer>

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

    <!-- Scripts Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../js/mini_estadisticas.js"></script>

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

        const crypto = require('crypto');
        const secret = 'y37cqdomdrgvbliyfnjgbq83ukgtsw3d'; // Your verification secret key
        const userId = current_user.id // A string UUID to identify your user

        const hash = crypto.createHmac('sha256', secret).update(userId).digest('hex');
    </script>

    <script src="../js/navbar.js"></script>
    <script src="../js/carousel.js"></script>

    <script>
        // Botón para subir arriba
        const scrollTopBtn = document.getElementById('scrollTopBtn');

        window.addEventListener('scroll', function() {
            if (window.scrollY > 300) {
                scrollTopBtn.classList.add('show');
            } else {
                scrollTopBtn.classList.remove('show');
            }
        });

        scrollTopBtn.addEventListener('click', function() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });

        // Manejo del formulario de sugerencias
        const sendSuggestionBtn = document.getElementById('sendSuggestionBtn');
        const suggestionModal = document.getElementById('suggestionModal');

        sendSuggestionBtn.addEventListener('click', function() {
            const name = document.getElementById('suggestionName').value;
            const email = document.getElementById('suggestionEmail').value;
            const message = document.getElementById('suggestionMessage').value;
            const isAnonymous = document.getElementById('suggestionAnonymous').checked;

            if (!message.trim()) {
                alert('Por favor, escribe tu sugerencia antes de enviar.');
                return;
            }

            let confirmMessage = '¡Gracias por tu sugerencia! ';
            if (isAnonymous) {
                confirmMessage += 'Tu opinión ha sido enviada de forma anónima.';
            } else {
                confirmMessage += ` Hemos recibido tu sugerencia, ${name || 'usuario'}.`;
            }

            alert(confirmMessage);
            document.getElementById('suggestionForm').reset();
            bootstrap.Modal.getInstance(suggestionModal).hide();
        });

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
        });
    </script>
</body>

</html>