<?php
declare(strict_types=1);
if (
  !isset($_SESSION['usuario']) ||
  !in_array($_SESSION['tipo_persona'], ['Administrativo', 'Encargado'])
) {
  header("Location: " . url('/login-admin'));
  exit();
}
$pageTitle = "Gestión de Actividades";
$pageStyles = [
  'cliente/assets/css/actividades.css',
];
ob_start();
$filtros = [];
if (($buscar ?? '') !== '') $filtros[] = 'buscar=' . urlencode($buscar);
$basePagina = url('/actividades') . (empty($filtros) ? '?pagina=' : '?' . implode('&', $filtros) . '&pagina=');
?>
<div class="container-fluid py-3 actividades-page">
  <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
    <form class="actividades-filtros d-flex flex-wrap flex-grow-1 gap-2 mb-0" method="get" action="<?= url('/actividades') ?>">
      <div class="position-relative">
        <input type="text" name="buscar" class="form-control"
               placeholder="Buscar por nombre..." value="<?= htmlspecialchars($buscar ?? '') ?>">
      </div>
      <button type="submit" class="btn btn-primary"><i class="fas fa-search me-1"></i>Buscar</button>
      <?php if (($buscar ?? '') !== ''): ?>
        <a href="<?= url('/actividades') ?>" class="btn btn-outline-secondary">
          <i class="fas fa-times me-1"></i>Limpiar
        </a>
      <?php endif; ?>
    </form>
    <a href="<?= url('/actividades/nuevo') ?>" class="btn btn-primary">
      <i class="fas fa-plus me-2"></i>Nueva Actividad
    </a>
  </div>

  <div>
    <div class="table-responsive">
      <table class="table table-hover table-bordered align-middle text-center">
        <thead>
          <tr>
            <th>Nombre</th>
            <th>Tipo</th>
            <th>Fecha Inicio</th>
            <th>Fecha Fin</th>
            <th>Horario</th>
            <th>Costo (Bs)</th>
            <th>Cupo</th>
            <th>Evento</th>
            <th>Estado</th>
            <th width="160">Acciones</th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($actividades)): ?>
            <tr>
              <td colspan="10" class="text-center text-muted py-4">No hay actividades registradas.</td>
            </tr>
          <?php else: ?>
            <?php foreach ($actividades as $a): ?>
              <tr>
                <td class="text-start"><?= htmlspecialchars($a['nombre_actividad']) ?></td>
                <td><?= htmlspecialchars($a['tipo_actividad'] ?? '—') ?></td>
                <td><?= htmlspecialchars($a['fecha_inicio'] ?? '—') ?></td>
                <td><?= htmlspecialchars($a['fecha_fin'] ?? '—') ?></td>
                <td><?= substr((string)$a['hora_inicio'], 0, 5) . ' - ' . substr((string)$a['hora_fin'], 0, 5) ?></td>
                <td><?= number_format((float) $a['costo'], 2) ?></td>
                <td>
                  <?php
                  if ($a['cupo_maximo'] !== null) {
                    echo (int) $a['cupo_disponible'] . ' / ' . (int) $a['cupo_maximo'];
                  } else {
                    echo '—';
                  }
                  ?>
                </td>
                <td><?= htmlspecialchars($a['nombre_evento'] ?? '—') ?></td>
                <td>
                  <?php
                  $estado = $a['estado'];
                  $badge = 'secondary';
                  if ($estado === 'Activo') $badge = 'success';
                  elseif ($estado === 'Cancelado') $badge = 'danger';
                  elseif ($estado === 'Completado') $badge = 'info';
                  elseif ($estado === 'En espera') $badge = 'warning';
                  ?>
                  <span class="badge bg-<?= $badge ?>"><?= htmlspecialchars($estado) ?></span>
                </td>
                <td>
                  <a href="<?= url('/actividades/editar?id=' . (int) $a['id_actividad']) ?>"
                     class="btn btn-sm btn-outline-primary" title="Editar">
                    <i class="fas fa-edit"></i>
                  </a>
                  <button class="btn btn-sm btn-outline-danger btn-eliminar-actividad"
                          data-id="<?= (int) $a['id_actividad'] ?>"
                          data-nombre="<?= htmlspecialchars($a['nombre_actividad'], ENT_QUOTES) ?>"
                          title="Eliminar">
                    <i class="fas fa-trash"></i>
                  </button>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>

    <?php if ($totalPaginas > 1): ?>
      <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mt-3">
        <small class="text-muted">
          Mostrando <?= count($actividades) ?> de <?= (int) $total ?> actividades
          (página <?= (int) $paginaActual ?> de <?= (int) $totalPaginas ?>)
        </small>
        <nav aria-label="Paginación de actividades">
          <ul class="pagination pagination-sm mb-0">
            <li class="page-item <?= $paginaActual <= 1 ? 'disabled' : '' ?>">
              <a class="page-link" href="<?= $basePagina . max(1, $paginaActual - 1) ?>" aria-label="Anterior">
                <i class="fas fa-chevron-left"></i>
              </a>
            </li>
            <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
              <li class="page-item <?= $i === $paginaActual ? 'active' : '' ?>">
                <a class="page-link" href="<?= $basePagina . $i ?>"><?= $i ?></a>
              </li>
            <?php endfor; ?>
            <li class="page-item <?= $paginaActual >= $totalPaginas ? 'disabled' : '' ?>">
              <a class="page-link" href="<?= $basePagina . min($totalPaginas, $paginaActual + 1) ?>" aria-label="Siguiente">
                <i class="fas fa-chevron-right"></i>
              </a>
            </li>
          </ul>
        </nav>
      </div>
    <?php endif; ?>
  </div>
