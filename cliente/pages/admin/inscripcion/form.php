<?php
declare(strict_types=1);
if (
  !isset($_SESSION['usuario']) ||
  !in_array($_SESSION['tipo_persona'], ['Administrativo', 'Encargado'])
) {
  header("Location: " . url('/login-admin'));
  exit();
}
$pageTitle = 'Editar Inscripción';
$pageStyles = [
  'cliente/assets/css/inscripcion.css',
];
ob_start();

$ins = $inscripcion;
?>
<div class="container-fluid py-3 inscripcion-page">
  <div class="page-head">
    <div>
      <h3>
        <i class="fas fa-edit me-2"></i>
        Editar Inscripción
      </h3>
      <p>Modifique la información de la inscripción</p>
    </div>
    <a href="<?= url('/inscripcion') ?>" class="btn btn-outline-secondary">
      <i class="fas fa-arrow-left me-2"></i>Volver
    </a>
  </div>

  <div class="card">
    <div class="card-header">
      <h4><i class="fas fa-file-signature"></i>Datos de la Inscripción</h4>
    </div>
    <div class="card-body">
      <form id="formInscripcion" novalidate>
        <input type="hidden" id="id_inscripcion" value="<?= (int) $ins['id_inscripcion'] ?>">

        <div class="section-title"><i class="bi bi-info-circle-fill"></i>Información Registrada</div>
        <div class="row g-3 mb-4">
          <div class="col-md-4">
            <div class="border rounded p-3 bg-light">
              <small class="text-muted d-block">Fecha de inscripción</small>
              <strong><?= htmlspecialchars($ins['fecha_inscripcion'] ?? '—') ?></strong>
            </div>
          </div>
          <div class="col-md-4">
            <div class="border rounded p-3 bg-light">
              <small class="text-muted d-block">Última actualización</small>
              <strong><?= htmlspecialchars($ins['fecha_actualizacion'] ?? '—') ?></strong>
            </div>
          </div>
          <div class="col-md-4">
            <div class="border rounded p-3 bg-light">
              <small class="text-muted d-block">ID de inscripción</small>
              <strong>#<?= (int) $ins['id_inscripcion'] ?></strong>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-6 mb-3">
            <label for="id_persona" class="form-label">Persona<span class="required-star">*</span></label>
            <select id="id_persona" class="form-select" required>
              <option value="" disabled>Seleccione una persona...</option>
              <?php foreach (($personas ?? []) as $per): ?>
                <option value="<?= (int) $per['id_persona'] ?>"
                  <?= (int) $ins['id_persona'] === (int) $per['id_persona'] ? 'selected' : '' ?>>
                  <?= htmlspecialchars($per['nombre'] ?? (($per['nombres'] ?? '') . ' ' . ($per['apellidos'] ?? ''))) ?> — CI: <?= htmlspecialchars($per['ci'] ?? '') ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="col-md-6 mb-3">
            <label for="id_actividad" class="form-label">Actividad<span class="required-star">*</span></label>
            <select id="id_actividad" class="form-select" required>
              <option value="" disabled>Seleccione una actividad...</option>
              <?php foreach (($actividades ?? []) as $act): ?>
                <option value="<?= (int) $act['id_actividad'] ?>"
                  <?= (int) $ins['id_actividad'] === (int) $act['id_actividad'] ? 'selected' : '' ?>>
                  <?= htmlspecialchars($act['nombre_actividad']) ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>

        <div class="row">
          <div class="col-md-6 mb-3">
            <label for="id_pago" class="form-label">Pago</label>
            <select id="id_pago" class="form-select">
              <option value="">Sin pago asociado</option>
              <?php foreach (($pagos ?? []) as $pag): ?>
                <option value="<?= (int) $pag['id_pago'] ?>"
                  <?= !empty($ins['id_pago']) && (int) $ins['id_pago'] === (int) $pag['id_pago'] ? 'selected' : '' ?>>
                  #<?= (int) $pag['id_pago'] ?> — <?= htmlspecialchars($pag['concepto'] ?? 'Pago') ?> (<?= number_format((float) $pag['monto'], 2) ?> Bs)
                </option>
              <?php endforeach; ?>
              <option value="nuevo">+ Nuevo pago...</option>
            </select>
          </div>

          <div class="col-md-3 mb-3">
            <label for="cumple_requisitos" class="form-label">Cumple Requisitos</label>
            <select id="cumple_requisitos" class="form-select">
              <?php
              $reqActual = $ins['cumple_requisitos'] ?? 'En revisión';
              foreach (['Si', 'No', 'En revisión'] as $opcion) {
                $sel = $reqActual === $opcion ? ' selected' : '';
                echo '<option value="' . $opcion . '"' . $sel . '>' . $opcion . '</option>';
              }
              ?>
            </select>
          </div>

          <div class="col-md-3 mb-3">
            <label for="estado" class="form-label">Estado</label>
            <select id="estado" class="form-select">
              <?php
              $estadoActual = $ins['estado'] ?? 'Pre-inscrito';
              foreach (['Pre-inscrito', 'Inscrito', 'En espera', 'Cancelado', 'Completado'] as $opcion) {
                $sel = $estadoActual === $opcion ? ' selected' : '';
                echo '<option value="' . $opcion . '"' . $sel . '>' . $opcion . '</option>';
              }
              ?>
            </select>
          </div>
        </div>

        <div class="row">
          <div class="col-md-6 mb-3">
            <label for="observaciones" class="form-label">Observaciones</label>
            <textarea class="form-control" id="observaciones" rows="3"
                      placeholder="Observaciones de la inscripción..."><?= htmlspecialchars($ins['observaciones'] ?? '') ?></textarea>
          </div>
          <div class="col-md-3 mb-3">
            <label for="asistencia" class="form-label">Asistencia</label>
            <input type="number" min="0" class="form-control" id="asistencia"
                   value="<?= (int) ($ins['asistencia'] ?? 0) ?>">
          </div>
          <div class="col-md-3 mb-3">
            <label for="calificacion" class="form-label">Calificación</label>
            <input type="number" min="0" max="100" class="form-control" id="calificacion"
                   value="<?= $ins['calificacion'] !== null ? (int) $ins['calificacion'] : '' ?>"
                   placeholder="0 - 100">
          </div>
        </div>

        <div class="row d-none" id="bloqueNuevoPago">
          <div class="col-12 mb-3">
            <div class="border rounded p-3 bg-light">
              <h6 class="mb-3"><i class="fas fa-credit-card me-2 text-primary"></i>Nuevo Pago</h6>
              <input type="hidden" id="pago_id_persona" value="<?= (int) $ins['id_persona'] ?>">
              <div class="row">
                <div class="col-md-4 mb-3">
                  <label for="pago_concepto" class="form-label">Concepto<span class="required-star">*</span></label>
                  <input type="text" class="form-control" id="pago_concepto" placeholder="Ej: Inscripción">
                </div>
                <div class="col-md-4 mb-3">
                  <label for="pago_monto" class="form-label">Monto (Bs)<span class="required-star">*</span></label>
                  <input type="number" step="0.01" min="0" class="form-control" id="pago_monto" placeholder="Ej: 100">
                </div>
                <div class="col-md-4 mb-3">
                  <label for="pago_fecha_pago" class="form-label">Fecha de Pago<span class="required-star">*</span></label>
                  <input type="date" class="form-control" id="pago_fecha_pago">
                </div>
              </div>
              <div class="row">
                <div class="col-md-4 mb-3">
                  <label for="pago_metodo_pago" class="form-label">Método de Pago<span class="required-star">*</span></label>
                  <select id="pago_metodo_pago" class="form-select">
                    <option value="" selected disabled>Seleccione</option>
                    <option>Efectivo</option>
                    <option>Transferencia</option>
                    <option>Tarjeta de Crédito</option>
                    <option>Tarjeta de Débito</option>
                    <option>Depósito Bancario</option>
                    <option>Cheque</option>
                  </select>
                </div>
                <div class="col-md-4 mb-3">
                  <label for="pago_estado" class="form-label">Estado</label>
                  <select id="pago_estado" class="form-select">
                    <option selected>Pendiente</option>
                    <option>Completado</option>
                    <option>Rechazado</option>
                    <option>Reembolsado</option>
                  </select>
                </div>
                <div class="col-md-4 mb-3">
                  <label for="pago_comprobante" class="form-label">Comprobante</label>
                  <input type="text" class="form-control" id="pago_comprobante" placeholder="Número o código">
                </div>
              </div>
              <div class="row">
                <div class="col-md-12 mb-3">
                  <label for="pago_observaciones" class="form-label">Observaciones</label>
                  <textarea class="form-control" id="pago_observaciones" rows="2" placeholder="Observaciones del pago si aplica"></textarea>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="btn-group-actions mt-4">
          <a href="<?= url('/inscripcion') ?>" class="btn btn-outline-secondary px-4">
            <i class="fas fa-times me-2"></i>Cancelar
          </a>
          <button type="submit" class="btn btn-primary px-4">
            <i class="fas fa-check-circle me-2"></i>Actualizar Inscripción
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

    const form = document.getElementById('formInscripcion');
    const id = document.getElementById('id_inscripcion').value;

    const selectPago = document.getElementById('id_pago');
    const selectPersona = document.getElementById('id_persona');
    const bloqueNuevoPago = document.getElementById('bloqueNuevoPago');
    const pagoIdPersona = document.getElementById('pago_id_persona');

    function toggleNuevoPago() {
      const mostrar = selectPago.value === 'nuevo';
      if (mostrar) {
        bloqueNuevoPago.classList.remove('d-none');
        pagoIdPersona.value = selectPersona.value;
      } else {
        bloqueNuevoPago.classList.add('d-none');
      }
    }

    selectPago.addEventListener('change', toggleNuevoPago);
    selectPersona.addEventListener('change', function () {
      if (selectPago.value === 'nuevo') {
        pagoIdPersona.value = selectPersona.value;
      }
    });
    toggleNuevoPago();

    form.addEventListener('submit', async function (e) {
      e.preventDefault();
      if (!form.checkValidity()) {
        form.reportValidity();
        return;
      }

      let idPago = selectPago.value;

      if (idPago === 'nuevo') {
        const pagoPayload = {
          id_persona: pagoIdPersona.value,
          concepto: document.getElementById('pago_concepto').value,
          monto: document.getElementById('pago_monto').value,
          fecha_pago: document.getElementById('pago_fecha_pago').value,
          metodo_pago: document.getElementById('pago_metodo_pago').value,
          comprobante: document.getElementById('pago_comprobante').value,
          estado: document.getElementById('pago_estado').value,
          observaciones: document.getElementById('pago_observaciones').value
        };

        if (!pagoPayload.concepto || pagoPayload.monto === '' || !pagoPayload.fecha_pago || !pagoPayload.metodo_pago) {
          showToast('Complete los campos obligatorios del pago (concepto, monto, fecha y método).', 'error');
          return;
        }

        const btn = form.querySelector('button[type="submit"]');
        btn.disabled = true;
        try {
          const respPago = await fetch('<?= url('/pagos/guardar') ?>', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(pagoPayload)
          });
          const resPago = await respPago.json();
          if (!resPago.success) {
            showToast(resPago.message, 'error');
            btn.disabled = false;
            return;
          }
          idPago = resPago.data.id_pago;
        } catch (error) {
          console.error(error);
          showToast('Error al registrar el pago.', 'error');
          btn.disabled = false;
          return;
        }
        btn.disabled = false;
      }

      const payload = {
        id_inscripcion: id,
        id_actividad: document.getElementById('id_actividad').value,
        id_persona: selectPersona.value,
        id_pago: idPago,
        cumple_requisitos: document.getElementById('cumple_requisitos').value,
        estado: document.getElementById('estado').value,
        observaciones: document.getElementById('observaciones').value,
        asistencia: document.getElementById('asistencia').value,
        calificacion: document.getElementById('calificacion').value
      };

      try {
        const resp = await fetch('<?= url('/inscripcion/actualizar') ?>', {
          method: 'PUT',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify(payload)
        });
        const result = await resp.json();
        if (!result.success) {
          showToast(result.message, 'error');
          return;
        }
        showToast(result.message, 'success');
        setTimeout(() => window.location.href = '<?= url('/inscripcion') ?>', 600);
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