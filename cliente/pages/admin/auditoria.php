<?php
declare(strict_types=1);
if (!isset($_SESSION['usuario']) || !in_array($_SESSION['tipo_persona'], ['Administrativo', 'Encargado'])) {
    header("Location: " . url('/login-admin'));
    exit();
}
$pageTitle = "Auditoría - Registro de Actividad";
ob_start();
?>
<style>
    .audit-page .badge-action {
        font-size: 0.7rem;
        padding: 0.25rem 0.5rem;
        font-weight: 600;
        text-transform: uppercase;
    }
    .audit-page .badge-insert { background: rgba(34,197,94,0.15); color: #22c55e; }
    .audit-page .badge-update { background: rgba(59,130,246,0.15); color: #3b82f6; }
    .audit-page .badge-delete { background: rgba(239,68,68,0.15); color: #ef4444; }
    .audit-page .audit-id {
        font-family: monospace;
        color: #6b7280;
        font-size: 0.85rem;
    }
    .audit-page .audit-user {
        font-weight: 500;
    }
    .audit-page .audit-desc {
        color: #6b7280;
        font-size: 0.85rem;
        max-width: 300px;
    }
    .audit-page .audit-fecha {
        font-size: 0.82rem;
        color: #6b7280;
        white-space: nowrap;
    }
    .audit-page .stat-card {
        background: #fff;
        border-radius: 10px;
        padding: 1.2rem;
        box-shadow: 0 1px 4px rgba(0,0,0,0.06);
        text-align: center;
    }
    .audit-page .stat-card .stat-number {
        font-size: 1.8rem;
        font-weight: 700;
    }
    .audit-page .stat-card .stat-label {
        font-size: 0.8rem;
        color: #6b7280;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    body.dark .audit-page .stat-card { background: #1e293b; }
    body.dark .audit-page .audit-desc { color: #94a3b8; }
    body.dark .audit-page .audit-fecha { color: #94a3b8; }
    body.dark .audit-page .audit-id { color: #64748b; }
    .audit-page .json-detail {
        font-family: monospace;
        font-size: 0.75rem;
        background: rgba(0,0,0,0.04);
        border-radius: 4px;
        padding: 4px 8px;
        max-height: 120px;
        overflow-y: auto;
        white-space: pre-wrap;
        word-break: break-all;
    }
    body.dark .audit-page .json-detail { background: rgba(255,255,255,0.05); color: #94a3b8; }
    .audit-page .pagination .page-link { font-size: 0.85rem; }
</style>

<div class="container-fluid py-3 audit-page">
    <div class="page-head mb-4">
        <h3><i class="fas fa-shield-alt me-2"></i>Auditoría del Sistema</h3>
        <p class="text-muted mb-0">Registro de todas las operaciones realizadas en las tablas sensibles del sistema</p>
    </div>

    <!-- Tarjetas de resumen -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-number text-primary" id="statTotal">-</div>
                <div class="stat-label">Total Registros</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-number text-success" id="statInsert">-</div>
                <div class="stat-label">Inserciones</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-number text-info" id="statUpdate">-</div>
                <div class="stat-label">Actualizaciones</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-number text-danger" id="statDelete">-</div>
                <div class="stat-label">Eliminaciones</div>
            </div>
        </div>
    </div>

    <!-- Filtros -->
    <div class="card shadow-sm mb-3">
        <div class="card-body py-2">
            <div class="row g-2 align-items-end">
                <div class="col-md-4">
                    <label class="form-label form-label-sm mb-1">Buscar</label>
                    <input type="text" id="filtroBuscar" class="form-control form-control-sm" placeholder="Buscar en descripción, usuario...">
                </div>
                <div class="col-md-3">
                    <label class="form-label form-label-sm mb-1">Acción</label>
                    <select id="filtroAccion" class="form-select form-select-sm">
                        <option value="">Todas</option>
                        <option value="INSERT">INSERT</option>
                        <option value="UPDATE">UPDATE</option>
                        <option value="DELETE">DELETE</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label form-label-sm mb-1">Tabla</label>
                    <select id="filtroTabla" class="form-select form-select-sm">
                        <option value="">Todas</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button id="btnFiltrar" class="btn btn-primary btn-sm w-100">
                        <i class="fas fa-filter me-1"></i>Filtrar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla de auditoría -->
    <div class="card shadow-sm">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h6 class="mb-0"><i class="fas fa-list-alt me-2"></i>Registro de Actividad</h6>
            <span id="totalRegistrosBadge" class="badge bg-secondary">0 registros</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Usuario</th>
                            <th>Acción</th>
                            <th>Tabla Afectada</th>
                            <th>Registro ID</th>
                            <th>Fecha / Hora</th>
                            <th>Descripción</th>
                            <th>Detalle</th>
                        </tr>
                    </thead>
                    <tbody id="auditTableBody">
                        <tr>
                            <td colspan="8" class="text-center py-4 text-muted">
                                <i class="fas fa-spinner fa-spin me-2"></i>Cargando datos...
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-white">
            <nav id="auditPagination"></nav>
        </div>
    </div>
</div>

<!-- Modal Detalle -->
<div class="modal fade" id="detalleModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title"><i class="fas fa-info-circle me-2"></i>Detalle de Auditoría</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>ID:</strong> <span id="detalleId"></span>
                    </div>
                    <div class="col-md-6">
                        <strong>Usuario:</strong> <span id="detalleUsuario"></span>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4">
                        <strong>Acción:</strong> <span id="detalleAccion"></span>
                    </div>
                    <div class="col-md-4">
                        <strong>Tabla:</strong> <span id="detalleTabla"></span>
                    </div>
                    <div class="col-md-4">
                        <strong>Registro ID:</strong> <span id="detalleRegistroId"></span>
                    </div>
                </div>
                <div class="mb-3">
                    <strong>Fecha/Hora:</strong> <span id="detalleFecha"></span>
                </div>
                <div class="mb-3">
                    <strong>Descripción:</strong> <span id="detalleDesc"></span>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <strong>Valores Anteriores:</strong>
                        <div id="detalleAnteriores" class="json-detail mt-1">N/A</div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Valores Nuevos:</strong>
                        <div id="detalleNuevos" class="json-detail mt-1">N/A</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
(function() {
    var currentPage = 1;
    var baseUrl = '<?= url('/auditoria-data') ?>';

    function cargarDatos(pagina) {
        currentPage = pagina || 1;
        var params = new URLSearchParams();
        params.set('pagina', currentPage);
        var buscar = document.getElementById('filtroBuscar').value.trim();
        var accion = document.getElementById('filtroAccion').value;
        var tabla = document.getElementById('filtroTabla').value;
        if (buscar) params.set('buscar', buscar);
        if (accion) params.set('accion', accion);
        if (tabla) params.set('tabla', tabla);

        fetch(baseUrl + '?' + params.toString())
            .then(function(r) { return r.json(); })
            .then(function(res) {
                if (!res.success) {
                    alert(res.message || 'Error al cargar datos');
                    return;
                }
                var d = res.data;
                document.getElementById('statTotal').textContent = d.totales.total || 0;
                document.getElementById('statInsert').textContent = d.totales.total_insert || 0;
                document.getElementById('statUpdate').textContent = d.totales.total_update || 0;
                document.getElementById('statDelete').textContent = d.totales.total_delete || 0;
                document.getElementById('totalRegistrosBadge').textContent = d.totalRegistros + ' registros';

                // Poblar filtro de tablas
                var selTabla = document.getElementById('filtroTabla');
                var valActual = selTabla.value;
                if (selTabla.options.length <= 1 && d.tablas.length > 0) {
                    d.tablas.forEach(function(t) {
                        var opt = document.createElement('option');
                        opt.value = t.tabla_afectada;
                        opt.textContent = t.tabla_afectada;
                        selTabla.appendChild(opt);
                    });
                    selTabla.value = valActual;
                }

                renderTabla(d.registros);
                renderPaginacion(d.paginaActual, d.totalPaginas);
            })
            .catch(function(e) {
                console.error(e);
                document.getElementById('auditTableBody').innerHTML =
                    '<tr><td colspan="8" class="text-center text-danger py-4">Error al cargar datos</td></tr>';
            });
    }

    function renderTabla(registros) {
        var tbody = document.getElementById('auditTableBody');
        if (registros.length === 0) {
            tbody.innerHTML = '<tr><td colspan="8" class="text-center py-4 text-muted">No se encontraron registros</td></tr>';
            return;
        }
        var html = '';
        registros.forEach(function(r) {
            var badgeClass = r.accion === 'INSERT' ? 'badge-insert' :
                             r.accion === 'UPDATE' ? 'badge-update' : 'badge-delete';
            var fecha = r.fecha_hora ? new Date(r.fecha_hora).toLocaleString('es-BO') : '-';
            html += '<tr>' +
                '<td class="audit-id">#' + r.id_auditoria + '</td>' +
                '<td class="audit-user">' + esc(r.usuario_mysql || '-') + '</td>' +
                '<td><span class="badge-action ' + badgeClass + '">' + esc(r.accion) + '</span></td>' +
                '<td>' + esc(r.tabla_afectada) + '</td>' +
                '<td>' + (r.registro_id || '-') + '</td>' +
                '<td class="audit-fecha">' + fecha + '</td>' +
                '<td class="audit-desc">' + esc(r.descripcion || '-') + '</td>' +
                '<td><button class="btn btn-outline-secondary btn-sm btn-detalle" ' +
                    'data-id="' + r.id_auditoria + '" ' +
                    'data-usuario="' + esc(r.usuario_mysql || '') + '" ' +
                    'data-accion="' + esc(r.accion) + '" ' +
                    'data-tabla="' + esc(r.tabla_afectada) + '" ' +
                    'data-registro="' + (r.registro_id || '') + '" ' +
                    'data-fecha="' + esc(r.fecha_hora || '') + '" ' +
                    'data-desc="' + esc(r.descripcion || '') + '" ' +
                    'data-anteriores="' + esc(r.valores_anteriores || '') + '" ' +
                    'data-nuevos="' + esc(r.valores_nuevos || '') + '" ' +
                    'title="Ver detalle"><i class="fas fa-eye"></i></button></td>' +
                '</tr>';
        });
        tbody.innerHTML = html;

        tbody.querySelectorAll('.btn-detalle').forEach(function(btn) {
            btn.addEventListener('click', function() {
                mostrarDetalle(this);
            });
        });
    }

    function mostrarDetalle(btn) {
        document.getElementById('detalleId').textContent = '#' + btn.dataset.id;
        document.getElementById('detalleUsuario').textContent = btn.dataset.usuario;
        var a = btn.dataset.accion;
        var cls = a === 'INSERT' ? 'badge-insert' : a === 'UPDATE' ? 'badge-update' : 'badge-delete';
        document.getElementById('detalleAccion').innerHTML = '<span class="badge-action ' + cls + '">' + esc(a) + '</span>';
        document.getElementById('detalleTabla').textContent = btn.dataset.tabla;
        document.getElementById('detalleRegistroId').textContent = btn.dataset.registro || 'N/A';
        document.getElementById('detalleFecha').textContent = btn.dataset.fecha ? new Date(btn.dataset.fecha).toLocaleString('es-BO') : '-';
        document.getElementById('detalleDesc').textContent = btn.dataset.desc || '-';
        document.getElementById('detalleAnteriores').textContent = formatearJson(btn.dataset.anteriores);
        document.getElementById('detalleNuevos').textContent = formatearJson(btn.dataset.nuevos);
        new bootstrap.Modal(document.getElementById('detalleModal')).show();
    }

    function formatearJson(str) {
        if (!str || str === 'null' || str === '') return 'N/A';
        try { return JSON.stringify(JSON.parse(str), null, 2); }
        catch(e) { return str; }
    }

    function renderPaginacion(actual, total) {
        if (total <= 1) {
            document.getElementById('auditPagination').innerHTML = '';
            return;
        }
        var html = '<ul class="pagination pagination-sm justify-content-center mb-0">';
        html += '<li class="page-item' + (actual <= 1 ? ' disabled' : '') + '">' +
                '<a class="page-link" href="#" data-page="' + (actual - 1) + '">&laquo;</a></li>';

        var start = Math.max(1, actual - 2);
        var end = Math.min(total, actual + 2);
        for (var i = start; i <= end; i++) {
            html += '<li class="page-item' + (i === actual ? ' active' : '') + '">' +
                    '<a class="page-link" href="#" data-page="' + i + '">' + i + '</a></li>';
        }

        html += '<li class="page-item' + (actual >= total ? ' disabled' : '') + '">' +
                '<a class="page-link" href="#" data-page="' + (actual + 1) + '">&raquo;</a></li>';
        html += '</ul>';
        document.getElementById('auditPagination').innerHTML = html;

        document.getElementById('auditPagination').querySelectorAll('.page-link').forEach(function(link) {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                var p = parseInt(this.dataset.page);
                if (p >= 1 && p <= total) cargarDatos(p);
            });
        });
    }

    function esc(s) {
        var div = document.createElement('div');
        div.textContent = s;
        return div.innerHTML;
    }

    document.getElementById('btnFiltrar').addEventListener('click', function() { cargarDatos(1); });
    document.getElementById('filtroBuscar').addEventListener('keydown', function(e) {
        if (e.key === 'Enter') cargarDatos(1);
    });

    cargarDatos(1);
})();
</script>
<?php $content = ob_get_clean(); require_once appPath('cliente/layouts/AdminLayout.php'); ?>
