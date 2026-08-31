<?php
declare(strict_types=1);
if (
  !isset($_SESSION['usuario']) ||
  !in_array($_SESSION['tipo_persona'], ['Administrativo', 'Encargado'])
) {
  header("Location: " . url('/login-admin'));
  exit();
}
$pageTitle = "Gestión de Eventos";
$pageStyles = [
  'cliente/assets/css/eventos.css',
];
ob_start();
?>
<div class="container-fluid py-3 eventos-page">
  <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
    <form class="eventos-filtros d-flex flex-wrap flex-grow-1 gap-2 mb-0" method="get" action="<?= url('/eventos') ?>">
      <div class="position-relative">
        <input type="text" name="buscar" id="buscar" class="form-control"
               placeholder="Buscar por nombre..." value="<?= htmlspecialchars($buscar ?? '') ?>">
      </div>
      <select name="mes" id="filtroMes" class="form-select" style="width:auto;">
        <option value="0">Todos los meses</option>
        <?php foreach (($meses ?? []) as $num => $nombreMes): ?>
          <option value="<?= $num ?>" <?= ($mes ?? 0) === $num ? 'selected' : '' ?>>
            <?= $nombreMes ?>
          </option>
        <?php endforeach; ?>
      </select>
      <button type="submit" class="btn btn-primary"><i class="fas fa-search me-1"></i>Filtrar</button>
      <?php if (($buscar ?? '') !== '' || ($mes ?? 0) > 0): ?>
        <a href="<?= url('/eventos') ?>" class="btn btn-outline-secondary">
          <i class="fas fa-times me-1"></i>Limpiar
        </a>
      <?php endif; ?>
    </form>
    <a href="<?= url('/eventos/nuevo') ?>" class="btn btn-primary">
      <i class="fas fa-plus me-2"></i>Nuevo Evento
    </a>
  </div>
  <div>
    <div class="table-responsive">
      <table class="table table-hover table-bordered align-middle text-center">
        <thead>
          <tr>
            <th>Nombre</th>
            <th>Descripción</th>
            <th>Fecha</th>
            <th>Hora</th>
            <th>Lugar</th>
            <th>Estado</th>
            <th width="90">Acciones</th>
          </tr>
        </thead>
          <tbody>
            <?php if (empty($eventos)): ?>
              <tr>
                <td colspan="7" class="text-center text-muted py-4">
                  No hay eventos registrados.
                </td>
              </tr>
            <?php else: ?>
              <?php foreach ($eventos as $ev): ?>
                <tr>
                  <td class="text-start"><?= htmlspecialchars($ev['nombre_evento']) ?></td>
                  <td class="text-start"><?= htmlspecialchars($ev['descripcion'] ?? '—') ?></td>
                  <td><?= htmlspecialchars($ev['fecha_evento'] ?? '—') ?></td>
                  <td><?= htmlspecialchars($ev['hora_evento'] ?? '—') ?></td>
                  <td class="text-start"><?= htmlspecialchars($ev['lugar'] ?? '—') ?></td>
                  <td>
                    <?php
                    $estado = $ev['estado'];
                    $badge = 'secondary';
                    if ($estado === 'Activo') $badge = 'success';
                    elseif ($estado === 'Inactivo') $badge = 'warning';
                    elseif ($estado === 'Cancelado') $badge = 'danger';
                    ?>
                    <span class="badge bg-<?= $badge ?>"><?= htmlspecialchars($estado) ?></span>
                  </td>
                  <td>
                    <a href="<?= url('/eventos/editar?id=' . (int) $ev['id_evento']) ?>"
                       class="btn btn-sm btn-outline-primary" title="Editar">
                      <i class="fas fa-edit"></i>
                    </a>
                    <button class="btn btn-sm btn-outline-danger btn-eliminar-evento"
                            data-id="<?= (int) $ev['id_evento'] ?>"
                            data-nombre="<?= htmlspecialchars($ev['nombre_evento'], ENT_QUOTES) ?>"
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

      <?php
      // Base de la URL de paginación preservando filtros
      $filtros = [];
      if (($buscar ?? '') !== '') $filtros[] = 'buscar=' . urlencode($buscar);
      if (($mes ?? 0) > 0) $filtros[] = 'mes=' . (int) $mes;
      $basePagina = url('/eventos') . (empty($filtros) ? '?pagina=' : '?' . implode('&', $filtros) . '&pagina=');
      ?>
      <?php if ($totalPaginas > 1): ?>
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mt-3">
          <small class="text-muted">
            Mostrando <?= count($eventos) ?> de <?= (int) $total ?> eventos
            (página <?= (int) $paginaActual ?> de <?= (int) $totalPaginas ?>)
          </small>
          <nav aria-label="Paginación de eventos">
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
        ¿Seguro que deseas eliminar el evento <strong id="nombreEventoEliminar"></strong>?
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
      const klass = type === 'error' ? 'bg-danger text-white' : type === 'warning' ? 'bg-warning text-dark' : 'bg-dark text-white';
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

    document.querySelectorAll('.btn-eliminar-evento').forEach(function (btn) {
      btn.addEventListener('click', function () {
        idEliminar = this.getAttribute('data-id');
        document.getElementById('nombreEventoEliminar').textContent = this.getAttribute('data-nombre');
        modal.show();
      });
    });

    document.getElementById('btnConfirmarEliminar').addEventListener('click', async function () {
      if (!idEliminar) return;
      try {
        const resp = await fetch('<?= url('/eventos/eliminar') ?>', {
          method: 'DELETE',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ id_evento: idEliminar })
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
        showToast('Error al eliminar el evento.', 'error');
        modal.hide();
      }
    });
  });
</script>
<?php
$content = ob_get_clean();
require_once appPath('cliente/layouts/AdminLayout.php');
