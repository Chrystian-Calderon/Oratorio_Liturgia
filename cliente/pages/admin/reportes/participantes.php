<?php
declare(strict_types=1);
if (!isset($_SESSION['usuario']) || !in_array($_SESSION['tipo_persona'], ['Administrativo', 'Encargado'])) {
  header("Location: " . url('/login-admin'));
  exit();
}
$pageTitle = "Reporte de Participantes";
$pageStyles = ['cliente/assets/css/reportes.css'];
ob_start();
?>
<div class="container-fluid py-3 reportes-page">
  <div class="page-head d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
    <h3 class="mb-0"><i class="fas fa-users-viewfinder me-2"></i>Reporte de Participantes</h3>
    <div class="export-buttons">
      <button class="btn btn-outline-success btn-sm" onclick="exportarExcel()"><i class="fas fa-file-excel me-1"></i>Excel</button>
      <button class="btn btn-outline-danger btn-sm" onclick="exportarPDF()"><i class="fas fa-file-pdf me-1"></i>PDF</button>
    </div>
  </div>
  <div class="card mb-4">
    <div class="card-body">
      <form class="row g-3" onsubmit="cargarReporte(event)">
        <div class="col-md-3"><label class="form-label">Buscar</label><input type="text" id="buscar" class="form-control" placeholder="Nombre o CI..."></div>
        <div class="col-md-3"><label class="form-label">Actividad</label><select id="actividad" class="form-select"><option value="">Todas</option></select></div>
        <div class="col-md-3"><label class="form-label">Estado</label><select id="estado" class="form-select"><option value="">Todos</option><option>Pre-inscrito</option><option>Inscrito</option><option>En espera</option><option>Cancelado</option><option>Completado</option></select></div>
        <div class="col-md-2 d-flex align-items-end"><button type="submit" class="btn btn-primary w-100"><i class="fas fa-search me-1"></i>Filtrar</button></div>
      </form>
    </div>
  </div>
  <div class="card">
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-hover table-bordered align-middle" id="tablaReporte">
          <thead class="table-primary">
            <tr><th>CI</th><th>Nombre</th><th>Actividad</th><th>Fecha Ins.</th><th>Estado</th><th>Requisitos</th><th>Pago</th><th>Asistencia</th></tr>
          </thead>
          <tbody><tr><td colspan="8" class="text-center text-muted">Cargando...</td></tr></tbody>
        </table>
      </div>
    </div>
  </div>
</div>
<div id="toasts" class="position-fixed top-0 end-0 p-3"></div>
<script src="<?= url('/cliente/assets/js/reportes.js') ?>"></script>
<script>
let datosReporte = [];
function cargarReporte(e) {
  if (e) e.preventDefault();
  const params = new URLSearchParams({buscar: document.getElementById('buscar').value, actividad: document.getElementById('actividad').value, estado: document.getElementById('estado').value});
  fetch('<?= url('/reportes/participantes-data') ?>?' + params).then(r => r.json()).then(d => {
    if (!d.success) { showToast(d.message, 'error'); return; }
    datosReporte = d.data.participantes;
    const sel = document.getElementById('actividad');
    if (sel.options.length <= 1) d.data.actividades.forEach(a => { const o = document.createElement('option'); o.value = a.id_actividad; o.textContent = a.nombre_actividad; sel.appendChild(o); });
    renderTabla();
  }).catch(() => showToast('Error al cargar datos.', 'error'));
}
function renderTabla() {
  const tbody = document.querySelector('#tablaReporte tbody');
  if (!datosReporte.length) { tbody.innerHTML = '<tr><td colspan="8" class="text-center text-muted">Sin resultados</td></tr>'; return; }
  tbody.innerHTML = datosReporte.map(p => {
    const be = p.estado === 'Inscrito' ? 'success' : p.estado === 'Completado' ? 'primary' : p.estado === 'Cancelado' ? 'danger' : 'warning';
    return `<tr><td>${p.ci}</td><td>${p.nombres} ${p.apellidos}</td><td>${p.nombre_actividad}</td><td>${(p.fecha_inscripcion||'').slice(0,10)}</td><td><span class="badge bg-${be}">${p.estado}</span></td><td>${p.cumple_requisitos}</td><td>${p.monto ? Number(p.monto).toFixed(2)+' Bs ('+p.estado_pago+')' : '—'}</td><td>${p.asistencia ?? 0}</td></tr>`;
  }).join('');
}
function exportarExcel() {
  if (!datosReporte.length) { showToast('No hay datos para exportar.', 'error'); return; }
  const data = datosReporte.map(p => ({CI: p.ci, Nombre: p.nombres+' '+p.apellidos, Correo: p.correo, Actividad: p.nombre_actividad, 'Fecha Ins.': (p.fecha_inscripcion||'').slice(0,10), Estado: p.estado, Requisitos: p.cumple_requisitos, Pago: p.monto ? Number(p.monto).toFixed(2) : '', Asistencia: p.asistencia||0}));
  const ws = XLSX.utils.json_to_sheet(data);
  const wb = XLSX.utils.book_new(); XLSX.utils.book_append_sheet(wb, ws, 'Participantes');
  XLSX.writeFile(wb, 'reporte_participantes.xlsx'); showToast('Excel exportado.', 'success');
}
function exportarPDF() {
  if (!datosReporte.length) { showToast('No hay datos para exportar.', 'error'); return; }
  const { jsPDF } = window.jspdf;
  const doc = new jsPDF('l', 'mm', 'a4');
  doc.setFontSize(16); doc.text('Reporte de Participantes', 14, 15);
  doc.setFontSize(10); doc.text('Fecha: ' + new Date().toLocaleDateString('es-ES'), 14, 22);
  doc.autoTable({ startY: 28, head: [['CI','Nombre','Actividad','Fecha Ins.','Estado','Requisitos','Pago','Asistencia']], body: datosReporte.map(p => [p.ci, p.nombres+' '+p.apellidos, p.nombre_actividad, (p.fecha_inscripcion||'').slice(0,10), p.estado, p.cumple_requisitos, p.monto ? Number(p.monto).toFixed(2) : '—', p.asistencia||0]) });
  doc.save('reporte_participantes.pdf'); showToast('PDF exportado.', 'success');
}
cargarReporte();
</script>
<?php $content = ob_get_clean(); require_once appPath('cliente/layouts/AdminLayout.php');