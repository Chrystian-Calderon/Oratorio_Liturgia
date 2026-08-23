<?php
session_start();

require_once "../servidor/conexionBD.php";

// Configuración de paginación
$registrosPorPagina = 6;
$paginaActual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$offset = ($paginaActual - 1) * $registrosPorPagina;

// Filtros
$filtroEstado = isset($_GET['estado']) ? mysqli_real_escape_string($conexion, $_GET['estado']) : '';
$filtroBusqueda = isset($_GET['buscar']) ? mysqli_real_escape_string($conexion, $_GET['buscar']) : '';
$filtroFecha = isset($_GET['fecha']) ? mysqli_real_escape_string($conexion, $_GET['fecha']) : '';

// Construir consulta base
$sqlBase = "FROM eventos WHERE 1=1";
$params = [];

if (!empty($filtroEstado)) {
    $sqlBase .= " AND estado = '$filtroEstado'";
}

if (!empty($filtroBusqueda)) {
    $sqlBase .= " AND (nombre_evento LIKE '%$filtroBusqueda%' OR descripcion LIKE '%$filtroBusqueda%' OR lugar LIKE '%$filtroBusqueda%')";
}

if (!empty($filtroFecha)) {
    $sqlBase .= " AND DATE(fecha_evento) = '$filtroFecha'";
}

// Contar total
$sqlCount = "SELECT COUNT(*) as total $sqlBase";
$resultCount = mysqli_query($conexion, $sqlCount);
$totalRegistros = mysqli_fetch_assoc($resultCount)['total'];
$totalPaginas = ceil($totalRegistros / $registrosPorPagina);

// Consulta principal
$sql = "SELECT 
            id_evento,
            nombre_evento,
            descripcion,
            fecha_evento,
            hora_evento,
            lugar,
            estado,
            fecha_creacion,
            fecha_actualizacion
        $sqlBase
        ORDER BY 
            CASE 
                WHEN estado = 'Próximo' THEN 1
                WHEN estado = 'En curso' THEN 2
                WHEN estado = 'Finalizado' THEN 3
                WHEN estado = 'Cancelado' THEN 4
            END,
            fecha_evento ASC
        LIMIT $offset, $registrosPorPagina";

$resultado = mysqli_query($conexion, $sql);

if (!$resultado) {
    die("Error al consultar eventos: " . mysqli_error($conexion));
}

