<?php
session_start();

require_once "../servidor/conexionBD.php";

// Configuración de paginación
$registrosPorPagina = 9;
$paginaActual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$offset = ($paginaActual - 1) * $registrosPorPagina;

// Filtros opcionales
$filtroTipo = isset($_GET['tipo']) ? mysqli_real_escape_string($conexion, $_GET['tipo']) : '';
$filtroBusqueda = isset($_GET['buscar']) ? mysqli_real_escape_string($conexion, $_GET['buscar']) : '';

// Construir consulta base
$sqlBase = "FROM actividades WHERE estado = 'Activo'";
$params = [];

if (!empty($filtroTipo)) {
    $sqlBase .= " AND tipo_actividad = '$filtroTipo'";
}

if (!empty($filtroBusqueda)) {
    $sqlBase .= " AND (nombre_actividad LIKE '%$filtroBusqueda%' OR descripcion LIKE '%$filtroBusqueda%')";
}

// Contar total de registros para paginación
$sqlCount = "SELECT COUNT(*) as total $sqlBase";
$resultCount = mysqli_query($conexion, $sqlCount);
$totalRegistros = mysqli_fetch_assoc($resultCount)['total'];
$totalPaginas = ceil($totalRegistros / $registrosPorPagina);

// Consulta principal
$sql = "SELECT 
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
        $sqlBase
        ORDER BY fecha_inicio ASC
        LIMIT $offset, $registrosPorPagina";

$resultado = mysqli_query($conexion, $sql);

if (!$resultado) {
    die("Error al consultar actividades: " . mysqli_error($conexion));
}

