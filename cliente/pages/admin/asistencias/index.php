<?php
declare(strict_types=1);
if (
  !isset($_SESSION['usuario']) ||
  !in_array($_SESSION['tipo_persona'], ['Administrativo', 'Encargado'])
) {
  header("Location: " . url('/login-admin'));
  exit();
}
$pageTitle = "Gestión de Asistencias";
$pageStyles = ['cliente/assets/css/inscripcion.css'];
ob_start();
?>
<div class="container-fluid py-3 inscripcion-page">
  <div class="page-head mb-4">
    <h3><i class="fas fa-user-check me-2"></i>Gestión de Asistencias</h3>
  </div>

  <!-- FILTROS -->
  <div class="card mb-4">
    <div class="card-body">
      <form class="row g-3" method="get" action="<?= url('/asistencias') ?>">
        <div class="col-md-5">
          <label class="form-label fw-semibold">Actividad</label>
          <select name="actividad" class="form-select" required>
            <option value="" disabled <?= $actividad === 0 ? 'selected' : '' ?>>Seleccione una actividad...</option>
            <?php foreach ($actividades as $act): ?>
              <option value="<?= (int) $act['id_actividad'] ?>"
                <?= (int) $actividad === (int) $act['id_actividad'] ? 'selected' : '' ?>>
                <?= htmlspecialchars($act['nombre_actividad']) ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="col-md-4">
          <label class="form-label fw-semibold">Fecha</label>
          <input type="date" name="fecha" class="form-control"
                 value="<?= htmlspecialchars($fecha) ?>" required>
        </div>
        <div class="col-md-3 d-flex align-items-end">
          <button type="submit" class="btn btn-primary w-100">
            <i class="fas fa-search me-1"></i>Cargar Asistencia
          </button>
        </div>
      </form>
    </div>
  </div>

  <?php if ($actividad > 0): ?>
  <!-- TABLA DE ASISTENCIA -->
  <div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
      <h5 class="mb-0"><i class="fas fa-list me-2"></i>Inscritos — <?= htmlspecialchars($fecha) ?></h5>
      <span class="badge bg-primary"><?= count($inscripciones) ?> persona(s)</span>
    </div>
    <div class="card-body">
      <?php if (empty($inscripciones)): ?>
        <div class="text-center text-muted py-4">
          <i class="fas fa-info-circle fs-1 mb-2 d-block"></i>
          No hay personas inscritas en esta actividad.
        </div>
      <?php else: ?>
        <div class="table-responsive">
          <table class="table table-hover table-bordered align-middle text-center">
            <thead>
              <tr>
                <th>Persona</th>
                <th>CI</th>
                <th width="200">Asistencia</th>
                <th>Observaciones</th>
                <th width="90">Acciones</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($inscripciones as $ins): ?>
                <tr data-id-inscripcion="<?= (int) $ins['id_inscripcion'] ?>"
                    data-id-asistencia="<?= (int) ($ins['id_asistencia'] ?? 0) ?>">
                  <td class="text-start">
                    <div class="fw-semibold"><?= htmlspecialchars($ins['apellidos'] . ', ' . $ins['nombres']) ?></div>
                  </td>
                  <td><?= htmlspecialchars($ins['ci']) ?></td>
                  <td>
                    <div class="btn-group btn-group-sm" role="group">
                      <input type="radio" class="btn-check" name="asistio_<?= (int) $ins['id_inscripcion'] ?>"
                             id="si_<?= (int) $ins['id_inscripcion'] ?>" value="Si"
                             <?= ($ins['asistio'] ?? '') === 'Si' ? 'checked' : '' ?>>
                      <label class="btn btn-outline-success" for="si_<?= (int) $ins['id_inscripcion'] ?>">
                        <i class="fas fa-check me-1"></i>Sí
                      </label>

                      <input type="radio" class="btn-check" name="asistio_<?= (int) $ins['id_inscripcion'] ?>"
                             id="no_<?= (int) $ins['id_inscripcion'] ?>" value="No"
                             <?= ($ins['asistio'] ?? '') === 'No' ? 'checked' : '' ?>>
                      <label class="btn btn-outline-danger" for="no_<?= (int) $ins['id_inscripcion'] ?>">
                        <i class="fas fa-times me-1"></i>No
                      </label>

                      <input type="radio" class="btn-check" name="asistio_<?= (int) $ins['id_inscripcion'] ?>"
                             id="just_<?= (int) $ins['id_inscripcion'] ?>" value="Justificado"
                             <?= ($ins['asistio'] ?? '') === 'Justificado' ? 'checked' : '' ?>>
                      <label class="btn btn-outline-warning" for="just_<?= (int) $ins['id_inscripcion'] ?>">
                        <i class="fas fa-clock me-1"></i>Justificado
                      </label>
                    </div>
                  </td>
                  <td>
                    <input type="text" class="form-control form-control-sm obs-input"
                           data-id-inscripcion="<?= (int) $ins['id_inscripcion'] ?>"
                           value="<?= htmlspecialchars($ins['observaciones'] ?? '') ?>"
                           placeholder="Observación...">
                  </td>
                  <td>
                    <button class="btn btn-sm btn-primary btn-guardar"
                            data-id-inscripcion="<?= (int) $ins['id_inscripcion'] ?>"
                            title="Guardar">
                      <i class="fas fa-save"></i>
                    </button>
                    <?php if (!empty($ins['id_asistencia'])): ?>
                      <button class="btn btn-sm btn-outline-danger btn-eliminar"
                              data-id-asistencia="<?= (int) $ins['id_asistencia'] ?>"
                              title="Eliminar">
                        <i class="fas fa-trash"></i>
                      </button>
                    <?php endif; ?>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>

        <div class="d-flex justify-content-end mt-3">
          <button class="btn btn-success" id="btnGuardarTodo">
            <i class="fas fa-save me-1"></i>Guardar Todo
          </button>
        </div>
      <?php endif; ?>
    </div>
  </div>
  <?php endif; ?>
