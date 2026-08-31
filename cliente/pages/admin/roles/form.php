<?php
declare(strict_types=1);
if (
  !isset($_SESSION['usuario']) ||
  !in_array($_SESSION['tipo_persona'], ['Administrativo', 'Encargado'])
) {
  header("Location: " . url('/login-admin'));
  exit();
}
$esEdicion = !empty($rol);
$pageTitle = $esEdicion ? 'Editar Rol' : 'Nuevo Rol';
$pageStyles = [
  'cliente/assets/css/roles.css',
];
ob_start();
?>
<div class="container-fluid py-3 roles-page">
  <div class="page-head d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
    <div>
      <h3 class="mb-0">
        <i class="fas fa-<?= $esEdicion ? 'edit' : 'plus' ?> me-2"></i>
        <?= $esEdicion ? 'Editar Rol' : 'Registro de Rol' ?>
      </h3>
    </div>
    <a href="<?= url('/roles') ?>" class="btn btn-outline-secondary">
      <i class="fas fa-arrow-left me-2"></i>Volver
    </a>
  </div>

  <div class="card">
    <div class="card-header">
      <h4><i class="fas fa-user-cog"></i><?= $esEdicion ? 'Editar' : 'Información del Rol' ?></h4>
    </div>
    <div class="card-body">
      <form id="formRol" novalidate>
        <input type="hidden" id="id_usuario" value="<?= $esEdicion ? (int) $rol['id_usuario'] : '' ?>">

        <div class="row">
          <div class="col-md-6 mb-3">
            <label for="rol" class="form-label">Rol<span class="required-star">*</span></label>
            <select id="rol" class="form-select" required>
              <option value="" disabled <?= !$esEdicion ? 'selected' : '' ?>>Seleccione un rol...</option>
              <?php
              $rolActual = $esEdicion ? ($rol['rol'] ?? '') : '';
              foreach (['Administrador', 'Coordinador', 'Estudiante', 'Docente', 'Voluntario', 'Sacerdote', 'Externo'] as $opcion) {
                $sel = $rolActual === $opcion ? ' selected' : '';
                echo '<option value="' . $opcion . '"' . $sel . '>' . $opcion . '</option>';
              }
              ?>
            </select>
          </div>

          <div class="col-md-6 mb-3">
            <label for="estado" class="form-label">Estado</label>
            <select id="estado" class="form-select">
              <?php
              $estadoActual = $esEdicion ? ($rol['estado'] ?? 'Activo') : 'Activo';
              foreach (['Activo', 'Inactivo', 'Suspendido'] as $opcion) {
                $sel = $estadoActual === $opcion ? ' selected' : '';
                echo '<option value="' . $opcion . '"' . $sel . '>' . $opcion . '</option>';
              }
              ?>
            </select>
          </div>
        </div>

        <div class="row">
          <div class="col-md-12 mb-3">
            <label for="permisos" class="form-label">Permisos</label>
            <input type="text" class="form-control" id="permisos"
                   value="<?= $esEdicion ? htmlspecialchars($rol['permisos'] ?? '') : '' ?>"
                   placeholder="Ej: todos, o separados por coma">
          </div>
        </div>

        <div class="btn-group-actions mt-4">
          <button type="reset" class="btn btn-outline-secondary px-4"><i class="bi bi-eraser me-2"></i>Limpiar</button>
          <button type="submit" class="btn btn-primary px-4">
            <i class="bi bi-check-circle me-2"></i><?= $esEdicion ? 'Actualizar Rol' : 'Registrar Rol' ?>
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

    const form = document.getElementById('formRol');
    const esEdicion = document.getElementById('id_usuario').value !== '';

    form.addEventListener('submit', async function (e) {
      e.preventDefault();
      if (!form.checkValidity()) {
        form.reportValidity();
        return;
      }

      const payload = {
        rol: document.getElementById('rol').value,
        permisos: document.getElementById('permisos').value,
        estado: document.getElementById('estado').value
      };

      const url = esEdicion
        ? '<?= url('/roles/actualizar') ?>'
        : '<?= url('/roles/guardar') ?>';
      const method = esEdicion ? 'PUT' : 'POST';

      if (esEdicion) payload.id_usuario = document.getElementById('id_usuario').value;

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
        setTimeout(() => window.location.href = '<?= url('/roles') ?>', 600);
      } catch (error) {
        console.error(error);
        showToast('Error al guardar el rol.', 'error');
      }
    });
  });
</script>
<?php
$content = ob_get_clean();
require_once appPath('cliente/layouts/AdminLayout.php');