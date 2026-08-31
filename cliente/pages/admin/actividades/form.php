<?php
declare(strict_types=1);
if (
  !isset($_SESSION['usuario']) ||
  !in_array($_SESSION['tipo_persona'], ['Administrativo', 'Encargado'])
) {
  header("Location: " . url('/login-admin'));
  exit();
}
$esEdicion = !empty($actividad);
$pageTitle = $esEdicion ? 'Editar Actividad' : 'Nueva Actividad';
$pageStyles = [
  'cliente/assets/css/actividades.css',
];
ob_start();

// Valores actuales para rellenar
$diasSeleccionados = $esEdicion && !empty($actividad['dias_semana'])
  ? explode(',', $actividad['dias_semana'])
  : [];
?>
<div class="container-fluid py-3 actividades-page">
  <div class="page-head">
    <div>
      <h3>
        <i class="fas fa-<?= $esEdicion ? 'edit' : 'plus' ?> me-2"></i>
        <?= $esEdicion ? 'Editar Actividad' : 'Registro de Actividad' ?>
      </h3>
      <p>Complete la información solicitada</p>
    </div>
    <a href="<?= url('/actividades') ?>" class="btn btn-outline-secondary">
      <i class="fas fa-arrow-left me-2"></i>Volver
    </a>
  </div>

  <div class="card">
    <div class="card-header">
      <h4><i class="fas fa-tasks"></i><?= $esEdicion ? 'Editar' : 'Información de la Actividad' ?></h4>
    </div>
    <div class="card-body">
      <form id="formActividad" novalidate>
        <input type="hidden" id="id_actividad" value="<?= $esEdicion ? (int) $actividad['id_actividad'] : '' ?>">

        <div class="section-title"><i class="bi bi-info-circle-fill"></i>Información General</div>
        <div class="row">
          <div class="col-md-6 mb-3">
            <label for="nombre_actividad" class="form-label">Nombre de la Actividad<span class="required-star">*</span></label>
            <input type="text" class="form-control" id="nombre_actividad" required
                   value="<?= $esEdicion ? htmlspecialchars($actividad['nombre_actividad']) : '' ?>"
                   placeholder="Ej: Primera Comunión">
          </div>
          <div class="col-md-6 mb-3">
            <label for="tipo_actividad" class="form-label">Tipo de Actividad</label>
            <input type="text" class="form-control" id="tipo_actividad"
                   value="<?= $esEdicion ? htmlspecialchars($actividad['tipo_actividad'] ?? '') : '' ?>"
                   placeholder="Ej: Sacramento, Retiro, Formación...">
          </div>
        </div>

        <div class="row">
          <div class="col-md-12 mb-3">
            <label for="descripcion" class="form-label">Descripción</label>
            <textarea class="form-control" id="descripcion" rows="3"
                      placeholder="Descripción detallada de la actividad..."><?= $esEdicion ? htmlspecialchars($actividad['descripcion'] ?? '') : '' ?></textarea>
          </div>
        </div>

        <div class="section-title mt-3"><i class="bi bi-calendar-check"></i>Programación</div>
        <div class="row">
          <div class="col-md-3 mb-3">
            <label for="fecha_inicio" class="form-label">Fecha de Inicio<span class="required-star">*</span></label>
            <input type="date" class="form-control" id="fecha_inicio" required
                   value="<?= $esEdicion ? htmlspecialchars($actividad['fecha_inicio'] ?? '') : '' ?>">
          </div>
          <div class="col-md-3 mb-3">
            <label for="fecha_fin" class="form-label">Fecha de Fin<span class="required-star">*</span></label>
            <input type="date" class="form-control" id="fecha_fin" required
                   value="<?= $esEdicion ? htmlspecialchars($actividad['fecha_fin'] ?? '') : '' ?>">
          </div>
          <div class="col-md-3 mb-3">
            <label for="hora_inicio" class="form-label">Hora de Inicio<span class="required-star">*</span></label>
            <input type="time" class="form-control" id="hora_inicio" required
                   value="<?= $esEdicion && !empty($actividad['hora_inicio']) ? substr($actividad['hora_inicio'], 0, 5) : '' ?>">
          </div>
          <div class="col-md-3 mb-3">
            <label for="hora_fin" class="form-label">Hora de Fin<span class="required-star">*</span></label>
            <input type="time" class="form-control" id="hora_fin" required
                   value="<?= $esEdicion && !empty($actividad['hora_fin']) ? substr($actividad['hora_fin'], 0, 5) : '' ?>">
          </div>
        </div>

        <div class="row">
          <div class="col-md-6 mb-3">
            <label for="duracion" class="form-label">Duración<span class="required-star">*</span></label>
            <input type="text" class="form-control" id="duracion" required
                   value="<?= $esEdicion ? htmlspecialchars($actividad['duracion'] ?? '') : '' ?>"
                   placeholder="Ej: 2 meses, 3 días, 4 semanas">
          </div>
          <div class="col-md-6 mb-3">
            <label class="form-label d-block">Días de la Semana</label>
            <div class="d-flex flex-wrap gap-3 pt-1">
              <?php foreach (($diasSemana ?? []) as $dia): ?>
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" name="dias_semana"
                         id="dia_<?= $dia ?>" value="<?= $dia ?>"
                         <?= in_array($dia, $diasSeleccionados, true) ? 'checked' : '' ?>>
                  <label class="form-check-label" for="dia_<?= $dia ?>"><?= $dia ?></label>
                </div>
              <?php endforeach; ?>
            </div>
          </div>
        </div>

        <div class="section-title mt-3"><i class="bi bi-clipboard-check"></i>Detalles</div>
        <div class="row">
          <div class="col-md-4 mb-3">
            <label for="costo" class="form-label">Costo (Bs)<span class="required-star">*</span></label>
            <input type="number" step="0.01" min="0" class="form-control" id="costo" required
                   value="<?= $esEdicion ? htmlspecialchars($actividad['costo'] ?? '0.00') : '0.00' ?>">
          </div>
          <div class="col-md-4 mb-3">
            <label for="cupo_maximo" class="form-label">Cupo Máximo</label>
            <input type="number" min="0" class="form-control" id="cupo_maximo"
                   value="<?= $esEdicion && $actividad['cupo_maximo'] !== null ? (int) $actividad['cupo_maximo'] : '' ?>">
          </div>
          <div class="col-md-4 mb-3">
            <label for="cupo_disponible" class="form-label">Cupo Disponible</label>
            <input type="number" min="0" class="form-control" id="cupo_disponible"
                   value="<?= $esEdicion && $actividad['cupo_disponible'] !== null ? (int) $actividad['cupo_disponible'] : '' ?>">
          </div>
        </div>

        <div class="row">
          <div class="col-md-6 mb-3">
            <label for="requisitos" class="form-label">Requisitos</label>
            <textarea class="form-control" id="requisitos" rows="3"
                      placeholder="Requisitos para participar..."><?= $esEdicion ? htmlspecialchars($actividad['requisitos'] ?? '') : '' ?></textarea>
          </div>
          <div class="col-md-6 mb-3">
            <label for="id_evento" class="form-label">Evento Asociado<span class="required-star">*</span></label>
            <select id="id_evento" class="form-select" required>
              <option value="" disabled <?= !$esEdicion ? 'selected' : '' ?>>Seleccione un evento...</option>
              <?php foreach (($eventos ?? []) as $ev): ?>
                <option value="<?= (int) $ev['id_evento'] ?>"
                  <?= $esEdicion && (int) $actividad['id_evento'] === (int) $ev['id_evento'] ? 'selected' : '' ?>>
                  <?= htmlspecialchars($ev['nombre_evento']) ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>

        <div class="row">
          <div class="col-md-6 mb-3">
            <label for="estado" class="form-label">Estado</label>
            <select id="estado" class="form-select">
              <?php
              $estadoActual = $esEdicion ? ($actividad['estado'] ?? 'Activo') : 'Activo';
              foreach (['Activo', 'Cancelado', 'Completado', 'En espera'] as $opcion) {
                $sel = $estadoActual === $opcion ? ' selected' : '';
                echo '<option value="' . $opcion . '"' . $sel . '>' . $opcion . '</option>';
              }
              ?>
            </select>
          </div>
        </div>

        <div class="btn-group-actions mt-4">
          <button type="reset" class="btn btn-outline-secondary px-4"><i class="bi bi-eraser me-2"></i>Limpiar</button>
          <button type="submit" class="btn btn-primary px-4">
            <i class="bi bi-check-circle me-2"></i><?= $esEdicion ? 'Actualizar Actividad' : 'Registrar Actividad' ?>
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Toast container -->
<div id="toasts" class="position-fixed top-0 end-0 p-3"></div>

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

    const form = document.getElementById('formActividad');
    const esEdicion = document.getElementById('id_actividad').value !== '';

    form.addEventListener('submit', async function (e) {
      e.preventDefault();
      if (!form.checkValidity()) {
        form.reportValidity();
        return;
      }

      const diasSeleccionados = Array.from(
        document.querySelectorAll('input[name="dias_semana"]:checked')
      ).map(cb => cb.value);

      const payload = {
        nombre_actividad: document.getElementById('nombre_actividad').value,
        tipo_actividad: document.getElementById('tipo_actividad').value,
        descripcion: document.getElementById('descripcion').value,
        fecha_inicio: document.getElementById('fecha_inicio').value,
        fecha_fin: document.getElementById('fecha_fin').value,
        hora_inicio: document.getElementById('hora_inicio').value,
        hora_fin: document.getElementById('hora_fin').value,
        duracion: document.getElementById('duracion').value,
        costo: document.getElementById('costo').value,
        cupo_maximo: document.getElementById('cupo_maximo').value,
        cupo_disponible: document.getElementById('cupo_disponible').value,
        requisitos: document.getElementById('requisitos').value,
        id_evento: document.getElementById('id_evento').value,
        estado: document.getElementById('estado').value,
        dias_semana: diasSeleccionados
      };

      const url = esEdicion
        ? '<?= url('/actividades/actualizar') ?>'
        : '<?= url('/actividades/guardar') ?>';
      const method = esEdicion ? 'PUT' : 'POST';

      if (esEdicion) payload.id_actividad = document.getElementById('id_actividad').value;

      try {
        const resp = await fetch(url, {
          method: method,
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify(payload)
        });
        const result = await resp.json();
        if (!result.success) {
          showToast(result.message, 'error');
          return;
        }
        showToast(result.message, 'success');
        setTimeout(() => window.location.href = '<?= url('/actividades') ?>', 600);
      } catch (error) {
        console.error(error);
        showToast('Error al guardar la actividad.', 'error');
      }
    });
  });
</script>
<?php
$content = ob_get_clean();
require_once appPath('cliente/layouts/AdminLayout.php');
