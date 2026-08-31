<?php
require_once appPath('servidor/config/database.php');
$conexion = conectar();

// Cargar eventos disponibles
$sqlEventos = "SELECT id_evento, nombre_evento, fecha_evento
               FROM eventos
               WHERE estado = 1
               ORDER BY fecha_evento ASC";

$resultadoEventos = mysqli_query($conexion, $sqlEventos);

if (!$resultadoEventos) {
    die("Error al cargar eventos: " . mysqli_error($conexion));
}

?>

<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Registro de Actividades</title>

    <!-- Bootstrap -->
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap"
          rel="stylesheet">

    <style>

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 30px 0;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont,
                         'Segoe UI', Roboto, sans-serif;
        }

        .main-wrapper {
            max-width: 1000px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .card {
            border: none;
            border-radius: 24px;
            background: #ffffff;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
            overflow: hidden;
            transition: transform 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        /* ==============================
           HEADER
        ============================== */

        .card-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 35px 40px 30px;
            border: none;
            position: relative;
            overflow: hidden;
        }

        .card-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 300px;
            height: 300px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 50%;
            pointer-events: none;
        }

        .card-header::after {
            content: '';
            position: absolute;
            bottom: -30%;
            left: -10%;
            width: 200px;
            height: 200px;
            background: rgba(255, 255, 255, 0.03);
            border-radius: 50%;
            pointer-events: none;
        }

        .card-header h3 {
            font-weight: 700;
            font-size: 28px;
            letter-spacing: -0.5px;
            position: relative;
            z-index: 1;
        }

        .card-header p {
            opacity: 0.9;
            font-weight: 400;
            font-size: 15px;
            position: relative;
            z-index: 1;
            margin-top: 5px;
        }

        /* ==============================
           BODY
        ============================== */

        .card-body {
            padding: 40px 45px 45px;
        }

        /* ==============================
           SECTION TITLE
        ============================== */

        .section-title {
            font-size: 16px;
            font-weight: 600;
            color: #4a5568;
            margin-bottom: 25px;
            padding-left: 14px;
            border-left: 4px solid #667eea;
            display: flex;
            align-items: center;
            gap: 10px;
            letter-spacing: 0.3px;
        }

        .section-title i {
            font-size: 20px;
            color: #667eea;
        }

        /* ==============================
           LABELS
        ============================== */

        .form-label {
            font-weight: 500;
            font-size: 14px;
            color: #2d3748;
            margin-bottom: 6px;
        }

        .required-star {
            color: #fc8181;
            font-weight: 600;
            margin-left: 2px;
        }

        /* ==============================
           INPUTS
        ============================== */

        .form-control,
        .form-select {
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            padding: 12px 16px;
            font-size: 14px;
            transition: all 0.3s ease;
            background: #f7fafc;
            color: #2d3748;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.15);
            background: #ffffff;
        }

        .form-control::placeholder {
            color: #a0aec0;
            font-size: 13px;
        }

        textarea.form-control {
            resize: vertical;
            min-height: 110px;
        }

        /* ==============================
           BOTONES
        ============================== */

        .btn {
            border-radius: 12px;
            padding: 12px 32px;
            font-weight: 600;
            font-size: 15px;
            transition: all 0.3s ease;
            letter-spacing: 0.3px;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            color: white;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.5);
            background: linear-gradient(135deg, #5a67d8 0%, #6b46a1 100%);
        }

        .btn-outline-secondary {
            border: 2px solid #e2e8f0;
            color: #4a5568;
            background: transparent;
        }

        .btn-outline-secondary:hover {
            background: #f7fafc;
            border-color: #cbd5e0;
            transform: translateY(-2px);
        }

        .btn-group-actions {
            display: flex;
            gap: 15px;
            justify-content: center;
            flex-wrap: wrap;
            padding-top: 10px;
        }

        /* ==============================
           FILA DEL FORMULARIO
        ============================== */

        .form-row {
            margin-bottom: 10px;
        }

        /* ==============================
           ANIMACIÓN
        ============================== */

        .form-control,
        .form-select {
            animation: fadeInUp 0.5s ease forwards;
            opacity: 0;
        }

        @keyframes fadeInUp {

            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }

        }

        /* ==============================
           RESPONSIVE
        ============================== */

        @media (max-width: 768px) {

            .card-body {
                padding: 30px 20px 35px;
            }

            .card-header {
                padding: 25px 20px 20px;
            }

            .card-header h3 {
                font-size: 22px;
            }

            .btn-group-actions {
                flex-direction: column;
                align-items: center;
            }

            .btn {
                width: 100%;
                max-width: 300px;
            }

        }

        @media (max-width: 576px) {

            .main-wrapper {
                padding: 0 10px;
            }

            .card-body {
                padding: 20px 15px 25px;
            }

            .section-title {
                font-size: 14px;
            }

        }

    </style>

