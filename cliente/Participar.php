<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../assets/img/logo.jpg">
    <title>Sistema de Registro - Parroquia</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    <style>
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #e4e8f0 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .header {
            background: linear-gradient(to right, #1a2980, #26d0ce);
            color: white;
            border-radius: 0 0 1rem 1rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .logo-container {
            display: flex;
            align-items: center;
            justify-content: center;
            background: white;
            width: 80px;
            height: 80px;
            border-radius: 50%;
            margin: 0 auto 1rem;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .logo-container i {
            font-size: 2.5rem;
            color: #1a2980;
        }

        .footer {
            background-color: rgba(0, 0, 0, 0.05);
            border-radius: 1rem 1rem 0 0;
            margin-top: 3rem;
            padding: 1.5rem 0;
            font-size: 0.9rem;
            color: #6c757d;
        }
    </style>
</head>

<body>

    <!-- HEADER -->
    <div class="header py-4 mb-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-2 text-center">
                    <div class="logo-container">
                        <i class="bi bi-house-heart-fill"></i>
                    </div>
                </div>
                <div class="col-md-10">
                    <h1 class="fw-bold mb-2">Registrar Participación</h1>
                    <p class="lead mb-0">Seleccione el tipo de registro que desea realizar</p>
                </div>
            </div>
        </div>
    </div>

    <!-- CONTENIDO PRINCIPAL -->
    <div class="container">
        <div class="row justify-content-center g-4">

            <!-- Formulario de Actividades-->
            <div class="col-lg-4 col-md-6">
                <div class="card shadow-sm h-100 text-center">
                    <div class="card-body">
                        <div class="mb-3">
                            <i class="bi bi-calendar-check text-success fs-1"></i>
                        </div>
                        <h5 class="card-title">Nuestras Actividades</h5>
                        <p class="card-text">
                            Inscríbase en actividades parroquiales como retiros, encuentros juveniles,
                            grupos de oración y eventos comunitarios.
                        </p>
                        <a href="../cliente/Ver_Actividades.php" class="btn btn-success">
                            <i class="bi bi-arrow-right-circle me-2"></i> Ver Actividades
                        </a>
                    </div>
                </div>
            </div>

            <!-- Formulario de Eventos-->
            <div class="col-lg-4 col-md-6">
                <div class="card shadow-sm h-100 text-center">
                    <div class="card-body">
                        <div class="mb-3">
                            <i class="bi bi-calendar-event text-warning fs-1"></i>
                        </div>
                        <h5 class="card-title">Formulario de Eventos</h5>
                        <p class="card-text">
                            Inscríbase y registre su participación en eventos y encuentros
                            organizados por el Oratorio Universitario.
                        </p>
                        <a href="../cliente/Ver_Eventos.php" class="btn btn-warning text-light">
                            <i class="bi bi-arrow-right-circle me-2"></i> Ver Eventos
                        </a>
                    </div>
                </div>
            </div>

            <!-- Formulario de Usuarios -->
            <!-- <div class="col-lg-4 col-md-6">
                <div class="card shadow-sm h-100 text-center">
                    <div class="card-body">
                        <div class="mb-3">
                            <i class="bi bi-mortarboard-fill text-info fs-1"></i>
                        </div>
                        <h5 class="card-title">Formulario de Usuarios</h5>
                        <p class="card-text">
                            Participe en talleres formativos sobre temas bíblicos,
                            familiares, espirituales y de crecimiento personal.
                        </p>
                        <a href="../cliente/usuarios_sistema.php" class="btn btn-info text-light">
                            <i class="bi bi-arrow-right-circle me-2"></i> Acceder al Registro
                        </a>
                    </div>
                </div>
            </div> -->

            <!-- Formulario de Formación Sacramental -->
            <div class="col-lg-4 col-md-6">
                <div class="card shadow-sm h-100 text-center">
                    <div class="card-body">
                        <div class="mb-3">
                            <i class="bi bi-book-half text-danger fs-1"></i>
                        </div>

                        <h5 class="card-title">Formulario de Formación Sacramental</h5>

                        <p class="card-text">
                            Inscríbase en el proceso de formación sacramental
                            para fortalecer su fe y prepararse adecuadamente
                            para recibir los sacramentos de la Iglesia.
                        </p>

                        <a href="../cliente/FormacionSacramental.php"
                            class="btn btn-danger text-light">

                            <i class="bi bi-arrow-right-circle me-2"></i>
                            Acceder al Registro
                        </a>
                    </div>
                </div>
            </div>

            <!-- Formulario de Inscripción -->
            <!-- <div class="col-lg-4 col-md-6">
                <div class="card shadow-sm h-100 text-center border-0">

                    <div class="card-body">

                        <div class="mb-3">
                            <i class="bi bi-person-plus-fill text-dark fs-1"></i>
                        </div>

                        <h5 class="card-title">Formulario de Inscripción</h5>

                        <p class="card-text text-muted">
                            Complete el formulario de inscripción para
                            participar en las actividades del oratorio,
                            ya sea como estudiante universitario o participante externo.
                        </p>

                        <a href="../cliente/inscripcion.php"
                            class="btn btn-dark px-4">

                            <i class="bi bi-arrow-right-circle me-2"></i>
                            Acceder al Registro
                        </a>

                    </div>
                </div>
            </div> -->

            <!-- Formulario de Registro de Participantes - Universidades -->
            <!-- <div class="col-lg-4 col-md-6">
                <div class="card shadow-sm h-100 text-center">

                    <div class="card-body">

                        <div class="mb-3">
                            <i class="bi bi-people-fill text-success fs-1"></i>
                        </div>

                        <h5 class="card-title ">Formulario de Participantes</h5>

                        <p class="card-text">
                            Complete el formulario de inscripción para
                            participar en las actividades del oratorio,
                            ya sea como estudiante universitario o participante externo.
                        </p>

                        <a href="../cliente/universidades.php"
                            class="btn btn-success text-light">

                            <i class="bi bi-arrow-right-circle me-2"></i>
                            Acceder al Registro
                        </a>

                    </div>
                </div>
            </div> -->

            <!-- Formulario de Asistencia -->
           <!-- */ <div class="col-lg-4 col-md-6">
                <div class="card shadow-sm h-100 text-center border-0">

                    <div class="card-body">

                        <div class="mb-3">
                            <i class="bi bi-clipboard2-check-fill text-warning fs-1"></i>
                        </div>

                        <h5 class="card-title">
                            Formulario de Asistencia
                        </h5>

                        <p class="card-text text-muted">
                            Registre su asistencia a las actividades
                            y eventos del oratorio de manera rápida
                            y organizada.
                        </p>

                        <a href="../cliente/asistencias.php"
                            class="btn btn-warning text-light px-4">

                            <i class="bi bi-arrow-right-circle me-2"></i>
                            Registrar Asistencia
                        </a>

                    </div>
                </div>
            </div>         -->

            <!-- Formulario de Personas -->
          <!--  <div class="col-lg-4 col-md-6">
                <div class="card shadow-sm h-100 text-center border-0">

                    <div class="card-body">

                        <div class="mb-3">
                            <i class="bi bi-person-lines-fill text-primary fs-1"></i>
                        </div>

                        <h5 class="card-title fw-bold">
                            Formulario de Personas
                        </h5>

                        <p class="card-text text-muted">
                            Registre y gestione la información personal de los participantes
                            del sistema de forma organizada y segura.
                        </p>

                        <a href="../cliente/personas.php"
                            class="btn btn-primary px-4">

                            <i class="bi bi-arrow-right-circle me-2"></i>
                            Gestionar Personas
                        </a>

                    </div>
                </div>
            </div>   -->

            <!-- Formulario de Pagos -->
            <!-- <div class="col-lg-4 col-md-6">
                <div class="card shadow-sm h-100 text-center border-0">

                    <div class="card-body">

                        <div class="mb-3">
                            <i class="bi bi-cash-coin text-danger fs-1"></i>
                        </div>

                        <h5 class="card-title fw-bold">
                            Formulario de Pagos
                        </h5>

                        <p class="card-text text-muted">
                            Registre y controle los pagos realizados por los participantes
                            dentro del sistema de actividades.
                        </p>

                        <a href="../cliente/pagos.php"
                            class="btn btn-danger px-4">

                            <i class="bi bi-arrow-right-circle me-2"></i>
                            Gestionar Pagos
                        </a>

                    </div>
                </div>
            </div>
        </div> -->


        <!-- INFORMACIÓN -->
        <div class="row mt-5">
            <div class="col-12">
                <div class="alert alert-info d-flex align-items-start">
                    <i class="bi bi-info-circle-fill fs-4 me-3"></i>
                    <div>
                        <h5 class="alert-heading">Información importante</h5>
                        <p class="mb-0">
                            Para completar cualquier registro, asegúrese de tener su información personal
                            actualizada. Para consultas, contacte a la oficina pastoral.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- FOOTER -->
    <div class="footer mt-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6 text-center text-md-start">
                    <p class="mb-0">
                        <i class="bi bi-c-circle me-1"></i> 2026 Oratorio y Liturgia. Todos los derechos reservados.
                    </p>
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <p class="mb-0">
                        <i class="bi bi-telephone me-1"></i> Contacto: (123) 456-7890
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Animación segura (no rompe botones) -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const cards = document.querySelectorAll('.card');

            cards.forEach((card, index) => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';

                setTimeout(() => {
                    card.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, index * 150);
            });
        });
    </script>

</body>

</html>