<?php
declare(strict_types=1);
if (
  !isset($_SESSION['usuario']) ||
  !in_array($_SESSION['tipo_persona'], ['Administrativo', 'Encargado'])
) {
  header("Location: " . url('/login-admin'));
  exit();
}
$pageTitle = "Gestión de Roles";
$pageStyles = [
  'cliente/assets/css/roles.css',
];
ob_start();
?>
<div class="container-fluid py-3 roles-page">
  <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
    <h4 class="mb-0">
      <i class="fas fa-user-cog me-2"></i>Roles del Sistema
    </h4>
    <a href="<?= url('/roles/nuevo') ?>" class="btn btn-primary">
      <i class="fas fa-plus me-2"></i>Nuevo Rol
    </a>
  </div>

  <div class="table-responsive">
    <table class="table table-hover table-bordered align-middle text-center">
      <thead>
        <tr>
          <th>ID</th>
          <th>Rol</th>
          <th>Permisos</th>
          <th>Estado</th>
          <th>Creado</th>
          <th>Actualizado</th>
          <th width="160">Acciones</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($roles)): ?>
          <tr>
            <td colspan="7" class="text-center text-muted py-4">No hay roles registrados.</td>
          </tr>
        <?php else: ?>
          <?php foreach ($roles as $r): ?>
            <tr id="rol-<?= (int) $r['id_usuario'] ?>">
              <td><?= (int) $r['id_usuario'] ?></td>
              <td><span class="badge bg-primary"><?= htmlspecialchars($r['rol']) ?></span></td>
              <td><?= htmlspecialchars($r['permisos'] ?? '—') ?></td>
              <td>
                <?php
                $estado = $r['estado'];
                $badge = 'secondary';
                if ($estado === 'Activo') $badge = 'success';
                elseif ($estado === 'Inactivo') $badge = 'danger';
                elseif ($estado === 'Suspendido') $badge = 'warning';
                ?>
                <span class="badge bg-<?= $badge ?>"><?= htmlspecialchars($estado) ?></span>
              </td>
              <td><?= htmlspecialchars($r['fecha_creacion']) ?></td>
              <td><?= htmlspecialchars($r['fecha_actualizacion']) ?></td>
              <td>
                <a href="<?= url('/roles/editar?id=' . (int) $r['id_usuario']) ?>"
                   class="btn btn-sm btn-outline-primary" title="Editar">
                  <i class="fas fa-edit"></i>
                </a>
                <button class="btn btn-sm btn-outline-danger btn-eliminar-rol"
                        data-id="<?= (int) $r['id_usuario'] ?>"
                        data-rol="<?= htmlspecialchars($r['rol'], ENT_QUOTES) ?>"
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
        ¿Seguro que deseas eliminar el rol <strong id="rolEliminar"></strong>?
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

    document.querySelectorAll('.btn-eliminar-rol').forEach(function (btn) {
      btn.addEventListener('click', function () {
        idEliminar = this.getAttribute('data-id');
        document.getElementById('rolEliminar').textContent = this.getAttribute('data-rol');
        modal.show();
      });
    });

    document.getElementById('btnConfirmarEliminar').addEventListener('click', async function () {
      if (!idEliminar) return;
      try {
        const resp = await fetch('<?= url('/roles/eliminar') ?>', {
          method: 'DELETE',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ id_usuario: idEliminar })
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
        showToast('Error al eliminar el rol.', 'error');
        modal.hide();
      }
    });
  });
</script>
<?php
$content = ob_get_clean();
require_once appPath('cliente/layouts/AdminLayout.php');