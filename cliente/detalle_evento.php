
<?php

require_once "../servidor/conexionBD.php";

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: eventos.php");
    exit;
}

$idEvento = (int) $_GET['id'];


/* =====================================================
   OBTENER EVENTO
   ===================================================== */

$sqlEvento = "SELECT
                id_evento,
                nombre_evento,
                descripcion,
                fecha_evento,
                hora_evento,
                lugar,
                estado,
                fecha_creacion,
                fecha_actualizacion
              FROM eventos
              WHERE id_evento = ?
              LIMIT 1";

$stmtEvento = mysqli_prepare($conexion, $sqlEvento);

if (!$stmtEvento) {
    die("Error al preparar la consulta del evento.");
}

mysqli_stmt_bind_param($stmtEvento, "i", $idEvento);
mysqli_stmt_execute($stmtEvento);

$resultadoEvento = mysqli_stmt_get_result($stmtEvento);

if (mysqli_num_rows($resultadoEvento) === 0) {
    header("Location: eventos.php");
    exit;
}

$evento = mysqli_fetch_assoc($resultadoEvento);

mysqli_stmt_close($stmtEvento);


/* =====================================================
   OBTENER ACTIVIDADES DEL EVENTO
   ===================================================== */

$sqlActividades = "SELECT
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
                    id_evento,
                    estado
                   FROM actividades
                   WHERE id_evento = ?
                   AND estado = 'Activo'
                   ORDER BY fecha_inicio ASC, hora_inicio ASC";

$stmtActividades = mysqli_prepare($conexion, $sqlActividades);

if (!$stmtActividades) {
    die("Error al preparar la consulta de actividades.");
}

mysqli_stmt_bind_param($stmtActividades, "i", $idEvento);
mysqli_stmt_execute($stmtActividades);

$resultadoActividades = mysqli_stmt_get_result($stmtActividades);

$totalActividades = mysqli_num_rows($resultadoActividades);

?>