// Obtener estados para el filtro
$estadosEventos = ['Próximo', 'En curso', 'Finalizado', 'Cancelado'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eventos | Oratorio y Liturgia</title>
    
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #8b5cf6 0%, #6366f1 50%, #3b82f6 100%);
            --shadow-sm: 0 2px 8px rgba(0,0,0,0.06);
            --shadow-md: 0 4px 20px rgba(0,0,0,0.08);
            --shadow-lg: 0 8px 40px rgba(0,0,0,0.12);
            --shadow-xl: 0 20px 60px rgba(0,0,0,0.15);
            --radius-lg: 24px;
            --radius-md: 16px;
            --radius-sm: 12px;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: #f8fafc;
            min-height: 100vh;
        }

        /* ===== HEADER ===== */
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
            top: -60%;
            right: -10%;
            width: 500px;
            height: 500px;
            background: rgba(255,255,255,0.05);
            border-radius: 50%;
            animation: float 20s ease-in-out infinite;
        }

        .page-header::after {
            content: '';
            position: absolute;
            bottom: -40%;
            left: -5%;
            width: 400px;
            height: 400px;
            background: rgba(255,255,255,0.05);
            border-radius: 50%;
            animation: float 25s ease-in-out infinite reverse;
        }

        @keyframes float {
            0%, 100% { transform: translate(0, 0) scale(1); }
            33% { transform: translate(30px, -20px) scale(1.1); }
            66% { transform: translate(-20px, 30px) scale(0.9); }
        }

        .page-header h1 {
            font-weight: 900;
            letter-spacing: -0.03em;
            color: #fff;
            position: relative;
            z-index: 1;
            text-shadow: 0 2px 20px rgba(0,0,0,0.1);
        }

        .page-header p {
            color: rgba(255,255,255,0.9);
            font-weight: 300;
            position: relative;
            z-index: 1;
        }

        .badge-header {
            background: rgba(255,255,255,0.2);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,0.2);
            color: #fff;
            padding: 0.6rem 1.5rem;
            font-weight: 600;
            letter-spacing: 0.5px;
            position: relative;
            z-index: 1;
        }

        /* ===== FILTROS ===== */
        .filters-section {
            background: #fff;
            border-radius: var(--radius-md);
            padding: 1.5rem 2rem;
            box-shadow: var(--shadow-sm);
            margin-bottom: 2.5rem;
            border: 1px solid rgba(0,0,0,0.03);
        }

        .filters-section .form-control,
        .filters-section .form-select {
            border: 2px solid #f1f5f9;
            border-radius: var(--radius-sm);
            padding: 0.7rem 1rem;
            font-size: 0.9rem;
            transition: all 0.2s;
            background: #fafbfc;
        }

        .filters-section .form-control:focus,
        .filters-section .form-select:focus {
            border-color: #8b5cf6;
            box-shadow: 0 0 0 4px rgba(139, 92, 246, 0.1);
            background: #fff;
        }

        .filters-section .form-label {
            font-weight: 600;
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #64748b;
        }

        .btn-primary-custom {
            background: var(--primary-gradient);
            border: none;
            color: #fff;
            font-weight: 600;
            padding: 0.7rem 1.5rem;
            border-radius: var(--radius-sm);
            transition: all 0.3s;
        }

        .btn-primary-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(99, 102, 241, 0.3);
            color: #fff;
        }

        /* ===== TARJETAS DE EVENTOS ===== */
        .event-card {
            background: #fff;
            border: none;
            border-radius: var(--radius-lg);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: var(--shadow-sm);
            height: 100%;
            overflow: hidden;
            position: relative;
        }

        .event-card:hover {
            transform: translateY(-8px);
            box-shadow: var(--shadow-xl);
        }

        .event-card .card-body {
            padding: 1.75rem;
        }

        /* Etiqueta de estado */
        .status-badge {
            padding: 0.35rem 1rem;
            border-radius: 50px;
            font-weight: 600;
            font-size: 0.75rem;
            letter-spacing: 0.3px;
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
        }

        .status-badge.proximo {
            background: #dbeafe;
            color: #1d4ed8;
        }

        .status-badge.en-curso {
            background: #fef3c7;
            color: #d97706;
        }

        .status-badge.finalizado {
            background: #d1fae5;
            color: #059669;
        }

        .status-badge.cancelado {
            background: #fee2e2;
            color: #dc2626;
        }

        /* Icono de estado en el borde */
        .event-card .status-indicator {
            position: absolute;
            top: 0;
            left: 0;
            width: 5px;
            height: 100%;
            border-radius: var(--radius-lg) 0 0 var(--radius-lg);
        }

        .event-card .status-indicator.proximo { background: #3b82f6; }
        .event-card .status-indicator.en-curso { background: #f59e0b; }
        .event-card .status-indicator.finalizado { background: #10b981; }
        .event-card .status-indicator.cancelado { background: #ef4444; }

        /* Información del evento */
        .event-info {
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
            padding: 0.6rem 0;
            border-bottom: 1px solid #f1f5f9;
        }

        .event-info:last-child {
            border-bottom: none;
        }

        .event-info .icon-wrapper {
            width: 36px;
            height: 36px;
            background: #f1f5f9;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #8b5cf6;
            flex-shrink: 0;
            font-size: 0.9rem;
            transition: all 0.3s;
        }

        .event-card:hover .icon-wrapper {
            background: #eef2ff;
            transform: scale(1.05);
        }

        .event-info .label {
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #94a3b8;
            font-weight: 600;
        }

        .event-info .value {
            font-weight: 600;
            color: #0f172a;
            font-size: 0.95rem;
            line-height: 1.4;
        }

        /* Lugar con ícono de mapa */
        .event-location {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: #f8fafc;
            padding: 0.3rem 0.8rem;
            border-radius: 50px;
            font-size: 0.8rem;
            color: #475569;
        }

        /* Botón de detalle */
        .btn-detail-event {
            background: var(--primary-gradient);
            border: none;
            color: #fff;
            font-weight: 600;
            padding: 0.75rem 1.5rem;
            border-radius: var(--radius-sm);
            transition: all 0.3s;
            width: 100%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .btn-detail-event:hover {
            transform: scale(1.02);
            box-shadow: 0 4px 20px rgba(99, 102, 241, 0.4);
            color: #fff;
        }

        .btn-detail-event i {
            transition: transform 0.3s;
        }

        .btn-detail-event:hover i {
            transform: translateX(4px);
        }

        /* ===== VISTA DE CALENDARIO ===== */
        .calendar-view {
            background: #fff;
            border-radius: var(--radius-md);
            padding: 1.5rem;
            box-shadow: var(--shadow-sm);
            margin-bottom: 2rem;
        }

        .calendar-view .day-card {
            text-align: center;
            padding: 0.75rem;
            border-radius: var(--radius-sm);
            transition: all 0.3s;
            cursor: pointer;
        }

        .calendar-view .day-card:hover {
            background: #f1f5f9;
        }

        .calendar-view .day-card.active {
            background: var(--primary-gradient);
            color: #fff;
        }

        .calendar-view .day-card .day-number {
            font-size: 1.5rem;
            font-weight: 700;
        }

        .calendar-view .day-card .day-name {
            font-size: 0.75rem;
            font-weight: 500;
        }

        /* ===== ESTADO VACÍO ===== */
        .empty-state {
            background: #fff;
            border-radius: var(--radius-lg);
            padding: 4rem 2rem;
            text-align: center;
            box-shadow: var(--shadow-sm);
        }

        .empty-state .empty-icon {
            width: 100px;
            height: 100px;
            background: #f1f5f9;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            font-size: 3rem;
            color: #94a3b8;
        }

        /* ===== PAGINACIÓN ===== */
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
            background: #f1f5f9;
            color: #8b5cf6;
        }

        .pagination-custom .page-item.active .page-link {
            background: var(--primary-gradient);
            color: #fff;
            box-shadow: 0 4px 15px rgba(99, 102, 241, 0.3);
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 768px) {
            .page-header {
                padding: 2.5rem 0 2rem;
            }
            
            .event-card .card-body {
                padding: 1.25rem;
            }
            
            .filters-section {
                padding: 1rem;
            }
            
            .calendar-view {
                padding: 1rem;
            }
        }
    </style>
</head>
<body>

<!-- =====================================================
     HEADER
====================================================== -->
<header class="page-header">
    <div class="container">
        <div class="text-center">
            <span class="badge-header d-inline-block px-4 py-2 rounded-pill mb-3">
                <i class="fa-regular fa-calendar-plus me-2"></i>
                EVENTOS
            </span>
            <h1 class="display-4 fw-bold mb-2">Calendario de eventos</h1>
            <p class="fs-5 mb-0 mx-auto" style="max-width: 550px;">
                Participa en nuestros eventos y vive experiencias únicas en comunidad
            </p>
        </div>
    </div>
</header>

<main class="container pb-5">

    <!-- =====================================================
         CALENDARIO RÁPIDO (Próximos 7 días)
    ====================================================== -->
    <section class="calendar-view mb-4" aria-label="Calendario rápido">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h6 class="fw-bold mb-0">
                <i class="fa-regular fa-calendar me-2"></i>Próximos días
            </h6>
            <small class="text-secondary">
                <i class="fa-regular fa-clock me-1"></i>
                <?php echo date('d M Y'); ?> - <?php echo date('d M Y', strtotime('+6 days')); ?>
            </small>
        </div>
        <div class="row g-2">
            <?php for ($i = 0; $i < 7; $i++): 
                $fecha = date('Y-m-d', strtotime("+$i days"));
                $diaNumero = date('d', strtotime($fecha));
                $diaNombre = date('D', strtotime($fecha));
                $esHoy = $i === 0;
            ?>
                <div class="col">
                    <div class="day-card <?php echo $esHoy ? 'active' : ''; ?>" 
                         onclick="window.location.href='?fecha=<?php echo $fecha; ?>'">
                        <div class="day-number"><?php echo $diaNumero; ?></div>
                        <div class="day-name"><?php echo $diaNombre; ?></div>
                        <?php if ($esHoy): ?>
                            <small class="d-block" style="font-size:0.6rem; opacity:0.7;">Hoy</small>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endfor; ?>
        </div>
    </section>

    <!-- =====================================================
         FILTROS
    ====================================================== -->
    <section class="filters-section" aria-label="Filtros de eventos">
        <form method="GET" action="" class="row g-3 align-items-end">
            <div class="col-12 col-md-4">
                <label for="buscar" class="form-label">
                    <i class="fa-solid fa-search me-1"></i> Buscar
                </label>
                <input 
                    type="text" 
                    name="buscar" 
                    id="buscar"
                    class="form-control" 
                    placeholder="Nombre, descripción o lugar..." 
                    value="<?php echo htmlspecialchars($filtroBusqueda); ?>"
                >
            </div>
            
            <div class="col-12 col-md-3">
                <label for="estado" class="form-label">
                    <i class="fa-solid fa-circle me-1"></i> Estado
                </label>
                <select name="estado" id="estado" class="form-select">
                    <option value="">Todos los estados</option>
                    <?php foreach ($estadosEventos as $estado): ?>
                        <option value="<?php echo $estado; ?>" 
                            <?php echo ($filtroEstado === $estado) ? 'selected' : ''; ?>>
                            <?php echo $estado; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="col-12 col-md-3">
                <label for="fecha" class="form-label">
                    <i class="fa-regular fa-calendar me-1"></i> Fecha
                </label>
                <input 
                    type="date" 
                    name="fecha" 
                    id="fecha"
                    class="form-control" 
                    value="<?php echo htmlspecialchars($filtroFecha); ?>"
                >
            </div>
            
            <div class="col-12 col-md-2">
                <button type="submit" class="btn-primary-custom w-100">
                    <i class="fa-solid fa-sliders me-2"></i> Filtrar
                </button>
            </div>
        </form>
        
        <?php if (!empty($filtroBusqueda) || !empty($filtroEstado) || !empty($filtroFecha)): ?>
            <div class="mt-3 pt-3 border-top">
                <a href="eventos.php" class="text-decoration-none small">
                    <i class="fa-solid fa-times-circle me-1"></i> Limpiar filtros
                </a>
            </div>
        <?php endif; ?>
    </section>

    <!-- =====================================================
         RESULTADOS
    ====================================================== -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <span class="fw-semibold"><?php echo $totalRegistros; ?></span>
            <span class="text-secondary">eventos encontrados</span>
        </div>
        <?php if ($totalPaginas > 1): ?>
            <small class="text-secondary">
                Página <?php echo $paginaActual; ?> de <?php echo $totalPaginas; ?>
            </small>
        <?php endif; ?>
    </div>

    <!-- =====================================================
         TARJETAS DE EVENTOS
    ====================================================== -->
    <div class="row g-4">
        <?php if (mysqli_num_rows($resultado) > 0): ?>
            <?php while ($evento = mysqli_fetch_assoc($resultado)): ?>
                <?php
                // Clases para el estado
                $estadoClase = strtolower(str_replace(' ', '-', $evento['estado']));
                $estadoIcono = [
                    'Próximo' => 'fa-clock',
                    'En curso' => 'fa-play-circle',
                    'Finalizado' => 'fa-check-circle',
                    'Cancelado' => 'fa-circle-xmark'
                ][$evento['estado']] ?? 'fa-circle';
                
                // Formatear fecha y hora
                $fechaEvento = date('d M Y', strtotime($evento['fecha_evento']));
                $horaEvento = date('H:i', strtotime($evento['hora_evento']));
                
                // Descripción corta
                $descripcion = $evento['descripcion'];
                if (strlen($descripcion) > 120) {
                    $descripcion = substr($descripcion, 0, 120) . "...";
                }
                
                // Días hasta el evento
                $diasPara = '';
                if ($evento['estado'] === 'Próximo') {
                    $fechaEventoObj = new DateTime($evento['fecha_evento']);
                    $hoy = new DateTime();
                    $diff = $hoy->diff($fechaEventoObj);
                    $dias = $diff->days;
                    if ($dias == 0) {
                        $diasPara = '<span class="text-warning fw-bold">¡Hoy!</span>';
                    } elseif ($dias == 1) {
                        $diasPara = 'Mañana';
                    } else {
                        $diasPara = "En $dias días";
                    }
                }
                ?>
                
                <div class="col-12 col-md-6 col-xl-4">
                    <article class="event-card">
                        <!-- Indicador de estado -->
                        <div class="status-indicator <?php echo $estadoClase; ?>"></div>
                        
                        <div class="card-body d-flex flex-column">
                            
                            <!-- Cabecera -->
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <span class="status-badge <?php echo $estadoClase; ?>">
                                    <i class="fa-regular <?php echo $estadoIcono; ?>"></i>
                                    <?php echo htmlspecialchars($evento['estado']); ?>
                                </span>
                                
                                <?php if (!empty($diasPara)): ?>
                                    <span class="badge bg-primary bg-opacity-10 text-primary border-0">
                                        <i class="fa-regular fa-hourglass-half me-1"></i>
                                        <?php echo $diasPara; ?>
                                    </span>
                                <?php endif; ?>
                            </div>
                            
                            <!-- Título -->
                            <h4 class="fw-bold mb-2">
                                <?php echo htmlspecialchars($evento['nombre_evento']); ?>
                            </h4>
                            
                            <!-- Descripción -->
                            <p class="text-secondary small mb-3">
                                <?php echo htmlspecialchars($descripcion); ?>
                            </p>
                            
                            <!-- Información -->
                            <div class="bg-light rounded-3 p-3 mb-3">
                                
                                <div class="event-info">
                                    <div class="icon-wrapper">
                                        <i class="fa-regular fa-calendar"></i>
                                    </div>
                                    <div>
                                        <div class="label">Fecha</div>
                                        <div class="value"><?php echo $fechaEvento; ?></div>
                                    </div>
                                </div>
                                
                                <div class="event-info">
                                    <div class="icon-wrapper">
                                        <i class="fa-regular fa-clock"></i>
                                    </div>
                                    <div>
                                        <div class="label">Hora</div>
                                        <div class="value"><?php echo $horaEvento; ?></div>
                                    </div>
                                </div>
                                
                                <div class="event-info mb-0">
                                    <div class="icon-wrapper">
                                        <i class="fa-solid fa-location-dot"></i>
                                    </div>
                                    <div>
                                        <div class="label">Lugar</div>
                                        <div class="value">
                                            <span class="event-location">
                                                <i class="fa-solid fa-map-pin text-primary"></i>
                                                <?php echo htmlspecialchars($evento['lugar']); ?>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                
                            </div>
                            
                            <!-- Metadatos adicionales -->
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <small class="text-secondary">
                                    <i class="fa-regular fa-calendar-plus me-1"></i>
                                    Creado: <?php echo date('d M Y', strtotime($evento['fecha_creacion'])); ?>
                                </small>
                                <?php if ($evento['fecha_actualizacion']): ?>
                                    <small class="text-secondary">
                                        <i class="fa-regular fa-pen-to-square me-1"></i>
                                        Actualizado
                                    </small>
                                <?php endif; ?>
                            </div>
                            
                            <!-- Botón -->
                            <a href="detalle_evento.php?id=<?php echo $evento['id_evento']; ?>" 
                               class="btn-detail-event mt-auto">
                                Ver evento
                                <i class="fa-solid fa-arrow-right"></i>
                            </a>
                            
                        </div>
                    </article>
                </div>
                
            <?php endwhile; ?>
            
        <?php else: ?>
            
            <!-- =====================================================
                 SIN EVENTOS
            ====================================================== -->
            <div class="col-12">
                <div class="empty-state">
                    <div class="empty-icon">
                        <i class="fa-regular fa-calendar-xmark"></i>
                    </div>
                    <h4 class="fw-bold mb-2">No hay eventos disponibles</h4>
                    <p class="text-secondary mb-0">
                        <?php if (!empty($filtroBusqueda) || !empty($filtroEstado) || !empty($filtroFecha)): ?>
                            No encontramos eventos que coincidan con tu búsqueda.
                            <br>
                            <a href="#" class="btn btn-link text-primary">
                                <i class="fa-solid fa-arrow-left me-1"></i> Ver todos los eventos
                            </a>
                        <?php else: ?>
                            Pronto tendremos nuevos eventos para ti.
                            <br>
                            <span class="text-secondary">¡No olvides visitarnos nuevamente!</span>
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
                        <a class="page-link" href="?pagina=<?php echo $paginaActual - 1; ?>&buscar=<?php echo urlencode($filtroBusqueda); ?>&estado=<?php echo urlencode($filtroEstado); ?>&fecha=<?php echo urlencode($filtroFecha); ?>">
                            <i class="fa-solid fa-chevron-left"></i>
                        </a>
                    </li>
                <?php endif; ?>
                
                <?php
                $rango = 2;
                $inicio = max(1, $paginaActual - $rango);
                $fin = min($totalPaginas, $paginaActual + $rango);
                
                if ($inicio > 1) {
                    echo '<li class="page-item"><a class="page-link" href="?pagina=1&buscar=' . urlencode($filtroBusqueda) . '&estado=' . urlencode($filtroEstado) . '&fecha=' . urlencode($filtroFecha) . '">1</a></li>';
                    if ($inicio > 2) {
                        echo '<li class="page-item disabled"><span class="page-link">…</span></li>';
                    }
                }
                
                for ($i = $inicio; $i <= $fin; $i++):
                ?>
                    <li class="page-item <?php echo $i === $paginaActual ? 'active' : ''; ?>">
                        <a class="page-link" href="?pagina=<?php echo $i; ?>&buscar=<?php echo urlencode($filtroBusqueda); ?>&estado=<?php echo urlencode($filtroEstado); ?>&fecha=<?php echo urlencode($filtroFecha); ?>">
                            <?php echo $i; ?>
                        </a>
                    </li>
                <?php endfor; ?>
                
                <?php if ($fin < $totalPaginas): ?>
                    <?php if ($fin < $totalPaginas - 1): ?>
                        <li class="page-item disabled"><span class="page-link">…</span></li>
                    <?php endif; ?>
                    <li class="page-item">
                        <a class="page-link" href="?pagina=<?php echo $totalPaginas; ?>&buscar=<?php echo urlencode($filtroBusqueda); ?>&estado=<?php echo urlencode($filtroEstado); ?>&fecha=<?php echo urlencode($filtroFecha); ?>">
                            <?php echo $totalPaginas; ?>
                        </a>
                    </li>
                <?php endif; ?>
                
                <?php if ($paginaActual < $totalPaginas): ?>
                    <li class="page-item">
                        <a class="page-link" href="?pagina=<?php echo $paginaActual + 1; ?>&buscar=<?php echo urlencode($filtroBusqueda); ?>&estado=<?php echo urlencode($filtroEstado); ?>&fecha=<?php echo urlencode($filtroFecha); ?>">
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