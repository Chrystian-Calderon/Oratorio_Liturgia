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

        <i class="bi bi-shield-lock-fill icono"></i>
        <h2 class="titulo">Recuperar contraseña</h2>
        <p class="texto">
            Ingresa tu correo electrónico y te enviaremos un enlace para restablecer tu contraseña.
        </p>

        <form action="../servidor/recuperar.php" method="POST">
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
    </div>
</body>
</html>