</div>

<!-- Toast container -->
<div id="toasts" class="position-fixed top-0 end-0 p-3"></div>

<!-- Modal de confirmación -->
<div class="modal fade" id="confirmarEliminar" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title">Confirmar eliminación</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        ¿Seguro que deseas eliminar la actividad <strong id="nombreEliminar"></strong>?
      </div>
      <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button class="btn btn-danger" id="btnConfirmarEliminar">Eliminar</button>
      </div>
    </div>
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    const toastContainer = document.getElementById('toasts');

    function showToast(message, type) {
      const klass = type === 'error' ? 'bg-danger text-white' : 'bg-dark text-white';
      const el = document.createElement('div');
      el.className = 'toast ' + klass;
      el.innerHTML = '<div class="toast-body"><i class="fas fa-info-circle me-2"></i>' + message + '</div>';
      toastContainer.appendChild(el);
      const bs = new bootstrap.Toast(el, { delay: 3000 });
      bs.show();
      el.addEventListener('hidden.bs.toast', () => el.remove());
    }

    const modal = new bootstrap.Modal(document.getElementById('confirmarEliminar'));
    let idEliminar = null;

    document.querySelectorAll('.btn-eliminar-actividad').forEach(function (btn) {
      btn.addEventListener('click', function () {
        idEliminar = this.getAttribute('data-id');
        document.getElementById('nombreEliminar').textContent = this.getAttribute('data-nombre');
        modal.show();
      });
    });

    document.getElementById('btnConfirmarEliminar').addEventListener('click', async function () {
      if (!idEliminar) return;
      try {
        const resp = await fetch('<?= url('/actividades/eliminar') ?>', {
          method: 'DELETE',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ id_actividad: idEliminar })
        });
        const result = await resp.json();
        if (!result.success) {
          showToast(result.message, 'error');
          modal.hide();
          return;
        }
        showToast(result.message, 'success');
        modal.hide();
        setTimeout(() => window.location.reload(), 600);
      } catch (error) {
        console.error(error);
        showToast('Error al eliminar la actividad.', 'error');
        modal.hide();
      }
    });
  });
</script>
<?php
$content = ob_get_clean();
require_once appPath('cliente/layouts/AdminLayout.php');
