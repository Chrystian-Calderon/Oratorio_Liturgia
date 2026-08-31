<?php
declare(strict_types=1);
if (!isset($_SESSION['usuario']) || !in_array($_SESSION['tipo_persona'], ['Administrativo', 'Encargado'])) {
  header("Location: " . url('/login-admin'));
  exit();
}
$pageTitle = "Reporte de Eventos";
$pageStyles = ['cliente/assets/css/reportes.css'];
ob_start();
?>
<div class="container-fluid py-3 reportes-page">
  <div class="page-head d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
    <h3 class="mb-0"><i class="fas fa-calendar-days me-2"></i>Reporte de Eventos</h3>
    <div class="export-buttons">
      <button class="btn btn-outline-success btn-sm" onclick="exportarExcel()"><i class="fas fa-file-excel me-1"></i>Excel</button>
      <button class="btn btn-outline-danger btn-sm" onclick="exportarPDF()"><i class="fas fa-file-pdf me-1"></i>PDF</button>
    </div>
  </div>

  <div class="card mb-4">
    <div class="card-body">
      <form id="filtroForm" class="row g-3" onsubmit="cargarReporte(event)">
        <div class="col-md-4">
          <label class="form-label">Buscar</label>
          <input type="text" id="buscar" class="form-control" placeholder="Nombre o descripción...">
        </div>
        <div class="col-md-3">
          <label class="form-label">Estado</label>
          <select id="estado" class="form-select">
            <option value="">Todos</option>
            <option>Activo</option>
            <option>Inactivo</option>
            <option>Cancelado</option>
          </select>
        </div>
        <div class="col-md-2 d-flex align-items-end">
          <button type="submit" class="btn btn-primary w-100"><i class="fas fa-search me-1"></i>Filtrar</button>
        </div>
      </form>
    </div>
  </div>

  <div class="card">
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-hover table-bordered align-middle" id="tablaReporte">
          <thead class="table-primary">
            <tr>
              <th>ID</th><th>Nombre</th><th>Estado</th><th>Fecha Evento</th>
              <th>Actividades</th><th>Inscripciones</th><th>Creado</th>
            </tr>
          </thead>
          <tbody><tr><td colspan="7" class="text-center text-muted">Cargando...</td></tr></tbody>
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
  const params = new URLSearchParams({buscar: document.getElementById('buscar').value, estado: document.getElementById('estado').value});
  fetch('<?= url('/reportes/eventos-data') ?>?' + params).then(r => r.json()).then(d => {
    if (!d.success) { showToast(d.message, 'error'); return; }
    datosReporte = d.data.eventos;
    renderTabla();
  }).catch(() => showToast('Error al cargar datos.', 'error'));
}
function renderTabla() {
  const tbody = document.querySelector('#tablaReporte tbody');
  if (!datosReporte.length) { tbody.innerHTML = '<tr><td colspan="7" class="text-center text-muted">Sin resultados</td></tr>'; return; }
  tbody.innerHTML = datosReporte.map(e => {
    const badge = e.estado === 'Activo' ? 'success' : e.estado === 'Cancelado' ? 'danger' : 'secondary';
    return `<tr><td>${e.id_evento}</td><td>${e.nombre_evento}</td><td><span class="badge bg-${badge}">${e.estado}</span></td><td>${e.fecha_evento || '—'}</td><td>${e.total_actividades}</td><td>${e.total_inscripciones}</td><td>${(e.fecha_creacion||'').slice(0,10)}</td></tr>`;
  }).join('');
}
function exportarExcel() {
  if (!datosReporte.length) { showToast('No hay datos para exportar.', 'error'); return; }
  const data = datosReporte.map(e => ({ID: e.id_evento, Nombre: e.nombre_evento, Estado: e.estado, 'Fecha Evento': e.fecha_evento||'', Actividades: e.total_actividades, Inscripciones: e.total_inscripciones, Creado: (e.fecha_creacion||'').slice(0,10)}));
  const ws = XLSX.utils.json_to_sheet(data);
  const wb = XLSX.utils.book_new(); XLSX.utils.book_append_sheet(wb, ws, 'Eventos');
  XLSX.writeFile(wb, 'reporte_eventos.xlsx'); showToast('Excel exportado.', 'success');
}
function exportarPDF() {
  if (!datosReporte.length) { showToast('No hay datos para exportar.', 'error'); return; }
  const { jsPDF } = window.jspdf;
  const doc = new jsPDF('l', 'mm', 'a4');
  doc.setFontSize(16); doc.text('Reporte de Eventos', 14, 15);
  doc.setFontSize(10); doc.text('Fecha: ' + new Date().toLocaleDateString('es-ES'), 14, 22);
  doc.autoTable({ startY: 28, head: [['ID','Nombre','Estado','Fecha Evento','Actividades','Inscripciones','Creado']], body: datosReporte.map(e => [e.id_evento, e.nombre_evento, e.estado, e.fecha_evento||'', e.total_actividades, e.total_inscripciones, (e.fecha_creacion||'').slice(0,10)]) });
  doc.save('reporte_eventos.pdf'); showToast('PDF exportado.', 'success');
}
cargarReporte();
</script>
<?php $content = ob_get_clean(); require_once appPath('cliente/layouts/AdminLayout.php');