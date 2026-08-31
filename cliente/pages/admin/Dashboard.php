<?php
if (
  !isset($_SESSION['usuario']) ||
  !in_array($_SESSION['tipo_persona'], ['Administrativo', 'Encargado'])
) {
  header("Location: " . url('/login-admin'));
  exit();
}

$pageTitle = 'Dashboard';
$pageStyles = [
  'cliente/assets/css/Dashboard.css',
];
ob_start();
?>

  <!-- MAIN CONTENT -->
  <div id="content">
    <div id="dashboard" class="content-section active">
      <div class="container-fluid">
        <!-- ===================== RESUMEN GENERAL ===================== -->
        <h2 class="section-title">Resumen General</h2>
        <div class="row mb-4">
          <div class="col-6 col-md-3 mb-3">
            <div class="card stats-card bg-custom-primary">
              <div class="d-flex justify-content-between align-items-center">
                <div>
                  <h6 class="mb-0">Personas</h6>
                  <div class="stats-number" id="resumen-personas">0</div>
                </div>
                <i class="fas fa-user-friends fa-2x opacity-75"></i>
              </div>
            </div>
          </div>
          <div class="col-6 col-md-3 mb-3">
            <div class="card stats-card bg-custom-info">
              <div class="d-flex justify-content-between align-items-center">
                <div>
                  <h6 class="mb-0">Eventos</h6>
                  <div class="stats-number" id="resumen-eventos">0</div>
                </div>
                <i class="fas fa-calendar-alt fa-2x opacity-75"></i>
              </div>
            </div>
          </div>
          <div class="col-6 col-md-3 mb-3">
            <div class="card stats-card bg-custom-success">
              <div class="d-flex justify-content-between align-items-center">
                <div>
                  <h6 class="mb-0">Actividades</h6>
                  <div class="stats-number" id="resumen-actividades">0</div>
                </div>
                <i class="fas fa-tasks fa-2x opacity-75"></i>
              </div>
            </div>
          </div>
          <div class="col-6 col-md-3 mb-3">
            <div class="card stats-card bg-custom-warning">
              <div class="d-flex justify-content-between align-items-center">
                <div>
                  <h6 class="mb-0">Inscripciones</h6>
                  <div class="stats-number" id="resumen-inscripciones">0</div>
                </div>
                <i class="fas fa-clipboard-list fa-2x opacity-75"></i>
              </div>
            </div>
          </div>
        </div>

        <!-- ===================== PRÓXIMOS ===================== -->
        <h2 class="section-title">Próximos</h2>
        <div class="row mb-4">
          <div class="col-lg-6 mb-3">
            <div class="card shadow-sm h-100">
              <div class="card-header d-flex align-items-center">
                <i class="fas fa-calendar-day me-2 text-primary"></i>
                <h5 class="mb-0">Próximos Eventos</h5>
              </div>
              <div class="card-body">
                <ul class="list-group list-group-flush" id="lista-proximos-eventos">
                  <li class="list-group-item text-muted">Cargando...</li>
                </ul>
              </div>
            </div>
          </div>
          <div class="col-lg-6 mb-3">
            <div class="card shadow-sm h-100">
              <div class="card-header d-flex align-items-center">
                <i class="fas fa-list-check me-2 text-success"></i>
                <h5 class="mb-0">Próximas Actividades</h5>
              </div>
              <div class="card-body">
                <ul class="list-group list-group-flush" id="lista-proximas-actividades">
                  <li class="list-group-item text-muted">Cargando...</li>
                </ul>
              </div>
            </div>
          </div>
        </div>

        <!-- ===================== ACTIVIDAD RECIENTE ===================== -->
        <h2 class="section-title">Actividad Reciente</h2>
        <div class="row mb-4">
          <div class="col-12">
            <div class="card shadow-sm">
              <div class="card-header d-flex align-items-center">
                <i class="fas fa-user-plus me-2 text-primary"></i>
                <h5 class="mb-0">Últimas Personas Registradas</h5>
              </div>
              <div class="card-body">
                <div class="table-responsive">
                  <table class="table table-hover table-bordered align-middle">
                    <thead class="table-light">
                      <tr>
                        <th>CI</th>
                        <th>Nombre Completo</th>
                        <th>Universidad</th>
                        <th>Estado</th>
                      </tr>
                    </thead>
                    <tbody id="tabla-ultimas-personas">
                      <tr>
                        <td colspan="4" class="text-center text-muted">Cargando...</td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- ===================== ACCESOS RÁPIDOS ===================== -->
        <h2 class="section-title">Accesos Rápidos</h2>
        <div class="row mb-3">
          <div class="col-sm-6 col-md-4 mb-3">
            <a href="<?= url('/eventos') ?>" class="card quick-access bg-custom-info">
              <div class="quick-access-body">
                <i class="fas fa-calendar-alt fa-2x"></i>
                <div>
                  <h5 class="mb-0">Gestionar Eventos</h5>
                  <small>Administra los eventos de la organización</small>
                </div>
              </div>
            </a>
          </div>
          <div class="col-sm-6 col-md-4 mb-3">
            <a href="<?= url('/actividades') ?>" class="card quick-access bg-custom-success">
              <div class="quick-access-body">
                <i class="fas fa-tasks fa-2x"></i>
                <div>
                  <h5 class="mb-0">Gestionar Actividades</h5>
                  <small>Administra las actividades disponibles</small>
                </div>
              </div>
            </a>
          </div>
          <div class="col-sm-6 col-md-4 mb-3">
            <a href="<?= url('/inscripcion') ?>" class="card quick-access bg-custom-warning">
              <div class="quick-access-body">
                <i class="fas fa-users fa-2x"></i>
                <div>
                  <h5 class="mb-0">Gestionar Participantes</h5>
                  <small>Administra las inscripciones de participantes</small>
                </div>
              </div>
            </a>
          </div>
        </div>

      </div>
    </div> <!-- end dashboard -->
  </div> <!-- end content -->

  <!-- Toast container -->
  <div id="toasts" class="position-fixed top-0 end-0 p-3"></div>

  <script>
    document.addEventListener('DOMContentLoaded', function () {

      function formatearFecha(cadena) {
        if (!cadena) return '—';
        const f = new Date(cadena);
        if (isNaN(f.getTime())) return cadena;
        return f.toLocaleDateString('es-ES', {
          year: 'numeric',
          month: 'long',
          day: 'numeric'
        });
      }

      function renderultimasPersonas(personas) {
        const tbody = document.getElementById('tabla-ultimas-personas');
        if (!tbody) return;
        tbody.innerHTML = '';
        if (!personas || personas.length === 0) {
          tbody.innerHTML = '<tr><td colspan="4" class="text-center text-muted">No hay personas registradas</td></tr>';
          return;
        }
        personas.forEach(function (p) {
          const tr = document.createElement('tr');
          const estado = p.estado === 'Activo'
            ? '<span class="badge bg-success">Activo</span>'
            : '<span class="badge bg-danger">' + (p.estado || 'Inactivo') + '</span>';
          tr.innerHTML =
            '<td>' + (p.ci || '—') + '</td>' +
            '<td>' + (p.nombre || '—') + '</td>' +
            '<td>' + (p.universidad || '—') + '</td>' +
            '<td>' + estado + '</td>';
          tbody.appendChild(tr);
        });
      }

      function renderLista(contenedorId, items, tipo) {
        const lista = document.getElementById(contenedorId);
        if (!lista) return;
        lista.innerHTML = '';
        if (!items || items.length === 0) {
          lista.innerHTML = '<li class="list-group-item text-muted">No hay ' + tipo + ' próximos</li>';
          return;
        }
        items.forEach(function (item) {
          const li = document.createElement('li');
          li.className = 'list-group-item d-flex justify-content-between align-items-center';
          li.innerHTML =
            '<span>' + (item.nombre || '—') + '</span>' +
            '<span class="badge rounded-pill text-bg-light">' + formatearFecha(item.fecha) + '</span>';
          lista.appendChild(li);
        });
      }

      fetch('<?= url('/dashboard-data') ?>')
        .then(function (respuesta) { return respuesta.json(); })
        .then(function (datos) {
          if (!datos.success) {
            console.error(datos.message);
            return;
          }
          const data = datos.data;

          // Resumen General
          if (data.resumen) {
            const setNum = function (id, val) {
              const el = document.getElementById(id);
              if (el) el.textContent = val;
            };
            setNum('resumen-personas', data.resumen.personas ?? 0);
            setNum('resumen-eventos', data.resumen.eventos ?? 0);
            setNum('resumen-actividades', data.resumen.actividades ?? 0);
            setNum('resumen-inscripciones', data.resumen.inscripciones ?? 0);
          }

          // Próximos
          renderLista('lista-proximos-eventos', data.proximos_eventos, 'eventos');
          renderLista('lista-proximas-actividades', data.proximas_actividades, 'actividades');

          // Actividad Reciente
          renderultimasPersonas(data.ultimas_personas);
        })
        .catch(function (error) {
          console.error('Error al cargar el dashboard:', error);
          document.getElementById('tabla-ultimas-personas').innerHTML =
            '<tr><td colspan="4" class="text-center text-danger">Error al cargar los datos</td></tr>';
        });
    });
  </script>
<?php
$content = ob_get_clean();
require_once appPath('cliente/layouts/AdminLayout.php');
