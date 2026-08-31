<?php
declare(strict_types=1);
if (!isset($_SESSION['usuario']) || !in_array($_SESSION['tipo_persona'], ['Administrativo', 'Encargado'])) {
  header("Location: " . url('/login-admin'));
  exit();
}
$pageTitle = "Reporte de Actividades";
$pageStyles = ['cliente/assets/css/reportes.css'];
ob_start();
?>
<div class="container-fluid py-3 reportes-page">
  <div class="page-head d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
    <h3 class="mb-0"><i class="fas fa-clipboard-list me-2"></i>Reporte de Actividades</h3>
    <div class="export-buttons">
      <button class="btn btn-outline-success btn-sm" onclick="exportarExcel()"><i class="fas fa-file-excel me-1"></i>Excel</button>
      <button class="btn btn-outline-danger btn-sm" onclick="exportarPDF()"><i class="fas fa-file-pdf me-1"></i>PDF</button>
    </div>
  </div>
  <div class="card mb-4">
    <div class="card-body">
      <form class="row g-3" onsubmit="cargarReporte(event)">
        <div class="col-md-3"><label class="form-label">Buscar</label><input type="text" id="buscar" class="form-control" placeholder="Nombre o tipo..."></div>
        <div class="col-md-3"><label class="form-label">Evento</label><select id="evento" class="form-select"><option value="">Todos</option></select></div>
        <div class="col-md-3"><label class="form-label">Estado</label><select id="estado" class="form-select"><option value="">Todos</option><option>Activo</option><option>Cancelado</option><option>Completado</option><option>En espera</option></select></div>
        <div class="col-md-2 d-flex align-items-end"><button type="submit" class="btn btn-primary w-100"><i class="fas fa-search me-1"></i>Filtrar</button></div>
      </form>
    </div>
  </div>
  <div class="card">
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-hover table-bordered align-middle" id="tablaReporte">
          <thead class="table-primary">
            <tr><th>ID</th><th>Nombre</th><th>Tipo</th><th>Evento</th><th>Inicio</th><th>Fin</th><th>Inscritos</th><th>Pagos</th><th>Estado</th></tr>
          </thead>
          <tbody><tr><td colspan="9" class="text-center text-muted">Cargando...</td></tr></tbody>
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
  const params = new URLSearchParams({buscar: document.getElementById('buscar').value, evento: document.getElementById('evento').value, estado: document.getElementById('estado').value});
  fetch('<?= url('/reportes/actividades-data') ?>?' + params).then(r => r.json()).then(d => {
    if (!d.success) { showToast(d.message, 'error'); return; }
    datosReporte = d.data.actividades;
    const sel = document.getElementById('evento');
    if (sel.options.length <= 1) d.data.eventos.forEach(ev => { const o = document.createElement('option'); o.value = ev.id_evento; o.textContent = ev.nombre_evento; sel.appendChild(o); });
    renderTabla();
  }).catch(() => showToast('Error al cargar datos.', 'error'));
}
function renderTabla() {
  const tbody = document.querySelector('#tablaReporte tbody');
  if (!datosReporte.length) { tbody.innerHTML = '<tr><td colspan="9" class="text-center text-muted">Sin resultados</td></tr>'; return; }
  tbody.innerHTML = datosReporte.map(a => {
    const b = a.estado === 'Activo' ? 'success' : a.estado === 'Completado' ? 'primary' : a.estado === 'Cancelado' ? 'danger' : 'warning';
    return `<tr><td>${a.id_actividad}</td><td>${a.nombre_actividad}</td><td>${a.tipo_actividad||'—'}</td><td>${a.nombre_evento}</td><td>${a.fecha_inicio}</td><td>${a.fecha_fin}</td><td>${a.total_inscripciones}/${a.cupo_maximo||'∞'}</td><td>${Number(a.total_pagos).toFixed(2)} Bs</td><td><span class="badge bg-${b}">${a.estado}</span></td></tr>`;
  }).join('');
}
function exportarExcel() {
  if (!datosReporte.length) { showToast('No hay datos para exportar.', 'error'); return; }
  const data = datosReporte.map(a => ({ID: a.id_actividad, Nombre: a.nombre_actividad, Tipo: a.tipo_actividad||'', Evento: a.nombre_evento, Inicio: a.fecha_inicio, Fin: a.fecha_fin, Inscritos: a.total_inscripciones, Pagos: Number(a.total_pagos).toFixed(2), Estado: a.estado}));
  const ws = XLSX.utils.json_to_sheet(data);
  const wb = XLSX.utils.book_new(); XLSX.utils.book_append_sheet(wb, ws, 'Actividades');
  XLSX.writeFile(wb, 'reporte_actividades.xlsx'); showToast('Excel exportado.', 'success');
}
function exportarPDF() {
  if (!datosReporte.length) { showToast('No hay datos para exportar.', 'error'); return; }
  const { jsPDF } = window.jspdf;
  const doc = new jsPDF('l', 'mm', 'a4');
  doc.setFontSize(16); doc.text('Reporte de Actividades', 14, 15);
  doc.setFontSize(10); doc.text('Fecha: ' + new Date().toLocaleDateString('es-ES'), 14, 22);
  doc.autoTable({ startY: 28, head: [['ID','Nombre','Tipo','Evento','Inicio','Fin','Inscritos','Pagos','Estado']], body: datosReporte.map(a => [a.id_actividad, a.nombre_actividad, a.tipo_actividad||'', a.nombre_evento, a.fecha_inicio, a.fecha_fin, a.total_inscripciones, Number(a.total_pagos).toFixed(2), a.estado]) });
  doc.save('reporte_actividades.pdf'); showToast('PDF exportado.', 'success');
}
cargarReporte();
</script>
<?php $content = ob_get_clean(); require_once appPath('cliente/layouts/AdminLayout.php');