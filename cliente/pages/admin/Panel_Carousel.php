<?php
declare(strict_types=1);
if (!isset($_SESSION['usuario']) || !in_array($_SESSION['tipo_persona'], ['Administrativo', 'Encargado'])) {
    header("Location: " . url('/login-admin'));
    exit();
}
$pageTitle = "Panel del Carrusel";
$pageStyles = ['cliente/assets/css/carousel-admin.css'];
ob_start();
?>
<div class="container-fluid py-3 carousel-admin-page">
    <div class="page-head d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
        <h3 class="mb-0"><i class="fas fa-images me-2"></i>Panel del Carrusel</h3>
        <div class="d-flex gap-2">
            <a href="<?= url('/') ?>" class="btn btn-outline-secondary btn-sm" target="_blank">
                <i class="fas fa-eye me-1"></i>Ver en Sitio
            </a>
            <button class="btn btn-primary btn-sm" onclick="guardarTodos()">
                <i class="fas fa-save me-1"></i>Guardar Cambios
            </button>
        </div>
    </div>

    <p class="text-muted mb-4">
        <i class="fas fa-info-circle me-1"></i>
        Edite los títulos, subtítulos, descripciones e imágenes del carrusel principal.
        Los cambios se reflejan en la página de inicio y en su página de detalle.
    </p>

    <div class="row" id="slidesContainer">
        <div class="col-12 text-center text-muted py-5">Cargando...</div>
    </div>
</div>

<!-- Toast -->
<div id="toasts" class="position-fixed top-0 end-0 p-3" style="z-index:2000"></div>

<script>
let slidesData = [];

function showToast(msg, type) {
    const el = document.createElement('div');
    el.className = 'toast align-items-center text-bg-' + (type === 'error' ? 'danger' : 'success') + ' border-0';
    el.setAttribute('role', 'alert');
    el.innerHTML = '<div class="d-flex"><div class="toast-body">' + msg + '</div><button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button></div>';
    document.getElementById('toasts').appendChild(el);
    const t = new bootstrap.Toast(el, {delay: 3500});
    t.show();
    el.addEventListener('hidden.bs.toast', () => el.remove());
}

function cargarSlides() {
    fetch('<?= url('/panel-carousel-data') ?>')
        .then(r => r.json())
        .then(d => {
            if (!d.success) { showToast(d.message, 'error'); return; }
            slidesData = d.data.slides;
            renderSlides();
        })
        .catch(() => showToast('Error al cargar los slides.', 'error'));
}

function renderSlides() {
    const container = document.getElementById('slidesContainer');
    if (!slidesData.length) {
        container.innerHTML = '<div class="col-12 text-center text-muted py-5">No hay slides configurados.</div>';
        return;
    }
    container.innerHTML = slidesData.map((s, i) => {
        const imgSrc = s.imagen
            ? '<?= url('') ?>cliente/assets/img/carusel/' + s.imagen + '?t=' + Date.now()
            : '';
        return `
        <div class="col-lg-6 col-xl-3 mb-4">
            <div class="card slide-card h-100 ${s.activo ? '' : 'slide-inactivo'}">
                <div class="slide-img-wrapper">
                    ${imgSrc
                        ? '<img src="' + imgSrc + '" class="slide-img" alt="Slide ' + s.id + '">'
                        : '<div class="slide-placeholder"><i class="fas fa-image"></i><span>Sin imagen</span></div>'
                    }
                    <span class="slide-badge">Slide ${s.id}</span>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Título</label>
                        <input type="text" class="form-control slide-titulo" data-id="${s.id}" value="${escHtml(s.titulo)}" maxlength="100">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Subtítulo</label>
                        <input type="text" class="form-control slide-subtitulo" data-id="${s.id}" value="${escHtml(s.subtitulo)}" maxlength="200">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Descripción</label>
                        <textarea class="form-control slide-descripcion" data-id="${s.id}" rows="4" maxlength="1000">${escHtml(s.descripcion || '')}</textarea>
                        <div class="form-text">Texto que se muestra en la página de detalle al pulsar "Leer más".</div>
                    </div>
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <label class="form-label mb-0 fw-semibold">Activo</label>
                        <div class="form-check form-switch">
                            <input class="form-check-input slide-activo" type="checkbox" data-id="${s.id}" ${s.activo ? 'checked' : ''}>
                        </div>
                    </div>
                    <div class="d-flex gap-2">
                        <label class="btn btn-outline-primary btn-sm flex-fill mb-0">
                            <i class="fas fa-upload me-1"></i>${s.imagen ? 'Cambiar' : 'Subir'}
                            <input type="file" class="d-none slide-file" data-id="${s.id}" accept="image/jpeg,image/png,image/webp" onchange="subirImagen(this, ${s.id})">
                        </label>
                        ${s.imagen
                            ? '<button class="btn btn-outline-danger btn-sm" onclick="eliminarImagen(' + s.id + ')"><i class="fas fa-trash"></i></button>'
                            : ''
                        }
                    </div>
                </div>
            </div>
        </div>`;
    }).join('');
}

