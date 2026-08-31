<?php
declare(strict_types=1);
if (!isset($_SESSION['usuario']) || !in_array($_SESSION['tipo_persona'], ['Administrativo', 'Encargado'])) {
  header("Location: " . url('/login-admin'));
  exit();
}
$pageTitle = "Reporte de Pagos";
$pageStyles = ['cliente/assets/css/reportes.css'];
ob_start();
?>
<div class="container-fluid py-3 reportes-page">
  <div class="page-head d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
    <h3 class="mb-0"><i class="fas fa-file-invoice-dollar me-2"></i>Reporte de Pagos</h3>
    <div class="export-buttons">
      <button class="btn btn-outline-success btn-sm" onclick="exportarExcel()"><i class="fas fa-file-excel me-1"></i>Excel</button>
      <button class="btn btn-outline-danger btn-sm" onclick="exportarPDF()"><i class="fas fa-file-pdf me-1"></i>PDF</button>
    </div>
  </div>

  <!-- Totales -->
  <div class="row mb-4">
    <div class="col-md-3 mb-3"><div class="card stats-card bg-custom-primary"><div class="d-flex justify-content-between align-items-center"><div><h6 class="mb-0">Total Pagos</h6><div class="stats-number" id="totalPagos">0</div></div><i class="fas fa-receipt fa-2x opacity-75"></i></div></div></div>
    <div class="col-md-3 mb-3"><div class="card stats-card bg-custom-success"><div class="d-flex justify-content-between align-items-center"><div><h6 class="mb-0">Monto Total</h6><div class="stats-number" id="totalMonto">0 Bs</div></div><i class="fas fa-coins fa-2x opacity-75"></i></div></div></div>
    <div class="col-md-3 mb-3"><div class="card stats-card bg-custom-info"><div class="d-flex justify-content-between align-items-center"><div><h6 class="mb-0">Completados</h6><div class="stats-number" id="totalCompletados">0</div></div><i class="fas fa-check-circle fa-2x opacity-75"></i></div></div></div>
    <div class="col-md-3 mb-3"><div class="card stats-card bg-custom-warning"><div class="d-flex justify-content-between align-items-center"><div><h6 class="mb-0">Pendientes</h6><div class="stats-number" id="totalPendientes">0</div></div><i class="fas fa-clock fa-2x opacity-75"></i></div></div></div>
  </div>

  <div class="card mb-4">
    <div class="card-body">
      <form class="row g-3" onsubmit="cargarReporte(event)">
        <div class="col-md-2"><label class="form-label">Buscar</label><input type="text" id="buscar" class="form-control" placeholder="Nombre, concepto..."></div>
        <div class="col-md-3"><label class="form-label">Estado</label><select id="estado" class="form-select"><option value="">Todos</option><option>Pendiente</option><option>Completado</option><option>Rechazado</option><option>Reembolsado</option></select></div>
        <div class="col-md-3"><label class="form-label">Método</label><select id="metodo" class="form-select"><option value="">Todos</option><option>Efectivo</option><option>Transferencia</option><option>Tarjeta de Crédito</option><option>Tarjeta de Débito</option><option>Depósito Bancario</option><option>Cheque</option></select></div>
        <div class="col-md-2 d-flex align-items-end"><button type="submit" class="btn btn-primary w-100"><i class="fas fa-search me-1"></i>Filtrar</button></div>
      </form>
    </div>
  </div>

  <div class="card">
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-hover table-bordered align-middle" id="tablaReporte">
          <thead class="table-primary">
            <tr><th>ID</th><th>Persona</th><th>Concepto</th><th>Monto</th><th>Fecha</th><th>Método</th><th>Estado</th><th>Comprobante</th></tr>
          </thead>
          <tbody><tr><td colspan="8" class="text-center text-muted">Cargando...</td></tr></tbody>
        </table>
      </div>
    </div>
  </div>
