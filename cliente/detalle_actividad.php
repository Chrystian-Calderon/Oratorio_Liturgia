<?php

session_start();

include("../servidor/conexionBD.php");

/*
|--------------------------------------------------------------------------
| 1. RECIBIR ID DE LA ACTIVIDAD
|--------------------------------------------------------------------------
*/

$idActividad = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($idActividad <= 0) {
    die("Actividad no válida.");
}

/*
|--------------------------------------------------------------------------
| 2. BUSCAR ACTIVIDAD
|--------------------------------------------------------------------------
*/

$sql = "
    SELECT
        id_actividad,
        nombre_actividad,
        tipo_actividad,
        fecha_inicio,
        fecha_fin,
        dias_semana,
        hora_inicio,
        hora_fin,
        duracion,
        requisitos,
        costo,
        cupo_maximo,
        cupo_disponible,
        descripcion,
        lugar,
        estado
    FROM actividades
    WHERE id_actividad = ?
      AND estado = 'Activo'
    LIMIT 1
";

$stmt = $conexion->prepare($sql);

if (!$stmt) {
    die("Error al preparar la consulta: " . $conexion->error);
}

$stmt->bind_param("i", $idActividad);
$stmt->execute();

$resultado = $stmt->get_result();
$actividad = $resultado->fetch_assoc();

$stmt->close();

/*
|--------------------------------------------------------------------------
| 3. VERIFICAR QUE EXISTA
|--------------------------------------------------------------------------
*/

if (!$actividad) {
    die("La actividad no existe o no está disponible.");
}

/*
|--------------------------------------------------------------------------
| 4. VERIFICAR CUPOS
|--------------------------------------------------------------------------
*/

$cupoDisponible = isset($actividad['cupo_disponible'])
    ? (int) $actividad['cupo_disponible']
    : 0;

$actividadAgotada = ($cupoDisponible <= 0);

/*
|--------------------------------------------------------------------------
| 5. DATOS DE SESIÓN
|--------------------------------------------------------------------------
*/

$nombre = $_SESSION['nombre'] ?? '';
$apellidos = $_SESSION['apellidos'] ?? '';
$correo = $_SESSION['correo'] ?? '';

?>

<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        Inscripción |
        <?php echo htmlspecialchars($actividad['nombre_actividad']); ?>
    </title>

    <!-- Bootstrap -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <!-- Font Awesome -->
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
    >

    <style>

        body {
            font-family: Arial, Helvetica, sans-serif;
            background: #f5f7fb;
            min-height: 100vh;
        }

        .page-header {
            background:
                linear-gradient(
                    135deg,
                    #6366f1,
                    #8b5cf6
                );
            color: white;
            padding: 50px 20px;
        }

        .registration-container {
            margin-top: -40px;
            padding-bottom: 50px;
        }

        .card-custom {
            border: none;
            border-radius: 20px;
            box-shadow:
                0 8px 30px rgba(0,0,0,.10);
            overflow: hidden;
        }

        .activity-summary {
            background: #f8f9ff;
            border-radius: 15px;
            padding: 20px;
        }

        .info-item {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 12px;
        }

        .info-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            background: #eef2ff;
            color: #6366f1;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .form-control {
            border-radius: 10px;
            padding: 11px 14px;
        }

        .form-control:focus {
            border-color: #6366f1;
            box-shadow:
                0 0 0 3px rgba(99,102,241,.12);
        }

        .btn-register {
            background:
                linear-gradient(
                    135deg,
                    #6366f1,
                    #8b5cf6
                );
            border: none;
            color: white;
            border-radius: 12px;
            padding: 13px;
            font-weight: 600;
        }

        .btn-register:hover {
            color: white;
            opacity: .92;
        }

        .required {
            color: #dc3545;
        }

    </style>

</head>

<body>

<!-- =========================================================
     ENCABEZADO
========================================================= -->

<header class="page-header">

    <div class="container text-center">

        <span class="badge bg-light text-primary px-3 py-2 mb-3">

            <i class="fa-solid fa-user-plus me-1"></i>

            INSCRIPCIÓN

        </span>

        <h1 class="fw-bold">
            Inscribirme a la actividad
        </h1>

        <p class="mb-0">
            Completa tus datos para participar
        </p>

    </div>

