<?php
declare(strict_types=1);
if (!isset($_SESSION['usuario']) || !in_array($_SESSION['tipo_persona'], ['Administrativo', 'Encargado'])) {
    header("Location: " . url('/login-admin'));
    exit();
}
$pageTitle = "Sugerencias";
ob_start();
?>
<style>
    .sugerencias-page .badge-estado {
        font-size: 0.7rem;
        padding: 0.25rem 0.5rem;
        font-weight: 600;
    }
    .sugerencias-page .badge-nuevo { background: rgba(59,130,246,0.15); color: #3b82f6; }
    .sugerencias-page .badge-leido { background: rgba(107,114,128,0.15); color: #6b7280; }
    .sugerencias-page .badge-respondido { background: rgba(34,197,94,0.15); color: #22c55e; }
    .sugerencias-page .msg-mensaje {
        font-size: 0.85rem;
        color: #6b7280;
        max-width: 300px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    body.dark .sugerencias-page .msg-mensaje { color: #94a3b8; }
</style>

<div class="container-fluid py-3 sugerencias-page">
    <div class="page-head mb-4">
        <h3><i class="fas fa-lightbulb me-2"></i>Sugerencias</h3>
        <p class="text-muted mb-0">Sugerencias recibidas desde el sitio web</p>
    </div>

    <!-- Filtros -->
    <div class="card shadow-sm mb-3">
        <div class="card-body py-2">
            <div class="row g-2 align-items-end">
                <div class="col-md-5">
                    <label class="form-label form-label-sm mb-1">Buscar</label>
                    <input type="text" id="filtroBuscar" class="form-control form-control-sm" placeholder="Buscar por nombre, correo, asunto...">
                </div>
                <div class="col-md-3">
                    <label class="form-label form-label-sm mb-1">Estado</label>
                    <select id="filtroEstado" class="form-select form-select-sm">
                        <option value="">Todos</option>
                        <option value="Nuevo">Nuevo</option>
                        <option value="Leido">Leído</option>
                        <option value="Respondido">Respondido</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button id="btnFiltrar" class="btn btn-primary btn-sm w-100">
                        <i class="fas fa-filter me-1"></i>Filtrar
                    </button>
                </div>
                <div class="col-md-2">
                    <span id="totalBadge" class="badge bg-secondary">0 registros</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla -->
    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Correo</th>
                            <th>Asunto</th>
                            <th>Mensaje</th>
                            <th>Estado</th>
                            <th>Fecha</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody">
                        <tr><td colspan="8" class="text-center py-4 text-muted"><i class="fas fa-spinner fa-spin me-2"></i>Cargando...</td></tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-white">
            <nav id="pagination"></nav>
        </div>
    </div>
</div>

<!-- Modal Detalle -->
<div class="modal fade" id="detalleModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title"><i class="fas fa-lightbulb me-2"></i>Detalle de Sugerencia</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-3">
                    <div class="col-md-6"><strong>Nombre:</strong> <span id="dNombre"></span></div>
                    <div class="col-md-6"><strong>Correo:</strong> <span id="dCorreo"></span></div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4"><strong>Teléfono:</strong> <span id="dTelefono"></span></div>
                    <div class="col-md-4"><strong>Asunto:</strong> <span id="dAsunto"></span></div>
                    <div class="col-md-4"><strong>Estado:</strong> <span id="dEstado"></span></div>
                </div>
                <div class="mb-3"><strong>Fecha:</strong> <span id="dFecha"></span></div>
                <div><strong>Mensaje:</strong><div id="dMensaje" class="border rounded p-3 mt-1 bg-light"></div></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-outline-success" id="btnMarcarLeido">
                    <i class="fas fa-check me-1"></i>Marcar como Leído
                </button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<script>
(function() {
    var currentPage = 1;
    var currentId = null;

    function cargar(pagina) {
        currentPage = pagina || 1;
        var params = new URLSearchParams();
        params.set('tipo', 'sugerencias');
        params.set('pagina', currentPage);
        var buscar = document.getElementById('filtroBuscar').value.trim();
        var estado = document.getElementById('filtroEstado').value;
        if (buscar) params.set('buscar', buscar);
        if (estado) params.set('estado', estado);

        fetch('<?= url('/sugerencias/listar') ?>?' + params.toString())
            .then(function(r) { return r.json(); })
            .then(function(res) {
                if (!res.success) { alert(res.message); return; }
                var d = res.data;
                document.getElementById('totalBadge').textContent = d.total + ' registros';
                renderTabla(d.registros);
                renderPaginacion(d.paginaActual, d.totalPaginas);
            })
            .catch(function() {
                document.getElementById('tableBody').innerHTML = '<tr><td colspan="8" class="text-center text-danger py-4">Error al cargar</td></tr>';
            });
    }

    function renderTabla(registros) {
        var tbody = document.getElementById('tableBody');
        if (registros.length === 0) {
            tbody.innerHTML = '<tr><td colspan="8" class="text-center py-4 text-muted">No se encontraron registros</td></tr>';
            return;
        }
        var html = '';
        registros.forEach(function(r) {
            var cls = r.estado === 'Nuevo' ? 'badge-nuevo' : r.estado === 'Leido' ? 'badge-leido' : 'badge-respondido';
            var fecha = r.fecha_creacion ? new Date(r.fecha_creacion).toLocaleDateString('es-BO') : '-';
            html += '<tr>' +
                '<td>#' + r.id + '</td>' +
                '<td>' + esc(r.nombre) + ' ' + esc(r.apellido) + '</td>' +
                '<td>' + esc(r.correo) + '</td>' +
                '<td>' + esc(r.asunto) + '</td>' +
                '<td class="msg-mensaje">' + esc(r.mensaje) + '</td>' +
                '<td><span class="badge-estado ' + cls + '">' + esc(r.estado) + '</span></td>' +
                '<td>' + fecha + '</td>' +
                '<td><button class="btn btn-outline-primary btn-sm btn-detalle" data-id="' + r.id + '" data-nombre="' + esc(r.nombre) + '" data-apellido="' + esc(r.apellido) + '" data-correo="' + esc(r.correo) + '" data-telefono="' + esc(r.telefono || '') + '" data-asunto="' + esc(r.asunto) + '" data-mensaje="' + esc(r.mensaje) + '" data-estado="' + esc(r.estado) + '" data-fecha="' + esc(r.fecha_creacion) + '"><i class="fas fa-eye"></i></button></td>' +
                '</tr>';
        });
        tbody.innerHTML = html;
        tbody.querySelectorAll('.btn-detalle').forEach(function(btn) {
            btn.addEventListener('click', function() { mostrarDetalle(this); });
        });
    }

    function mostrarDetalle(btn) {
        currentId = btn.dataset.id;
        document.getElementById('dNombre').textContent = btn.dataset.nombre + ' ' + btn.dataset.apellido;
        document.getElementById('dCorreo').textContent = btn.dataset.correo;
        document.getElementById('dTelefono').textContent = btn.dataset.telefono || '-';
        document.getElementById('dAsunto').textContent = btn.dataset.asunto;
        var cls = btn.dataset.estado === 'Nuevo' ? 'badge-nuevo' : btn.dataset.estado === 'Leido' ? 'badge-leido' : 'badge-respondido';
        document.getElementById('dEstado').innerHTML = '<span class="badge-estado ' + cls + '">' + btn.dataset.estado + '</span>';
        document.getElementById('dFecha').textContent = btn.dataset.fecha ? new Date(btn.dataset.fecha).toLocaleString('es-BO') : '-';
        document.getElementById('dMensaje').textContent = btn.dataset.mensaje;
        new bootstrap.Modal(document.getElementById('detalleModal')).show();
    }

    document.getElementById('btnMarcarLeido').addEventListener('click', function() {
        if (!currentId) return;
        fetch('<?= url('/sugerencias/actualizar-estado') ?>', {
            method: 'PUT',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ tipo: 'sugerencias', id: parseInt(currentId), estado: 'Leido' })
        })
        .then(function(r) { return r.json(); })
        .then(function(res) {
            if (res.success) {
                bootstrap.Modal.getInstance(document.getElementById('detalleModal')).hide();
                cargar(currentPage);
            }
        });
    });

    document.getElementById('btnFiltrar').addEventListener('click', function() { cargar(1); });
    document.getElementById('filtroBuscar').addEventListener('keydown', function(e) { if (e.key === 'Enter') cargar(1); });

    function renderPaginacion(actual, total) {
        if (total <= 1) { document.getElementById('pagination').innerHTML = ''; return; }
        var html = '<ul class="pagination pagination-sm justify-content-center mb-0">';
        html += '<li class="page-item' + (actual <= 1 ? ' disabled' : '') + '"><a class="page-link" href="#" data-page="' + (actual - 1) + '">&laquo;</a></li>';
        var start = Math.max(1, actual - 2), end = Math.min(total, actual + 2);
        for (var i = start; i <= end; i++) {
            html += '<li class="page-item' + (i === actual ? ' active' : '') + '"><a class="page-link" href="#" data-page="' + i + '">' + i + '</a></li>';
        }
        html += '<li class="page-item' + (actual >= total ? ' disabled' : '') + '"><a class="page-link" href="#" data-page="' + (actual + 1) + '">&raquo;</a></li></ul>';
        document.getElementById('pagination').innerHTML = html;
        document.getElementById('pagination').querySelectorAll('.page-link').forEach(function(link) {
            link.addEventListener('click', function(e) { e.preventDefault(); var p = parseInt(this.dataset.page); if (p >= 1 && p <= total) cargar(p); });
        });
    }

    function esc(s) { var d = document.createElement('div'); d.textContent = s || ''; return d.innerHTML; }

    cargar(1);
})();
</script>
<?php $content = ob_get_clean(); require_once appPath('cliente/layouts/AdminLayout.php'); ?>
