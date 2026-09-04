<?php
$pageTitle = 'Noticias';
$pageStyles = [
    'cliente/assets/css/noticias.css',
];
ob_start();
?>
    <header class="noticias-header">
        <div class="container text-center">
            <span class="noticias-badge d-inline-block px-4 py-2 rounded-pill mb-3">
                <i class="fa-regular fa-newspaper me-2"></i> NOTICIAS
            </span>
            <h1 class="display-4 fw-bold mb-2">Noticias de Oratorio y Liturgia</h1>
            <p class="fs-5 mb-0 mx-auto" style="max-width: 600px;">Entérate de todo lo que acontece en la Pastoral Universitaria de la USB</p>
        </div>
    </header>

    <main class="container py-5">
        <div class="row g-4">

            <!-- Noticia 1 -->
            <div class="col-12 col-md-6 col-lg-6">
                <article class="noticia-card h-100">
                    <img src="<?= url('cliente/assets/img/comunidad-1.jpeg') ?>" class="noticia-img w-100" alt="Formación Sacramental USB">
                    <div class="noticia-body d-flex flex-column">
                        <div class="noticia-meta"><i class="fa-regular fa-calendar me-1"></i> 15 de Marzo, 2026</div>
                        <h3 class="fw-bold mb-2">Inscripciones Abiertas: Formación Sacramental USB</h3>
                        <p class="text-secondary flex-grow-1">La Pastoral Universitaria de la USB abre las inscripciones para los cursos de preparación de Primera Comunión y Confirmación...</p>
                        <button type="button" class="btn-noticia align-self-start" data-bs-toggle="modal" data-bs-target="#noticia1">Leer más</button>
                    </div>
                </article>
            </div>

            <!-- Noticia 2 -->
            <div class="col-12 col-md-6 col-lg-6">
                <article class="noticia-card h-100">
                    <img src="<?= url('cliente/assets/img/comunidad-2.jpeg') ?>" class="noticia-img w-100" alt="Retiro Espiritual de Cuaresma">
                    <div class="noticia-body d-flex flex-column">
                        <div class="noticia-meta"><i class="fa-regular fa-calendar me-1"></i> 22 de Marzo, 2026</div>
                        <h3 class="fw-bold mb-2">Retiro Espiritual de Cuaresma</h3>
                        <p class="text-secondary flex-grow-1">Una jornada de oración, reflexión y silencio para vivir el tiempo de Cuaresma en comunidad universitaria...</p>
                        <button type="button" class="btn-noticia align-self-start" data-bs-toggle="modal" data-bs-target="#noticia2">Leer más</button>
                    </div>
                </article>
            </div>

            <!-- Noticia 3 -->
            <div class="col-12 col-md-6 col-lg-6">
                <article class="noticia-card h-100">
                    <img src="<?= url('cliente/assets/img/comunidad-3.jpeg') ?>" class="noticia-img w-100" alt="Talleres de Liturgia y Canto">
                    <div class="noticia-body d-flex flex-column">
                        <div class="noticia-meta"><i class="fa-regular fa-calendar me-1"></i> 05 de Abril, 2026</div>
                        <h3 class="fw-bold mb-2">Talleres de Liturgia y Canto para la Comunidad</h3>
                        <p class="text-secondary flex-grow-1">Talleres formativos para ministros, lectores, acólitos y miembros de coros, orientados a enriquecer las celebraciones...</p>
                        <button type="button" class="btn-noticia align-self-start" data-bs-toggle="modal" data-bs-target="#noticia3">Leer más</button>
                    </div>
                </article>
            </div>

            <!-- Noticia 4 -->
            <div class="col-12 col-md-6 col-lg-6">
                <article class="noticia-card h-100">
                    <img src="<?= url('cliente/assets/img/comunidad-4.jpeg') ?>" class="noticia-img w-100" alt="Voluntariado y Servicio Comunitario">
                    <div class="noticia-body d-flex flex-column">
                        <div class="noticia-meta"><i class="fa-regular fa-calendar me-1"></i> 12 de Abril, 2026</div>
                        <h3 class="fw-bold mb-2">Jornada de Voluntariado y Servicio Comunitario</h3>
                        <p class="text-secondary flex-grow-1">Invitamos a estudiantes y docentes a sumarse a la campaña solidaria con las familias de la zona de Achachicala...</p>
                        <button type="button" class="btn-noticia align-self-start" data-bs-toggle="modal" data-bs-target="#noticia4">Leer más</button>
                    </div>
                </article>
            </div>
        </div>
    </main>

    <!-- Modales de noticias -->
    <div class="modal fade" id="noticia1" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Inscripciones Abiertas: Formación Sacramental USB</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <img src="<?= url('cliente/assets/img/comunidad-1.jpeg') ?>" class="w-100 rounded-3 mb-3" alt="Formación Sacramental USB">
                    <p>La Pastoral Universitaria de la USB abre las inscripciones para los cursos de preparación de Primera Comunión y Confirmación. Las clases se desarrollarán en la Capilla Universitaria y estarán a cargo de catequistas y formadores de la comunidad salesiana.</p>
                    <p>Los encuentros se realizarán los sábados por la mañana, con acompañamiento espiritual, material de estudio y actividades de integración. Las inscripciones están abiertas para estudiantes, familiares y miembros de la comunidad.</p>
                    <p>Para mayor información, acércate a la oficina de Pastoral Universitaria o escribe a nuestras redes sociales.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="noticia2" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Retiro Espiritual de Cuaresma</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <img src="<?= url('cliente/assets/img/comunidad-2.jpeg') ?>" class="w-100 rounded-3 mb-3" alt="Retiro Espiritual de Cuaresma">
                    <p>Te invitamos a participar del Retiro Espiritual de Cuaresma, una jornada de oración, reflexión y silencio para vivir este tiempo de gracia en comunidad universitaria.</p>
                    <p>El retiro incluye meditaciones guiadas, adoración eucarística, espacios de silencio personal y la celebración de la reconciliación. Está abierto a todos los estudiantes y miembros de la comunidad que deseen renovar su vida espiritual.</p>
                    <p>Lugar: Casa de Retiros San José. ¡No faltes a esta oportunidad de encuentro con Dios y con los hermanos!</p>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="noticia3" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Talleres de Liturgia y Canto para la Comunidad</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <img src="<?= url('cliente/assets/img/comunidad-3.jpeg') ?>" class="w-100 rounded-3 mb-3" alt="Talleres de Liturgia y Canto">
                    <p>Lanzamos una nueva edición de los talleres de liturgia y canto, orientados a formar a ministros, lectores, acólitos y miembros de los coros de la comunidad.</p>
                    <p>En los talleres aprenderás sobre el significado de la liturgia, la celebración de los sacramentos, técnicas de lectura y proclamación, y formación musical para el canto litúrgico.</p>
                    <p>Las sesiones son gratuitas y se llevan a cabo en el Aula de Formación de la USB. Inscríbete en la oficina de Pastoral Universitaria.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="noticia4" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Jornada de Voluntariado y Servicio Comunitario</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <img src="<?= url('cliente/assets/img/comunidad-4.jpeg') ?>" class="w-100 rounded-3 mb-3" alt="Voluntariado y Servicio Comunitario">
                    <p>Invitamos a estudiantes y docentes a sumarse a la Jornada de Voluntariado y Servicio Comunitario, una campaña solidaria organizada junto a las familias de la zona de Achachicala.</p>
                    <p>Durante la jornada realizaremos colectas de alimentos y abrigo, visitas de acompañamiento a personas mayores y actividades recreativas para los niños de la comunidad.</p>
                    <p>El servicio y la entrega a los demás son parte esencial de la identidad salesiana. ¡Únete y haz la diferencia!</p>
                </div>
            </div>
        </div>
    </div>
<?php
$content = ob_get_clean();
require appPath('cliente/layouts/PublicLayout.php');
