<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #4e73df, #1cc88a);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
        }

        .login-card {
            background-color: #fff;
            padding: 2rem;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 400px;
        }

        h1 {
            text-align: center;
            margin-bottom: 2rem;
            font-weight: 600;
            color: #333;
        }

        .form-control:focus {
            border-color: #4e73df;
            box-shadow: 0 0 8px rgba(78, 115, 223, 0.4);
        }

        .input-group-text {
            background-color: #fff;
            border-left: none;
            cursor: pointer;
        }

        #boton-iniciar-sesion {
            background-color: #4e73df;
            border: none;
            font-weight: 500;
            transition: 0.3s;
        }

        #boton-iniciar-sesion:hover {
            background-color: #2e59d9;
            box-shadow: 0 5px 15px rgba(78, 115, 223, 0.4);
        }

        .mensaje {
            text-align: center;
            margin-top: 1rem;
            font-size: 14px;
            color: #666;
        }

        .mensaje a {
            color: #4e73df;
            text-decoration: none;
        }

        .mensaje a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <div class="login-card">
        <h1>Iniciar Sesión</h1>
        <form action="<?php url('/login-admin') ?>" method="POST">
            <div class="mb-3">
                <label for="correo" class="form-label">Correo electrónico</label>
                <input type="email" class="form-control" id="correo" name="txtcorreo" placeholder="ejemplo@correo.com" required>
            </div>
            <div class="mb-3">
                <label for="contraseña" class="form-label">Contraseña</label>
                <div class="input-group">
                    <input type="password" class="form-control" id="contraseña" name="txtpassword" placeholder="********" required>
                    <span class="input-group-text" id="ojo-contraseña">
                        <i class="fas fa-eye" id="icono-ojo"></i>
                    </span>
                </div>
            </div>
            <button type="submit" class="btn btn-primary w-100" id="boton-iniciar-sesion">Iniciar Sesión</button>
        </form>
        <div class="mensaje">
            ¿No tienes cuenta? <a href="#">Regístrate aquí</a>
        </div>
    </div>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const ojoContraseña = document.getElementById('ojo-contraseña');
        const iconoOjo = document.getElementById('icono-ojo');
        const inputContraseña = document.getElementById('contraseña');

        ojoContraseña.addEventListener('click', () => {
            if (inputContraseña.type === 'password') {
                inputContraseña.type = 'text';
                iconoOjo.classList.remove('fa-eye');
                iconoOjo.classList.add('fa-eye-slash');
            } else {
                inputContraseña.type = 'password';
                iconoOjo.classList.remove('fa-eye-slash');
                iconoOjo.classList.add('fa-eye');
            }
        });
    </script>
</body>
</html>