<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>
        <?php echo htmlspecialchars($evento['nombre_evento']); ?> -
        Actividades
    </title>


    <!-- =====================================================
         BOOTSTRAP 5.3.7
         ===================================================== -->

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css"
        rel="stylesheet">


    <!-- =====================================================
         FONT AWESOME 6.5.0
         ===================================================== -->

    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">


    <!-- =====================================================
         ESTILOS PERSONALIZADOS
         ===================================================== -->

    <style>

        /* =====================================================
           SECCIÓN GENERAL
           ===================================================== */

        .actividades-section {
            position: relative;
        }


        /* =====================================================
           ENCABEZADO
           ===================================================== */

        .actividades-header {
            padding: 10px 0;
        }

        .actividades-header h2 {
            color: #212529;
            letter-spacing: -0.5px;
        }

        .actividades-header p {
            font-size: 0.95rem;
        }

        .badge-titulo {
            font-size: 0.75rem;
            font-weight: 700;
            letter-spacing: 0.5px;
        }

        .contador-actividades {
            font-size: 0.82rem;
            font-weight: 600;
            white-space: nowrap;
        }


        /* =====================================================
           TARJETA DE ACTIVIDAD
           ===================================================== */

        .activity-card {
            position: relative;
            overflow: hidden;
            height: 100%;
            background: #ffffff;
            border: 1px solid #e9ecef;
            border-radius: 20px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.06);

            transition:
                transform 0.3s ease,
                box-shadow 0.3s ease,
                border-color 0.3s ease;
        }


        /* Línea superior */

        .activity-card::before {
            content: "";
            position: absolute;

            top: 0;
            left: 0;

            width: 100%;
            height: 4px;

            background: linear-gradient(
                90deg,
                #0d6efd,
                #6f42c1
            );
        }


        /* Efecto hover */

        .activity-card:hover {
            transform: translateY(-7px);

            box-shadow:
                0 18px 40px rgba(0, 0, 0, 0.12);

            border-color: rgba(13, 110, 253, 0.20);
        }


        /* =====================================================
           CUERPO DE LA TARJETA
           ===================================================== */

        .activity-card .card-body {
            padding: 25px;
        }


        /* =====================================================
           TIPO DE ACTIVIDAD
           ===================================================== */

        .badge-tipo {
            font-size: 0.73rem;
            font-weight: 600;

            border: 1px solid rgba(13, 110, 253, 0.08);
        }


        /* =====================================================
           CUPOS
           ===================================================== */

        .badge-cupo {
            font-size: 0.70rem;
            font-weight: 600;
        }


        /* =====================================================
           NOMBRE
           ===================================================== */

        .activity-card h4 {
            color: #212529;

            font-size: 1.25rem;

            line-height: 1.4;

            min-height: 52px;
        }


        /* =====================================================
           DESCRIPCIÓN
           ===================================================== */

        .activity-description {
            color: #6c757d;

            line-height: 1.6;

            min-height: 51px;
        }


        /* =====================================================
           BLOQUE DE INFORMACIÓN
           ===================================================== */

        .activity-info {
            background: #f8f9fa;

            border: 1px solid #f0f0f0;

            border-radius: 15px;

            padding: 15px;
        }


        /* =====================================================
           ITEMS DE INFORMACIÓN
           ===================================================== */

        .info-item {
            display: flex;

            align-items: center;

            gap: 12px;

            padding: 9px 0;
        }


        .info-item:not(:last-child) {
            border-bottom: 1px solid #e9ecef;
        }


        /* =====================================================
           ICONOS
           ===================================================== */

        .info-item .icon-wrapper {
            width: 40px;
            height: 40px;

            min-width: 40px;

            display: flex;

            align-items: center;
            justify-content: center;

            background: #ffffff;

            color: #0d6efd;

            border-radius: 10px;

            box-shadow:
                0 3px 8px rgba(0, 0, 0, 0.05);
        }


        /* =====================================================
           LABEL
           ===================================================== */

        .info-item .label {
            font-size: 0.67rem;

            text-transform: uppercase;

            color: #8a8f98;

            font-weight: 700;

            letter-spacing: 0.5px;

            margin-bottom: 2px;
        }


        /* =====================================================
           VALOR
           ===================================================== */

        .info-item .value {
            font-size: 0.88rem;

            color: #343a40;

            font-weight: 600;

            line-height: 1.4;
        }


        /* =====================================================
           INVERSIÓN
           ===================================================== */

        .activity-label {
            font-size: 0.67rem;

            text-transform: uppercase;

            color: #8a8f98;

            font-weight: 700;

            letter-spacing: 0.6px;
        }


        .activity-price {
            font-size: 1.25rem;

            font-weight: 700;

            color: #212529;
        }


        .activity-price .gratis {
            color: #198754;
        }


        /* =====================================================
           DURACIÓN
           ===================================================== */

        .activity-duration {
            font-size: 0.95rem;

            font-weight: 600;

            color: #343a40;
        }


        /* =====================================================
           BOTÓN
           ===================================================== */

        .activity-button {
            border: none;

            padding: 11px 18px;

            font-size: 0.92rem;

            font-weight: 600;

            border-radius: 12px;

            box-shadow:
                0 5px 12px rgba(13, 110, 253, 0.16);

            transition:
                transform 0.25s ease,
                box-shadow 0.25s ease;
        }


        .activity-button:hover {
            transform: translateY(-2px);

            box-shadow:
                0 8px 18px rgba(13, 110, 253, 0.25);
        }


        .activity-button i {
            transition:
                transform 0.25s ease;
        }


        .activity-button:hover i {
            transform: translateX(4px);
        }


        /* =====================================================
           SIN ACTIVIDADES
           ===================================================== */

        .empty-activities {
            background: #f8f9fa;

            border: 1px dashed #ced4da;

            border-radius: 20px;

            padding: 55px 25px;
        }


        .empty-icon {
            width: 75px;
            height: 75px;

            margin: 0 auto 20px;

            display: flex;

            align-items: center;
            justify-content: center;

            background: #ffffff;

            border-radius: 50%;

            box-shadow:
                0 5px 18px rgba(0, 0, 0, 0.06);
        }


        .empty-icon i {
            font-size: 2rem;

            color: #adb5bd;
        }


        /* =====================================================
           TABLET
           ===================================================== */

        @media (max-width: 991.98px) {

            .activity-card .card-body {
                padding: 22px;
            }

            .activity-card h4 {
                font-size: 1.15rem;
            }

        }


        /* =====================================================
           CELULAR
           ===================================================== */

        @media (max-width: 767.98px) {

            .actividades-header {
                align-items: flex-start !important;
            }

            .actividades-header h2 {
                font-size: 1.45rem;
            }

            .actividades-header p {
                font-size: 0.88rem;
            }

            .activity-card .card-body {
                padding: 20px;
            }

            .activity-card {
                border-radius: 17px;
            }

            .activity-card h4 {
                min-height: auto;

                font-size: 1.15rem;
            }

            .activity-description {
                min-height: auto;
            }

            .activity-info {
                padding: 12px;
            }

            .info-item {
                gap: 10px;
            }

            .info-item .icon-wrapper {
                width: 36px;
                height: 36px;

                min-width: 36px;
            }

        }


        /* =====================================================
           CELULARES PEQUEÑOS
           ===================================================== */

        @media (max-width: 480px) {

            .actividades-header {
                display: block !important;
            }

            .contador-actividades {
                display: inline-block;

                margin-top: 12px;
            }

            .activity-card .card-body {
                padding: 18px;
            }

            .activity-card
            .d-flex.justify-content-between {
                gap: 8px;

                flex-wrap: wrap;
            }

            .activity-card .badge {
                font-size: 0.67rem;
            }

            .info-item .value {
                font-size: 0.84rem;
            }

            .empty-activities {
                padding: 40px 18px;
            }

        }

    </style>