</div>
<div id="toasts" class="position-fixed top-0 end-0 p-3"></div>
<script>
let datosReporte = [];
let resumen = {};
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
  const params = new URLSearchParams({buscar: document.getElementById('buscar').value, estado: document.getElementById('estado').value, metodo: document.getElementById('metodo').value});
  fetch('<?= url('/reportes/pagos-data') ?>?' + params).then(r => r.json()).then(d => {
    if (!d.success) { showToast(d.message, 'error'); return; }
    datosReporte = d.data.pagos;
    resumen = {total: d.data.total, monto: d.data.total_monto, completados: d.data.total_completados, pendientes: d.data.total_pendientes};
    document.getElementById('totalPagos').textContent = resumen.total;
    document.getElementById('totalMonto').textContent = Number(resumen.monto).toFixed(2) + ' Bs';
    document.getElementById('totalCompletados').textContent = resumen.completados;
    document.getElementById('totalPendientes').textContent = resumen.pendientes;
    renderTabla();
  }).catch(() => showToast('Error al cargar datos.', 'error'));
}
function renderTabla() {
  const tbody = document.querySelector('#tablaReporte tbody');
  if (!datosReporte.length) { tbody.innerHTML = '<tr><td colspan="8" class="text-center text-muted">Sin resultados</td></tr>'; return; }
  tbody.innerHTML = datosReporte.map(p => {
    const b = p.estado === 'Completado' ? 'success' : p.estado === 'Pendiente' ? 'warning' : p.estado === 'Rechazado' ? 'danger' : 'info';
    return `<tr><td>${p.id_pago}</td><td>${p.nombres} ${p.apellidos}<br><small class="text-muted">${p.ci}</small></td><td>${p.concepto}</td><td><strong>${Number(p.monto).toFixed(2)} Bs</strong></td><td>${p.fecha_pago}</td><td>${p.metodo_pago}</td><td><span class="badge bg-${b}">${p.estado}</span></td><td>${p.comprobante||'—'}</td></tr>`;
  }).join('');
}
function exportarExcel() {
  if (!datosReporte.length) { showToast('No hay datos para exportar.', 'error'); return; }
  const data = datosReporte.map(p => ({ID: p.id_pago, Persona: p.nombres+' '+p.apellidos, CI: p.ci, Concepto: p.concepto, Monto: Number(p.monto).toFixed(2), Fecha: p.fecha_pago, Método: p.metodo_pago, Estado: p.estado, Comprobante: p.comprobante||''}));
  const ws = XLSX.utils.json_to_sheet(data);
  const wb = XLSX.utils.book_new(); XLSX.utils.book_append_sheet(wb, ws, 'Pagos');
  XLSX.writeFile(wb, 'reporte_pagos.xlsx'); showToast('Excel exportado.', 'success');
}
function exportarPDF() {
  if (!datosReporte.length) { showToast('No hay datos para exportar.', 'error'); return; }
  const { jsPDF } = window.jspdf;
  const doc = new jsPDF('l', 'mm', 'a4');
  doc.setFontSize(16); doc.text('Reporte de Pagos', 14, 15);
  doc.setFontSize(10); doc.text('Fecha: ' + new Date().toLocaleDateString('es-ES') + ' | Total: ' + resumen.total + ' pagos | Monto: ' + Number(resumen.monto).toFixed(2) + ' Bs', 14, 22);
  doc.autoTable({ startY: 28, head: [['ID','Persona','Concepto','Monto','Fecha','Método','Estado','Comprobante']], body: datosReporte.map(p => [p.id_pago, p.nombres+' '+p.apellidos, p.concepto, Number(p.monto).toFixed(2)+' Bs', p.fecha_pago, p.metodo_pago, p.estado, p.comprobante||'—']) });
  doc.save('reporte_pagos.pdf'); showToast('PDF exportado.', 'success');
}
cargarReporte();
</script>
<?php $content = ob_get_clean(); require_once appPath('cliente/layouts/AdminLayout.php');