// Obtener tipos de actividades para el filtro
$sqlTipos = "SELECT DISTINCT tipo_actividad FROM actividades WHERE estado = 'Activo' ORDER BY tipo_actividad";
$resultTipos = mysqli_query($conexion, $sqlTipos);
$tiposActividades = [];
while ($tipo = mysqli_fetch_assoc($resultTipos)) {
    $tiposActividades[] = $tipo['tipo_actividad'];
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Actividades | Oratorio y Liturgia</title>
    
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
            --shadow-sm: 0 2px 8px rgba(0,0,0,0.06);
            --shadow-md: 0 4px 20px rgba(0,0,0,0.08);
            --shadow-lg: 0 8px 40px rgba(0,0,0,0.12);
            --radius-lg: 20px;
            --radius-md: 16px;
            --radius-sm: 12px;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: #f8fafc;
            min-height: 100vh;
        }

        .page-header {
            background: var(--primary-gradient);
            padding: 4rem 0 3.5rem;
            margin-bottom: 3rem;
            position: relative;
            overflow: hidden;
        }

        .page-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 300px;
            height: 300px;
            background: rgba(255,255,255,0.05);
            border-radius: 50%;
        }

        .page-header::after {
            content: '';
            position: absolute;
            bottom: -60%;
            left: -5%;
            width: 250px;
            height: 250px;
            background: rgba(255,255,255,0.05);
            border-radius: 50%;
        }

        .page-header h1 {
            font-weight: 800;
            letter-spacing: -0.02em;
            color: #fff;
            position: relative;
            z-index: 1;
        }

        .page-header p {
            color: rgba(255,255,255,0.85);
            font-weight: 400;
            position: relative;
            z-index: 1;
        }

        .page-header .badge-header {
            background: rgba(255,255,255,0.2);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,0.15);
            color: #fff;
            padding: 0.6rem 1.5rem;
            font-weight: 500;
            letter-spacing: 0.5px;
            position: relative;
            z-index: 1;
        }

        /* Tarjetas */
        .activity-card {
            background: #ffffff;
            border: none;
            border-radius: var(--radius-lg);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: var(--shadow-sm);
            height: 100%;
            overflow: hidden;
            position: relative;
        }

        .activity-card:hover {
            transform: translateY(-6px);
            box-shadow: var(--shadow-lg);
        }

        .activity-card .card-body {
            padding: 1.75rem;
        }

        /* Badge de tipo */
        .badge-type {
            background: #f1f5f9;
            color: #475569;
            font-weight: 500;
            padding: 0.35rem 1rem;
            border-radius: 50px;
            font-size: 0.75rem;
        }

        .badge-type i {
            color: #6366f1;
            margin-right: 0.4rem;
        }

        .badge-status {
            background: #dcfce7;
            color: #166534;
            font-weight: 500;
            padding: 0.35rem 1rem;
            border-radius: 50px;
            font-size: 0.75rem;
        }

        .badge-status i {
            margin-right: 0.3rem;
        }

        /* Info items */
        .info-item {
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
            padding: 0.5rem 0;
        }

        .info-item .icon-wrapper {
            width: 32px;
            height: 32px;
            background: #eef2ff;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #6366f1;
            flex-shrink: 0;
            font-size: 0.9rem;
        }

        .info-item .label {
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #94a3b8;
            font-weight: 600;
        }

        .info-item .value {
            font-weight: 600;
            color: #1e293b;
            font-size: 0.95rem;
        }

        /* Métricas */
        .metric-box {
            background: #f8fafc;
            border-radius: var(--radius-sm);
            padding: 0.75rem 1rem;
            text-align: center;
            transition: all 0.2s;
        }

        .metric-box:hover {
            background: #f1f5f9;
        }

        .metric-box .metric-label {
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            color: #94a3b8;
            font-weight: 600;
        }

        .metric-box .metric-value {
            font-weight: 700;
            font-size: 1.1rem;
            color: #1e293b;
        }

        /* Botón */
        .btn-detail {
            background: var(--primary-gradient);
            border: none;
            color: #fff;
            font-weight: 600;
            padding: 0.7rem 1.5rem;
            border-radius: var(--radius-sm);
            transition: all 0.3s;
            width: 100%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .btn-detail:hover {
            transform: scale(1.02);
            box-shadow: 0 4px 20px rgba(99, 102, 241, 0.4);
            color: #fff;
        }

        .btn-detail i {
            transition: transform 0.3s;
        }

        .btn-detail:hover i {
            transform: translateX(4px);
        }

        /* Filtros */
        .filters-section {
            background: #fff;
            border-radius: var(--radius-md);
            padding: 1.25rem 1.5rem;
            box-shadow: var(--shadow-sm);
            margin-bottom: 2.5rem;
        }

        .filters-section .form-control,
        .filters-section .form-select {
            border: 2px solid #e2e8f0;
            border-radius: var(--radius-sm);
            padding: 0.6rem 1rem;
            font-size: 0.9rem;
            transition: all 0.2s;
        }

        .filters-section .form-control:focus,
        .filters-section .form-select:focus {
            border-color: #6366f1;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
        }

        .filters-section .btn-clear {
            color: #94a3b8;
            transition: all 0.2s;
        }

        .filters-section .btn-clear:hover {
            color: #475569;
        }

        /* Paginación */
        .pagination-custom .page-link {
            border: none;
            color: #475569;
            font-weight: 500;
            border-radius: 10px;
            padding: 0.5rem 1rem;
            margin: 0 0.2rem;
            transition: all 0.2s;
        }

        .pagination-custom .page-link:hover {
            background: #eef2ff;
            color: #6366f1;
        }

        .pagination-custom .page-item.active .page-link {
            background: var(--primary-gradient);
            color: #fff;
            box-shadow: 0 4px 15px rgba(99, 102, 241, 0.3);
        }

        /* Estado vacío */
        .empty-state {
            background: #fff;
            border-radius: var(--radius-lg);
            padding: 4rem 2rem;
            text-align: center;
            box-shadow: var(--shadow-sm);
        }

        .empty-state .empty-icon {
            width: 80px;
            height: 80px;
            background: #f1f5f9;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            font-size: 2.5rem;
            color: #94a3b8;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .page-header {
                padding: 2.5rem 0 2rem;
            }
            
            .activity-card .card-body {
                padding: 1.25rem;
            }
            
            .filters-section {
                padding: 1rem;
            }
        }
    </style>
</head>
<body>

<!-- =====================================================
     ENCABEZADO MEJORADO
