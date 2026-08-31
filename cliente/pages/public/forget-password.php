<?php
$resultado = $_SESSION['recuperar'] ?? null;
unset($_SESSION['recuperar']);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar contraseña</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- link forget-password.css -->
    <link rel="stylesheet" href="<?= url('cliente/assets/css/forget-password.css') ?>">
</head>
<body>

    <div class="card-recuperar">

    <?php if ($resultado): ?>

        <?php if ($resultado['tipo'] === 'success'): ?>

            <i class="bi bi-check-circle-fill text-success icono"></i>
            <h2 class="titulo text-success">Enlace generado</h2>
            <p class="texto">
                <?= htmlspecialchars($resultado['mensaje']) ?>
            </p>
            <a href="<?= htmlspecialchars($resultado['link']) ?>" class="btn btn-primary w-100 btn-recuperar">
                <i class="bi bi-key-fill"></i>
                Recuperar contraseña
            </a>

        <?php else: ?>

            <i class="bi bi-x-circle-fill text-danger icono"></i>
            <h2 class="titulo text-danger">Correo no encontrado</h2>
            <p class="texto">
                <?= htmlspecialchars($resultado['mensaje']) ?>
            </p>
            <a href="<?= url('/recuperar-password') ?>" class="btn btn-primary w-100 btn-recuperar">
                <i class="bi bi-arrow-repeat"></i>
                Intentar de nuevo
            </a>

        <?php endif; ?>

    <?php else: ?>

        <i class="bi bi-shield-lock-fill icono"></i>
        <h2 class="titulo">Recuperar contraseña</h2>
        <p class="texto">
            Ingresa tu correo electrónico y te enviaremos un enlace para restablecer tu contraseña.
        </p>

        <form action="<?= url('/recuperar-password') ?>" method="POST">
            <div class="mb-3">
                <label class="form-label">Correo electrónico</label>
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="bi bi-envelope-fill"></i>
                    </span>
                    <input 
                        type="email" 
                        name="correo" 
                        class="form-control"
                        placeholder="ejemplo@gmail.com"
                        required
                    >

                </div>
            </div>

            <button type="submit" class="btn btn-primary btn-recuperar w-100">
                <i class="bi bi-send-fill"></i>
                Enviar enlace
            </button>
        </form>

    <?php endif; ?>

    </div>
</body>
</html>