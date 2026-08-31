<?php

function renderSidebar(): void {
    $currentPath = parse_url($_SERVER['REQUEST_URI'] ?? '/',PHP_URL_PATH);
?>
<nav id="sidebar" aria-label="Sidebar">
    <!-- HEADER -->
    <div class="sidebar-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <button
                    id="themeToggle"
                    class="btn btn-outline-light me-2"
                    title="Cambiar tema"
                >
                    <i class="fas fa-sun"></i>
                </button>
            </div>
        </div>
    </div>
    <ul class="list-unstyled components">
        <!-- ================= DASHBOARD ================= -->
        <li>
            <a href="<?= url('/dashboard') ?>">
                <i class="fas fa-chart-line me-2"></i>
                Dashboard
            </a>
        </li>
        <!-- ================= GESTIÓN ================= -->
        <li class="has-submenu">
            <div
                class="submenu-toggle"
                aria-expanded="false"
            >
                <span>
                    <i class="fas fa-layer-group me-2"></i>
                    Gestión
                </span>
                <i class="fas fa-chevron-right rotate-icon"></i>
            </div>
            <ul class="submenu list-unstyled">
                <li>
                    <a href="<?= url('/eventos') ?>">
                        <i class="fas fa-calendar-alt me-2"></i>
                        Eventos
                    </a>
                </li>
                <li>
                    <a href="<?= url('/actividades') ?>">
                        <i class="fas fa-tasks me-2"></i>
                        Actividades
                    </a>
                </li>
                <!-- <li>
                    <a href="<?= url('/participantes') ?>">
                        <i class="fas fa-users me-2"></i>
                        Participantes
                    </a>
                </li> -->
                <li>
                    <a href="<?= url('/inscripcion') ?>">
                        <i class="fas fa-clipboard-list me-2"></i>
                        Inscripciones
                    </a>
                </li>
                <li>
                    <a href="<?= url('/asistencias') ?>">
                        <i class="fas fa-user-check me-2"></i>
                        Asistencias
                    </a>
                </li>
                <!-- <li>
                    <a href="<?= url('/pagos') ?>">
                        <i class="fas fa-credit-card me-2"></i>
                        Pagos
                    </a>
                </li> -->
            </ul>
        </li>
        <!-- ================= PERSONAS Y USUARIOS ================= -->
        <li class="has-submenu">
            <div
                class="submenu-toggle"
                aria-expanded="false"
            >
                <span>
                    <i class="fas fa-users me-2"></i>
                    Personas y Usuarios
                </span>
                <i class="fas fa-chevron-right rotate-icon"></i>
            </div>
            <ul class="submenu list-unstyled">
                <li>
                    <a href="<?= url('/personas') ?>">
                        <i class="fas fa-id-card me-2"></i>
                        Personas
                    </a>
                </li>
                <li>
                    <a href="<?= url('/roles') ?>">
                        <i class="fas fa-user-cog me-2"></i>
                        Roles del Sistema
                    </a>
                </li>
                <li>
                    <a href="<?= url('/universidades') ?>">
                        <i class="fas fa-university me-2"></i>
                        Universidades
                    </a>
                </li>
            </ul>
        </li>
        <!-- ================= SACRAMENTOS ================= -->
        <li>
            <a href="<?= url('/sacramentos') ?>">
                <i class="fas fa-church me-2"></i>
                Sacramentos
            </a>
        </li>
        <!-- ================= PANELES ================= -->
        <li class="has-submenu">
            <div
                class="submenu-toggle"
                aria-expanded="false"
            >
                <span>
                    <i class="fas fa-table me-2"></i>
                    Paneles
                </span>
                <i class="fas fa-chevron-right rotate-icon"></i>
            </div>
            <ul class="submenu list-unstyled">
                <li>
                    <a href="<?= url('/panel-eventos') ?>">
                        <i class="fas fa-calendar-check me-2"></i>
                        Panel de Eventos
                    </a>
                </li>
                <li>
                    <a href="<?= url('/panel-actividades') ?>">
                        <i class="fas fa-tasks me-2"></i>
                        Panel de Actividades
                    </a>
                </li>
                <li>
                    <a href="<?= url('/panel-carousel') ?>">
                        <i class="fas fa-images me-2"></i>
                        Carrusel
                    </a>
                </li>
            </ul>
        </li>
        <!-- ================= REPORTES ================= -->
        <li class="has-submenu">
            <div
                class="submenu-toggle"
                aria-expanded="false"
            >
                <span>
                    <i class="fas fa-chart-pie me-2"></i>
                    Reportes
                </span>
                <i class="fas fa-chevron-right rotate-icon"></i>
            </div>
            <ul class="submenu list-unstyled">
                <li>
                    <a href="<?= url('/reportes/eventos') ?>">
                        <i class="fas fa-calendar-days me-2"></i>
                        Reporte de Eventos
                    </a>
                </li>
                <li>
                    <a href="<?= url('/reportes/actividades') ?>">
                        <i class="fas fa-clipboard-list me-2"></i>
                        Reporte de Actividades
                    </a>
                </li>
                <li>
                    <a href="<?= url('/reportes/participantes') ?>">
                        <i class="fas fa-users-viewfinder me-2"></i>
                        Reporte de Participantes
                    </a>
                </li>
                <li>
                    <a href="<?= url('/reportes/formacion-sacramental') ?>">
                        <i class="fas fa-place-of-worship me-2"></i>
                        Reporte de Formación Sacramental
                    </a>
                </li>
                <li>
                    <a href="<?= url('/reportes/asistencias') ?>">
                        <i class="fas fa-square-check me-2"></i>
                        Reporte de Asistencias
                    </a>
                </li>
                <li>
                    <a href="<?= url('/reportes/pagos') ?>">
                        <i class="fas fa-file-invoice-dollar me-2"></i>
                        Reporte de Pagos
                    </a>
                </li>
            </ul>
        </li>
        <!-- ================= AYUDA ================= -->
        <li>
            <a href="<?= url('/ayuda') ?>">
                <i class="fas fa-question-circle me-2"></i>
                Ayuda
            </a>
        </li>
    </ul>
    <!-- ================= FOOTER ================= -->
    <div class="sidebar-footer">
        <div class="d-flex align-items-center">
            <?php
            $nombreUsuario = $_SESSION['usuario'] ?? 'Usuario';
            $correoUsuario = $_SESSION['correo'] ?? '';
            $iniciales = '';
            $partes = explode(' ', $nombreUsuario);
            foreach ($partes as $p) {
                if ($p !== '') $iniciales .= strtoupper(substr($p, 0, 1));
            }
            $iniciales = substr($iniciales, 0, 2);
            ?>
            <div class="sidebar-avatar"><?= htmlspecialchars($iniciales) ?></div>
            <div class="ms-3 overflow-hidden">
                <h6 class="mb-0 text-truncate"><?= htmlspecialchars($nombreUsuario) ?></h6>
                <small class="small-muted text-truncate d-block"><?= htmlspecialchars($correoUsuario) ?></small>
            </div>
        </div>
        <a href="<?= url('/logout') ?>" class="sidebar-logout mt-3">
            <i class="fas fa-sign-out-alt me-2"></i> Cerrar Sesión
        </a>
    </div>
</nav>

<script>

(function () {
    var sidebar = document.getElementById('sidebar');
    if (!sidebar) {
        return;
    }

    // ================= SUBMENU TOGGLE =================
    var toggles = sidebar.querySelectorAll('.submenu-toggle');
    toggles.forEach(function (toggle) {
        toggle.addEventListener('click', function () {
                var li = toggle.closest('.has-submenu');

                if (!li) {
                    return;
                }


                var submenu =li.querySelector('.submenu');

                if (!submenu) {
                    return;
                }

                var isOpen = li.classList.contains('open');

                // Cerrar otros dropdowns
                sidebar.querySelectorAll('.has-submenu.open').forEach(function (other) {
                        if (other !== li) {
                            other.classList.remove('open');

                            var otherSubmenu = other.querySelector('.submenu');
                            var otherToggle = other.querySelector('.submenu-toggle');
                            if (otherSubmenu) {
                                otherSubmenu.classList.remove('show');
                            }

                            if (otherToggle) {
                                otherToggle.setAttribute('aria-expanded', 'false');
                            }
                        }
                    });

                // Abrir/cerrar actual
                li.classList.toggle('open', !isOpen);
                submenu.classList.toggle('show', !isOpen);
                toggle.setAttribute('aria-expanded', String(!isOpen));
            }
        );
    });

    // ================= RUTA ACTUAL =================
    var currentPath = <?= json_encode($currentPath) ?>;
    sidebar.querySelectorAll('a[href]').forEach(function (link) {
            var href = link.getAttribute('href');
            // Comparar ruta exacta
            if (href === currentPath) {
                var linkLi = link.closest('li');
                if (linkLi) {
                    linkLi.classList.add('active');
                }

                // Buscar submenu padre
                var parentSubmenu = link.closest('.submenu');
                var parentLi = link.closest('.has-submenu');
                if (parentLi) {
                    parentLi.classList.add('open');
                }

                if (parentSubmenu) {
                    parentSubmenu.classList.add('show');
                }

                if (parentLi) {
                    var parentToggle = parentLi.querySelector('.submenu-toggle');
                    if (parentToggle) {
                        parentToggle.setAttribute('aria-expanded', 'true');
                    }
                }
            }
        });

    // ================= MÓVIL =================
    sidebar.querySelectorAll('a[href]').forEach(function (link) {
            link.addEventListener('click', function () {
                    if (window.innerWidth > 990) {
                        return;
                    }

                    var grid = document.querySelector('.grid');
                    var overlay = document.getElementById('sidebarOverlay');
                    if (grid) {
                        grid.classList.remove('sidebar-open');
                    }

                    if (overlay) {
                      overlay.classList.remove('show');
                    }
                }
            );
        });
    // ================= TEMA =================
    var themeBtn = document.getElementById('themeToggle');
    if (themeBtn) {
        var savedTheme = localStorage.getItem('theme');
        if (savedTheme === 'dark') {
            document.body.classList.add('dark');
            themeBtn.querySelector('i').className ='fas fa-moon';
        }

        themeBtn.addEventListener('click', function () {
                document.body.classList.toggle('dark');
                var isDark = document.body.classList.contains('dark');
                themeBtn.querySelector('i').className = isDark ? 'fas fa-moon' : 'fas fa-sun';
                localStorage.setItem('theme', isDark ? 'dark' : 'light');
            }
        );
    }
})();
</script>
<?php
}
?>