<?php
declare(strict_types=1);
if (
  !isset($_SESSION['usuario']) ||
  !in_array($_SESSION['tipo_persona'], ['Administrativo', 'Encargado'])
) {
  header("Location: " . url('/login-admin'));
  exit();
}
$pageTitle = "Gestión de Personas";
$pageStyles = [
  'cliente/assets/css/personas.css',
];
ob_start();
$filtros = [];
if (($buscar ?? '') !== '') $filtros[] = 'buscar=' . urlencode($buscar);
if (($rol ?? '') !== '') $filtros[] = 'rol=' . urlencode($rol);
$basePagina = url('/personas') . (empty($filtros) ? '?pagina=' : '?' . implode('&', $filtros) . '&pagina=');
?>
<div class="container-fluid py-3 personas-page">
  <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
    <form class="personas-filtros d-flex flex-wrap gap-2 mb-0" method="get" action="<?= url('/personas') ?>">
      <div class="position-relative">
        <input type="text" name="buscar" class="form-control"
               placeholder="Buscar por nombre o CI..." value="<?= htmlspecialchars($buscar ?? '') ?>">
      </div>
      <select name="rol" class="form-select" style="width:auto;">
        <option value="">Todos los roles</option>
        <?php foreach (($roles ?? []) as $r): ?>
          <option value="<?= htmlspecialchars($r['rol']) ?>" <?= ($rol ?? '') === $r['rol'] ? 'selected' : '' ?>>
            <?= htmlspecialchars($r['rol']) ?>
          </option>
        <?php endforeach; ?>
      </select>
      <button type="submit" class="btn btn-primary"><i class="fas fa-search me-1"></i>Buscar</button>
      <?php if (($buscar ?? '') !== '' || ($rol ?? '') !== ''): ?>
        <a href="<?= url('/personas') ?>" class="btn btn-outline-secondary">
          <i class="fas fa-times me-1"></i>Limpiar
        </a>
      <?php endif; ?>
    </form>
    <a href="<?= url('/personas/nuevo') ?>" class="btn btn-primary">
      <i class="fas fa-plus me-2"></i>Nueva Persona
    </a>
  </div>

  <div>
    <div class="table-responsive">
      <table class="table table-hover table-bordered align-middle text-center">
        <thead>
          <tr>
            <th>ID</th>
            <th>CI</th>
            <th>Nombre Completo</th>
            <th>Tipo</th>
            <th>Universidad</th>
            <th>Rol</th>
            <th>Estado</th>
            <th width="160">Acciones</th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($personas)): ?>
            <tr>
              <td colspan="8" class="text-center text-muted py-4">No hay personas registradas.</td>
            </tr>
          <?php else: ?>
            <?php foreach ($personas as $p): ?>
              <tr>
                <td><?= (int) $p['id_persona'] ?></td>
                <td><?= htmlspecialchars($p['ci']) ?></td>
                <td class="text-start">
                  <div><?= htmlspecialchars(trim($p['nombres'] . ' ' . $p['apellidos'])) ?></div>
                  <small class="text-muted"><?= htmlspecialchars($p['correo'] ?? '') ?></small>
                </td>
                <td>
                  <span class="badge bg-primary"><?= htmlspecialchars($p['tipo_persona']) ?></span>
                </td>
                <td><?= htmlspecialchars($p['universidad_sigla'] ?? ($p['universidad_nombre'] ?? '—')) ?></td>
                <td>
                  <?php if (!empty($p['rol'])): ?>
                    <span class="badge bg-secondary"><?= htmlspecialchars($p['rol']) ?></span>
                  <?php else: ?>
                    <span class="text-muted">Sin rol</span>
                  <?php endif; ?>
                </td>
                <td>
                  <?php
                  $estado = $p['estado'];
                  $badge = 'secondary';
                  if ($estado === 'Activo') $badge = 'success';
                  elseif ($estado === 'Inactivo') $badge = 'danger';
                  elseif ($estado === 'Suspendido') $badge = 'warning';
                  ?>
                  <span class="badge bg-<?= $badge ?>"><?= htmlspecialchars($estado) ?></span>
                </td>
                <td>
                  <a href="<?= url('/personas/editar?id=' . (int) $p['id_persona']) ?>"
                     class="btn btn-sm btn-outline-primary" title="Editar">
                    <i class="fas fa-edit"></i>
                  </a>
                  <button class="btn btn-sm btn-outline-danger btn-eliminar-persona"
                          data-id="<?= (int) $p['id_persona'] ?>"
                          data-nombre="<?= htmlspecialchars(trim($p['nombres'] . ' ' . $p['apellidos']), ENT_QUOTES) ?>"
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
          Mostrando <?= count($personas) ?> de <?= (int) $total ?> personas
          (página <?= (int) $paginaActual ?> de <?= (int) $totalPaginas ?>)
        </small>
          <nav aria-label="Paginación de personas">
          <ul class="pagination pagination-sm mb-0">
            <li class="page-item <?= $paginaActual <= 1 ? 'disabled' : '' ?>">
              <a class="page-link" href="<?= $basePagina . max(1, $paginaActual - 1) ?>" aria-label="Anterior">
                <i class="fas fa-chevron-left"></i>
              </a>
            </li>
            <?php
            $paginas = [];
            if ($paginaActual <= 2) {
                for ($i = 1; $i <= min(3, $totalPaginas); $i++) $paginas[] = $i;
            } else {
                $paginas[] = $paginaActual;
                if ($paginaActual + 1 <= $totalPaginas) $paginas[] = $paginaActual + 1;
            }
            if ($totalPaginas > 2) {
                if (!in_array($totalPaginas - 1, $paginas)) $paginas[] = $totalPaginas - 1;
                if (!in_array($totalPaginas, $paginas)) $paginas[] = $totalPaginas;
            }
            $paginas = array_unique($paginas);
            sort($paginas);
            $ultimaMostrada = 0;
            foreach ($paginas as $p):
                if ($ultimaMostrada > 0 && $p > $ultimaMostrada + 1):
                    echo '<li class="page-item disabled"><span class="page-link">…</span></li>';
                endif;
            ?>
              <li class="page-item <?= $p === $paginaActual ? 'active' : '' ?>">
                <a class="page-link" href="<?= $basePagina . $p ?>"><?= $p ?></a>
              </li>
            <?php $ultimaMostrada = $p; endforeach; ?>
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
        ¿Seguro que deseas eliminar a <strong id="nombreEliminar"></strong>?
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

    document.querySelectorAll('.btn-eliminar-persona').forEach(function (btn) {
      btn.addEventListener('click', function () {
        idEliminar = this.getAttribute('data-id');
        document.getElementById('nombreEliminar').textContent = this.getAttribute('data-nombre');
        modal.show();
      });
    });

    document.getElementById('btnConfirmarEliminar').addEventListener('click', async function () {
      if (!idEliminar) return;
      try {
        const resp = await fetch('<?= url('/personas/eliminar') ?>', {
          method: 'DELETE',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ id_persona: idEliminar })
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
        showToast('Error al eliminar la persona.', 'error');
        modal.hide();
      }
    });
  });
</script>
<?php
$content = ob_get_clean();
require_once appPath('cliente/layouts/AdminLayout.php');