</head>

<body>

<div class="main-wrapper">

    <div class="card shadow-lg">

        <!-- ==============================
             HEADER
        ============================== -->

        <div class="card-header text-center">

            <h3>
                <i class="bi bi-calendar-plus-fill me-2"></i>
                Registro de Actividades
            </h3>

            <p class="mb-0">
                Complete la información solicitada para registrar una nueva actividad
            </p>

        </div>


        <!-- ==============================
             BODY
        ============================== -->

        <div class="card-body">

            <form action="../servidor/validar_actividades.php"
                  method="POST">

                <input type="hidden"
                       name="action"
                       value="registrar">


                <!-- ==============================
                     INFORMACIÓN GENERAL
                ============================== -->

                <div class="section-title">

                    <i class="bi bi-info-circle-fill"></i>

                    Información General

                </div>


                <!-- NOMBRE + TIPO -->

                <div class="row form-row">

                    <div class="col-md-6 mb-3">

                        <label for="nombre_actividad"
                               class="form-label">

                            <i class="bi bi-card-text me-1"></i>

                            Nombre de la Actividad

                            <span class="required-star">*</span>

                        </label>

                        <input
                            type="text"
                            class="form-control"
                            id="nombre_actividad"
                            name="txtnombre_actividad"
                            placeholder="Ej: Taller de Formación"
                            required
                        >

                    </div>


                    <div class="col-md-6 mb-3">

                        <label for="tipo_actividad"
                               class="form-label">

                            <i class="bi bi-tags me-1"></i>

                            Tipo de Actividad

                            <span class="required-star">*</span>

                        </label>

                        <input
                            type="text"
                            class="form-control"
                            id="tipo_actividad"
                            name="txttipo_actividad"
                            placeholder="Ej: Formación, Retiro, Taller..."
                            required
                        >

                    </div>

                </div>


                <!-- ==============================
                     FECHAS
                ============================== -->

                <div class="section-title mt-4">

                    <i class="bi bi-calendar-event"></i>

                    Programación

                </div>


                <div class="row form-row">

                    <div class="col-md-6 mb-3">

                        <label for="fecha_inicio"
                               class="form-label">

                            <i class="bi bi-calendar-check me-1"></i>

                            Fecha de Inicio

                            <span class="required-star">*</span>

                        </label>

                        <input
                            type="date"
                            class="form-control"
                            id="fecha_inicio"
                            name="txtfecha_inicio"
                            required
                        >

                    </div>


                    <div class="col-md-6 mb-3">

                        <label for="fecha_fin"
                               class="form-label">

                            <i class="bi bi-calendar-x me-1"></i>

                            Fecha de Fin

                            <span class="required-star">*</span>

                        </label>

                        <input
                            type="date"
                            class="form-control"
                            id="fecha_fin"
                            name="txtfecha_fin"
                            required
                        >

                    </div>

                </div>


                <!-- DÍAS + HORAS + DURACIÓN -->

                <div class="row form-row">

                    <div class="col-md-3 mb-3">

                        <label for="dias_semana"
                               class="form-label">

                            <i class="bi bi-calendar-week me-1"></i>

                            Días de la Semana

                        </label>

                        <input
                            type="text"
                            class="form-control"
                            id="dias_semana"
                            name="txtdias_semana"
                            placeholder="Lun, Mié, Vie"
                        >

                    </div>


                    <div class="col-md-3 mb-3">

                        <label for="hora_inicio"
                               class="form-label">

                            <i class="bi bi-clock me-1"></i>

                            Hora de Inicio

                            <span class="required-star">*</span>

                        </label>

                        <input
                            type="time"
                            class="form-control"
                            id="hora_inicio"
                            name="txthora_inicio"
                            required
                        >

                    </div>


                    <div class="col-md-3 mb-3">

                        <label for="hora_fin"
                               class="form-label">

                            <i class="bi bi-clock-fill me-1"></i>

                            Hora de Fin

                            <span class="required-star">*</span>

                        </label>

                        <input
                            type="time"
                            class="form-control"
                            id="hora_fin"
                            name="txthora_fin"
                            required
                        >

                    </div>


                    <div class="col-md-3 mb-3">

                        <label for="duracion"
                               class="form-label">

                            <i class="bi bi-hourglass-split me-1"></i>

                            Duración

                        </label>

                        <input
                            type="text"
                            class="form-control"
                            id="duracion"
                            name="txtduracion"
                            placeholder="Ej: 2 horas"
                        >

                    </div>

                </div>


                <!-- ==============================
                     REQUISITOS Y COSTOS
                ============================== -->

                <div class="section-title mt-4">

                    <i class="bi bi-clipboard-check"></i>

                    Requisitos y Cupos

                </div>


                <div class="row form-row">

                    <div class="col-md-6 mb-3">

                        <label for="requisitos"
                               class="form-label">

                            <i class="bi bi-list-check me-1"></i>

                            Requisitos

                        </label>

                        <input
                            type="text"
                            class="form-control"
                            id="requisitos"
                            name="txtrequisitos"
                            placeholder="Ej: Ser estudiante, llevar material..."
                        >

                    </div>


                    <div class="col-md-3 mb-3">

                        <label for="costo"
                               class="form-label">

                            <i class="bi bi-cash-coin me-1"></i>

                            Costo

                        </label>

                        <input
                            type="number"
                            class="form-control"
                            id="costo"
                            name="txtcosto"
                            min="0"
                            step="0.01"
                            placeholder="0.00"
                        >

                    </div>


                    <div class="col-md-3 mb-3">

                        <label for="cupo_maximo"
                               class="form-label">

                            <i class="bi bi-people-fill me-1"></i>

                            Cupo Máximo

                        </label>

                        <input
                            type="number"
                            class="form-control"
                            id="cupo_maximo"
                            name="txtcupo_maximo"
                            min="1"
                            placeholder="Ej: 30"
                        >

                    </div>

                </div>


                <!-- CUPO DISPONIBLE + DESCRIPCIÓN -->

                <div class="row form-row">

                    <div class="col-md-3 mb-3">

                        <label for="cupo_disponible"
                               class="form-label">

                            <i class="bi bi-person-check me-1"></i>

                            Cupo Disponible

                        </label>

                        <input
                            type="number"
                            class="form-control"
                            id="cupo_disponible"
                            name="txtcupo_disponible"
                            min="0"
                            placeholder="Ej: 30"
                        >

                    </div>


                    <div class="col-md-9 mb-3">

                        <label for="descripcion"
                               class="form-label">

                            <i class="bi bi-file-text me-1"></i>

                            Descripción

                        </label>

                        <textarea
                            class="form-control"
                            id="descripcion"
                            name="txtdescripcion"
                            rows="4"
                            placeholder="Describa brevemente la actividad..."
                        ></textarea>

                    </div>

                </div>


                <!-- ==============================
                     EVENTO Y ESTADO
                ============================== -->

                <div class="section-title mt-4">

                    <i class="bi bi-diagram-3-fill"></i>

                    Asociación y Estado

                </div>


                <div class="row form-row">


                    <!-- EVENTO -->

                    <div class="col-md-6 mb-3">

                        <label for="txtid_evento"
                               class="form-label">

                            <i class="bi bi-calendar2-event me-1"></i>

                            Evento

                            <span class="required-star">*</span>

                        </label>


                        <select
                            class="form-select"
                            name="txtid_evento"
                            id="txtid_evento"
                            required
                        >

                            <option value=""
                                    selected
                                    disabled>

                                Seleccione un evento

                            </option>


                            <?php while ($evento = mysqli_fetch_assoc($resultadoEventos)) { ?>

                                <option value="<?= $evento['id_evento']; ?>">

                                    <?= $evento['id_evento']; ?> -
                                    <?= htmlspecialchars($evento['nombre_evento']); ?>

                                </option>

                            <?php } ?>

                        </select>

                        <small class="text-muted"
                               style="font-size: 12px;">

                            <i class="bi bi-info-circle"></i>

                            Seleccione el evento al que pertenece esta actividad.

                        </small>

                    </div>


                    <!-- ESTADO -->

                    <div class="col-md-6 mb-3">

                        <label for="txtestado"
                               class="form-label">

                            <i class="bi bi-toggle-on me-1"></i>

                            Estado

                            <span class="required-star">*</span>

                        </label>


                        <select
                            class="form-select"
                            name="txtestado"
                            id="txtestado"
                            required
                        >

                            <option value=""
                                    selected
                                    disabled>

                                Seleccione

                            </option>

                            <option value="1">
                                Activo
                            </option>

                            <option value="0">
                                Inactivo
                            </option>

                        </select>

                    </div>

                </div>


                <!-- ==============================
                     BOTONES
                ============================== -->

                <div class="btn-group-actions mt-4">


                    <button
                        type="reset"
                        class="btn btn-outline-secondary px-5"
                    >

                        <i class="bi bi-eraser me-2"></i>

                        Limpiar

                    </button>


                    <button
                        type="submit"
                        class="btn btn-primary px-5"
                    >

                        <i class="bi bi-check-circle me-2"></i>

                        Registrar Actividad

                    </button>

                </div>
            </form>
        </div>
    </div>
