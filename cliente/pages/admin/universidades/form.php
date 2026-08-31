<?php
declare(strict_types=1);
if (
  !isset($_SESSION['usuario']) ||
  !in_array($_SESSION['tipo_persona'], ['Administrativo', 'Encargado'])
) {
  header("Location: " . url('/login-admin'));
  exit();
}
$esEdicion = !empty($universidad);
$pageTitle = $esEdicion ? 'Editar Universidad' : 'Nueva Universidad';
$pageStyles = [
  'cliente/assets/css/universidades.css',
];
ob_start();
?>
<div class="container-fluid py-3 universidades-page">
  <div class="page-head d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
    <div>
      <h3 class="mb-0">
        <i class="fas fa-<?= $esEdicion ? 'edit' : 'plus' ?> me-2"></i>
        <?= $esEdicion ? 'Editar Universidad' : 'Registro de Universidad' ?>
      </h3>
    </div>
    <a href="<?= url('/universidades') ?>" class="btn btn-outline-secondary">
      <i class="fas fa-arrow-left me-2"></i>Volver
    </a>
  </div>

  <div class="card">
    <div class="card-header">
      <h4><i class="fas fa-university"></i><?= $esEdicion ? 'Editar' : 'Información de la Universidad' ?></h4>
    </div>
    <div class="card-body">
      <form id="formUniversidad" novalidate>
        <input type="hidden" id="id_universidad" value="<?= $esEdicion ? (int) $universidad['id_universidad'] : '' ?>">

        <div class="row">
          <div class="col-md-8 mb-3">
            <label for="nombre" class="form-label">Nombre<span class="required-star">*</span></label>
            <input type="text" class="form-control" id="nombre" required
                   value="<?= $esEdicion ? htmlspecialchars($universidad['nombre']) : '' ?>"
                   placeholder="Ej: Universidad Salesiana de Bolivia">
          </div>
          <div class="col-md-4 mb-3">
            <label for="sigla" class="form-label">Sigla</label>
            <input type="text" class="form-control" id="sigla"
                   value="<?= $esEdicion ? htmlspecialchars($universidad['sigla'] ?? '') : '' ?>"
                   placeholder="Ej: USB">
          </div>
        </div>

        <div class="row">
          <div class="col-md-4 mb-3">
            <label for="ciudad" class="form-label">Ciudad<span class="required-star">*</span></label>
            <input type="text" class="form-control" id="ciudad" required
                   value="<?= $esEdicion ? htmlspecialchars($universidad['ciudad']) : '' ?>"
                   placeholder="Ej: La Paz">
          </div>
          <div class="col-md-8 mb-3">
            <label for="direccion" class="form-label">Dirección<span class="required-star">*</span></label>
            <input type="text" class="form-control" id="direccion" required
                   value="<?= $esEdicion ? htmlspecialchars($universidad['direccion']) : '' ?>"
                   placeholder="Ej: Av. Achachicala N° 500">
          </div>
        </div>

        <div class="row">
          <div class="col-md-4 mb-3">
            <label for="telefono" class="form-label">Teléfono</label>
            <input type="text" class="form-control" id="telefono"
                   value="<?= $esEdicion ? htmlspecialchars($universidad['telefono'] ?? '') : '' ?>"
                   placeholder="Ej: +591 2 1234567">
          </div>
          <div class="col-md-4 mb-3">
            <label for="correo" class="form-label">Correo</label>
            <input type="email" class="form-control" id="correo"
                   value="<?= $esEdicion ? htmlspecialchars($universidad['correo'] ?? '') : '' ?>"
                   placeholder="Ej: info@universidad.edu.bo">
          </div>
          <div class="col-md-4 mb-3">
            <label for="sitio_web" class="form-label">Sitio Web</label>
            <input type="text" class="form-control" id="sitio_web"
                   value="<?= $esEdicion ? htmlspecialchars($universidad['sitio_web'] ?? '') : '' ?>"
                   placeholder="Ej: www.universidad.edu.bo">
          </div>
        </div>

        <div class="row">
          <div class="col-md-4 mb-3">
            <label for="estado" class="form-label">Estado</label>
            <select id="estado" class="form-select">
              <?php
              $estadoActual = $esEdicion ? ($universidad['estado'] ?? 'Activo') : 'Activo';
              foreach (['Activo', 'Inactivo'] as $opcion) {
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
            <i class="bi bi-check-circle me-2"></i><?= $esEdicion ? 'Actualizar Universidad' : 'Registrar Universidad' ?>
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

    const form = document.getElementById('formUniversidad');
    const esEdicion = document.getElementById('id_universidad').value !== '';

    form.addEventListener('submit', async function (e) {
      e.preventDefault();
      if (!form.checkValidity()) {
        form.reportValidity();
        return;
      }

      const payload = {
        nombre: document.getElementById('nombre').value,
        sigla: document.getElementById('sigla').value,
        ciudad: document.getElementById('ciudad').value,
        direccion: document.getElementById('direccion').value,
        telefono: document.getElementById('telefono').value,
        correo: document.getElementById('correo').value,
        sitio_web: document.getElementById('sitio_web').value,
        estado: document.getElementById('estado').value
      };

      const url = esEdicion
        ? '<?= url('/universidades/actualizar') ?>'
        : '<?= url('/universidades/guardar') ?>';
      const method = esEdicion ? 'PUT' : 'POST';

      if (esEdicion) payload.id_universidad = document.getElementById('id_universidad').value;

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
        setTimeout(() => window.location.href = '<?= url('/universidades') ?>', 600);
      } catch (error) {
        console.error(error);
        showToast('Error al guardar la universidad.', 'error');
      }
    });
  });
</script>
<?php
$content = ob_get_clean();
require_once appPath('cliente/layouts/AdminLayout.php');