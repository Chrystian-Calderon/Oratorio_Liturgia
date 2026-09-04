<?php
function renderFooterIndex(): void {
?>
  <footer class="bg-dark py-5 text-white">
    <div class="container">
      <div class="row">
        <div class="col-lg-4 mb-4 mb-lg-0">
          <h4 class="mb-4"><i class="bi bi-church me-2"></i>Oratorio y Liturgia</h4>
          <p>Una comunidad dedicada a fortalecer la fe a través de la oración, la liturgia y el servicio comunitario.</p>
        <div class="d-flex mt-4">
          <a href="https://www.facebook.com/share/1DXqH1baJa/" class="text-white me-3"><i class="bi bi-facebook fs-4"></i></a>
          <a href="https://www.instagram.com/pastoraluniversitariausb?igsi=YzVlcW9uNDM3aHJm" class="text-white me-3"><i class="bi bi-tiktok fs-4"></i></a>
          <a href="https://www.tiktok.com/@pastoraluniversitariausb?_r=1&_t=ZS-99N1d8re8JA" class="text-white me-3"><i class="bi bi-instagram fs-4"></i></a>
        </div>
      </div>
      <div class="col-lg-4 col-md-6 mb-4 mb-md-0">
        <h5 class="mb-4">Enlaces Rápidos</h5>
        <ul class="list-unstyled">
          <li class="mb-2"><a href="<?= url('/inicio') ?>" class="text-white text-decoration-none">Inicio</a></li>
          <li class="mb-2"><a href="<?= url('/servicios') ?>" class="text-white text-decoration-none">Servicios</a></li>
          <li class="mb-2"><a href="<?= url('/ver-eventos') ?>" class="text-white text-decoration-none">Eventos</a></li>
          <li class="mb-2"><a href="<?= url('/ver-actividades') ?>" class="text-white text-decoration-none">Actividades</a></li>
          <li><a href="<?= url('/contacto') ?>" class="text-white text-decoration-none">Contacto</a></li>
        </ul>
      </div>
      <div class="col-lg-4 col-md-6 mb-4 mb-md-0">
        <h5 class="mb-4">Horarios</h5>
        <ul class="list-unstyled">
          <li class="mb-2">Misas Dominicales: 8AM, 10AM, 12PM</li>
          <li class="mb-2">Misas Diarias: 7AM, 6PM</li>
          <li class="mb-2">Confesiones: Sábados 4-5PM</li>
          <li>Oficina: Lunes-Viernes 9AM-5PM</li>
        </ul>
      </div>
    </div>
    <hr class="my-5">
    <div class="row align-items-center">
      <div class="col-md-6 text-center text-md-start">
        <p class="mb-0">&copy; 2026 Oratorio y Liturgia. Todos los derechos reservados.</p>
      </div>
      <!-- <div class="col-md-6 text-center text-md-end"><a href="#" class="text-white text-decoration-none me-3">Política de Privacidad</a><a href="#" class="text-white text-decoration-none">Términos de Uso</a></div> -->
    </div>
  </div>
</footer>
<?php
}