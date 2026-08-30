<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Registro de Eventos</title>

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
        /* ==========================================
           CONFIGURACIÓN GENERAL
        ========================================== */

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


        /* ==========================================
           CONTENEDOR PRINCIPAL
        ========================================== */

        .main-wrapper {
            max-width: 1000px;
            margin: 0 auto;
            padding: 0 20px;
        }


        /* ==========================================
           TARJETA
        ========================================== */

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


        /* ==========================================
           HEADER
        ========================================== */

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


        /* ==========================================
           BODY
        ========================================== */

        .card-body {
            padding: 40px 45px 45px;
        }


        /* ==========================================
           TÍTULOS DE SECCIÓN
        ========================================== */

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


        /* ==========================================
           LABELS
        ========================================== */

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


        /* ==========================================
           INPUTS
        ========================================== */

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
            min-height: 120px;
        }


        /* ==========================================
           BOTONES
        ========================================== */

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


        /* ==========================================
           FILAS
        ========================================== */

        .form-row {
            margin-bottom: 10px;
        }


        /* ==========================================
           INFORMACIÓN AUXILIAR
        ========================================== */

        .form-help {
            font-size: 12px;
            color: #718096;
            margin-top: 5px;
            display: block;
        }


        /* ==========================================
           ANIMACIÓN
        ========================================== */

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


        /* ==========================================
           RESPONSIVE
        ========================================== */

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


            <!-- ==========================================
             HEADER
        ========================================== -->

            <div class="card-header text-center">

                <h3>

                    <i class="bi bi-calendar-event-fill me-2"></i>

                    Registro de Eventos

                </h3>

                <p class="mb-0">

                    Complete la información solicitada para registrar un nuevo evento

                </p>

            </div>


            <!-- ==========================================
             BODY
        ========================================== -->

            <div class="card-body">


                <form action="../servidor/validar_eventos.php"
                    method="POST">


                    <!-- Acción -->

                    <input type="hidden"
                        name="action"
                        value="registrar">


                    <!-- ==========================================
                     INFORMACIÓN GENERAL
                ========================================== -->

                    <div class="section-title">

                        <i class="bi bi-info-circle-fill"></i>

                        Información General

                    </div>


                    <div class="row form-row">


                        <!-- NOMBRE EVENTO -->

                        <div class="col-md-12 mb-3">

                            <label for="nombre_evento"
                                class="form-label">

                                <i class="bi bi-card-heading me-1"></i>

                                Nombre del Evento

                                <span class="required-star">*</span>

                            </label>

                            <input
                                type="text"
                                class="form-control"
                                id="nombre_evento"
                                name="txtnombre_evento"
                                placeholder="Ej: Retiro Espiritual 2026"
                                required>

                        </div>

                    </div>


                    <!-- ==========================================
                     DESCRIPCIÓN
                ========================================== -->

                    <div class="row form-row">

                        <div class="col-md-12 mb-3">

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
                                placeholder="Ingrese una descripción detallada del evento..."></textarea>

                        </div>

                    </div>


                    <!-- ==========================================
                     FECHA, HORA Y LUGAR
                ========================================== -->

                    <div class="section-title mt-4">

                        <i class="bi bi-calendar-check"></i>

                        Programación del Evento

                    </div>


                    <div class="row form-row">


                        <!-- FECHA -->

                        <div class="col-md-4 mb-3">

                            <label for="fecha_evento"
                                class="form-label">

                                <i class="bi bi-calendar3 me-1"></i>

                                Fecha del Evento

                                <span class="required-star">*</span>

                            </label>

                            <input
                                type="date"
                                class="form-control"
                                id="fecha_evento"
                                name="txtfecha_evento"
                                required>

                        </div>


                        <!-- HORA -->

                        <div class="col-md-4 mb-3">

                            <label for="hora_evento"
                                class="form-label">

                                <i class="bi bi-clock me-1"></i>

                                Hora del Evento

                                <span class="required-star">*</span>

                            </label>

                            <input
                                type="time"
                                class="form-control"
                                id="hora_evento"
                                name="txthora_evento"
                                required>

                        </div>


                        <!-- LUGAR -->

                        <div class="col-md-4 mb-3">

                            <label for="lugar"
                                class="form-label">

                                <i class="bi bi-geo-alt-fill me-1"></i>

                                Lugar

                                <span class="required-star">*</span>

                            </label>

                            <input
                                type="text"
                                class="form-control"
                                id="lugar"
                                name="txtlugar"
                                placeholder="Ej: Salón Principal"
                                required>

                        </div>

                    </div>


                    <!-- ==========================================
                     ESTADO
                ========================================== -->

                    <div class="section-title mt-4">

                        <i class="bi bi-toggle-on"></i>

                        Estado del Evento

                    </div>


                    <div class="row form-row">

                        <div class="col-md-6 mb-3">

                            <label for="estado"
                                class="form-label">

                                <i class="bi bi-check-circle me-1"></i>

                                Estado

                                <span class="required-star">*</span>

                            </label>


                            <select
                                id="estado"
                                name="txtestado"
                                class="form-select"
                                required>

                                <option value=""
                                    selected
                                    disabled>

                                    Seleccione el estado

                                </option>

                                <option value="1">
                                    Activo
                                </option>

                                <option value="0">
                                    Inactivo
                                </option>

                            </select>


                            <small class="form-help">

                                <i class="bi bi-info-circle me-1"></i>

                                Los eventos activos estarán disponibles para asociarlos a actividades.

                            </small>

                        </div>

                    </div>


                    <!-- ==========================================
                     BOTONES
                ========================================== -->

                    <div class="btn-group-actions mt-4">


                        <!-- LIMPIAR -->

                        <button
                            type="reset"
                            class="btn btn-outline-secondary px-5">

                            <i class="bi bi-eraser me-2"></i>

                            Limpiar

                        </button>


                        <!-- REGISTRAR -->

                        <button
                            type="submit"
                            class="btn btn-primary px-5">

                            <i class="bi bi-check-circle me-2"></i>

                            Registrar Evento

                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <!-- Bootstrap JS -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>


    <script>
        document.addEventListener('DOMContentLoaded', function() {


            /* ==========================================
               EVITAR FECHAS PASADAS
            ========================================== */

            const fechaEvento = document.getElementById('fecha_evento');

            const hoy = new Date();

            const año = hoy.getFullYear();

            const mes = String(hoy.getMonth() + 1).padStart(2, '0');

            const dia = String(hoy.getDate()).padStart(2, '0');

            const fechaActual = `${año}-${mes}-${dia}`;

            fechaEvento.setAttribute('min', fechaActual);


        });
    </script>


</body>

</html>