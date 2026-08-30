<?php
function renderSidebar(): void {
  $currentPage = basename($_SERVER['SCRIPT_NAME'] ?? '');
?>
  <nav id="sidebar" aria-label="Sidebar">
    <div class="sidebar-header">
      <div class="d-flex justify-content-between align-items-center">
        <div>
          <button id="themeToggle" class="btn btn-sm btn-outline-light me-2" title="Cambiar tema"><i class="fas fa-sun"></i></button>
        </div>
      </div>
    </div>

    <ul class="list-unstyled components">

      <li class="has-submenu">
        <div class="submenu-toggle" aria-expanded="false">
          <span><i class="fas fa-table me-2"></i> Dashboard</span>
          <i class="fas fa-chevron-right rotate-icon"></i>
        </div>
        <ul class="submenu list-unstyled">
          <li><a href="<?= url('/panel-eventos') ?>"><i class="fas fa-users me-2"></i> Panel de Eventos</a></li>
          <li><a href="<?= url('/panel-actividades') ?>"><i class="fas fa-id-card me-2"></i> Panel de Actividades</a></li>
        </ul>
      </li>

      <li class="has-submenu">
        <div class="submenu-toggle" aria-expanded="false">
          <span><i class="fas fa-table me-2"></i> Tablas</span>
          <i class="fas fa-chevron-right rotate-icon"></i>
        </div>
        <ul class="submenu list-unstyled">
          <li><a href="<?= url('/usuarios') ?>"><i class="fas fa-users me-2"></i> Usuarios</a></li>
          <li><a href="<?= url('/personas') ?>"><i class="fas fa-id-card me-2"></i> Personas</a></li>
          <li><a href="#"><i class="fas fa-user-friends me-2"></i> Participantes</a></li>
        </ul>
      </li>

      <li class="has-submenu">
        <div class="submenu-toggle" aria-expanded="false">
          <span><i class="fas fa-calendar-days me-2"></i> Eventos y Actividades</span>
          <i class="fas fa-chevron-right rotate-icon"></i>
        </div>
        <ul class="submenu list-unstyled">
          <li><a href="#"><i class="fas fa-calendar-check me-2"></i> Eventos</a></li>
          <li><a href="#"><i class="fas fa-tasks me-2"></i> Actividades</a></li>
          <li><a href="#"><i class="fas fa-church me-2"></i> Formación Sacramental</a></li>
          <li><a href="#"><i class="fas fa-user-plus me-2"></i> Inscripciones</a></li>
          <li><a href="#"><i class="fas fa-clipboard-check me-2"></i> Asistencias</a></li>
          <li><a href="#"><i class="fas fa-money-check-alt me-2"></i> Pagos</a></li>
        </ul>
      </li>

      <li class="has-submenu">
        <div class="submenu-toggle" aria-expanded="false">
          <span><i class="fas fa-chart-pie me-2"></i> Reportes</span>
          <i class="fas fa-chevron-right rotate-icon"></i>
        </div>
        <ul class="submenu list-unstyled">
          <li><a href="#"><i class="fas fa-calendar-days me-2"></i> Reporte de Eventos</a></li>
          <li><a href="#"><i class="fas fa-clipboard-list me-2"></i> Reporte de Actividades</a></li>
          <li><a href="#"><i class="fas fa-users-viewfinder me-2"></i> Reporte de Participantes</a></li>
          <li><a href="#"><i class="fas fa-place-of-worship me-2"></i> Reporte de Formación Sacramental</a></li>
          <li><a href="#"><i class="fas fa-square-check me-2"></i> Reporte de Asistencias</a></li>
          <li><a href="#"><i class="fas fa-file-invoice-dollar me-2"></i> Reporte de Pagos</a></li>
        </ul>
      </li>

      <li class="has-submenu">
        <div class="submenu-toggle" aria-expanded="false">
          <span><i class="fas fa-file-alt me-2"></i> Formularios</span>
          <i class="fas fa-chevron-right rotate-icon"></i>
        </div>
        <ul class="submenu list-unstyled">
          <li><a href="<?= url('/actividades') ?>"><i class="fas fa-hands-helping me-2"></i> Actividades</a></li>
          <li><a href="<?= url('/asistencias') ?>"><i class="fas fa-user-check me-2"></i> Asistencias</a></li>
          <li><a href="<?= url('/eventos') ?>"><i class="fas fa-calendar-alt me-2"></i> Eventos</a></li>
          <li><a href="<?= url('/inscripcion') ?>"><i class="fas fa-clipboard-list me-2"></i> Inscripción</a></li>
          <li><a href="<?= url('/pagos') ?>"><i class="fas fa-credit-card me-2"></i> Pagos</a></li>
          <li><a href="<?= url('/personas-form') ?>"><i class="fas fa-user-friends me-2"></i> Personas</a></li>
          <li><a href="<?= url('/universidades') ?>"><i class="fas fa-university me-2"></i> Universidades</a></li>
          <li><a href="<?= url('/usuarios-form') ?>"><i class="fas fa-user-cog me-2"></i> Usuario</a></li>
          <li><a href="<?= url('/formacion-sacramental') ?>"><i class="fas fa-book-reader me-2"></i> Formación Sacramental</a></li>
        </ul>
      </li>

      <li>
        <a href="<?= url('/mis-eventos') ?>"><i class="fas fa-calendar-check"></i> Mis Eventos</a>
      </li>

      <li data-section="participantes">
        <a href="#"><i class="fas fa-users"></i> Participantes</a>
      </li>

      <li data-section="reportes">
        <a href="#"><i class="fas fa-chart-bar"></i> Reportes</a>
      </li>

      <li data-section="ayuda">
        <a href="#"><i class="fas fa-question-circle"></i> Ayuda</a>
      </li>
    </ul>

    <div class="sidebar-footer">
      <div class="d-flex align-items-center">
        <img src="https://ui-avatars.com/api/?name=Admin+User&background=0D8ABC&color=fff" class="rounded-circle" width="40" height="40" alt="Usuario">
        <div class="ms-3">
          <h6 class="mb-0">Administrador</h6>
          <small class="small-muted">Favio@gmail.com</small>
        </div>
      </div>
    </div>
  </nav>

<script>
(function() {
    var sidebar = document.getElementById('sidebar');
    if (!sidebar) return;

    // === SUBMENU TOGGLE ===
    var toggles = sidebar.querySelectorAll('.submenu-toggle');
    toggles.forEach(function(toggle) {
        toggle.addEventListener('click', function(e) {
            e.preventDefault();
            var li = toggle.closest('.has-submenu');
            if (!li) return;
            var submenu = li.querySelector('.submenu');
            if (!submenu) return;
            
            var isOpen = li.classList.contains('open');

            // Cerrar otros abiertos
            sidebar.querySelectorAll('.has-submenu.open').forEach(function(other) {
                if (other !== li) {
                    other.classList.remove('open');
                    var sub = other.querySelector('.submenu');
                    var tog = other.querySelector('.submenu-toggle');
                    if (sub) sub.classList.remove('show');
                    if (tog) tog.setAttribute('aria-expanded', 'false');
                }
            });

            // Toggle actual
            li.classList.toggle('open', !isOpen);
            submenu.classList.toggle('show', !isOpen);
            toggle.setAttribute('aria-expanded', String(!isOpen));
        });
    });

    // === RESALTAR PÁGINA ACTUAL ===
    var currentPage = '<?= $currentPage ?>';
    if (currentPage) {
        sidebar.querySelectorAll('.submenu a[href]').forEach(function(link) {
            if (link.getAttribute('href').indexOf(currentPage) !== -1) {
                var parentLi = link.closest('.has-submenu');
                var parentSub = link.closest('.submenu');
                var parentToggle = parentLi ? parentLi.querySelector('.submenu-toggle') : null;
                if (parentLi) parentLi.classList.add('open');
                if (parentSub) parentSub.classList.add('show');
                if (parentToggle) parentToggle.setAttribute('aria-expanded', 'true');
                link.closest('li').classList.add('active');
            }
        });

        sidebar.querySelectorAll('.components > li > a[href]').forEach(function(link) {
            if (link.getAttribute('href').indexOf(currentPage) !== -1) {
                link.closest('li').classList.add('active');
            }
        });
    }

    // === CERRAR SIDEBAR EN MÓVIL AL NAVEGAR ===
    sidebar.querySelectorAll('a[href]').forEach(function(link) {
        link.addEventListener('click', function() {
            if (window.innerWidth > 990) return;
            var grid = document.querySelector('.grid');
            var overlay = document.getElementById('sidebarOverlay');
            if (grid) grid.classList.remove('sidebar-open');
            if (overlay) overlay.classList.remove('show');
        });
    });

    // === TEMA OSCURO ===
    var themeBtn = document.getElementById('themeToggle');
    if (themeBtn) {
        var savedTheme = localStorage.getItem('theme');
        console.log('Saved theme:', savedTheme);
        if (savedTheme === 'dark') {
            document.body.classList.add('dark');
            themeBtn.querySelector('i').className = 'fas fa-moon';
        }

        themeBtn.addEventListener('click', function() {
            document.body.classList.toggle('dark');
            var isDark = document.body.classList.contains('dark');
            themeBtn.querySelector('i').className = isDark ? 'fas fa-moon' : 'fas fa-sun';
            localStorage.setItem('theme', isDark ? 'dark' : 'light');
        });
    }
})();
</script>
<?php
}