function escHtml(str) {
    const d = document.createElement('div');
    d.textContent = str || '';
    return d.innerHTML;
}

function subirImagen(input, slideId) {
    if (!input.files || !input.files[0]) return;
    const file = input.files[0];
    if (file.size > 5 * 1024 * 1024) {
        showToast('La imagen no debe exceder 5 MB.', 'error');
        input.value = '';
        return;
    }
    const fd = new FormData();
    fd.append('imagen', file);
    fd.append('slide_id', slideId);

    showToast('Subiendo imagen...', 'success');

    fetch('<?= url('/panel-carousel/subir-imagen') ?>', {method: 'POST', body: fd})
        .then(r => r.json())
        .then(d => {
            if (!d.success) { showToast(d.message, 'error'); return; }
            slidesData = d.data.slides.slides;
            renderSlides();
            showToast('Imagen subida correctamente.', 'success');
        })
        .catch(() => showToast('Error al subir la imagen.', 'error'));
}

function eliminarImagen(slideId) {
    if (!confirm('¿Eliminar la imagen de este slide?')) return;

    fetch('<?= url('/panel-carousel/eliminar-imagen') ?>', {
        method: 'DELETE',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({slide_id: slideId})
    })
    .then(r => r.json())
    .then(d => {
        if (!d.success) { showToast(d.message, 'error'); return; }
        slidesData = d.data.slides;
        renderSlides();
        showToast('Imagen eliminada.', 'success');
    })
    .catch(() => showToast('Error al eliminar la imagen.', 'error'));
}

function guardarTodos() {
    const promises = slidesData.map(s => {
        const titulo = document.querySelector('.slide-titulo[data-id="' + s.id + '"]').value.trim();
        const subtitulo = document.querySelector('.slide-subtitulo[data-id="' + s.id + '"]').value.trim();
        const descripcion = document.querySelector('.slide-descripcion[data-id="' + s.id + '"]').value.trim();
        const activo = document.querySelector('.slide-activo[data-id="' + s.id + '"]').checked;

        if (!titulo) {
            showToast('El título del Slide ' + s.id + ' es obligatorio.', 'error');
            return Promise.reject();
        }

        return fetch('<?= url('/panel-carousel/actualizar') ?>', {
            method: 'PUT',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({id: s.id, titulo, subtitulo, descripcion, activo})
        }).then(r => r.json());
    });

    Promise.all(promises).then(results => {
        const last = results[results.length - 1];
        if (last && last.success) {
            slidesData = last.data.slides;
            showToast('Todos los cambios guardados.', 'success');
        }
    }).catch(() => {});
}

cargarSlides();
</script>
<?php $content = ob_get_clean(); require_once appPath('cliente/layouts/AdminLayout.php');