</div>

<!-- Toast container -->
<div id="toasts" class="position-fixed top-0 end-0 p-3"></div>

<script>
document.addEventListener('DOMContentLoaded', function () {
  const toastContainer = document.getElementById('toasts');
  const fecha = <?= json_encode($fecha) ?>;

  function showToast(msg, type) {
    const el = document.createElement('div');
    el.className = 'toast align-items-center text-bg-' + (type === 'error' ? 'danger' : 'success') + ' border-0';
    el.setAttribute('role', 'alert');
    el.innerHTML = '<div class="d-flex"><div class="toast-body">' + msg + '</div><button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button></div>';
    toastContainer.appendChild(el);
    const t = new bootstrap.Toast(el, {delay: 3000});
    t.show();
    el.addEventListener('hidden.bs.toast', () => el.remove());
  }

  async function guardarAsistencia(idInscripcion) {
    const checked = document.querySelector('input[name="asistio_' + idInscripcion + '"]:checked');
    if (!checked) {
      showToast('Seleccione un estado de asistencia.', 'error');
      return false;
    }

    const obs = document.querySelector('.obs-input[data-id-inscripcion="' + idInscripcion + '"]');
    const payload = {
      id_inscripcion: idInscripcion,
      fecha: fecha,
      asistio: checked.value,
      observaciones: obs ? obs.value : ''
    };

    try {
      const resp = await fetch('<?= url('/asistencias/guardar') ?>', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify(payload)
      });
      const result = await resp.json();
      return result.success;
    } catch (e) {
      console.error(e);
      return false;
    }
  }

  // Guardar individual
  document.querySelectorAll('.btn-guardar').forEach(function (btn) {
    btn.addEventListener('click', async function () {
      const id = this.getAttribute('data-id-inscripcion');
      const ok = await guardarAsistencia(id);
      showToast(ok ? 'Asistencia guardada.' : 'Error al guardar.', ok ? 'success' : 'error');
      if (ok) setTimeout(() => location.reload(), 500);
    });
  });

  // Guardar todos
  const btnTodo = document.getElementById('btnGuardarTodo');
  if (btnTodo) {
    btnTodo.addEventListener('click', async function () {
      const rows = document.querySelectorAll('tr[data-id-inscripcion]');
      let ok = true;
      for (const row of rows) {
        const id = row.getAttribute('data-id-inscripcion');
        const result = await guardarAsistencia(id);
        if (!result) ok = false;
      }
      showToast(ok ? 'Todas las asistencias guardadas.' : 'Algunas asistencias fallaron.', ok ? 'success' : 'error');
      if (ok) setTimeout(() => location.reload(), 500);
    });
  }

  // Eliminar
  document.querySelectorAll('.btn-eliminar').forEach(function (btn) {
    btn.addEventListener('click', async function () {
      if (!confirm('¿Eliminar este registro de asistencia?')) return;
      const id = this.getAttribute('data-id-asistencia');
      try {
        const resp = await fetch('<?= url('/asistencias/eliminar') ?>', {
          method: 'DELETE',
          headers: {'Content-Type': 'application/json'},
          body: JSON.stringify({id_asistencia: id})
        });
        const result = await resp.json();
        showToast(result.message, result.success ? 'success' : 'error');
        if (result.success) setTimeout(() => location.reload(), 500);
      } catch (e) {
        showToast('Error al eliminar.', 'error');
      }
    });
  });
});
</script>
<?php
$content = ob_get_clean();
require_once appPath('cliente/layouts/AdminLayout.php');