====================================================== -->
<header class="page-header">
    <div class="container">
        <div class="text-center">
            <span class="badge-header d-inline-block px-4 py-2 rounded-pill mb-3">
                <i class="fa-solid fa-calendar-days me-2"></i>
                ACTIVIDADES
            </span>
            <h1 class="display-4 fw-bold mb-2">Descubre tu próxima actividad</h1>
            <p class="fs-5 mb-0 mx-auto" style="max-width: 500px;">
                Encuentra una actividad y forma parte de nuestra comunidad
            </p>
        </div>
    </div>
</header>

<main class="container pb-5">

    <!-- =====================================================
         FILTROS Y BÚSQUEDA
    ====================================================== -->
    <section class="filters-section" aria-label="Filtros de búsqueda">
        <form method="GET" action="" class="row g-3 align-items-end">
            <div class="col-12 col-md-5">
                <label for="buscar" class="form-label fw-semibold small text-secondary">
                    <i class="fa-solid fa-search me-1"></i> Buscar
                </label>
                <div class="input-group">
                    <span class="input-group-text bg-white border-0 ps-0">
                        <i class="fa-solid fa-search text-secondary"></i>
                    </span>
                    <input 
                        type="text" 
                        name="buscar" 
                        id="buscar"
                        class="form-control border-0 ps-0" 
                        placeholder="Nombre o descripción..." 
                        value="<?php echo htmlspecialchars($filtroBusqueda); ?>"
                    >
                </div>
            </div>
            
            <div class="col-12 col-md-4">
                <label for="tipo" class="form-label fw-semibold small text-secondary">
                    <i class="fa-solid fa-tag me-1"></i> Tipo
                </label>
                <select name="tipo" id="tipo" class="form-select">
                    <option value="">Todos los tipos</option>
                    <?php foreach ($tiposActividades as $tipo): ?>
                        <option value="<?php echo htmlspecialchars($tipo); ?>" 
                            <?php echo ($filtroTipo === $tipo) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($tipo); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="col-12 col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary w-100 rounded-3">
                    <i class="fa-solid fa-filter me-2"></i> Filtrar
                </button>
                <?php if (!empty($filtroBusqueda) || !empty($filtroTipo)): ?>
                    <a href="actividades.php" class="btn btn-outline-secondary rounded-3">
                        <i class="fa-solid fa-times"></i>
                    </a>
                <?php endif; ?>
            </div>
        </form>
    </section>

    <!-- =====================================================
         RESULTADOS
    ====================================================== -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <span class="fw-semibold"><?php echo $totalRegistros; ?></span>
            <span class="text-secondary">actividades disponibles</span>
        </div>
        <?php if ($totalPaginas > 1): ?>
            <small class="text-secondary">
                Página <?php echo $paginaActual; ?> de <?php echo $totalPaginas; ?>
            </small>
        <?php endif; ?>
    </div>

    <!-- =====================================================
         TARJETAS
    ====================================================== -->
    <div class="row g-4">
        <?php if (mysqli_num_rows($resultado) > 0): ?>
            <?php while ($actividad = mysqli_fetch_assoc($resultado)): ?>
                <?php
                $cupoMaximo = (int) $actividad['cupo_maximo'];
                $cupoDisponible = (int) $actividad['cupo_disponible'];
                
                // Estado de cupos
                if ($cupoDisponible <= 0) {
                    $colorCupo = "danger";
                    $textoCupo = "Cupos agotados";
                    $iconoCupo = "fa-circle-exclamation";
                    $statusBadge = 'danger';
                    $statusText = 'Agotado';
                } elseif ($cupoDisponible <= 3) {
                    $colorCupo = "warning";
                    $textoCupo = "Últimos $cupoDisponible cupos";
                    $iconoCupo = "fa-triangle-exclamation";
                    $statusBadge = 'warning';
                    $statusText = '¡Últimos cupos!';
                } else {
                    $colorCupo = "success";
                    $textoCupo = "$cupoDisponible cupos disponibles";
                    $iconoCupo = "fa-circle-check";
                    $statusBadge = 'success';
                    $statusText = 'Disponible';
                }
                
                // Formato de fechas
                $fechaInicio = date("d M Y", strtotime($actividad['fecha_inicio']));
                $fechaFin = date("d M Y", strtotime($actividad['fecha_fin']));
                $horaInicio = date("H:i", strtotime($actividad['hora_inicio']));
                $horaFin = date("H:i", strtotime($actividad['hora_fin']));
                
                // Descripción corta
                $descripcion = $actividad['descripcion'];
                if (strlen($descripcion) > 110) {
                    $descripcion = substr($descripcion, 0, 110) . "...";
                }
                
                // Costo formateado
                $costoMostrar = (empty($actividad['costo']) || $actividad['costo'] == 0) 
                    ? '<span class="text-success">Gratis</span>' 
                    : 'Bs. ' . number_format($actividad['costo'], 2);
                ?>
                
                <div class="col-12 col-md-6 col-xl-4">
                    <article class="activity-card">
                        <div class="card-body d-flex flex-column">
                            
                            <!-- Cabecera -->
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="badge-type">
                                    <i class="fa-solid fa-tag"></i>
                                    <?php echo htmlspecialchars($actividad['tipo_actividad']); ?>
                                </span>
                                <span class="badge-status bg-<?php echo $statusBadge; ?> bg-opacity-10 text-<?php echo $statusBadge; ?>">
                                    <i class="fa-regular <?php echo $iconoCupo; ?>"></i>
                                    <?php echo $statusText; ?>
                                </span>
                            </div>
                            
                            <!-- Título -->
                            <h4 class="fw-bold mb-1">
                                <?php echo htmlspecialchars($actividad['nombre_actividad']); ?>
                            </h4>
                            
                            <!-- Descripción -->
                            <p class="text-secondary small mb-3">
                                <?php echo htmlspecialchars($descripcion); ?>
                            </p>
                            
                            <!-- Información -->
                            <div class="bg-light rounded-3 p-3 mb-3">
                                
                                <div class="info-item">
                                    <div class="icon-wrapper">
                                        <i class="fa-regular fa-calendar"></i>
                                    </div>
                                    <div>
                                        <div class="label">Fecha</div>
                                        <div class="value">
                                            <?php echo $fechaInicio; ?>
                                            <?php if ($fechaInicio != $fechaFin): ?>
                                                <span class="text-secondary">— <?php echo $fechaFin; ?></span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="info-item">
                                    <div class="icon-wrapper">
                                        <i class="fa-regular fa-clock"></i>
                                    </div>
                                    <div>
                                        <div class="label">Horario</div>
                                        <div class="value">
                                            <?php echo $horaInicio; ?> — <?php echo $horaFin; ?>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="info-item mb-0">
                                    <div class="icon-wrapper">
                                        <i class="fa-solid fa-calendar-week"></i>
                                    </div>
                                    <div>
                                        <div class="label">Días</div>
                                        <div class="value">
                                            <?php echo htmlspecialchars($actividad['dias_semana']); ?>
                                        </div>
                                    </div>
                                </div>
                                
                            </div>
                            
                            <!-- Métricas -->
                            <div class="row g-2 mb-3">
                                <div class="col-6">
                                    <div class="metric-box">
                                        <div class="metric-label">
                                            <i class="fa-solid fa-hourglass-half me-1"></i> Duración
                                        </div>
                                        <div class="metric-value">
                                            <?php echo htmlspecialchars($actividad['duracion']); ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="metric-box">
                                        <div class="metric-label">
                                            <i class="fa-solid fa-users me-1"></i> Cupos
                                        </div>
                                        <div class="metric-value text-<?php echo $colorCupo; ?>">
                                            <?php echo $textoCupo; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Costo y cupo máximo -->
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    <small class="text-secondary text-uppercase fw-semibold" style="font-size:0.65rem; letter-spacing:0.5px;">
                                        Inversión
                                    </small>
                                    <div class="fs-5 fw-bold">
                                        <?php echo $costoMostrar; ?>
                                    </div>
                                </div>
                                <div class="text-end">
                                    <small class="text-secondary text-uppercase fw-semibold" style="font-size:0.65rem; letter-spacing:0.5px;">
                                        Cupo máximo
                                    </small>
                                    <div class="fw-semibold">
                                        <?php echo $cupoMaximo; ?> personas
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Botón -->
                            <a href="detalle_actividad.php?id=<?php echo $actividad['id_actividad']; ?>" 
                               class="btn-detail mt-auto">
                                Ver actividad
                                <i class="fa-solid fa-arrow-right"></i>
                            </a>
                            
                        </div>
                    </article>
                </div>
                
            <?php endwhile; ?>
            
        <?php else: ?>
            
            <!-- =====================================================
                 SIN ACTIVIDADES
            ====================================================== -->
            <div class="col-12">
                <div class="empty-state">
                    <div class="empty-icon">
                        <i class="fa-regular fa-calendar-xmark"></i>
                    </div>
                    <h4 class="fw-bold mb-2">No hay actividades disponibles</h4>
                    <p class="text-secondary mb-0">
                        <?php if (!empty($filtroBusqueda) || !empty($filtroTipo)): ?>
                            No encontramos actividades que coincidan con tu búsqueda.
                            <br>
                            <a href="actividades.php" class="btn btn-link text-primary">
                                <i class="fa-solid fa-arrow-left me-1"></i> Ver todas las actividades
                            </a>
                        <?php else: ?>
                            En este momento no existen actividades disponibles.
                            <br>
                            <span class="text-secondary">Vuelve a consultar más tarde.</span>
                        <?php endif; ?>
                    </p>
                </div>
            </div>
            
        <?php endif; ?>
    </div>
    
    <!-- =====================================================
         PAGINACIÓN
    ====================================================== -->
    <?php if ($totalPaginas > 1): ?>
        <nav aria-label="Navegación de páginas" class="mt-5">
            <ul class="pagination justify-content-center pagination-custom">
                <?php if ($paginaActual > 1): ?>
                    <li class="page-item">
                        <a class="page-link" href="?pagina=<?php echo $paginaActual - 1; ?>&buscar=<?php echo urlencode($filtroBusqueda); ?>&tipo=<?php echo urlencode($filtroTipo); ?>">
                            <i class="fa-solid fa-chevron-left"></i>
                        </a>
                    </li>
                <?php endif; ?>
                
                <?php
                $rango = 2;
                $inicio = max(1, $paginaActual - $rango);
                $fin = min($totalPaginas, $paginaActual + $rango);
                
                if ($inicio > 1) {
                    echo '<li class="page-item"><a class="page-link" href="?pagina=1&buscar=' . urlencode($filtroBusqueda) . '&tipo=' . urlencode($filtroTipo) . '">1</a></li>';
                    if ($inicio > 2) {
                        echo '<li class="page-item disabled"><span class="page-link">…</span></li>';
                    }
                }
                
                for ($i = $inicio; $i <= $fin; $i++):
                ?>
                    <li class="page-item <?php echo $i === $paginaActual ? 'active' : ''; ?>">
                        <a class="page-link" href="?pagina=<?php echo $i; ?>&buscar=<?php echo urlencode($filtroBusqueda); ?>&tipo=<?php echo urlencode($filtroTipo); ?>">
                            <?php echo $i; ?>
                        </a>
                    </li>
                <?php endfor; ?>
                
                <?php if ($fin < $totalPaginas): ?>
                    <?php if ($fin < $totalPaginas - 1): ?>
                        <li class="page-item disabled"><span class="page-link">…</span></li>
                    <?php endif; ?>
                    <li class="page-item">
                        <a class="page-link" href="?pagina=<?php echo $totalPaginas; ?>&buscar=<?php echo urlencode($filtroBusqueda); ?>&tipo=<?php echo urlencode($filtroTipo); ?>">
                            <?php echo $totalPaginas; ?>
                        </a>
                    </li>
                <?php endif; ?>
                
                <?php if ($paginaActual < $totalPaginas): ?>
                    <li class="page-item">
                        <a class="page-link" href="?pagina=<?php echo $paginaActual + 1; ?>&buscar=<?php echo urlencode($filtroBusqueda); ?>&tipo=<?php echo urlencode($filtroTipo); ?>">
                            <i class="fa-solid fa-chevron-right"></i>
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </nav>
    <?php endif; ?>

</main>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>