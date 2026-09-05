<?php
declare(strict_types=1);
if (!isset($_SESSION['usuario']) || !in_array($_SESSION['tipo_persona'], ['Administrativo', 'Encargado'])) {
  header("Location: " . url('/login-admin'));
  exit();
}
$pageTitle = "Reporte de Sacramentos";
$pageStyles = ['cliente/assets/css/reportes.css'];
ob_start();
?>
<div class="container-fluid py-3 reportes-page">
  <div class="page-head d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
    <h3 class="mb-0"><i class="fas fa-place-of-worship me-2"></i>Reporte de Formación Sacramental</h3>
    <div class="export-buttons">
      <button class="btn btn-outline-success btn-sm" onclick="exportarExcel()"><i class="fas fa-file-excel me-1"></i>Excel</button>
      <button class="btn btn-outline-danger btn-sm" onclick="exportarPDF()"><i class="fas fa-file-pdf me-1"></i>PDF</button>
    </div>
  </div>
  <div class="card mb-4">
    <div class="card-body">
      <form class="row g-3" onsubmit="cargarReporte(event)">
        <div class="col-md-4"><label class="form-label">Buscar</label><input type="text" id="buscar" class="form-control" placeholder="Nombre, email o teléfono..."></div>
        <div class="col-md-3"><label class="form-label">Sacramento</label><select id="sacramento" class="form-select"><option value="">Todos</option><option>Bautizo</option><option>Primera Comunión</option><option>Confirmación</option><option>Matrimonio</option><option>Penitencia</option><option>Unción de los Enfermos</option></select></div>
        <div class="col-md-2 d-flex align-items-end"><button type="submit" class="btn btn-primary w-100"><i class="fas fa-search me-1"></i>Filtrar</button></div>
      </form>
    </div>
  </div>
  <div class="card">
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-hover table-bordered align-middle" id="tablaReporte">
          <thead class="table-primary">
            <tr><th>Sacramento</th><th>Solicitante</th><th>Nacimiento</th><th>Lugar</th><th>Padre</th><th>Madre</th><th>Teléfono</th><th>Correo</th><th>Registro</th></tr>
          </thead>
          <tbody><tr><td colspan="9" class="text-center text-muted">Cargando...</td></tr></tbody>
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
  const params = new URLSearchParams({buscar: document.getElementById('buscar').value, sacramento: document.getElementById('sacramento').value});
  fetch('<?= url('/reportes/sacramentos-data') ?>?' + params).then(r => r.json()).then(d => {
    if (!d.success) { showToast(d.message, 'error'); return; }
    datosReporte = d.data.sacramentos;
    renderTabla();
  }).catch(() => showToast('Error al cargar datos.', 'error'));
}
function renderTabla() {
  const tbody = document.querySelector('#tablaReporte tbody');
  if (!datosReporte.length) { tbody.innerHTML = '<tr><td colspan="9" class="text-center text-muted">Sin resultados</td></tr>'; return; }
  tbody.innerHTML = datosReporte.map(s => `<tr><td><span class="badge bg-primary">${s.sacramento}</span></td><td>${s.nombre_solicitante}</td><td>${s.fecha_nacimiento}</td><td>${s.lugar_nacimiento}</td><td>${s.nombre_padre||'—'}</td><td>${s.nombre_madre||'—'}</td><td>${s.telefono}</td><td>${s.email}</td><td>${(s.fecha_registro||'').slice(0,10)}</td></tr>`).join('');
}
function exportarExcel() {
  if (!datosReporte.length) { showToast('No hay datos para exportar.', 'error'); return; }
  const data = datosReporte.map(s => ({ID: s.id_inscripcion, Sacramento: s.sacramento, Solicitante: s.nombre_solicitante, Nacimiento: s.fecha_nacimiento, Lugar: s.lugar_nacimiento, Padre: s.nombre_padre||'', Madre: s.nombre_madre||'', Padrino: s.nombre_padrino||'', Madrina: s.nombre_madrina||'', Teléfono: s.telefono, Correo: s.email, Registro: (s.fecha_registro||'').slice(0,10)}));
  const ws = XLSX.utils.json_to_sheet(data);
  const wb = XLSX.utils.book_new(); XLSX.utils.book_append_sheet(wb, ws, 'Sacramentos');
  XLSX.writeFile(wb, 'reporte_sacramentos.xlsx'); showToast('Excel exportado.', 'success');
}
function exportarPDF() {
  if (!datosReporte.length) { showToast('No hay datos para exportar.', 'error'); return; }
  const { jsPDF } = window.jspdf;
  const doc = new jsPDF('l', 'mm', 'a4');
  doc.setFontSize(16); doc.text('Reporte de Formación Sacramental', 14, 15);
  doc.setFontSize(10); doc.text('Fecha: ' + new Date().toLocaleDateString('es-ES'), 14, 22);
  doc.autoTable({ startY: 28, head: [['ID','Sacramento','Solicitante','Nacimiento','Lugar','Padre','Madre','Teléfono','Correo']], body: datosReporte.map(s => [s.id_inscripcion, s.sacramento, s.nombre_solicitante, s.fecha_nacimiento, s.lugar_nacimiento, s.nombre_padre||'—', s.nombre_madre||'—', s.telefono, s.email]) });
  doc.save('reporte_sacramentos.pdf'); showToast('PDF exportado.', 'success');
}
cargarReporte();
</script>
<?php $content = ob_get_clean(); require_once appPath('cliente/layouts/AdminLayout.php');