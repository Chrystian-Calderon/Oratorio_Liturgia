<?php
declare(strict_types=1);
if (
  !isset($_SESSION['usuario']) ||
  !in_array($_SESSION['tipo_persona'], ['Administrativo', 'Encargado'])
) {
  header("Location: " . url('/login-admin'));
  exit();
}
$esEdicion = !empty($persona);
$pageTitle = $esEdicion ? 'Editar Persona' : 'Nueva Persona';
$pageStyles = [
  'cliente/assets/css/personas.css',
];
ob_start();
?>
<div class="container-fluid py-3 personas-page">
  <div class="page-head">
    <div>
      <h3>
        <i class="fas fa-<?= $esEdicion ? 'edit' : 'plus' ?> me-2"></i>
        <?= $esEdicion ? 'Editar Persona' : 'Registro de Persona' ?>
      </h3>
      <p>Complete la información solicitada</p>
    </div>
    <a href="<?= url('/personas') ?>" class="btn btn-outline-secondary">
      <i class="fas fa-arrow-left me-2"></i>Volver
    </a>
  </div>

  <div class="card">
    <div class="card-header">
      <h4><i class="fas fa-user"></i><?= $esEdicion ? 'Editar' : 'Información de la Persona' ?></h4>
    </div>
    <div class="card-body">
      <form id="formPersona" novalidate>
        <input type="hidden" id="id_persona" value="<?= $esEdicion ? (int) $persona['id_persona'] : '' ?>">

        <div class="section-title"><i class="bi bi-info-circle-fill"></i>Datos Personales</div>
        <div class="row">
          <div class="col-md-3 mb-3">
            <label for="ci" class="form-label">Cédula de Identidad<span class="required-star">*</span></label>
            <input type="text" class="form-control" id="ci" required
                   value="<?= $esEdicion ? htmlspecialchars($persona['ci']) : '' ?>"
                   placeholder="Ej: 1234567">
          </div>
          <div class="col-md-5 mb-3">
            <label for="nombres" class="form-label">Nombres<span class="required-star">*</span></label>
            <input type="text" class="form-control" id="nombres" required
                   value="<?= $esEdicion ? htmlspecialchars($persona['nombres']) : '' ?>"
                   placeholder="Ej: Juan Carlos">
          </div>
          <div class="col-md-4 mb-3">
            <label for="apellidos" class="form-label">Apellidos<span class="required-star">*</span></label>
            <input type="text" class="form-control" id="apellidos" required
                   value="<?= $esEdicion ? htmlspecialchars($persona['apellidos']) : '' ?>"
                   placeholder="Ej: Pérez López">
          </div>
        </div>

        <div class="row">
          <div class="col-md-3 mb-3">
            <label for="genero" class="form-label">Género</label>
            <select id="genero" class="form-select">
              <option value="">No especificado</option>
              <?php
              $generoActual = $esEdicion ? ($persona['genero'] ?? '') : '';
              foreach (['Masculino', 'Femenino', 'Otro', 'Prefiero no decir'] as $g) {
                $sel = $generoActual === $g ? ' selected' : '';
                echo '<option value="' . $g . '"' . $sel . '>' . $g . '</option>';
              }
              ?>
            </select>
          </div>
          <div class="col-md-4 mb-3">
            <label for="correo" class="form-label">Correo Electrónico<span class="required-star">*</span></label>
            <input type="email" class="form-control" id="correo" required
                   value="<?= $esEdicion ? htmlspecialchars($persona['correo']) : '' ?>"
                   placeholder="Ej: correo@ejemplo.com">
          </div>
          <div class="col-md-3 mb-3">
            <label for="telefono" class="form-label">Teléfono</label>
            <input type="text" class="form-control" id="telefono"
                   value="<?= $esEdicion ? htmlspecialchars($persona['telefono'] ?? '') : '' ?>"
                   placeholder="Ej: 77654321">
          </div>
          <div class="col-md-2 mb-3">
            <label for="estado" class="form-label">Estado</label>
            <select id="estado" class="form-select">
              <?php
              $estadoActual = $esEdicion ? ($persona['estado'] ?? 'Activo') : 'Activo';
              foreach (['Activo', 'Inactivo', 'Suspendido'] as $e) {
                $sel = $estadoActual === $e ? ' selected' : '';
                echo '<option value="' . $e . '"' . $sel . '>' . $e . '</option>';
              }
              ?>
            </select>
          </div>
        </div>

        <div class="row">
          <div class="col-md-12 mb-3">
            <label for="direccion" class="form-label">Dirección</label>
            <input type="text" class="form-control" id="direccion"
                   value="<?= $esEdicion ? htmlspecialchars($persona['direccion'] ?? '') : '' ?>"
                   placeholder="Ej: Calle Principal #123, Zona Centro">
          </div>
        </div>

        <div class="section-title mt-3"><i class="bi bi-shield-lock"></i>Autenticación</div>
        <div class="row">
          <div class="col-md-6 mb-3">
            <label for="password" class="form-label">
              Contraseña<?= $esEdicion ? '' : '<span class="required-star">*</span>' ?>
            </label>
            <input type="password" class="form-control" id="password"
                   <?= $esEdicion ? '' : 'required' ?>
                   placeholder="<?= $esEdicion ? 'Dejar vacío para mantener actual' : 'Mínimo 6 caracteres' ?>">
            <?php if ($esEdicion): ?>
              <div class="form-text">Dejar vacío si no desea cambiar la contraseña.</div>
            <?php endif; ?>
          </div>
        </div>

        <div class="section-title mt-3"><i class="bi bi-building"></i>Información Institucional</div>
        <div class="row">
          <div class="col-md-6 mb-3">
            <label for="tipo_persona" class="form-label">Tipo de Persona</label>
            <select id="tipo_persona" class="form-select">
              <?php
              $tipoActual = $esEdicion ? ($persona['tipo_persona'] ?? 'Estudiante') : 'Estudiante';
              foreach (['Estudiante', 'Docente', 'Voluntario', 'Sacerdote', 'Administrativo', 'Externo'] as $t) {
                $sel = $tipoActual === $t ? ' selected' : '';
                echo '<option value="' . $t . '"' . $sel . '>' . $t . '</option>';
              }
              ?>
            </select>
          </div>
          <div class="col-md-6 mb-3">
            <label for="id_universidad" class="form-label">Universidad</label>
            <select id="id_universidad" class="form-select">
              <option value="">Sin universidad</option>
              <?php foreach (($universidades ?? []) as $u): ?>
                <option value="<?= (int) $u['id_universidad'] ?>"
                  <?= $esEdicion && isset($persona['id_universidad']) && (int) $persona['id_universidad'] === (int) $u['id_universidad'] ? 'selected' : '' ?>>
                  <?= htmlspecialchars($u['nombre']) ?> (<?= htmlspecialchars($u['sigla']) ?>)
                </option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>

        <div class="row">
          <div class="col-md-6 mb-3">
            <label for="rol" class="form-label">Rol del Sistema</label>
            <select id="rol" class="form-select">
              <option value="">Sin rol asignado</option>
              <?php
              $rolActual = $esEdicion ? ($persona['rol'] ?? '') : '';
              foreach (($roles ?? []) as $r) {
                $sel = $rolActual === $r['rol'] ? ' selected' : '';
                echo '<option value="' . htmlspecialchars($r['rol']) . '"' . $sel . '>' . htmlspecialchars($r['rol']) . '</option>';
              }
              ?>
            </select>
            <div class="form-text">Rol asignado en el sistema de usuarios.</div>
          </div>
        </div>

        <div class="btn-group-actions mt-4">
          <button type="reset" class="btn btn-outline-secondary px-4"><i class="bi bi-eraser me-2"></i>Limpiar</button>
          <button type="submit" class="btn btn-primary px-4">
            <i class="bi bi-check-circle me-2"></i><?= $esEdicion ? 'Actualizar Persona' : 'Registrar Persona' ?>
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

    const form = document.getElementById('formPersona');
    const esEdicion = document.getElementById('id_persona').value !== '';

    form.addEventListener('submit', async function (e) {
      e.preventDefault();
      if (!form.checkValidity()) {
        form.reportValidity();
        return;
      }

      const payload = {
        ci: document.getElementById('ci').value,
        nombres: document.getElementById('nombres').value,
        apellidos: document.getElementById('apellidos').value,
        genero: document.getElementById('genero').value,
        correo: document.getElementById('correo').value,
        telefono: document.getElementById('telefono').value,
        direccion: document.getElementById('direccion').value,
        password: document.getElementById('password').value,
        tipo_persona: document.getElementById('tipo_persona').value,
        id_universidad: document.getElementById('id_universidad').value,
        rol: document.getElementById('rol').value,
        estado: document.getElementById('estado').value
      };

      const url = esEdicion
        ? '<?= url('/personas/actualizar') ?>'
        : '<?= url('/personas/guardar') ?>';
      const method = esEdicion ? 'PUT' : 'POST';

      if (esEdicion) payload.id_persona = document.getElementById('id_persona').value;

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
        setTimeout(() => window.location.href = '<?= url('/personas') ?>', 600);
      } catch (error) {
        console.error(error);
        showToast('Error al guardar la persona.', 'error');
      }
    });
  });
</script>
<?php
$content = ob_get_clean();
require_once appPath('cliente/layouts/AdminLayout.php');