</head>


<body>


<!-- =====================================================
     CONTENIDO
     ===================================================== -->

<section class="container mt-5 actividades-section">


    <!-- =====================================================
         ENCABEZADO
         ===================================================== -->

    <div class="d-flex justify-content-between align-items-center mb-4 actividades-header">

        <div>

            <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-2 badge-titulo">

                <i class="fa-solid fa-list-check me-1"></i>

                ACTIVIDADES

            </span>


            <h2 class="fw-bold mt-2 mb-1">

                Actividades de este evento

            </h2>


            <p class="text-secondary mb-0">

                Participa en las actividades disponibles para este evento.

            </p>

        </div>


        <!-- CONTADOR -->

        <span class="badge bg-light text-dark border rounded-pill px-3 py-2 contador-actividades">

            <?php echo $totalActividades; ?>

            <?php echo $totalActividades == 1
                ? 'actividad'
                : 'actividades'; ?>

        </span>

    </div>


    <?php if ($totalActividades > 0): ?>


        <!-- =====================================================
             TARJETAS DE ACTIVIDADES
             ===================================================== -->

        <div class="row g-4">


            <?php while ($actividad = mysqli_fetch_assoc($resultadoActividades)): ?>


                <?php

                /* =================================================
                   CUPOS
                   ================================================= */

                $cupoDisponible =
                    (int) $actividad['cupo_disponible'];


                if ($cupoDisponible <= 0) {

                    $colorCupo = 'danger';

                    $textoCupo = 'Cupos agotados';

                    $iconoCupo = 'fa-circle-xmark';

                } elseif ($cupoDisponible <= 3) {

                    $colorCupo = 'warning';

                    $textoCupo =
                        "Últimos {$cupoDisponible} cupos";

                    $iconoCupo =
                        'fa-triangle-exclamation';

                } else {

                    $colorCupo = 'success';

                    $textoCupo =
                        "{$cupoDisponible} cupos disponibles";

                    $iconoCupo =
                        'fa-circle-check';
                }


                /* =================================================
                   FECHA
                   ================================================= */

                $fechaInicio = !empty($actividad['fecha_inicio'])
                    ? date(
                        'd/m/Y',
                        strtotime($actividad['fecha_inicio'])
                    )
                    : 'Fecha no definida';


                $fechaFin = !empty($actividad['fecha_fin'])
                    ? date(
                        'd/m/Y',
                        strtotime($actividad['fecha_fin'])
                    )
                    : '';


                /* =================================================
                   HORARIO
                   ================================================= */

                $horaInicio = !empty($actividad['hora_inicio'])
                    ? date(
                        'H:i',
                        strtotime($actividad['hora_inicio'])
                    )
                    : '--:--';


                $horaFin = !empty($actividad['hora_fin'])
                    ? date(
                        'H:i',
                        strtotime($actividad['hora_fin'])
                    )
                    : '--:--';


                /* =================================================
                   DESCRIPCIÓN
                   ================================================= */

                $descripcionActividad =
                    $actividad['descripcion'] ?? '';

                if (strlen($descripcionActividad) > 110) {

                    $descripcionActividad =
                        substr(
                            $descripcionActividad,
                            0,
                            110
                        ) . '...';
                }

                ?>


                <!-- =================================================
                     COLUMNA
                     ================================================= -->

                <div class="col-12 col-md-6 col-xl-4">


                    <!-- =================================================
                         TARJETA
                         ================================================= -->

                    <article class="activity-card">


                        <div class="card-body d-flex flex-column">


                            <!-- =================================================
                                 TIPO Y CUPOS
                                 ================================================= -->

                            <div class="d-flex justify-content-between align-items-center mb-3">


                                <!-- TIPO -->

                                <span class="badge bg-light text-primary rounded-pill px-3 py-2 badge-tipo">

                                    <i class="fa-solid fa-tag me-1"></i>

                                    <?php
                                    echo htmlspecialchars(
                                        $actividad['tipo_actividad']
                                    );
                                    ?>

                                </span>


                                <!-- CUPOS -->

                                <span
                                    class="badge bg-<?php echo $colorCupo; ?>-subtle text-<?php echo $colorCupo; ?> rounded-pill px-3 py-2 badge-cupo">

                                    <i class="fa-solid <?php echo $iconoCupo; ?> me-1"></i>

                                    <?php echo $textoCupo; ?>

                                </span>


                            </div>


                            <!-- =================================================
                                 NOMBRE
                                 ================================================= -->

                            <h4 class="fw-bold mb-2">

                                <?php

                                echo htmlspecialchars(
                                    $actividad['nombre_actividad']
                                );

                                ?>

                            </h4>


                            <!-- =================================================
                                 DESCRIPCIÓN
                                 ================================================= -->

                            <p class="small activity-description">

                                <?php

                                echo htmlspecialchars(
                                    $descripcionActividad
                                );

                                ?>

                            </p>


                            <!-- =================================================
                                 INFORMACIÓN
                                 ================================================= -->

                            <div class="activity-info mb-3">


                                <!-- FECHA -->

                                <div class="info-item">


                                    <div class="icon-wrapper">

                                        <i class="fa-regular fa-calendar"></i>

                                    </div>


                                    <div>

                                        <div class="label">

                                            Fecha

                                        </div>


                                        <div class="value">

                                            <?php echo $fechaInicio; ?>


                                            <?php

                                            if (
                                                $fechaInicio !== $fechaFin &&
                                                !empty($fechaFin)
                                            ):

                                            ?>

                                                —
                                                <?php echo $fechaFin; ?>

                                            <?php endif; ?>

                                        </div>

                                    </div>

                                </div>


                                <!-- HORARIO -->

                                <div class="info-item">


                                    <div class="icon-wrapper">

                                        <i class="fa-regular fa-clock"></i>

                                    </div>


                                    <div>

                                        <div class="label">

                                            Horario

                                        </div>


                                        <div class="value">

                                            <?php echo $horaInicio; ?>

                                            —

                                            <?php echo $horaFin; ?>

                                        </div>

                                    </div>

                                </div>


                                <!-- DÍAS -->

                                <div class="info-item">


                                    <div class="icon-wrapper">

                                        <i class="fa-solid fa-calendar-week"></i>

                                    </div>


                                    <div>

                                        <div class="label">

                                            Días

                                        </div>


                                        <div class="value">

                                            <?php

                                            echo htmlspecialchars(
                                                $actividad['dias_semana']
                                            );

                                            ?>

                                        </div>

                                    </div>

                                </div>


                            </div>


                            <!-- =================================================
                                 COSTO Y DURACIÓN
                                 ================================================= -->

                            <div class="d-flex justify-content-between align-items-center mb-3">


                                <!-- COSTO -->

                                <div>

                                    <small class="activity-label">

                                        Inversión

                                    </small>


                                    <div class="activity-price">


                                        <?php

                                        if (
                                            empty($actividad['costo']) ||
                                            $actividad['costo'] == 0
                                        ):

                                        ?>

                                            <span class="gratis">

                                                Gratis

                                            </span>


                                        <?php else: ?>


                                            Bs.

                                            <?php

                                            echo number_format(
                                                $actividad['costo'],
                                                2
                                            );

                                            ?>


                                        <?php endif; ?>


                                    </div>

                                </div>


                                <!-- DURACIÓN -->

                                <div class="text-end">

                                    <small class="activity-label">

                                        Duración

                                    </small>


                                    <div class="activity-duration">

                                        <?php

                                        echo htmlspecialchars(
                                            $actividad['duracion']
                                        );

                                        ?>

                                    </div>

                                </div>


                            </div>


                            <!-- =================================================
                                 BOTÓN
                                 ================================================= -->

                            <a
                                href="detalle_actividad.php?id=<?php echo $actividad['id_actividad']; ?>"
                                class="btn btn-primary activity-button mt-auto">

                                Ver actividad

                                <i class="fa-solid fa-arrow-right ms-2"></i>

                            </a>


                        </div>

                    </article>

                </div>


            <?php endwhile; ?>


        </div>


    <?php else: ?>


        <!-- =====================================================
             NO HAY ACTIVIDADES
             ===================================================== -->

        <div class="text-center empty-activities">


            <div class="empty-icon">

                <i class="fa-regular fa-calendar-xmark"></i>

            </div>


            <h4 class="fw-bold">

                No hay actividades disponibles

            </h4>


            <p class="text-secondary mb-0">

                Este evento todavía no tiene actividades registradas.

            </p>


        </div>


    <?php endif; ?>


</section>


<!-- =====================================================
     BOOTSTRAP JAVASCRIPT
     ===================================================== -->

<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js">
</script>


</body>

</html>


<?php

mysqli_stmt_close($stmtActividades);

?>
```
