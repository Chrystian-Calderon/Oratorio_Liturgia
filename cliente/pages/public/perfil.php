<?php
declare(strict_types=1);

if (empty($_SESSION['correo'])) {
    header("Location: " . url('/login'));
    exit;
}

require_once appPath('servidor/perfil/obtener.php');

$correo = $_SESSION['correo'];
$persona = obtenerPerfil($correo);

if (!$persona) {
    header("Location: " . url('/'));
    exit;
}

$nombres = $persona['nombres'] ?? '';
$apellidos = $persona['apellidos'] ?? '';
$telefono = $persona['telefono'] ?? '';
$correoEmail = $persona['correo'] ?? $correo;
$direccion = $persona['direccion'] ?? '';

$pageTitle = "Mi Perfil";
$pageStyles = ['cliente/assets/css/perfil.css'];
ob_start();
?>
<div class="container-fluid py-3 perfil-page">
    <div class="page-head mb-4">
        <h3><i class="fas fa-user-circle me-2"></i>Mi Perfil</h3>
    </div>

    <div class="row g-4 justify-content-center">
        <!-- INFO CARD -->
        <div class="col-lg-4">
            <div class="perfil-card">
                <div class="perfil-header">
                    <div class="perfil-avatar" id="avatarInitials"></div>
                    <h4 id="avatarName"><?= htmlspecialchars($nombres . ' ' . $apellidos) ?></h4>
                    <p>Mi Cuenta</p>
                </div>
                <div class="perfil-body">
                    <div class="info-row">
                        <span class="info-label"><i class="fas fa-envelope me-1"></i> Correo</span>
                        <span class="info-value" id="infoCorreo"><?= htmlspecialchars($correoEmail) ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label"><i class="fas fa-phone me-1"></i> Teléfono</span>
                        <span class="info-value" id="infoTelefono"><?= htmlspecialchars($telefono ?: 'No registrado') ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label"><i class="fas fa-map-marker-alt me-1"></i> Dirección</span>
                        <span class="info-value" id="infoDireccion"><?= htmlspecialchars($direccion ?: 'No registrada') ?></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- FORM -->
        <div class="col-lg-8">
            <div class="perfil-card">
                <div class="perfil-body">
                    <form id="perfilForm" class="perfil-form">
                        <!-- DATOS PERSONALES -->
                        <div class="perfil-section">
                            <h5><i class="fas fa-user"></i> Datos Personales</h5>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Nombres *</label>
                                    <input type="text" class="form-control" id="nombres" value="<?= htmlspecialchars($nombres) ?>" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Apellidos *</label>
                                    <input type="text" class="form-control" id="apellidos" value="<?= htmlspecialchars($apellidos) ?>" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Teléfono</label>
                                    <input type="text" class="form-control" id="telefono" value="<?= htmlspecialchars($telefono) ?>" maxlength="20">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Correo Electrónico *</label>
                                    <input type="email" class="form-control" id="correo" value="<?= htmlspecialchars($correoEmail) ?>" required>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Dirección</label>
                                    <input type="text" class="form-control" id="direccion" value="<?= htmlspecialchars($direccion) ?>" maxlength="150">
                                </div>
                            </div>
                        </div>

                        <!-- CONTRASEÑA -->
                        <div class="perfil-section">
                            <h5><i class="fas fa-lock"></i> Cambiar Contraseña <small class="text-muted fw-normal">(opcional)</small></h5>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Nueva Contraseña</label>
                                    <div class="position-relative">
                                        <input type="password" class="form-control" id="password" placeholder="Mínimo 8 caracteres" minlength="8">
                                        <i class="fas fa-eye password-toggle" onclick="togglePass('password', this)"></i>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Confirmar Contraseña</label>
                                    <div class="position-relative">
                                        <input type="password" class="form-control" id="password2" placeholder="Repita la contraseña" minlength="8">
                                        <i class="fas fa-eye password-toggle" onclick="togglePass('password2', this)"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <button type="button" class="btn btn-outline-secondary btn-perfil" onclick="recargar()"><i class="fas fa-undo me-1"></i>Restablecer</button>
                            <button type="submit" class="btn btn-primary btn-perfil"><i class="fas fa-save me-1"></i>Guardar Cambios</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="toasts" class="position-fixed top-0 end-0 p-3" style="z-index:2000"></div>

<script>
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

function togglePass(id, icon) {
    const input = document.getElementById(id);
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.replace('fa-eye', 'fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.replace('fa-eye-slash', 'fa-eye');
    }
}

function recargar() { location.reload(); }

function inicializarIniciales() {
    const nombres = document.getElementById('nombres').value.trim();
    const apellidos = document.getElementById('apellidos').value.trim();
    const partes = (nombres + ' ' + apellidos).split(/\s+/).filter(Boolean);
    const iniciales = partes.map(p => p.charAt(0).toUpperCase()).join('').substring(0, 2);
    document.getElementById('avatarInitials').textContent = iniciales;
}

inicializarIniciales();

document.getElementById('perfilForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const pass = document.getElementById('password').value;
    const pass2 = document.getElementById('password2').value;

    if (pass && pass !== pass2) {
        showToast('Las contraseñas no coinciden.', 'error');
        return;
    }
    if (pass && pass.length < 8) {
        showToast('La contraseña debe tener al menos 8 caracteres.', 'error');
        return;
    }

    const data = {
        nombres: document.getElementById('nombres').value.trim(),
        apellidos: document.getElementById('apellidos').value.trim(),
        telefono: document.getElementById('telefono').value.trim(),
        correo: document.getElementById('correo').value.trim(),
        direccion: document.getElementById('direccion').value.trim(),
        password: pass
    };

    fetch('<?= url('/perfil/actualizar') ?>', {
        method: 'PUT',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify(data)
    })
    .then(r => r.json())
    .then(d => {
        if (!d.success) { showToast(d.message, 'error'); return; }
        showToast('Perfil actualizado correctamente.', 'success');
        document.getElementById('password').value = '';
        document.getElementById('password2').value = '';
        document.getElementById('infoCorreo').textContent = data.correo;
        document.getElementById('infoTelefono').textContent = data.telefono || 'No registrado';
        document.getElementById('infoDireccion').textContent = data.direccion || 'No registrada';
        document.getElementById('avatarName').textContent = data.nombres + ' ' + data.apellidos;
        inicializarIniciales();
        setTimeout(() => location.reload(), 1500);
    })
    .catch(() => showToast('Error al actualizar el perfil.', 'error'));
});
</script>
<?php $content = ob_get_clean(); require_once appPath('cliente/layouts/PublicLayout.php');
