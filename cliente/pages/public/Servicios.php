<?php
$pageTitle = "Servicios";
ob_start();
$pageStyles = [
    'cliente/assets/css/servicios.css',
];
?>
    <!-- Header simple -->
    <header class="simple-header">
        <div class="container">
            <h1><i class="fas fa-church"></i> Oratorio y Liturgia</h1>
            <p>Servicios para el crecimiento espiritual y comunitario</p>
        </div>
    </header>

    <!-- Sección de Servicios -->
    <section class="services-section">
        <div class="container">
            <div class="section-title">
                <h2>Nuestros Servicios</h2>
                <p>Descubre todas las actividades que ofrecemos</p>
            </div>

            <div class="row">
                <!-- Formación Sacramental -->
                <div class="col-md-6 col-lg-4">
                    <div class="service-card" id="formacion">
                        <div class="service-icon">
                            <i class="fas fa-bible"></i>
                        </div>
                        <div class="service-card-body">
                            <h3 class="service-card-title">Formación Sacramental</h3>
                            <p class="service-card-text">
                                Preparación para sacramentos: Bautismo, Primera Comunión, Confirmación y Matrimonio.
                            </p>
                            <a href="#" class="service-btn" data-bs-toggle="modal" data-bs-target="#modalFormacion">Más información</a>
                        </div>
                    </div>
                </div>

                <!-- Voluntariado Social -->
                <div class="col-md-6 col-lg-4">
                    <div class="service-card" id="voluntariado">
                        <div class="service-icon">
                            <i class="fas fa-hands-helping"></i>
                        </div>
                        <div class="service-card-body">
                            <h3 class="service-card-title">Voluntariado Social</h3>
                            <p class="service-card-text">
                                Iniciativas de servicio comunitario para ayudar a quienes más lo necesitan.
                            </p>
                            <a href="#" class="service-btn" data-bs-toggle="modal" data-bs-target="#modalVoluntariado">Más información</a>
                        </div>
                    </div>
                </div>

                <!-- Musical -->
                <div class="col-md-6 col-lg-4">
                    <div class="service-card" id="musical">
                        <div class="service-icon">
                            <i class="fas fa-music"></i>
                        </div>
                        <div class="service-card-body">
                            <h3 class="service-card-title">Musical</h3>
                            <p class="service-card-text">
                                Coros, grupos instrumentales y talleres de música sacra para la liturgia.
                            </p>
                            <a href="#" class="service-btn" data-bs-toggle="modal" data-bs-target="#modalMusical">Más información</a>
                        </div>
                    </div>
                </div>

                <!-- Talleres Universitarios -->
                <div class="col-md-6 col-lg-4">
                    <div class="service-card" id="talleres">
                        <div class="service-icon">
                            <i class="fas fa-user-graduate"></i>
                        </div>
                        <div class="service-card-body">
                            <h3 class="service-card-title">Talleres Universitarios</h3>
                            <p class="service-card-text">
                                Espacios de reflexión y crecimiento personal para estudiantes universitarios.
                            </p>
                            <a href="#" class="service-btn" data-bs-toggle="modal" data-bs-target="#modalTalleres">Más información</a>
                        </div>
                    </div>
                </div>

                <!-- Asociacionismo -->
                <div class="col-md-6 col-lg-4">
                    <div class="service-card" id="asociacionismo">
                        <div class="service-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="service-card-body">
                            <h3 class="service-card-title">Asociacionismo</h3>
                            <p class="service-card-text">
                                Grupos y asociaciones para desarrollar liderazgo y construir comunidad.
                            </p>
                            <a href="#" class="service-btn" data-bs-toggle="modal" data-bs-target="#modalAsociacionismo">Más información</a>
                        </div>
                    </div>
                </div>

                <!-- Próximamente -->
                <div class="col-md-6 col-lg-4">
                    <div class="service-card">
                        <div class="service-icon">
                            <i class="fas fa-plus"></i>
                        </div>
                        <div class="service-card-body">
                            <h3 class="service-card-title">Nuevos Programas</h3>
                            <p class="service-card-text">
                                Estamos trabajando en nuevos servicios para nuestra comunidad.
                            </p>
                            <a href="#" class="service-btn" data-bs-toggle="modal" data-bs-target="#modalProximamente">Informarse</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Modales de cada servicio -->
    <!-- Formación Sacramental -->
    <div class="modal fade" id="modalFormacion" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Formación Sacramental</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <p>Ofrecemos preparación completa para sacramentos: Bautismo, Primera Comunión, Confirmación y Matrimonio. Clases personalizadas, acompañamiento espiritual y recursos online para estudiantes y familias.</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Voluntariado Social -->
    <div class="modal fade" id="modalVoluntariado" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Voluntariado Social</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <p>Participa en actividades de servicio comunitario: apoyo a personas vulnerables, campañas de solidaridad, talleres educativos y más.</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Musical -->
    <div class="modal fade" id="modalMusical" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Musical</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <p>Clases de coro, talleres de instrumentos y música sacra para liturgia, desarrollando habilidades musicales y espirituales.</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Talleres Universitarios -->
    <div class="modal fade" id="modalTalleres" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Talleres Universitarios</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <p>Espacios de reflexión, crecimiento personal y formación espiritual especialmente diseñados para estudiantes universitarios.</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Asociacionismo -->
    <div class="modal fade" id="modalAsociacionismo" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Asociacionismo</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <p>Grupos y asociaciones para desarrollar liderazgo, fomentar la colaboración y construir comunidad en el oratorio.</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Próximamente -->
    <div class="modal fade" id="modalProximamente" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Nuevos Programas</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <p>Estamos trabajando en nuevos servicios para nuestra comunidad. ¡Mantente atento a nuestras novedades y próximos programas!</p>
          </div>
        </div>
      </div>
    </div>
<?php
$content = ob_get_clean();
require appPath('cliente/layouts/PublicLayout.php');