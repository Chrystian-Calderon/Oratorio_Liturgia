<?php
$errores = $_SESSION['errors'] ?? [];
$old = $_SESSION['old'] ?? [];

unset($_SESSION['errors'], $_SESSION['old']);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
<!--CDn CSS Bootrap-->
    <link rel="stylesheet" href="../assets/librerias/bootstrap.min.css">
    <link rel="stylesheet" href="../css/login.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="vh-100 d-flex justify-content-center align-items-center ">
        <div class="col-md-5 p-5 shadow-sm border rounded-5 border-primary
        bg-white">
        <!-- Logo -->
            <div class="text-center mb-3">
                <img src="../portafolio/img/logo.jpg" alt="Logo Oratorio y Liturgia" class="logo-form">
            </div>
            <h2 class="text-center mb-4 text-primary">ORATORIO Y LITURGIA</h2>

            <!--Formulario de Login de Acceso-->
                <form action="<?php url('/login') ?>" method="POST">
                    <div class="mb-3">
                        <label for="exampleInputEmail" class="form-label">Correo:</label>
                        <input type="email" class="form-control border border-primary <?= isset($errores['txtemail']) ? 'is-invalid' : '' ?>" name="txtemail" value="<?= htmlspecialchars($old['txtemail'] ?? '') ?>" required>
                        <?php if (isset($errores['txtemail'])): ?>
                            <div class="text-danger mt-1">
                                <?= htmlspecialchars($errores['txtemail']) ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="mb-3">
                        <label for="exampleInputPassword1" class="form-label">Password:</label>
                        <input type="password" class="form-control border border-primary <?= isset($errores['txtpassword']) ? 'is-invalid' : '' ?>" name="txtpassword" required>
                        <?php if (isset($errores['txtpassword'])): ?>
                            <div class="text-danger mt-1">
                                <?= htmlspecialchars($errores['txtpassword']) ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <p class="small"><a class="text-primary" href="../cliente/forget-password.php">Has olvidado tu contraseña?</a></p>

                    <div class="d-grid">
                        <button class="btn btn-primary" type="submit">Acceso</button>
                    </div>
                </form>
                <div class="mt-3">
                    <p class="mb-0 text-center"> No tienes una cuenta? <a href="registrarse.php" class="text-primary fw-bold">Regístrate</a></p>
                </div>
<!--CDN JS Bootrap-->
    <script src="../assets/librerias/bootstrap.bundle.min.js"></script>
</body>
</html>