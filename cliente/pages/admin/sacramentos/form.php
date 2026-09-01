<?php
declare(strict_types=1);
if (
  !isset($_SESSION['usuario']) ||
  !in_array($_SESSION['tipo_persona'], ['Administrativo', 'Encargado'])
) {
  header("Location: " . url('/login-admin'));
  exit();
}
$esEdicion = !empty($sacramento);
$pageTitle = $esEdicion ? 'Editar Inscripción' : 'Nueva Inscripción';
$pageStyles = [
  'cliente/assets/css/sacramentos.css',
];
ob_start();
$sac = $sacramento;
?>
<div class="container-fluid py-3 sacramentos-page">
  <div class="page-head d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
    <div>
      <h3 class="mb-0">
        <i class="fas fa-<?= $esEdicion ? 'edit' : 'plus' ?> me-2"></i>
        <?= $esEdicion ? 'Editar Inscripción' : 'Registro de Inscripción Sacramental' ?>
      </h3>
    </div>
    <a href="<?= url('/sacramentos') ?>" class="btn btn-outline-secondary">
      <i class="fas fa-arrow-left me-2"></i>Volver
    </a>
  </div>

  <div class="card">
    <div class="card-header">
      <h4><i class="fas fa-baby"></i><?= $esEdicion ? 'Editar' : 'Información de la Inscripción' ?></h4>
    </div>
    <div class="card-body">
      <form id="formSacramento" novalidate>
        <input type="hidden" id="id_inscripcion" value="<?= $esEdicion ? (int) $sac['id_inscripcion'] : '' ?>">

        <div class="row">
          <div class="col-md-6 mb-3">
            <label for="sacramento" class="form-label">Sacramento<span class="required-star">*</span></label>
            <?php
            $sacramentoActual = $esEdicion ? ($sac['sacramento'] ?? '') : '';
            $sacramentosLista = ['Bautizo', 'Primera Comunión', 'Confirmación', 'Matrimonio', 'Penitencia', 'Unción de los Enfermos'];
            ?>
            <select id="sacramento" class="form-select" required>
              <option value="" disabled selected>Seleccione un sacramento...</option>
              <?php foreach ($sacramentosLista as $opc): ?>
                <option value="<?= $opc ?>" <?= $sacramentoActual === $opc ? 'selected' : '' ?>><?= $opc ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="col-md-6 mb-3">
            <label for="nombre_solicitante" class="form-label">Nombre del Solicitante<span class="required-star">*</span></label>
            <input type="text" class="form-control" id="nombre_solicitante" required
                   value="<?= $esEdicion ? htmlspecialchars($sac['nombre_solicitante']) : '' ?>"
                   placeholder="Ej: Raquel Milca Lanza Flores">
          </div>
        </div>

        <div class="row">
          <div class="col-md-4 mb-3">
            <label for="fecha_nacimiento" class="form-label">Fecha de Nacimiento<span class="required-star">*</span></label>
            <input type="date" class="form-control" id="fecha_nacimiento" required
                   value="<?= $esEdicion ? htmlspecialchars($sac['fecha_nacimiento']) : '' ?>">
          </div>
          <div class="col-md-4 mb-3">
            <label for="lugar_nacimiento" class="form-label">Lugar de Nacimiento<span class="required-star">*</span></label>
            <input type="text" class="form-control" id="lugar_nacimiento" required
                   value="<?= $esEdicion ? htmlspecialchars($sac['lugar_nacimiento']) : '' ?>"
                   placeholder="Ej: La Paz">
          </div>
          <div class="col-md-4 mb-3">
            <label for="telefono" class="form-label">Teléfono<span class="required-star">*</span></label>
            <input type="text" class="form-control" id="telefono" required
                   value="<?= $esEdicion ? htmlspecialchars($sac['telefono']) : '' ?>"
                   placeholder="Ej: 73736872">
          </div>
        </div>

        <div class="row">
          <div class="col-md-6 mb-3">
            <label for="email" class="form-label">Correo Electrónico<span class="required-star">*</span></label>
            <input type="email" class="form-control" id="email" required
                   value="<?= $esEdicion ? htmlspecialchars($sac['email']) : '' ?>"
                   placeholder="Ej: correo@ejemplo.com">
          </div>
        </div>

        <div class="row">
          <div class="col-md-6 mb-3">
            <label for="nombre_padre" class="form-label">Nombre del Padre <span class="required-star">*</span></label>
            <input type="text" class="form-control" id="nombre_padre" required
                   value="<?= $esEdicion ? htmlspecialchars($sac['nombre_padre'] ?? '') : '' ?>"
                   placeholder="Ej: Gustavo David Lanza Ramos">
          </div>
          <div class="col-md-6 mb-3">
            <label for="nombre_madre" class="form-label">Nombre de la Madre <span class="required-star">*</span></label>
            <input type="text" class="form-control" id="nombre_madre" required
                   value="<?= $esEdicion ? htmlspecialchars($sac['nombre_madre'] ?? '') : '' ?>"
                   placeholder="Ej: Sandra Marlene Flores Tapia">
          </div>
        </div>

        <div class="row">
          <div class="col-md-6 mb-3">
            <label for="nombre_padrino" class="form-label">Nombre del Padrino</label>
            <input type="text" class="form-control" id="nombre_padrino"
                   value="<?= $esEdicion ? htmlspecialchars($sac['nombre_padrino'] ?? '') : '' ?>"
                   placeholder="Ej: Jorge Flores">
          </div>
          <div class="col-md-6 mb-3">
            <label for="nombre_madrina" class="form-label">Nombre de la Madrina</label>
            <input type="text" class="form-control" id="nombre_madrina"
                   value="<?= $esEdicion ? htmlspecialchars($sac['nombre_madrina'] ?? '') : '' ?>"
                   placeholder="Ej: Mónica Tapia">
          </div>
        </div>

        <div class="btn-group-actions mt-4">
          <button type="reset" class="btn btn-outline-secondary px-4"><i class="bi bi-eraser me-2"></i>Limpiar</button>
          <button type="submit" class="btn btn-primary px-4">
            <i class="bi bi-check-circle me-2"></i><?= $esEdicion ? 'Actualizar Inscripción' : 'Registrar Inscripción' ?>
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

    const form = document.getElementById('formSacramento');
    const esEdicion = document.getElementById('id_inscripcion').value !== '';

    form.addEventListener('submit', async function (e) {
      e.preventDefault();
      if (!form.checkValidity()) {
        form.reportValidity();
        return;
      }

      const payload = {
        sacramento: document.getElementById('sacramento').value,
        nombre_solicitante: document.getElementById('nombre_solicitante').value,
        fecha_nacimiento: document.getElementById('fecha_nacimiento').value,
        lugar_nacimiento: document.getElementById('lugar_nacimiento').value,
        telefono: document.getElementById('telefono').value,
        email: document.getElementById('email').value,
        nombre_padre: document.getElementById('nombre_padre').value,
        nombre_madre: document.getElementById('nombre_madre').value,
        nombre_padrino: document.getElementById('nombre_padrino').value,
        nombre_madrina: document.getElementById('nombre_madrina').value
      };

      const url = esEdicion
        ? '<?= url('/sacramentos/actualizar') ?>'
        : '<?= url('/sacramentos/guardar') ?>';
      const method = esEdicion ? 'PUT' : 'POST';

      if (esEdicion) payload.id_inscripcion = document.getElementById('id_inscripcion').value;

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
        setTimeout(() => window.location.href = '<?= url('/sacramentos') ?>', 600);
      } catch (error) {
        console.error(error);
        showToast('Error al guardar la inscripción.', 'error');
      }
    });
  });
</script>
<?php
$content = ob_get_clean();
require_once appPath('cliente/layouts/AdminLayout.php');