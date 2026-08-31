<?php
declare(strict_types=1);
if (!isset($_SESSION['usuario']) || !in_array($_SESSION['tipo_persona'], ['Administrativo', 'Encargado'])) {
  header("Location: " . url('/login-admin'));
  exit();
}
$pageTitle = "Reporte de Asistencias";
$pageStyles = ['cliente/assets/css/reportes.css'];
ob_start();
?>
<div class="container-fluid py-3 reportes-page">
  <div class="page-head d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
    <h3 class="mb-0"><i class="fas fa-square-check me-2"></i>Reporte de Asistencias</h3>
    <div class="export-buttons">
      <button class="btn btn-outline-success btn-sm" onclick="exportarExcel()"><i class="fas fa-file-excel me-1"></i>Excel</button>
      <button class="btn btn-outline-danger btn-sm" onclick="exportarPDF()"><i class="fas fa-file-pdf me-1"></i>PDF</button>
    </div>
  </div>
  <div class="card mb-4">
    <div class="card-body">
      <form class="row g-3" onsubmit="cargarReporte(event)">
        <div class="col-md-6"><label class="form-label">Buscar</label><input type="text" id="buscar" class="form-control" placeholder="Nombre o actividad..."></div>
        <div class="col-md-2 d-flex align-items-end"><button type="submit" class="btn btn-primary w-100"><i class="fas fa-search me-1"></i>Filtrar</button></div>
      </form>
    </div>
  </div>
  <div class="card">
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-hover table-bordered align-middle" id="tablaReporte">
          <thead class="table-primary">
            <tr><th>Fecha</th><th>CI</th><th>Participante</th><th>Actividad</th><th>Asistió</th><th>Observaciones</th><th>Registrado por</th></tr>
          </thead>
          <tbody><tr><td colspan="7" class="text-center text-muted" id="msgVacio">Cargando...</td></tr></tbody>
        </table>
      </div>
    </div>
  </div>
</div>
<div id="toasts" class="position-fixed top-0 end-0 p-3"></div>
<script>
let datosReporte = [];
function showToast(msg, type) {
  const el = document.createElement('div');
  el.className = 'toast ' + (type === 'error' ? 'bg-danger text-white' : 'bg-success text-white');
  el.innerHTML = '<div class="toast-body">' + msg + '</div>';
  document.getElementById('toasts').appendChild(el);
  new bootstrap.Toast(el, {delay:3000}).show();
  el.addEventListener('hidden.bs.toast', () => el.remove());
}
function cargarReporte(e) {
  if (e) e.preventDefault();
  const params = new URLSearchParams({buscar: document.getElementById('buscar').value});
  fetch('<?= url('/reportes/asistencias-data') ?>?' + params).then(r => r.json()).then(d => {
    if (!d.success) { showToast(d.message, 'error'); return; }
    datosReporte = d.data.asistencias;
    renderTabla();
  }).catch(() => showToast('Error al cargar datos.', 'error'));
}
function renderTabla() {
  const tbody = document.querySelector('#tablaReporte tbody');
  if (!datosReporte.length) { tbody.innerHTML = '<tr><td colspan="7" class="text-center text-muted">No hay registros de asistencia</td></tr>'; return; }
  tbody.innerHTML = datosReporte.map(a => {
    const b = a.asistio === 'Si' ? 'success' : a.asistio === 'Justificado' ? 'warning' : 'danger';
    return `<tr><td>${a.fecha}</td><td>${a.ci}</td><td>${a.nombres} ${a.apellidos}</td><td>${a.nombre_actividad}</td><td><span class="badge bg-${b}">${a.asistio}</span></td><td>${a.observaciones||'—'}</td><td>${a.registrado_por_nombre} ${a.registrado_por_apellidos}</td></tr>`;
  }).join('');
}
function exportarExcel() {
  if (!datosReporte.length) { showToast('No hay datos para exportar.', 'error'); return; }
  const data = datosReporte.map(a => ({Fecha: a.fecha, CI: a.ci, Participante: a.nombres+' '+a.apellidos, Actividad: a.nombre_actividad, Asistió: a.asistio, Observaciones: a.observaciones||'', 'Registrado por': a.registrado_por_nombre+' '+a.registrado_por_apellidos}));
  const ws = XLSX.utils.json_to_sheet(data);
  const wb = XLSX.utils.book_new(); XLSX.utils.book_append_sheet(wb, ws, 'Asistencias');
  XLSX.writeFile(wb, 'reporte_asistencias.xlsx'); showToast('Excel exportado.', 'success');
}
function exportarPDF() {
  if (!datosReporte.length) { showToast('No hay datos para exportar.', 'error'); return; }
  const { jsPDF } = window.jspdf;
  const doc = new jsPDF('l', 'mm', 'a4');
  doc.setFontSize(16); doc.text('Reporte de Asistencias', 14, 15);
  doc.setFontSize(10); doc.text('Fecha: ' + new Date().toLocaleDateString('es-ES'), 14, 22);
  doc.autoTable({ startY: 28, head: [['Fecha','CI','Participante','Actividad','Asistió','Observaciones','Registrado por']], body: datosReporte.map(a => [a.fecha, a.ci, a.nombres+' '+a.apellidos, a.nombre_actividad, a.asistio, a.observaciones||'—', a.registrado_por_nombre+' '+a.registrado_por_apellidos]) });
  doc.save('reporte_asistencias.pdf'); showToast('PDF exportado.', 'success');
}
cargarReporte();
</script>
<?php $content = ob_get_clean(); require_once appPath('cliente/layouts/AdminLayout.php');