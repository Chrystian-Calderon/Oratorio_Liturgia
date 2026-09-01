<?php
function renderNavbar(): void {
  $nombre = $_SESSION['nombre'] ?? '';
  $apellido = $_SESSION['apellidos'] ?? '';
  $usuario_nombre = trim($nombre . ' ' . $apellido);
  $usuario_correo = $_SESSION['correo'] ?? '';
  $tipoPersona = $_SESSION['tipo_persona'] ?? '';
?>
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
            <a class="nav-link nav-hover active" href="<?php echo url('/'); ?>">Inicio</a>
          </li>
          <li class="nav-item">
            <a class="nav-link nav-hover" href="<?php echo url('/servicios'); ?>">Servicios</a>
          </li>
          <li class="nav-item">
            <a class="nav-link nav-hover" href="<?php echo url('/nosotros'); ?>">Nosotros</a>
          </li>
          <li class="nav-item">
            <a class="nav-link nav-hover" href="<?php echo url('/contacto'); ?>">Contacto</a>
          </li>
          <li class="nav-item">
            <a class="nav-link nav-hover" href="<?php echo url('/calendario'); ?>">Calendario</a>
          </li>
          <!-- <li class="nav-item">
            <a class="nav-link nav-hover" href="<?php echo url('/ayuda'); ?>">Ayuda</a>
          </li> -->
          <li class="nav-item d-flex gap-2 ms-lg-3 mt-2 mt-lg-0">
            <?php if ($tipoPersona === 'Administrativo'): ?>
            <a class="btn btn-outline-light btn-sm px-3 rounded-pill" href="<?php echo url('/dashboard'); ?>">
              Panel Administrativo
            </a>
            <?php elseif ($usuario_nombre === '' || $usuario_correo === ''): ?>
            <a class="btn btn-outline-light btn-sm px-3 rounded-pill" href="<?php echo url('/login'); ?>">
              Iniciar Sesion
            </a>
            <?php endif; ?>
            <a class="btn btn-light btn-sm px-3 rounded-pill fw-semibold text-dark" href="<?php echo url('/participar'); ?>">
              Registrarse
            </a>
          </li>
          <?php if ($usuario_nombre !== '' && $usuario_correo !== ''): ?>
          <li class="nav-item dropdown ms-lg-3 mt-3 mt-lg-0">
            <a class="nav-link dropdown-toggle user-box text-white d-flex align-items-center gap-2" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              <i class="bi bi-person-circle text-warning fs-4"></i>
              <div class="d-flex flex-column text-start" style="line-height: 1.2;">
                <span class="fw-semibold user-name" style="font-size: 0.85rem;"><?php echo $usuario_nombre; ?></span>
                <small class="text-white-50" style="font-size: 0.70rem;"><?php echo $usuario_correo; ?></small>
              </div>
            </a>
                              
            <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2">
              <li><h6 class="dropdown-header text-muted text-center small"><?php echo $usuario_correo; ?></h6></li>
              <li><hr class="dropdown-divider"></li>
              <li>
                <a class="dropdown-item d-flex align-items-center gap-2" href="<?php echo url('/mi-perfil'); ?>">
                  <i class="bi bi-person"></i>
                  Mi Perfil
                </a>
              </li>
              <li>
                <form action="<?php echo url('/logout'); ?>" method="POST" class="m-0">
                  <button
                      type="submit"
                      class="dropdown-item text-danger d-flex align-items-center gap-2"
                  >
                      <i class="bi bi-box-arrow-right"></i>
                      Salir
                  </button>
              </form>
              </li>
            </ul>
          </li>
          <?php endif; ?>         
        </ul>
      </div>
    </div>
  </nav>
<?php
}