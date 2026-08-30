<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Inscripción Sacramental</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css"
          rel="stylesheet">

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
           CONTENEDOR
        ========================================== */

        .main-wrapper {
            max-width: 900px;
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
           BOTÓN VOLVER
        ========================================== */

        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            color: #718096;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            margin-bottom: 25px;
            transition: all 0.3s ease;
        }

        .back-link:hover {
            color: #667eea;
            transform: translateX(-3px);
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


        /* ==========================================
           SELECT SACRAMENTO
        ========================================== */

        .sacramento-select {
            border: 2px solid #667eea;
            background: #f7f8ff;
            font-weight: 500;
        }

        .sacramento-select:focus {
            border-color: #667eea;
        }


        /* ==========================================
           INFORMACIÓN
        ========================================== */

        .form-help {
            font-size: 12px;
            color: #718096;
            margin-top: 5px;
            display: block;
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

        .btn-primary:active {
            transform: translateY(0);
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

                <i class="bi bi-heart-fill me-2"></i>

                Inscripción Sacramental

            </h3>

            <p class="mb-0">

                Complete la información solicitada para realizar su inscripción

            </p>

        </div>


        <!-- ==========================================
             BODY
        ========================================== -->

        <div class="card-body">


            <!-- VOLVER -->

            <a href="../cliente/Participar.php"
               class="back-link">

                <i class="bi bi-arrow-left"></i>

                Volver al panel

            </a>


            <form action="../servidor/sacramentos_db.php"
                  method="POST">


                <!-- ==========================================
                     SACRAMENTO
                ========================================== -->

                <div class="section-title">

                    <i class="bi bi-cross"></i>

                    Sacramento a Recibir

                </div>


                <div class="row form-row">

                    <div class="col-md-12 mb-3">

                        <label for="sacramento"
                               class="form-label">

                            <i class="bi bi-stars me-1"></i>

                            Seleccione el Sacramento

                            <span class="required-star">*</span>

                        </label>


                        <select
                            id="sacramento"
                            name="sacramento"
                            class="form-select sacramento-select"
                            required
                        >

                            <option value=""
                                    selected
                                    disabled>

                                Seleccione un sacramento

                            </option>

                            <option value="Bautizo">
                                Bautizo
                            </option>

                            <option value="Primera Comunión">
                                Primera Comunión
                            </option>

                            <option value="Confirmación">
                                Confirmación
                            </option>

                        </select>


                        <small class="form-help">

                            <i class="bi bi-info-circle me-1"></i>

                            Seleccione el sacramento para el cual desea realizar la inscripción.

                        </small>

                    </div>

                </div>


                <!-- ==========================================
                     DATOS DEL SOLICITANTE
                ========================================== -->

                <div class="section-title mt-4">

                    <i class="bi bi-person-circle"></i>

                    Datos del Solicitante

                </div>


                <div class="row form-row">


                    <!-- NOMBRE -->

                    <div class="col-md-6 mb-3">

                        <label for="nombre_solicitante"
                               class="form-label">

                            <i class="bi bi-person me-1"></i>

                            Nombre Completo

                            <span class="required-star">*</span>

                        </label>

                        <input
                            type="text"
                            id="nombre_solicitante"
                            name="nombre_solicitante"
                            class="form-control"
                            placeholder="Ingrese su nombre completo"
                            required
                        >

                    </div>


                    <!-- FECHA NACIMIENTO -->

                    <div class="col-md-6 mb-3">

                        <label for="fecha_nacimiento"
                               class="form-label">

                            <i class="bi bi-calendar3 me-1"></i>

                            Fecha de Nacimiento

                            <span class="required-star">*</span>

                        </label>

                        <input
                            type="date"
                            id="fecha_nacimiento"
                            name="fecha_nacimiento"
                            class="form-control"
                            required
                        >

                    </div>

                </div>


                <div class="row form-row">

                    <div class="col-md-12 mb-3">

                        <label for="lugar_nacimiento"
                               class="form-label">

                            <i class="bi bi-geo-alt me-1"></i>

                            Lugar de Nacimiento

                            <span class="required-star">*</span>

                        </label>

                        <input
                            type="text"
                            id="lugar_nacimiento"
                            name="lugar_nacimiento"
                            class="form-control"
                            placeholder="Ej: La Paz, Bolivia"
                            required
                        >

                    </div>

                </div>


                <!-- ==========================================
                     PADRES Y PADRINOS
                ========================================== -->

                <div class="section-title mt-4">

                    <i class="bi bi-people-fill"></i>

                    Datos de Padres y Padrinos

                </div>


                <div class="row form-row">


                    <!-- PADRE -->

                    <div class="col-md-6 mb-3">

                        <label for="nombre_padre"
                               class="form-label">

                            <i class="bi bi-person-vcard me-1"></i>

                            Nombre del Padre

                            <span class="required-star">*</span>

                        </label>

                        <input
                            type="text"
                            id="nombre_padre"
                            name="nombre_padre"
                            class="form-control"
                            placeholder="Nombre completo del padre"
                            required
                        >

                    </div>


                    <!-- MADRE -->

                    <div class="col-md-6 mb-3">

                        <label for="nombre_madre"
                               class="form-label">

                            <i class="bi bi-person-vcard me-1"></i>

                            Nombre de la Madre

                            <span class="required-star">*</span>

                        </label>

                        <input
                            type="text"
                            id="nombre_madre"
                            name="nombre_madre"
                            class="form-control"
                            placeholder="Nombre completo de la madre"
                            required
                        >

                    </div>

                </div>


                <div class="row form-row">


                    <!-- PADRINO -->

                    <div class="col-md-6 mb-3">

                        <label for="nombre_padrino"
                               class="form-label">

                            <i class="bi bi-person-heart me-1"></i>

                            Nombre del Padrino

                        </label>

                        <input
                            type="text"
                            id="nombre_padrino"
                            name="nombre_padrino"
                            class="form-control"
                            placeholder="Nombre completo del padrino"
                        >

                    </div>


                    <!-- MADRINA -->

                    <div class="col-md-6 mb-3">

                        <label for="nombre_madrina"
                               class="form-label">

                            <i class="bi bi-person-heart me-1"></i>

                            Nombre de la Madrina

                        </label>

                        <input
                            type="text"
                            id="nombre_madrina"
                            name="nombre_madrina"
                            class="form-control"
                            placeholder="Nombre completo de la madrina"
                        >

                    </div>

                </div>


                <!-- ==========================================
                     CONTACTO
                ========================================== -->

                <div class="section-title mt-4">

                    <i class="bi bi-telephone-fill"></i>

                    Información de Contacto

                </div>


                <div class="row form-row">


                    <!-- TELÉFONO -->

                    <div class="col-md-6 mb-3">

                        <label for="telefono"
                               class="form-label">

                            <i class="bi bi-phone me-1"></i>

                            Teléfono

                            <span class="required-star">*</span>

                        </label>

                        <input
                            type="tel"
                            id="telefono"
                            name="telefono"
                            class="form-control"
                            placeholder="Ej: 76543210"
                            pattern="[0-9]{7,10}"
                            title="Ingrese un número de teléfono válido"
                            required
                        >

                    </div>


                    <!-- EMAIL -->

                    <div class="col-md-6 mb-3">

                        <label for="email"
                               class="form-label">

                            <i class="bi bi-envelope me-1"></i>

                            Correo Electrónico

                            <span class="required-star">*</span>

                        </label>

                        <input
                            type="email"
                            id="email"
                            name="email"
                            class="form-control"
                            placeholder="ejemplo@correo.com"
                            required
                        >

                    </div>

                </div>


                <!-- ==========================================
                     BOTONES
                ========================================== -->

                <div class="btn-group-actions mt-4">


                    <!-- LIMPIAR -->

                    <button
                        type="reset"
                        class="btn btn-outline-secondary px-5"
                    >

                        <i class="bi bi-eraser me-2"></i>

                        Limpiar

                    </button>


                    <!-- ENVIAR -->

                    <button
                        type="submit"
                        class="btn btn-primary px-5"
                    >

                        <i class="bi bi-send-fill me-2"></i>

                        Enviar Inscripción

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

    /* ==========================================
       VALIDACIÓN DE FECHA DE NACIMIENTO
    ========================================== */

    const fechaNacimiento =
        document.getElementById('fecha_nacimiento');

    const hoy = new Date();

    const año = hoy.getFullYear();

    const mes = String(hoy.getMonth() + 1).padStart(2, '0');

    const dia = String(hoy.getDate()).padStart(2, '0');

    const fechaActual = `${año}-${mes}-${dia}`;

    fechaNacimiento.setAttribute('max', fechaActual);


    /* ==========================================
       VALIDACIÓN DEL TELÉFONO
    ========================================== */

    const telefono =
        document.getElementById('telefono');

    telefono.addEventListener('input', function () {

        this.value = this.value.replace(/[^0-9]/g, '');

    });


});

</script>


</body>

</html>