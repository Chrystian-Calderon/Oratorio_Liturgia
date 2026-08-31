<?php
$errores = $_SESSION['errors'] ?? [];
$old = $_SESSION['old'] ?? [];

unset($_SESSION['errors'], $_SESSION['old']);

if (isset($_SESSION['notificacion'])) {
    $notificacion = $_SESSION['notificacion'];
    require_once appPath('cliente/components/Notificacion.php');
    mostrarNotificacion($notificacion['mensaje'], $notificacion['tipo']);
    unset($_SESSION['notificacion']);
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>
    <link rel="stylesheet" href="<?= url('cliente/assets/css/bootstrap.min.css') ?>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= url('cliente/assets/css/notificacion.css') ?>">
    <style>
        body {
            background: linear-gradient(135deg, #4e73df, #1cc88a);
        }
        .registro-box {
            width: 450px;
        }
    </style>
</head>
<body>
    <div class="vh-100 d-flex justify-content-center align-items-center">
        <div class="registro-box bg-white p-5 rounded-5 shadow border border-primary">
            <div class="text-center mb-3">
                <img src="<?= url('cliente/assets/img/logo.jpg') ?>" width=100">
            </div>
            <h2 class="text-center text-primary mb-4">
                Crear Cuenta
            </h2>

            <form action="<?= url('/registrarse') ?>" method="POST">
                <div class="mb-3">
                    <label class="form-label">Nombres:</label>
                    <input type="text"
                        class="form-control border border-primary"
                        name="txtnombre"
                        required>
                    <?php if (isset($errores['txtnombre'])): ?>
                        <div class="text-danger mt-1">
                            <?= htmlspecialchars($errores['txtnombre']) ?>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="mb-3">
                    <label class="form-label">Apellidos:</label>
                    <input type="text"
                        class="form-control border border-primary"
                        name="txtapellidos"
                        required>
                    <?php if (isset($errores['txtapellidos'])): ?>
                        <div class="text-danger mt-1">
                            <?= htmlspecialchars($errores['txtapellidos']) ?>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="mb-3">
                    <label class="form-label">Carnet de Identidad:</label>
                    <input type="text"
                        class="form-control border border-primary"
                        name="txtci"
                        required>
                    <?php if (isset($errores['txtci'])): ?>
                        <div class="text-danger mt-1">
                            <?= htmlspecialchars($errores['txtci']) ?>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="mb-3">
                    <label class="form-label">Correo:</label>
                    <input type="email"
                        class="form-control border border-primary"
                        name="txtcorreo"
                        required>
                    <?php if (isset($errores['txtcorreo'])): ?>
                        <div class="text-danger mt-1">
                            <?= htmlspecialchars($errores['txtcorreo']) ?>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="mb-3">
                    <label class="form-label">Contraseña:</label>
                    <input type="password"
                        class="form-control border border-primary"
                        name="txtpassword"
                        required>
                    <?php if (isset($errores['txtpassword'])): ?>
                        <div class="text-danger mt-1">
                            <?= htmlspecialchars($errores['txtpassword']) ?>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="d-grid">
                    <button class="btn btn-primary" type="submit">
                        Registrarse
                    </button>
                </div>
            </form>

            <div class="mt-3 text-center">
                <a href="<?= url('/login') ?>" class="text-primary fw-bold">
                    Ya tengo cuenta
                </a>
            </div>
        </div>
    </div>

    <script src="<?= url('cliente/assets/js/notificacion.js') ?>"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>