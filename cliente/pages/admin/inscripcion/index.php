<?php
declare(strict_types=1);
if (
  !isset($_SESSION['usuario']) ||
  !in_array($_SESSION['tipo_persona'], ['Administrativo', 'Encargado'])
) {
  header("Location: " . url('/login-admin'));
  exit();
}
$pageTitle = "Gestión de Inscripciones";
$pageStyles = [
  'cliente/assets/css/inscripcion.css',
];
ob_start();
$filtros = [];
if (($buscar ?? '') !== '') $filtros[] = 'buscar=' . urlencode($buscar);
$basePagina = url('/inscripcion') . (empty($filtros) ? '?pagina=' : '?' . implode('&', $filtros) . '&pagina=');
?>
<div class="container-fluid py-3 inscripcion-page">
  <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
    <form class="inscripcion-filtros d-flex flex-wrap flex-grow-1 gap-2 mb-0" method="get" action="<?= url('/inscripcion') ?>">
      <div class="position-relative">
        <input type="text" name="buscar" class="form-control"
               placeholder="Buscar por ID persona o nombre..." value="<?= htmlspecialchars($buscar ?? '') ?>">
      </div>
      <button type="submit" class="btn btn-primary"><i class="fas fa-search me-1"></i>Buscar</button>
      <?php if (($buscar ?? '') !== ''): ?>
        <a href="<?= url('/inscripcion') ?>" class="btn btn-outline-secondary">
          <i class="fas fa-times me-1"></i>Limpiar
        </a>
      <?php endif; ?>
    </form>
  </div>

  <div>
    <div class="table-responsive">
      <table class="table table-hover table-bordered align-middle text-center">
        <thead>
          <tr>
            <th>Persona</th>
            <th>Actividad</th>
            <th>Pago</th>
            <th>Requisitos</th>
            <th>Estado</th>
            <th>Asist.</th>
            <th width="90">Acciones</th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($inscripciones)): ?>
            <tr>
              <td colspan="9" class="text-center text-muted py-4">No hay inscripciones registradas.</td>
            </tr>
          <?php else: ?>
            <?php foreach ($inscripciones as $i): ?>
              <tr>
                <td class="text-start">
                  <div><?= htmlspecialchars($i['persona_nombre'] ?? '—') ?></div>
                </td>
                <td><?= htmlspecialchars($i['nombre_actividad'] ?? '—') ?></td>
                <td>
                  <?php if (!empty($i['id_pago'])): ?>
                    <?= htmlspecialchars($i['pago_concepto'] ?? ('Pago #' . (int) $i['id_pago'])) ?>
                    <small class="d-block text-muted"><?= number_format((float) $i['pago_monto'], 2) ?> Bs</small>
                  <?php else: ?>
                    <span class="text-muted">Sin pago</span>
                  <?php endif; ?>
                </td>
                <td>
                  <?php
                  $req = $i['cumple_requisitos'];
                  $reqBadge = 'secondary';
                  if ($req === 'Si') $reqBadge = 'success';
                  elseif ($req === 'No') $reqBadge = 'danger';
                  ?>
                  <span class="badge bg-<?= $reqBadge ?>"><?= htmlspecialchars($req) ?></span>
                </td>
                <td>
                  <?php
                  $estado = $i['estado'];
                  $badge = 'secondary';
                  if ($estado === 'Inscrito') $badge = 'success';
                  elseif ($estado === 'Pre-inscrito') $badge = 'info';
                  elseif ($estado === 'En espera') $badge = 'warning';
                  elseif ($estado === 'Cancelado') $badge = 'danger';
                  elseif ($estado === 'Completado') $badge = 'primary';
                  ?>
                  <span class="badge bg-<?= $badge ?>"><?= htmlspecialchars($estado) ?></span>
                </td>
                <td><?= (int) $i['asistencia'] ?></td>
                <td>
                  <a href="<?= url('/inscripcion/editar?id=' . (int) $i['id_inscripcion']) ?>"
                     class="btn btn-sm btn-outline-primary" title="Editar">
                    <i class="fas fa-edit"></i>
                  </a>
                  <button class="btn btn-sm btn-outline-danger btn-eliminar-inscripcion"
                          data-id="<?= (int) $i['id_inscripcion'] ?>"
                          data-nombre="<?= htmlspecialchars($i['persona_nombre'] ?? 'inscripción', ENT_QUOTES) ?>"
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
          Mostrando <?= count($inscripciones) ?> de <?= (int) $total ?> inscripciones
          (página <?= (int) $paginaActual ?> de <?= (int) $totalPaginas ?>)
        </small>
        <nav aria-label="Paginación de inscripciones">
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
        ¿Seguro que deseas eliminar la inscripción de <strong id="nombreEliminar"></strong>?
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
      const klass = type === 'error' ? 'bg-danger text-white' : type === 'success' ? 'bg-success text-white' : 'bg-dark text-white';
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

    document.querySelectorAll('.btn-eliminar-inscripcion').forEach(function (btn) {
      btn.addEventListener('click', function () {
        idEliminar = this.getAttribute('data-id');
        document.getElementById('nombreEliminar').textContent = this.getAttribute('data-nombre');
        modal.show();
      });
    });

    document.getElementById('btnConfirmarEliminar').addEventListener('click', async function () {
      if (!idEliminar) return;
      try {
        const resp = await fetch('<?= url('/inscripcion/eliminar') ?>', {
          method: 'DELETE',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ id_inscripcion: idEliminar })
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
        showToast('Error al eliminar la inscripción.', 'error');
        modal.hide();
      }
    });
  });
</script>
<?php
$content = ob_get_clean();
require_once appPath('cliente/layouts/AdminLayout.php');