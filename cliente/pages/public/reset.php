<?php
$resultado = $_SESSION['reset'] ?? null;
unset($_SESSION['reset']);

$token = $_GET['token'] ?? '';
?>
<?php if ($resultado && $resultado['tipo'] === 'error'): ?>
<!DOCTYPE html>
<html lang='es'>
<head>

    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>

    <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css' rel='stylesheet'>

    <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css'>

    <style>

        body{
            height:100vh;
            display:flex;
            justify-content:center;
            align-items:center;
            background:linear-gradient(135deg,#0f172a,#1e293b);
            font-family:Arial;
        }

        .card-error{
            width:100%;
            max-width:420px;
            background:#fff;
            border-radius:25px;
            padding:40px;
            text-align:center;
            box-shadow:0 10px 30px rgba(0,0,0,0.3);
        }

        .icon-error{
            font-size:70px;
            color:#dc3545;
            margin-bottom:20px;
        }

    </style>

</head>

<body>

    <div class='card-error'>

        <i class='bi bi-x-circle-fill icon-error'></i>

        <h2 class='text-danger fw-bold'>
            Token inválido
        </h2>

        <p class='text-secondary mt-3'>
            <?= htmlspecialchars($resultado['mensaje']) ?>
        </p>

    </div>

</body>
</html>
<?php else: ?>
<!DOCTYPE html>
<html lang="es">
<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Restablecer contraseña</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>

        *{
            margin:0;
            padding:0;
            box-sizing:border-box;
        }

        body{
            min-height:100vh;
            display:flex;
            justify-content:center;
            align-items:center;
            padding:20px;
            background:
            linear-gradient(
                135deg,
                #0f172a,
                #1e293b,
                #334155
            );

            font-family:Arial, Helvetica, sans-serif;
        }

        .card-reset{

            width:100%;
            max-width:450px;

            background:rgba(255,255,255,0.95);

            backdrop-filter:blur(10px);

            border-radius:28px;

            padding:40px;

            box-shadow:
            0 15px 40px rgba(0,0,0,0.35);

            animation:aparecer .6s ease;
        }

        @keyframes aparecer{

            from{
                opacity:0;
                transform:translateY(20px);
            }

            to{
                opacity:1;
                transform:translateY(0);
            }
        }

        .icono{
            font-size:75px;
            text-align:center;
            display:block;
            margin-bottom:20px;
        }

        .titulo{
            text-align:center;
            font-size:30px;
            font-weight:bold;
            margin-bottom:12px;
        }

        .texto{
            text-align:center;
            color:#6c757d;
            margin-bottom:30px;
            line-height:1.6;
        }

        .input-group-text{
            border-radius:14px 0 0 14px;
        }

        .form-control{
            height:55px;
            border-radius:0 14px 14px 0;
            font-size:15px;
        }

        .form-control:focus{
            box-shadow:none;
            border-color:#2563eb;
        }

        .btn-guardar{

            height:55px;

            border:none;

            border-radius:14px;

            font-weight:bold;

            font-size:16px;

            transition:.3s;
        }

        .btn-guardar:hover{
            transform:translateY(-2px);
        }

    </style>

</head>

<body>

<div class="card-reset">

<?php if ($resultado && $resultado['tipo'] === 'success'): ?>

    <!-- ÉXITO -->

    <div class="text-center">

        <i class="bi bi-check-circle-fill text-success icono"></i>

        <h2 class="titulo text-success">
            Contraseña actualizada
        </h2>

        <p class="texto">
            Tu contraseña fue cambiada correctamente.
            <br>
            Serás redirigido al inicio de sesión.
        </p>

        <div class="spinner-border text-primary mt-2"></div>

    </div>

    <script>

        setTimeout(function(){

            window.location.href='<?= url('/login') ?>';

        },2500);

    </script>

<?php else: ?>

    <!-- FORMULARIO -->

    <i class="bi bi-shield-lock-fill text-primary icono"></i>

    <h2 class="titulo">
        Nueva contraseña
    </h2>

    <p class="texto">
        Ingresa una contraseña segura para proteger tu cuenta.
    </p>

    <form action="<?= url('/reset-password') ?>" method="POST">

        <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">

        <div class="mb-4">

            <label class="form-label fw-semibold">
                Nueva contraseña
            </label>

            <div class="input-group">

                <span class="input-group-text">
                    <i class="bi bi-lock-fill"></i>
                </span>

                <input
                    type="password"
                    name="password"
                    class="form-control"
                    placeholder="Ingrese nueva contraseña"
                    required
                >

            </div>

        </div>

        <button
            type="submit"
            class="btn btn-primary w-100 btn-guardar"
        >

            <i class="bi bi-save-fill"></i>

            Guardar contraseña

        </button>

    </form>

<?php endif; ?>

</div>

</body>
</html>
<?php endif; ?>