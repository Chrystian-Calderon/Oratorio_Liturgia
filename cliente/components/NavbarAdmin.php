<?php
function renderNavbarAdmin(bool $sidebarVisible, string $pageTitle = ''): void {
  $toggleIcon = $sidebarVisible ? 'fa-xmark' : 'fa-bars';
?>
  <nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom">
      <div class="container-fluid d-flex align-items-center">
        <button id="sidebarToggle" class="me-3 btn sidebar-toggle-desktop" aria-label="<?= $sidebarVisible ? 'Ocultar menú' : 'Mostrar menú' ?>"><i class="fas <?= $toggleIcon ?>"></i></button>
        <a class="navbar-brand" href="#">
          <i class="fas fa-tachometer-alt me-2"></i><?= htmlspecialchars($pageTitle) ?>
        </a>

        <div class="ms-auto d-flex align-items-center gap-2">
          <div class="dropdown">
            <a class="nav-link dropdown-toggle p-0 d-flex align-items-center gap-2" href="#" data-bs-toggle="dropdown">
              <span class="navbar-user-name d-none d-md-inline"><?= htmlspecialchars($_SESSION['usuario'] ?? 'Usuario') ?></span>
              <i class="fas fa-user-circle fa-2x"></i>
            </a>
            <ul class="dropdown-menu dropdown-menu-end">
              <li><span class="dropdown-item-text"><strong><?= htmlspecialchars($_SESSION['usuario'] ?? '') ?></strong><br><small class="text-muted"><?= htmlspecialchars($_SESSION['correo'] ?? '') ?></small></span></li>
              <li><hr class="dropdown-divider"></li>
              <li><a class="dropdown-item" href="<?= url('/perfil') ?>"><i class="fas fa-user me-2"></i> Mi Perfil</a></li>
              <li><a class="dropdown-item" href="<?= url('/logout') ?>"><i class="fas fa-sign-out-alt me-2"></i> Cerrar Sesión</a></li>
            </ul>
          </div>
        </div>
      </div>
    </nav>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const toggleBtn = document.getElementById('sidebarToggle');
    const grid = document.querySelector('.grid');
    const overlay = document.getElementById('sidebarOverlay');
    const isMobile = () => window.innerWidth <= 990;

    if (!toggleBtn || !grid) return;

    const icon = toggleBtn.querySelector('i');

    function updateIcon(sidebarVisible) {
        if (!icon) return;
        icon.className = sidebarVisible ? 'fas fa-xmark' : 'fas fa-bars';
        toggleBtn.setAttribute('aria-label', sidebarVisible ? 'Ocultar menú' : 'Mostrar menú');
    }

    function openSidebar() {
        grid.classList.add('sidebar-open');
        grid.classList.remove('sidebar-hidden');
        if (overlay) overlay.classList.add('show');
        updateIcon(true);
        localStorage.setItem('sidebarVisible', 'true');
    }

    function closeSidebar() {
        grid.classList.remove('sidebar-open');
        if (overlay) overlay.classList.remove('show');
        updateIcon(false);
        localStorage.setItem('sidebarVisible', 'false');
    }

    // Cargar estado guardado
    const savedState = localStorage.getItem('sidebarVisible');
    if (savedState === 'false' && !isMobile()) {
        grid.classList.add('sidebar-hidden');
        updateIcon(false);
    }

    toggleBtn.addEventListener('click', function(e) {
        e.preventDefault();

        if (isMobile()) {
            // En móvil: abrir/cerrar sidebar con overlay
            const isOpen = grid.classList.contains('sidebar-open');
            if (isOpen) {
                closeSidebar();
            } else {
                openSidebar();
            }
        } else {
            // En desktop: contraer/ancho del sidebar
            const isHidden = grid.classList.contains('sidebar-hidden');
            if (isHidden) {
                grid.classList.remove('sidebar-hidden');
                updateIcon(true);
                localStorage.setItem('sidebarVisible', 'true');
            } else {
                grid.classList.add('sidebar-hidden');
                updateIcon(false);
                localStorage.setItem('sidebarVisible', 'false');
            }
        }

        // Evento personalizado
        document.dispatchEvent(new CustomEvent('sidebarToggle', {
            detail: { visible: isMobile() ? grid.classList.contains('sidebar-open') : !grid.classList.contains('sidebar-hidden') }
        }));
    });

    // Cerrar sidebar al hacer clic en overlay
    if (overlay) {
        overlay.addEventListener('click', function() {
            closeSidebar();
        });
    }

    // Cerrar sidebar con Escape
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            if (isMobile() && grid.classList.contains('sidebar-open')) {
                closeSidebar();
            }
        }
    });

    // Cerrar sidebar al redimensionar a desktop
    window.addEventListener('resize', function() {
        if (!isMobile()) {
            grid.classList.remove('sidebar-open');
            if (overlay) overlay.classList.remove('show');
        }
    });
});
</script>
<?php
}