</div>


<!-- Bootstrap JS -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>


<script>

document.addEventListener('DOMContentLoaded', function () {

    /*
     * ==========================================
     * VALIDACIÓN DE FECHAS
     * ==========================================
     */

    const fechaInicio = document.getElementById('fecha_inicio');
    const fechaFin = document.getElementById('fecha_fin');

    function validarFechas() {

        if (
            fechaInicio.value &&
            fechaFin.value &&
            fechaFin.value < fechaInicio.value
        ) {

            fechaFin.setCustomValidity(
                'La fecha de fin no puede ser anterior a la fecha de inicio.'
            );

        } else {

            fechaFin.setCustomValidity('');

        }

    }

    fechaInicio.addEventListener('change', validarFechas);
    fechaFin.addEventListener('change', validarFechas);


    /*
     * ==========================================
     * VALIDACIÓN DE HORAS
     * ==========================================
     */

    const horaInicio = document.getElementById('hora_inicio');
    const horaFin = document.getElementById('hora_fin');

    function validarHoras() {

        if (
            horaInicio.value &&
            horaFin.value &&
            horaFin.value <= horaInicio.value
        ) {

            horaFin.setCustomValidity(
                'La hora de fin debe ser posterior a la hora de inicio.'
            );

        } else {

            horaFin.setCustomValidity('');

        }

    }

    horaInicio.addEventListener('change', validarHoras);
    horaFin.addEventListener('change', validarHoras);


    /*
     * ==========================================
     * VALIDACIÓN DE CUPOS
     * ==========================================
     */

    const cupoMaximo = document.getElementById('cupo_maximo');
    const cupoDisponible = document.getElementById('cupo_disponible');

    function validarCupos() {

        if (
            cupoMaximo.value &&
            cupoDisponible.value &&
            parseInt(cupoDisponible.value) > parseInt(cupoMaximo.value)
        ) {

            cupoDisponible.setCustomValidity(
                'El cupo disponible no puede ser mayor al cupo máximo.'
            );

        } else {

            cupoDisponible.setCustomValidity('');

        }

    }

    cupoMaximo.addEventListener('input', validarCupos);
    cupoDisponible.addEventListener('input', validarCupos);

});

</script>

</body>

</html>