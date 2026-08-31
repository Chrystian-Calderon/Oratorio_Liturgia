<?php
declare(strict_types=1);
if (
  !isset($_SESSION['usuario']) ||
  !in_array($_SESSION['tipo_persona'], ['Administrativo', 'Encargado'])
) {
  header("Location: " . url('/login-admin'));
  exit();
}

$pageTitle = 'Panel de Eventos';
$pageStyles = [
  'cliente/assets/css/Dashboard.css',
];
ob_start();
?>
  <div id="content">
    <div class="container-fluid py-3">
      <!-- ===================== ESTADÍSTICAS ===================== -->
      <h2 class="section-title">Estadísticas</h2>
      <div class="row mb-4">
        <div class="col-6 col-md-3 mb-3">
          <div class="card stats-card bg-custom-info">
            <div class="d-flex justify-content-between align-items-center">
              <div>
                <h6 class="mb-0">Total Eventos</h6>
                <div class="stats-number" id="stat-total-eventos">0</div>
              </div>
              <i class="fas fa-calendar-alt fa-2x opacity-75"></i>
            </div>
          </div>
        </div>
        <div class="col-6 col-md-3 mb-3">
          <div class="card stats-card bg-custom-primary">
            <div class="d-flex justify-content-between align-items-center">
              <div>
                <h6 class="mb-0">Próximos</h6>
                <div class="stats-number" id="stat-proximos">0</div>
              </div>
              <i class="fas fa-calendar-day fa-2x opacity-75"></i>
            </div>
          </div>
        </div>
        <div class="col-6 col-md-3 mb-3">
          <div class="card stats-card bg-custom-success">
            <div class="d-flex justify-content-between align-items-center">
              <div>
                <h6 class="mb-0">Realizados</h6>
                <div class="stats-number" id="stat-realizados">0</div>
              </div>
              <i class="fas fa-calendar-check fa-2x opacity-75"></i>
            </div>
          </div>
        </div>
        <div class="col-6 col-md-3 mb-3">
          <div class="card stats-card bg-custom-warning">
            <div class="d-flex justify-content-between align-items-center">
              <div>
                <h6 class="mb-0">Total Inscripciones</h6>
                <div class="stats-number" id="stat-inscripciones">0</div>
              </div>
              <i class="fas fa-clipboard-list fa-2x opacity-75"></i>
            </div>
          </div>
        </div>
      </div>

      <!-- ===================== GRÁFICOS ===================== -->
      <h2 class="section-title">Gráficos</h2>
      <div class="row mb-4">
        <div class="col-lg-6 mb-3">
          <div class="card shadow-sm h-100">
            <div class="card-header d-flex align-items-center">
              <i class="fas fa-chart-bar me-2 text-primary"></i>
              <h5 class="mb-0">Eventos por Mes</h5>
            </div>
            <div class="card-body">
              <canvas id="graficoEventosMes"></canvas>
            </div>
          </div>
        </div>
        <div class="col-lg-6 mb-3">
          <div class="card shadow-sm h-100">
            <div class="card-header d-flex align-items-center">
              <i class="fas fa-users me-2 text-success"></i>
              <h5 class="mb-0">Participación por Evento</h5>
            </div>
            <div class="card-body">
              <canvas id="graficoParticipacion"></canvas>
            </div>
          </div>
        </div>
      </div>

      <!-- ===================== INFORMACIÓN ===================== -->
      <h2 class="section-title">Información</h2>
      <div class="row mb-3">
        <div class="col-12">
          <div class="card shadow-sm">
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
      </div>
    </div>
  </div>

  <!-- Toast container -->
  <div id="toasts" class="position-fixed top-0 end-0 p-3"></div>

  <script>
    document.addEventListener('DOMContentLoaded', function () {
      let chartMes = null;
      let chartParticipacion = null;

      function formatearFecha(cadena) {
        if (!cadena) return 'Por definir';
        const f = new Date(cadena + 'T00:00:00');
        if (isNaN(f.getTime())) return cadena;
        return f.toLocaleDateString('es-ES', { year: 'numeric', month: 'long', day: 'numeric' });
      }

      fetch('<?= url('/panel-eventos-data') ?>')
        .then(function (resp) { return resp.json(); })
        .then(function (datos) {
          if (!datos.success) {
            console.error(datos.message);
            return;
          }
          const data = datos.data;

          // Estadísticas
          const setNum = function (id, val) {
            const el = document.getElementById(id);
            if (el) el.textContent = val;
          };
          if (data.estadisticas) {
            setNum('stat-total-eventos', data.estadisticas.total_eventos ?? 0);
            setNum('stat-proximos', data.estadisticas.proximos ?? 0);
            setNum('stat-realizados', data.estadisticas.realizados ?? 0);
            setNum('stat-inscripciones', data.estadisticas.total_inscripciones ?? 0);
          }

          // Próximos eventos
          const lista = document.getElementById('lista-proximos-eventos');
          lista.innerHTML = '';
          const items = data.proximos_eventos || [];
          if (items.length === 0) {
            lista.innerHTML = '<li class="list-group-item text-muted">No hay eventos próximos</li>';
          } else {
            items.forEach(function (ev) {
              const li = document.createElement('li');
              li.className = 'list-group-item d-flex justify-content-between align-items-center';
              li.innerHTML =
                '<span>' + (ev.nombre || '—') + '</span>' +
                '<span class="badge rounded-pill text-bg-light">' + formatearFecha(ev.fecha) + '</span>';
              lista.appendChild(li);
            });
          }

          // Gráfico: Eventos por Mes
          if (data.graficos) {
            const meses = data.graficos.meses || [];
            const valores = data.graficos.eventos_por_mes || [];
            chartMes = new Chart(document.getElementById('graficoEventosMes'), {
              type: 'bar',
              data: {
                labels: meses,
                datasets: [{
                  label: 'Eventos',
                  data: valores,
                  backgroundColor: 'rgba(70, 130, 180, 0.6)',
                  borderColor: 'rgba(70, 130, 180, 1)',
                  borderWidth: 1
                }]
              },
              options: {
                responsive: true,
                scales: { y: { beginAtZero: true, ticks: { precision: 0 } } }
              }
            });

            // Gráfico: Participación por Evento
            const participacion = data.graficos.participacion_por_evento || [];
            if (participacion.length > 0) {
              chartParticipacion = new Chart(document.getElementById('graficoParticipacion'), {
                type: 'doughnut',
                data: {
                  labels: participacion.map(function (p) { return p.evento || '—'; }),
                  datasets: [{
                    label: 'Inscripciones',
                    data: participacion.map(function (p) { return Number(p.total) || 0; }),
                    backgroundColor: [
                      'rgba(54, 162, 235, 0.7)',
                      'rgba(255, 159, 64, 0.7)',
                      'rgba(75, 192, 192, 0.7)',
                      'rgba(153, 102, 255, 0.7)',
                      'rgba(255, 99, 132, 0.7)',
                      'rgba(255, 206, 86, 0.7)'
                    ]
                  }]
                },
                options: { responsive: true }
              });
            } else {
              document.getElementById('graficoParticipacion').parentElement.innerHTML = '<p class="text-center text-muted py-4">No hay inscripciones registradas</p>';
            }
          }
        })
        .catch(function (error) {
          console.error('Error al cargar el panel de eventos:', error);
          document.getElementById('lista-proximos-eventos').innerHTML =
            '<li class="list-group-item text-danger">Error al cargar los datos</li>';
        });
    });
  </script>
<?php
$content = ob_get_clean();
require_once appPath('cliente/layouts/AdminLayout.php');