</header>


<!-- =========================================================
     CONTENIDO
========================================================= -->

<main class="container registration-container">

    <div class="row justify-content-center">

        <div class="col-lg-9">

            <div class="card card-custom">

                <div class="card-body p-4 p-lg-5">


                    <!-- =================================================
                         INFORMACIÓN DE LA ACTIVIDAD
                    ================================================== -->

                    <div class="activity-summary mb-4">

                        <div class="d-flex justify-content-between align-items-start mb-3">

                            <div>

                                <span class="badge bg-primary-subtle text-primary">

                                    <i class="fa-solid fa-tag me-1"></i>

                                    <?php
                                    echo htmlspecialchars(
                                        $actividad['tipo_actividad'] ?? 'Actividad'
                                    );
                                    ?>

                                </span>

                            </div>


                            <?php if ($actividadAgotada): ?>

                                <span class="badge bg-danger">

                                    <i class="fa-solid fa-circle-xmark me-1"></i>

                                    Cupos agotados

                                </span>

                            <?php else: ?>

                                <span class="badge bg-success">

                                    <i class="fa-solid fa-users me-1"></i>

                                    <?php echo $cupoDisponible; ?>

                                    cupos disponibles

                                </span>

                            <?php endif; ?>

                        </div>


                        <!-- NOMBRE -->

                        <h3 class="fw-bold mb-3">

                            <?php
                            echo htmlspecialchars(
                                $actividad['nombre_actividad']
                            );
                            ?>

                        </h3>


                        <div class="row">


                            <!-- FECHA -->

                            <div class="col-md-6">

                                <div class="info-item">

                                    <div class="info-icon">

                                        <i class="fa-regular fa-calendar"></i>

                                    </div>

                                    <div>

                                        <small class="text-muted d-block">
                                            Fecha
                                        </small>

                                        <strong>

                                            <?php
                                            if (!empty($actividad['fecha_inicio'])) {

                                                echo date(
                                                    "d/m/Y",
                                                    strtotime(
                                                        $actividad['fecha_inicio']
                                                    )
                                                );

                                            } else {

                                                echo "No definida";

                                            }
                                            ?>

                                            <?php
                                            if (
                                                !empty($actividad['fecha_fin']) &&
                                                $actividad['fecha_inicio'] !=
                                                $actividad['fecha_fin']
                                            ):
                                            ?>

                                                -

                                                <?php
                                                echo date(
                                                    "d/m/Y",
                                                    strtotime(
                                                        $actividad['fecha_fin']
                                                    )
                                                );
                                                ?>

                                            <?php endif; ?>

                                        </strong>

                                    </div>

                                </div>

                            </div>


                            <!-- HORARIO -->

                            <div class="col-md-6">

                                <div class="info-item">

                                    <div class="info-icon">

                                        <i class="fa-regular fa-clock"></i>

                                    </div>

                                    <div>

                                        <small class="text-muted d-block">
                                            Horario
                                        </small>

                                        <strong>

                                            <?php

                                            if (!empty($actividad['hora_inicio'])) {

                                                echo date(
                                                    "H:i",
                                                    strtotime(
                                                        $actividad['hora_inicio']
                                                    )
                                                );

                                            } else {

                                                echo "--:--";

                                            }

                                            ?>

                                            -

                                            <?php

                                            if (!empty($actividad['hora_fin'])) {

                                                echo date(
                                                    "H:i",
                                                    strtotime(
                                                        $actividad['hora_fin']
                                                    )
                                                );

                                            } else {

                                                echo "--:--";

                                            }

                                            ?>

                                        </strong>

                                    </div>

                                </div>

                            </div>


                            <!-- DÍAS -->

                            <div class="col-md-6">

                                <div class="info-item">

                                    <div class="info-icon">

                                        <i class="fa-solid fa-calendar-week"></i>

                                    </div>

                                    <div>

                                        <small class="text-muted d-block">
                                            Días
                                        </small>

                                        <strong>

                                            <?php

                                            if (!empty($actividad['dias_semana'])) {

                                                /*
                                                 * MySQL SET devuelve los
                                                 * valores separados por coma.
                                                 */

                                                echo htmlspecialchars(
                                                    str_replace(
                                                        ',',
                                                        ', ',
                                                        $actividad['dias_semana']
                                                    )
                                                );

                                            } else {

                                                echo "No definido";

                                            }

                                            ?>

                                        </strong>

                                    </div>

                                </div>

                            </div>


                            <!-- COSTO -->

                            <div class="col-md-6">

                                <div class="info-item">

                                    <div class="info-icon">

                                        <i class="fa-solid fa-money-bill"></i>

                                    </div>

                                    <div>

                                        <small class="text-muted d-block">
                                            Costo
                                        </small>

                                        <strong>

                                            <?php

                                            $costo = isset($actividad['costo'])
                                                ? (float) $actividad['costo']
                                                : 0;

                                            if ($costo <= 0):

                                            ?>

                                                <span class="text-success">
                                                    Gratis
                                                </span>

                                            <?php else: ?>

                                                Bs.
                                                <?php
                                                echo number_format(
                                                    $costo,
                                                    2
                                                );
                                                ?>

                                            <?php endif; ?>

                                        </strong>

                                    </div>

                                </div>

                            </div>


                            <!-- DURACIÓN -->

                            <div class="col-md-6">

                                <div class="info-item">

                                    <div class="info-icon">

                                        <i class="fa-solid fa-hourglass-half"></i>

                                    </div>

                                    <div>

                                        <small class="text-muted d-block">
                                            Duración
                                        </small>

                                        <strong>

                                            <?php

                                            echo !empty(
                                                $actividad['duracion']
                                            )
                                                ? htmlspecialchars(
                                                    $actividad['duracion']
                                                )
                                                : "No definida";

                                            ?>

                                        </strong>

                                    </div>

                                </div>

                            </div>


                            <!-- LUGAR -->

                            <div class="col-md-6">

                                <div class="info-item">

                                    <div class="info-icon">

                                        <i class="fa-solid fa-location-dot"></i>

                                    </div>

                                    <div>

                                        <small class="text-muted d-block">
                                            Lugar
                                        </small>

                                        <strong>

                                            <?php

                                            echo !empty(
                                                $actividad['lugar']
                                            )
                                                ? htmlspecialchars(
                                                    $actividad['lugar']
                                                )
                                                : "No definido";

                                            ?>

                                        </strong>

                                    </div>

                                </div>

                            </div>

                        </div>


                        <!-- DESCRIPCIÓN -->

                        <?php if (!empty($actividad['descripcion'])): ?>

                            <hr>

                            <div class="mt-3">

                                <small class="text-muted d-block mb-1">
                                    Descripción
                                </small>

                                <p class="mb-0">

                                    <?php
                                    echo nl2br(
                                        htmlspecialchars(
                                            $actividad['descripcion']
                                        )
                                    );
                                    ?>

                                </p>

                            </div>

                        <?php endif; ?>


                        <!-- REQUISITOS -->

                        <?php if (!empty($actividad['requisitos'])): ?>

                            <div class="mt-3">

                                <small class="text-muted d-block mb-1">
                                    Requisitos
                                </small>

                                <p class="mb-0">

                                    <?php
                                    echo nl2br(
                                        htmlspecialchars(
                                            $actividad['requisitos']
                                        )
                                    );
                                    ?>

                                </p>

                            </div>

                        <?php endif; ?>

                    </div>


                    <!-- =================================================
                         FORMULARIO
                    ================================================== -->

                    <?php if (!$actividadAgotada): ?>

                        <form
                            action="../servidor/validar_inscripcion.php"
                            method="POST"
                        >

                            <!-- ID ACTIVIDAD -->

                            <input
                                type="hidden"
                                name="id_actividad"
                                value="<?php echo $idActividad; ?>"
                            >


                            <h4 class="fw-bold mb-4">

                                <i class="fa-solid fa-user text-primary me-2"></i>

                                Datos del participante

                            </h4>


                            <div class="row">


                                <!-- NOMBRE -->

                                <div class="col-md-6 mb-3">

                                    <label
                                        for="nombre"
                                        class="form-label fw-semibold"
                                    >

                                        Nombre

                                        <span class="required">*</span>

                                    </label>

                                    <input
                                        type="text"
                                        class="form-control"
                                        id="nombre"
                                        name="nombre"
                                        value="<?php echo htmlspecialchars($nombre); ?>"
                                        required
                                    >

                                </div>


                                <!-- APELLIDOS -->

                                <div class="col-md-6 mb-3">

                                    <label
                                        for="apellidos"
                                        class="form-label fw-semibold"
                                    >

                                        Apellidos

                                        <span class="required">*</span>

                                    </label>

                                    <input
                                        type="text"
                                        class="form-control"
                                        id="apellidos"
                                        name="apellidos"
                                        value="<?php echo htmlspecialchars($apellidos); ?>"
                                        required
                                    >

                                </div>


                                <!-- CORREO -->

                                <div class="col-md-6 mb-3">

                                    <label
                                        for="correo"
                                        class="form-label fw-semibold"
                                    >

                                        Correo electrónico

                                        <span class="required">*</span>

                                    </label>

                                    <input
                                        type="email"
                                        class="form-control"
                                        id="correo"
                                        name="correo"
                                        value="<?php echo htmlspecialchars($correo); ?>"
                                        required
                                    >

                                </div>


                                <!-- CI -->

                                <div class="col-md-6 mb-3">

                                    <label
                                        for="ci"
                                        class="form-label fw-semibold"
                                    >

                                        Cédula de Identidad

                                        <span class="required">*</span>

                                    </label>

                                    <input
                                        type="text"
                                        class="form-control"
                                        id="ci"
                                        name="ci"
                                        placeholder="Ej. 12345678"
                                        required
                                    >

                                </div>


                                <!-- TELÉFONO -->

                                <div class="col-md-6 mb-3">

                                    <label
                                        for="telefono"
                                        class="form-label fw-semibold"
                                    >

                                        Teléfono

                                    </label>

                                    <input
                                        type="tel"
                                        class="form-control"
                                        id="telefono"
                                        name="telefono"
                                        placeholder="Ej. 70000000"
                                    >

                                </div>


                                <!-- OBSERVACIÓN -->

                                <div class="col-md-6 mb-3">

                                    <label
                                        for="observacion"
                                        class="form-label fw-semibold"
                                    >

                                        Observación

                                    </label>

                                    <input
                                        type="text"
                                        class="form-control"
                                        id="observacion"
                                        name="observacion"
                                        placeholder="Información adicional"
                                    >

                                </div>

                            </div>


                            <!-- TÉRMINOS -->

                            <div class="form-check mb-4 mt-2">

                                <input
                                    class="form-check-input"
                                    type="checkbox"
                                    id="aceptar"
                                    required
                                >

                                <label
                                    class="form-check-label"
                                    for="aceptar"
                                >

                                    Confirmo que deseo inscribirme en esta
                                    actividad y que los datos proporcionados
                                    son correctos.

                                </label>

                            </div>


                            <!-- BOTONES -->

                            <div class="d-flex flex-column flex-md-row gap-2">

                                <button
                                    type="submit"
                                    class="btn btn-register flex-grow-1"
                                >

                                    <i class="fa-solid fa-check me-2"></i>

                                    Confirmar inscripción

                                </button>


                                <button
                                    type="button"
                                    class="btn btn-outline-secondary px-4"
                                    onclick="history.back()"
                                >

                                    <i class="fa-solid fa-arrow-left me-2"></i>

                                    Volver

                                </button>

                            </div>

                        </form>

                    <?php else: ?>


                        <!-- ACTIVIDAD AGOTADA -->

                        <div class="alert alert-danger text-center">

                            <i class="fa-solid fa-circle-exclamation me-2"></i>

                            Esta actividad ya no tiene cupos disponibles.

                        </div>


                        <div class="text-center">

                            <button
                                type="button"
                                class="btn btn-outline-secondary"
                                onclick="history.back()"
                            >

                                <i class="fa-solid fa-arrow-left me-2"></i>

                                Volver a actividades

                            </button>

                        </div>

                    <?php endif; ?>

                </div>

            </div>

        </div>

    </div>

</main>


<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js">
</script>

</body>

</html>