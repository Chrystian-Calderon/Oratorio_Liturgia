<?php
declare(strict_types=1);
if (
  !isset($_SESSION['usuario']) ||
  !in_array($_SESSION['tipo_persona'], ['Administrativo', 'Encargado'])
) {
  header("Location: " . url('/login-admin'));
  exit();
}
$esEdicion = !empty($evento);
$pageTitle = $esEdicion ? 'Editar Evento' : 'Nuevo Evento';
$pageStyles = [
  'cliente/assets/css/eventos.css',
];
ob_start();
?>
<div class="container-fluid py-3 eventos-page">
  <div class="page-head">
    <div>
      <h3>
        <i class="fas fa-<?= $esEdicion ? 'edit' : 'calendar-plus' ?> me-2"></i>
        <?= $esEdicion ? 'Editar Evento' : 'Registro de Evento' ?>
      </h3>
      <p>Complete la información solicitada</p>
    </div>
    <a href="<?= url('/eventos') ?>" class="btn btn-outline-secondary">
      <i class="fas fa-arrow-left me-2"></i>Volver
    </a>
  </div>

  <div class="card">
    <div class="card-header">
      <h4><i class="fas fa-calendar-event"></i><?= $esEdicion ? 'Editar' : 'Información del Evento' ?></h4>
    </div>
    <div class="card-body">
      <div class="section-title">
        <i class="bi bi-info-circle-fill"></i>Información General
      </div>

      <form id="formEvento" novalidate>
        <input type="hidden" id="id_evento" value="<?= $esEdicion ? (int) $evento['id_evento'] : '' ?>">

        <div class="row">
          <div class="col-md-12 mb-3">
            <label for="nombre_evento" class="form-label">
              <i class="bi bi-card-heading me-1"></i>Nombre del Evento<span class="required-star">*</span>
            </label>
            <input type="text" class="form-control" id="nombre_evento" required
                   value="<?= $esEdicion ? htmlspecialchars($evento['nombre_evento']) : '' ?>"
                   placeholder="Ej: Retiro Espiritual 2026">
          </div>
        </div>

        <div class="row">
          <div class="col-md-12 mb-3">
            <label for="descripcion" class="form-label">
              <i class="bi bi-file-text me-1"></i>Descripción
            </label>
            <textarea class="form-control" id="descripcion" rows="4"
                      placeholder="Ingrese una descripción detallada del evento..."><?= $esEdicion ? htmlspecialchars($evento['descripcion'] ?? '') : '' ?></textarea>
          </div>
        </div>

        <div class="section-title mt-3">
          <i class="bi bi-calendar-check"></i>Programación del Evento
        </div>

        <div class="row">
          <div class="col-md-6 mb-3">
            <label for="fecha_evento" class="form-label">
              <i class="bi bi-calendar3 me-1"></i>Fecha del Evento<span class="required-star">*</span>
            </label>
            <input type="date" class="form-control" id="fecha_evento"
                   value="<?= $esEdicion ? htmlspecialchars($evento['fecha_evento'] ?? '') : '' ?>" required>
          </div>
          <div class="col-md-6 mb-3">
            <label for="hora_evento" class="form-label">
              <i class="bi bi-clock me-1"></i>Hora del Evento
            </label>
            <input type="time" class="form-control" id="hora_evento"
                   value="<?= $esEdicion ? htmlspecialchars($evento['hora_evento'] ?? '') : '' ?>">
          </div>
          <div class="col-md-6 mb-3">
            <label for="lugar" class="form-label">
              <i class="bi bi-geo-alt me-1"></i>Lugar
            </label>
            <input type="text" class="form-control" id="lugar"
                   value="<?= $esEdicion ? htmlspecialchars($evento['lugar'] ?? '') : '' ?>"
                   placeholder="Ej: Iglesia San Juan">
          </div>
          <div class="col-md-6 mb-3">
            <label for="estado" class="form-label">
              <i class="bi bi-check-circle me-1"></i>Estado<span class="required-star">*</span>
            </label>
            <select id="estado" class="form-select" required>
              <?php
              $estadoActual = $esEdicion ? ($evento['estado'] ?? 'Activo') : 'Activo';
              foreach (['Activo', 'Inactivo', 'Cancelado'] as $opcion) {
                $sel = $estadoActual === $opcion ? ' selected' : '';
                echo '<option value="' . $opcion . '"' . $sel . '>' . $opcion . '</option>';
              }
              ?>
            </select>
          </div>
        </div>

        <div class="btn-group-actions mt-4">
          <button type="reset" class="btn btn-outline-secondary px-4">
            <i class="bi bi-eraser me-2"></i>Limpiar
          </button>
          <button type="submit" class="btn btn-primary px-4">
            <i class="bi bi-check-circle me-2"></i><?= $esEdicion ? 'Actualizar Evento' : 'Registrar Evento' ?>
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

    // Evitar fechas pasadas al crear
    const fechaEvento = document.getElementById('fecha_evento');
    const esEdicion = document.getElementById('id_evento').value !== '';
    if (!esEdicion) {
      const hoy = new Date();
      const aa = hoy.getFullYear();
      const mm = String(hoy.getMonth() + 1).padStart(2, '0');
      const dd = String(hoy.getDate()).padStart(2, '0');
      fechaEvento.setAttribute('min', `${aa}-${mm}-${dd}`);
    }

    const form = document.getElementById('formEvento');

    form.addEventListener('submit', async function (e) {
      e.preventDefault();
      if (!form.checkValidity()) {
        form.reportValidity();
        return;
      }

      const payload = {
        nombre_evento: document.getElementById('nombre_evento').value,
        descripcion: document.getElementById('descripcion').value,
        fecha_evento: fechaEvento.value || null,
        hora_evento: document.getElementById('hora_evento').value || null,
        lugar: document.getElementById('lugar').value || null,
        estado: document.getElementById('estado').value
      };

      const url = esEdicion
        ? '<?= url('/eventos/actualizar') ?>'
        : '<?= url('/eventos/guardar') ?>';
      const method = esEdicion ? 'PUT' : 'POST';

      if (esEdicion) payload.id_evento = document.getElementById('id_evento').value;

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
        setTimeout(() => window.location.href = '<?= url('/eventos') ?>', 600);
      } catch (error) {
        console.error(error);
        showToast('Error al guardar el evento.', 'error');
      }
    });
  });
</script>
<?php
$content = ob_get_clean();
require_once appPath('cliente/layouts/AdminLayout.php');
