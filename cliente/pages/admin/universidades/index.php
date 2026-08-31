<?php
declare(strict_types=1);
if (
  !isset($_SESSION['usuario']) ||
  !in_array($_SESSION['tipo_persona'], ['Administrativo', 'Encargado'])
) {
  header("Location: " . url('/login-admin'));
  exit();
}
$pageTitle = "Gestión de Universidades";
$pageStyles = [
  'cliente/assets/css/universidades.css',
];
ob_start();
$basePagina = url('/universidades') . (($buscar ?? '') !== '' ? '?buscar=' . urlencode($buscar) . '&pagina=' : '?pagina=');
?>
<div class="container-fluid py-3 universidades-page">
  <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
    <form class="d-flex gap-2 mb-0" method="get" action="<?= url('/universidades') ?>">
      <input type="text" name="buscar" class="form-control"
             placeholder="Buscar por nombre..." value="<?= htmlspecialchars($buscar ?? '') ?>">
      <button type="submit" class="btn btn-primary"><i class="fas fa-search me-1"></i>Buscar</button>
      <?php if (($buscar ?? '') !== ''): ?>
        <a href="<?= url('/universidades') ?>" class="btn btn-outline-secondary">
          <i class="fas fa-times me-1"></i>Limpiar
        </a>
      <?php endif; ?>
    </form>
    <a href="<?= url('/universidades/nuevo') ?>" class="btn btn-primary">
      <i class="fas fa-plus me-2"></i>Nueva Universidad
    </a>
  </div>

  <div class="table-responsive">
    <table class="table table-hover table-bordered align-middle text-center">
      <thead>
        <tr>
          <th>ID</th>
          <th>Nombre</th>
          <th>Sigla</th>
          <th>Ciudad</th>
          <th>Teléfono</th>
          <th>Correo</th>
          <th>Estado</th>
          <th width="140">Acciones</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($universidades)): ?>
          <tr>
            <td colspan="8" class="text-center text-muted py-4">No hay universidades registradas.</td>
          </tr>
        <?php else: ?>
          <?php foreach ($universidades as $u): ?>
            <tr>
              <td><?= (int) $u['id_universidad'] ?></td>
              <td class="text-start"><?= htmlspecialchars($u['nombre']) ?></td>
              <td><span class="badge bg-secondary"><?= htmlspecialchars($u['sigla'] ?? '—') ?></span></td>
              <td><?= htmlspecialchars($u['ciudad']) ?></td>
              <td><?= htmlspecialchars($u['telefono'] ?? '—') ?></td>
              <td><?= htmlspecialchars($u['correo'] ?? '—') ?></td>
              <td>
                <?php
                $estado = $u['estado'];
                $badge = $estado === 'Activo' ? 'success' : 'danger';
                ?>
                <span class="badge bg-<?= $badge ?>"><?= htmlspecialchars($estado) ?></span>
              </td>
              <td>
                <a href="<?= url('/universidades/editar?id=' . (int) $u['id_universidad']) ?>"
                   class="btn btn-sm btn-outline-primary" title="Editar">
                  <i class="fas fa-edit"></i>
                </a>
                <button class="btn btn-sm btn-outline-danger btn-eliminar-universidad"
                        data-id="<?= (int) $u['id_universidad'] ?>"
                        data-nombre="<?= htmlspecialchars($u['nombre'], ENT_QUOTES) ?>"
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
        Mostrando <?= count($universidades) ?> de <?= (int) $total ?> universidades
        (página <?= (int) $paginaActual ?> de <?= (int) $totalPaginas ?>)
      </small>
      <nav aria-label="Paginación de universidades">
        <ul class="pagination pagination-sm mb-0">
          <li class="page-item <?= $paginaActual <= 1 ? 'disabled' : '' ?>">
            <a class="page-link" href="<?= $basePagina . max(1, $paginaActual - 1) ?>"><i class="fas fa-chevron-left"></i></a>
          </li>
          <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
            <li class="page-item <?= $i === $paginaActual ? 'active' : '' ?>">
              <a class="page-link" href="<?= $basePagina . $i ?>"><?= $i ?></a>
            </li>
          <?php endfor; ?>
          <li class="page-item <?= $paginaActual >= $totalPaginas ? 'disabled' : '' ?>">
            <a class="page-link" href="<?= $basePagina . min($totalPaginas, $paginaActual + 1) ?>"><i class="fas fa-chevron-right"></i></a>
          </li>
        </ul>
      </nav>
    </div>
  <?php endif; ?>
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
        ¿Seguro que deseas eliminar la universidad <strong id="nombreEliminar"></strong>?
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

    document.querySelectorAll('.btn-eliminar-universidad').forEach(function (btn) {
      btn.addEventListener('click', function () {
        idEliminar = this.getAttribute('data-id');
        document.getElementById('nombreEliminar').textContent = this.getAttribute('data-nombre');
        modal.show();
      });
    });

    document.getElementById('btnConfirmarEliminar').addEventListener('click', async function () {
      if (!idEliminar) return;
      try {
        const resp = await fetch('<?= url('/universidades/eliminar') ?>', {
          method: 'DELETE',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ id_universidad: idEliminar })
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
        showToast('Error al eliminar la universidad.', 'error');
        modal.hide();
      }
    });
  });
</script>
<?php
$content = ob_get_clean();
require_once appPath('cliente/layouts/AdminLayout.php');