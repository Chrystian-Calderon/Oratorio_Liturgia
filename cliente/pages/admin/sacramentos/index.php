<?php
declare(strict_types=1);
if (
  !isset($_SESSION['usuario']) ||
  !in_array($_SESSION['tipo_persona'], ['Administrativo', 'Encargado'])
) {
  header("Location: " . url('/login-admin'));
  exit();
}
$pageTitle = "Gestión de Sacramentos";
$pageStyles = [
  'cliente/assets/css/sacramentos.css',
];
ob_start();
$basePagina = url('/sacramentos') . (($buscar ?? '') !== '' ? '?buscar=' . urlencode($buscar) . '&pagina=' : '?pagina=');
?>
<div class="container-fluid py-3 sacramentos-page">
  <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
    <form class="d-flex gap-2 mb-0" method="get" action="<?= url('/sacramentos') ?>">
      <input type="text" name="buscar" class="form-control"
             placeholder="Buscar por nombre del solicitante..." value="<?= htmlspecialchars($buscar ?? '') ?>">
      <button type="submit" class="btn btn-primary"><i class="fas fa-search me-1"></i>Buscar</button>
      <?php if (($buscar ?? '') !== ''): ?>
        <a href="<?= url('/sacramentos') ?>" class="btn btn-outline-secondary">
          <i class="fas fa-times me-1"></i>Limpiar
        </a>
      <?php endif; ?>
    </form>
    <a href="<?= url('/sacramentos/nuevo') ?>" class="btn btn-primary">
      <i class="fas fa-plus me-2"></i>Nueva Inscripción
    </a>
  </div>

  <div class="table-responsive">
    <table class="table table-hover table-bordered align-middle text-center">
      <thead>
        <tr>
          <th>Sacramento</th>
          <th>Nombre del Solicitante</th>
          <th>Fecha Nacimiento</th>
          <th>Teléfono</th>
          <th>Correo</th>
          <th>Registrado</th>
          <th width="140">Acciones</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($sacramentos)): ?>
          <tr>
            <td colspan="7" class="text-center text-muted py-4">No hay inscripciones sacramentales registradas.</td>
          </tr>
        <?php else: ?>
          <?php foreach ($sacramentos as $s): ?>
            <tr>
              <td><span class="badge bg-primary"><?= htmlspecialchars($s['sacramento']) ?></span></td>
              <td class="text-start"><?= htmlspecialchars($s['nombre_solicitante']) ?></td>
              <td><?= htmlspecialchars($s['fecha_nacimiento']) ?></td>
              <td><?= htmlspecialchars($s['telefono']) ?></td>
              <td><?= htmlspecialchars($s['email']) ?></td>
              <td><?= htmlspecialchars($s['fecha_registro'] ?? '—') ?></td>
              <td>
                <a href="<?= url('/sacramentos/editar?id=' . (int) $s['id_inscripcion']) ?>"
                   class="btn btn-sm btn-outline-primary" title="Editar">
                  <i class="fas fa-edit"></i>
                </a>
                <button class="btn btn-sm btn-outline-danger btn-eliminar-sacramento"
                        data-id="<?= (int) $s['id_inscripcion'] ?>"
                        data-nombre="<?= htmlspecialchars($s['nombre_solicitante'], ENT_QUOTES) ?>"
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
        Mostrando <?= count($sacramentos) ?> de <?= (int) $total ?> inscripciones
        (página <?= (int) $paginaActual ?> de <?= (int) $totalPaginas ?>)
      </small>
      <nav aria-label="Paginación de sacramentos">
        <ul class="pagination pagination-sm mb-0">
          <li class="page-item <?= $paginaActual <= 1 ? 'disabled' : '' ?>">
            <a class="page-link" href="<?= $basePagina . max(1, $paginaActual - 1) ?>"><i class="fas fa-chevron-left"></i></a>
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

    document.querySelectorAll('.btn-eliminar-sacramento').forEach(function (btn) {
      btn.addEventListener('click', function () {
        idEliminar = this.getAttribute('data-id');
        document.getElementById('nombreEliminar').textContent = this.getAttribute('data-nombre');
        modal.show();
      });
    });

    document.getElementById('btnConfirmarEliminar').addEventListener('click', async function () {
      if (!idEliminar) return;
      try {
        const resp = await fetch('<?= url('/sacramentos/eliminar') ?>', {
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