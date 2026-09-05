 <?php
session_start();

if (
  !isset($_SESSION['usuario']) ||
  !in_array($_SESSION['tipo_persona'], ['Administrativo', 'Encargado'])
) {
  header("Location: IniciarSesion.php");
  exit();
}
?>
<!-- CONEXION CON LA BASE DE DATOS EL DASCHBOARD -->
<?php
require_once appPath("servidor/conexionBD.php");

// Total de personas
$sql = "SELECT COUNT(*) AS total FROM personas";
$resultado = $conexion->query($sql);
$totalPersonas = $resultado->fetch_assoc()['total'];

// Total de usuarios
$sql = "SELECT COUNT(*) AS total FROM usuarios_sistema";
$resultado = $conexion->query($sql);
$totalUsuarios = $resultado->fetch_assoc()['total'];

// Total de universidades
$sql = "SELECT COUNT(*) AS total FROM universidades";
$resultado = $conexion->query($sql);
$totalUniversidades = $resultado->fetch_assoc()['total'];

// Total de eventos
$sql = "SELECT COUNT(*) AS total FROM eventos";
$resultado = $conexion->query($sql);
$totalEventos = $resultado->fetch_assoc()['total'];

// Total de actividades
$sql = "SELECT COUNT(*) AS total FROM actividades";
$resultado = $conexion->query($sql);
$totalActividades = $resultado->fetch_assoc()['total'];

// Total de inscripciones
$sql = "SELECT COUNT(*) AS total FROM inscripcion";
$resultado = $conexion->query($sql);
$totalInscripciones = $resultado->fetch_assoc()['total'];

// Total de pagos
$sql = "SELECT COUNT(*) AS total FROM pagos";
$resultado = $conexion->query($sql);
$totalPagos = $resultado->fetch_assoc()['total'];

// Total de asistencias
$sql = "SELECT COUNT(*) AS total FROM asistencias";
$resultado = $conexion->query($sql);
$totalAsistencias = $resultado->fetch_assoc()['total'];

// PERSONAS POR TIPO
$sql = "SELECT tipo_persona, COUNT(*) AS total
        FROM personas
        GROUP BY tipo_persona";

$resultado = $conexion->query($sql);

$tiposPersona = [];
$totalesPersona = [];

while ($fila = $resultado->fetch_assoc()) {
  $tiposPersona[] = $fila['tipo_persona'];
  $totalesPersona[] = $fila['total'];
}

// EVENTOS POR MES
$sql = "SELECT
            MONTH(fecha_evento) AS mes,
            COUNT(*) AS total
        FROM eventos
        WHERE fecha_evento IS NOT NULL
        GROUP BY MONTH(fecha_evento)
        ORDER BY MONTH(fecha_evento)";

$resultado = $conexion->query($sql);

// Meses del año
$meses = [
  "Enero",
  "Febrero",
  "Marzo",
  "Abril",
  "Mayo",
  "Junio",
  "Julio",
  "Agosto",
  "Septiembre",
  "Octubre",
  "Noviembre",
  "Diciembre"
];

// Inicializar todos los meses con 0
$datosEventos = array_fill(0, 12, 0);

while ($fila = $resultado->fetch_assoc()) {
  $datosEventos[$fila['mes'] - 1] = $fila['total'];
}

// INSCRIPCIONES POR ACTIVIDAD
$sql = "SELECT
            a.nombre_actividad,
            COUNT(i.id_inscripcion) AS total
        FROM actividades a
        LEFT JOIN inscripcion i
            ON a.id_actividad = i.id_actividad
        GROUP BY a.id_actividad
        ORDER BY total DESC";

$resultado = $conexion->query($sql);

$actividades = [];
$totalInscripcionesActividad = [];

while ($fila = $resultado->fetch_assoc()) {

  $actividades[] = $fila['nombre_actividad'];
  $totalInscripcionesActividad[] = $fila['total'];
}

// ÚLTIMAS PERSONAS REGISTRADAS
$sql = "SELECT
            p.ci,
            CONCAT(p.nombres,' ',p.apellidos) AS nombre,
            u.nombre AS universidad,
            p.estado
        FROM personas p
        LEFT JOIN universidades u
            ON p.id_universidad = u.id_universidad
        ORDER BY p.id_persona DESC
        LIMIT 5";

$ultimasPersonas = $conexion->query($sql);
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Panel de Administración - Todo Incluido</title>

  <!-- Bootstrap, FontAwesome, ChartJS, Export libs -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="../css/Dashboard.css">

  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.28/jspdf.plugin.autotable.min.js"></script>
  <script src="../js/Dashboard.js"></script>

  <style>
    /* ============================================
       ORGANIZACIÓN DE COLORES DEL MENÚ LATERAL
       ============================================ */

    /* === ESTILOS GENERALES DEL SIDEBAR === */
    #sidebar {
      background: linear-gradient(180deg, #1a2332 0%, #243447 100%);
      box-shadow: 2px 0 15px rgba(0, 0, 0, 0.3);
      transition: all 0.3s ease;
      width: 280px;
      min-height: 100vh;
      position: fixed;
      top: 0;
      left: 0;
      z-index: 1050;
      overflow-y: auto;
      padding-bottom: 20px;
    }

    #sidebar.hidden {
      transform: translateX(-100%);
    }

    #sidebar .sidebar-header {
      padding: 1.2rem 1rem;
      border-bottom: 1px solid rgba(255, 255, 255, 0.08);
      background: rgba(0, 0, 0, 0.15);
    }

    /* === ÍCONOS DE SECCIÓN PRINCIPAL === */
    #sidebar .components {
      padding: 0;
      margin: 0;
    }

    #sidebar .components>li {
      list-style: none;
    }

    #sidebar .components>li>a,
    #sidebar .components>li .submenu-toggle {
      padding: 0.7rem 1.2rem;
      margin: 2px 0;
      border-radius: 0;
      color: rgba(255, 255, 255, 0.75);
      font-weight: 500;
      font-size: 0.9rem;
      transition: all 0.2s ease;
      border-left: 3px solid transparent;
      cursor: pointer;
      display: flex;
      align-items: center;
      text-decoration: none;
    }

    #sidebar .components>li>a:hover,
    #sidebar .components>li .submenu-toggle:hover {
      background: rgba(255, 255, 255, 0.08);
      color: #ffffff;
      border-left-color: #4e9eff;
    }

    #sidebar .components>li>a i,
    #sidebar .components>li .submenu-toggle i {
      width: 24px;
      text-align: center;
      margin-right: 10px;
      font-size: 1rem;
      flex-shrink: 0;
    }

    /* === COLORES POR SECCIÓN (ÍCONOS) === */

    /* Dashboard - Azul */
    #sidebar .components>li:first-child>a i {
      color: #4e9eff;
    }

    /* Estadísticas - Verde */
    #sidebar .components>li:nth-child(2)>a i {
      color: #34d399;
    }

    /* Calendario - Naranja */
    #sidebar .components>li:nth-child(3)>a i {
      color: #f59e0b;
    }

    /* Panel Actividades - Morado */
    #sidebar .components>li:nth-child(4)>a i {
      color: #a78bfa;
    }

    /* Tablas - Cian */
    #sidebar .components>li:nth-child(5) .submenu-toggle i:first-child {
      color: #22d3ee;
    }

    /* Eventos y Actividades - Rosa */
    #sidebar .components>li:nth-child(6) .submenu-toggle i:first-child {
      color: #f472b6;
    }

    /* Reportes - Rojo */
    #sidebar .components>li:nth-child(7) .submenu-toggle i:first-child {
      color: #f87171;
    }

    /* Formularios - Amarillo */
    #sidebar .components>li:nth-child(8) .submenu-toggle i:first-child {
      color: #fbbf24;
    }

    /* Mis Eventos - Verde */
    #sidebar .components>li[data-section="mis-eventos"]>a i {
      color: #34d399;
    }

    /* Participantes - Azul */
    #sidebar .components>li[data-section="participantes"]>a i {
      color: #60a5fa;
    }

    /* Reportes - Rojo */
    #sidebar .components>li[data-section="reportes"]>a i {
      color: #f87171;
    }

    /* Ayuda - Gris */
    #sidebar .components>li[data-section="ayuda"]>a i {
      color: #9ca3af;
    }

    /* === SUBMENÚS === */
    #sidebar .submenu {
      background: rgba(0, 0, 0, 0.2);
      border-left: 2px solid rgba(78, 158, 255, 0.2);
      margin: 0 0 0 8px;
      padding: 4px 0;
      max-height: 0;
      overflow: hidden;
      transition: max-height 0.3s ease-out, opacity 0.3s ease;
      opacity: 0;
      list-style: none;
    }

    #sidebar .submenu.show {
      max-height: 600px;
      opacity: 1;
      transition: max-height 0.4s ease-in, opacity 0.3s ease;
    }

    #sidebar .submenu li a {
      padding: 0.5rem 1rem 0.5rem 2.8rem;
      color: rgba(255, 255, 255, 0.65);
      font-size: 0.85rem;
      transition: all 0.2s ease;
      border-left: 2px solid transparent;
      display: flex;
      align-items: center;
      text-decoration: none;
    }

    #sidebar .submenu li a:hover {
      background: rgba(255, 255, 255, 0.06);
      color: #ffffff;
      border-left-color: #4e9eff;
    }

    #sidebar .submenu li a i {
      width: 20px;
      text-align: center;
      margin-right: 8px;
      font-size: 0.85rem;
      color: rgba(255, 255, 255, 0.5);
    }

    #sidebar .submenu li a:hover i {
      color: #4e9eff;
    }

    /* === ESTADOS ACTIVOS === */
    #sidebar .components>li.active>a,
    #sidebar .components>li.active .submenu-toggle {
      background: rgba(78, 158, 255, 0.15);
      color: #ffffff;
      border-left-color: #4e9eff;
    }

    #sidebar .components>li.active>a i,
    #sidebar .components>li.active .submenu-toggle i {
      color: #4e9eff !important;
    }

    /* === ICONO DE ROTACIÓN EN SUBMENÚ === */
    #sidebar .rotate-icon {
      transition: transform 0.3s ease;
      margin-left: auto;
      font-size: 0.7rem;
      color: rgba(255, 255, 255, 0.4);
    }

    #sidebar .has-submenu.open .rotate-icon {
      transform: rotate(90deg);
    }

    .submenu-toggle {
      display: flex;
      align-items: center;
      width: 100%;
      background: none;
      border: none;
      color: rgba(255, 255, 255, 0.75);
      padding: 0.7rem 1.2rem;
      cursor: pointer;
    }

    .submenu-toggle span {
      display: flex;
      align-items: center;
      flex: 1;
    }

    /* === PERFIL DE USUARIO EN EL PIE === */
    #sidebar .p-3 {
      background: rgba(0, 0, 0, 0.2);
      border-top: 1px solid rgba(255, 255, 255, 0.06);
      margin-top: auto;
    }

    #sidebar .p-3 h6 {
      color: #ffffff;
      font-weight: 600;
      margin-bottom: 0;
    }

    #sidebar .p-3 .small-muted {
      color: rgba(255, 255, 255, 0.5);
      font-size: 0.8rem;
    }

    /* === SCROLLBAR DEL SIDEBAR === */
    #sidebar::-webkit-scrollbar {
      width: 4px;
    }

    #sidebar::-webkit-scrollbar-track {
      background: rgba(255, 255, 255, 0.05);
    }

    #sidebar::-webkit-scrollbar-thumb {
      background: rgba(78, 158, 255, 0.4);
      border-radius: 4px;
    }

    #sidebar::-webkit-scrollbar-thumb:hover {
      background: rgba(78, 158, 255, 0.6);
    }

    /* === OVERLAY PARA MÓVIL === */
    #overlaySidebar {
      display: none;
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.5);
      z-index: 1040;
    }

    #overlaySidebar.show {
      display: block;
    }

    /* === CONTENIDO PRINCIPAL === */
    #content {
      margin-left: 280px;
      transition: margin-left 0.3s ease;
      min-height: 100vh;
    }

    #content.fullwidth {
      margin-left: 0;
    }

    .sidebar-toggle-desktop {
      background: transparent;
      border: none;
      font-size: 1.5rem;
      color: #333;
      cursor: pointer;
      transition: transform 0.3s ease;
    }

    .sidebar-toggle-desktop.rotated {
      transform: rotate(90deg);
    }

    .close-sidebar {
      background: transparent;
      border: none;
      color: #fff;
      font-size: 1.5rem;
      cursor: pointer;
    }

    /* === RESPONSIVE === */
    @media (max-width: 991.98px) {
      #sidebar {
        transform: translateX(-100%);
        width: 300px;
        transition: transform 0.3s ease;
      }

      #sidebar.visible {
        transform: translateX(0);
      }

      #content {
        margin-left: 0;
      }

      #sidebar .components>li>a,
      #sidebar .components>li .submenu-toggle {
        padding: 0.6rem 1rem;
        font-size: 0.85rem;
      }

      #sidebar .submenu li a {
        padding: 0.4rem 1rem 0.4rem 2.5rem;
        font-size: 0.8rem;
      }
    }

    /* === TEMA OSCURO AJUSTES === */
    body.dark #sidebar {
      background: linear-gradient(180deg, #0d1117 0%, #161b22 100%);
    }

    body.dark #sidebar .components>li>a,
    body.dark #sidebar .components>li .submenu-toggle {
      color: rgba(255, 255, 255, 0.7);
    }

    body.dark #sidebar .components>li>a:hover,
    body.dark #sidebar .components>li .submenu-toggle:hover {
      background: rgba(255, 255, 255, 0.05);
      color: #ffffff;
    }

    body.dark #sidebar .submenu {
      background: rgba(0, 0, 0, 0.3);
      border-left-color: rgba(78, 158, 255, 0.15);
    }

    body.dark #sidebar .submenu li a {
      color: rgba(255, 255, 255, 0.55);
    }

    body.dark #sidebar .submenu li a:hover {
      background: rgba(255, 255, 255, 0.04);
      color: #ffffff;
    }

    body.dark #sidebar .components>li.active>a,
    body.dark #sidebar .components>li.active .submenu-toggle {
      background: rgba(78, 158, 255, 0.1);
    }

    body.dark #sidebar .p-3 {
      background: rgba(0, 0, 0, 0.3);
    }

    body.dark .sidebar-toggle-desktop {
      color: #e0e0e0;
    }

    /* ============================================
       MEJORA VISUAL DE ÍCONOS DEL SIDEBAR
       ============================================ */

    /* Tamaño y estilo consistente de íconos */
    #sidebar .components>li>a i.fa-fw,
    #sidebar .components>li .submenu-toggle i.fa-fw {
      width: 1.5rem;
      text-align: center;
      font-size: 1.05rem;
      transition: all 0.2s ease;
    }

    /* Efecto hover en íconos */
    #sidebar .components>li>a:hover i,
    #sidebar .components>li .submenu-toggle:hover i {
      transform: scale(1.1);
    }

    /* Badges de notificaciones en el sidebar */
    #sidebar .badge-notification {
      background: #ef4444;
      color: white;
      font-size: 0.65rem;
      padding: 0.15rem 0.5rem;
      border-radius: 20px;
      margin-left: 8px;
    }

    /* Separador sutil entre secciones */
    #sidebar .components>li:not(:last-child) {
      border-bottom: 1px solid rgba(255, 255, 255, 0.03);
    }

    /* ============================================
       ESTILOS ADICIONALES PARA EL DASHBOARD
       ============================================ */

    .stats-card {
      padding: 1.2rem 1.5rem;
      border-radius: 12px;
      color: #fff;
      transition: transform 0.2s ease, box-shadow 0.2s ease;
      border: none;
    }

    .stats-card:hover {
      transform: translateY(-4px);
      box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    }

    .stats-number {
      font-size: 2rem;
      font-weight: 700;
      line-height: 1.2;
    }

    .bg-custom-primary {
      background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
    }

    .bg-custom-success {
      background: linear-gradient(135deg, #1cc88a 0%, #13855c 100%);
    }

    .bg-custom-warning {
      background: linear-gradient(135deg, #f6c23e 0%, #dda20a 100%);
    }

    .bg-custom-info {
      background: linear-gradient(135deg, #36b9cc 0%, #258391 100%);
    }

    .bg-custom-purple {
      background: linear-gradient(135deg, #858796 0%, #5a5c69 100%);
    }

    .badge-status {
      padding: 0.3rem 0.8rem;
      border-radius: 20px;
      font-size: 0.75rem;
      font-weight: 600;
      text-transform: capitalize;
    }

    .badge-status.active {
      background: #d4edda;
      color: #155724;
    }

    .badge-status.upcoming {
      background: #cce5ff;
      color: #004085;
    }

    .badge-status.completed {
      background: #e2e3e5;
      color: #383d41;
    }

    .badge-status.confirmado {
      background: #d4edda;
      color: #155724;
    }

    .badge-status.pendiente {
      background: #fff3cd;
      color: #856404;
    }

    .badge-status.asistio {
      background: #d1ecf1;
      color: #0c5460;
    }

    .badge-status.cancelado {
      background: #f8d7da;
      color: #721c24;
    }

    .content-section {
      display: none;
      padding: 20px 0;
    }

    .content-section.active {
      display: block;
    }

    .section-title {
      font-weight: 700;
      color: #2d3748;
      margin-bottom: 0.25rem;
    }

    .small-muted {
      color: #6c757d;
      font-size: 0.9rem;
    }

    .filters-container {
      background: #f8f9fa;
      padding: 1rem;
      border-radius: 10px;
      margin-bottom: 1.5rem;
    }

    .quick-stats {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 1rem;
      margin-bottom: 1.5rem;
    }

    .quick-stat-item {
      background: #fff;
      padding: 1rem;
      border-radius: 10px;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
      text-align: center;
      border: 1px solid #e9ecef;
    }

    .quick-stat-item .stat-icon {
      font-size: 1.8rem;
      color: #4e73df;
      margin-bottom: 0.5rem;
    }

    .quick-stat-item .stat-value {
      font-size: 1.8rem;
      font-weight: 700;
      color: #2d3748;
    }

    .quick-stat-item .stat-label {
      font-size: 0.85rem;
      color: #6c757d;
    }

    .help-category {
      background: #f8f9fa;
      padding: 1.5rem;
      border-radius: 10px;
      margin-bottom: 1rem;
    }

    .help-item {
      padding: 0.8rem;
      border-bottom: 1px solid #e9ecef;
      cursor: pointer;
      transition: background 0.2s ease;
    }

    .help-item:last-child {
      border-bottom: none;
    }

    .help-item:hover {
      background: #e9ecef;
      border-radius: 6px;
    }

    .progress {
      background-color: #e9ecef;
      border-radius: 10px;
      overflow: hidden;
    }

    .progress-bar {
      border-radius: 10px;
      transition: width 0.6s ease;
    }

    .btn-action {
      padding: 0.2rem 0.5rem;
      font-size: 0.8rem;
    }

    .search-input {
      border-radius: 20px;
      padding: 0.375rem 1rem;
      border: 1px solid #d1d5db;
      min-width: 200px;
    }

    .search-input:focus {
      border-color: #4e73df;
      box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
    }
  </style>
</head>

<body>

  <!-- Overlay (mobile) -->
  <div id="overlaySidebar" tabindex="-1" aria-hidden="true"></div>

  <!-- SIDEBAR -->
  <nav id="sidebar" aria-label="Sidebar">
    <div class="sidebar-header">
      <div class="d-flex justify-content-between align-items-center">
        <div>
          <button id="themeToggle" class="btn btn-sm btn-outline-light me-2" title="Cambiar tema"><i class="fas fa-sun"></i></button>
        </div>
        <button class="close-sidebar d-lg-none" id="closeSidebar" aria-label="Cerrar menú"><i class="fas fa-times"></i></button>
      </div>
    </div>

    <ul class="list-unstyled components">
      <!-- Dashboard -->
     
      <li class="has-submenu">
        <div class="submenu-toggle" aria-expanded="false">
          <span><i class="fas fa-home"></i> Dashboard</span>
          <i class="fas fa-chevron-right rotate-icon"></i>
        </div>
        <ul class="submenu list-unstyled">
          <li>
    <a href="../cliente/Panel_Eventos.php">
        <i class="fas fa-calendar-alt me-2"></i> Panel de Eventos
    </a>
</li>

<li>
    <a href="../cliente/Panel_actividades.php">
        <i class="fas fa-tasks me-2"></i> Panel de Actividades
    </a>
</li>
        </ul>
      </li>

    

      <!-- Gestión - Tablas -->
      <li class="has-submenu">
        <div class="submenu-toggle" aria-expanded="false">
          <span><i class="fas fa-table me-2"></i> Tablas</span>
          <i class="fas fa-chevron-right rotate-icon"></i>
        </div>
        <ul class="submenu list-unstyled">
          <li><a href="../cliente/usuarios.php"><i class="fas fa-users me-2"></i> Usuarios</a></li>
          <li><a href="/personas"><i class="fas fa-id-card me-2"></i> Personas</a></li>
          <li><a href="#"><i class="fas fa-user-friends me-2"></i> Participantes</a></li>
        </ul>
      </li>

      <!-- Eventos y Actividades -->
      <li class="has-submenu">
        <div class="submenu-toggle" aria-expanded="false">
          <span><i class="fas fa-calendar-days me-2"></i> Eventos y Actividades</span>
          <i class="fas fa-chevron-right rotate-icon"></i>
        </div>
        <ul class="submenu list-unstyled">
          <li><a href="#"><i class="fas fa-calendar-check me-2"></i> Eventos</a></li>
          <li><a href="#"><i class="fas fa-tasks me-2"></i> Actividades</a></li>
          <li><a href="#"><i class="fas fa-church me-2"></i> Formación Sacramental</a></li>
          <li><a href="#"><i class="fas fa-user-plus me-2"></i> Inscripciones</a></li>
          <li><a href="#"><i class="fas fa-clipboard-check me-2"></i> Asistencias</a></li>
          <li><a href="#"><i class="fas fa-money-check-alt me-2"></i> Pagos</a></li>
        </ul>
      </li>

      <!-- Reportes -->
      <li class="has-submenu">
        <div class="submenu-toggle" aria-expanded="false">
          <span><i class="fas fa-chart-pie me-2"></i> Reportes</span>
          <i class="fas fa-chevron-right rotate-icon"></i>
        </div>
        <ul class="submenu list-unstyled">
          <li><a href="#"><i class="fas fa-calendar-days me-2"></i> Reporte de Eventos</a></li>
          <li><a href="#"><i class="fas fa-clipboard-list me-2"></i> Reporte de Actividades</a></li>
          <li><a href="#"><i class="fas fa-users-viewfinder me-2"></i> Reporte de Participantes</a></li>
          <li><a href="#"><i class="fas fa-place-of-worship me-2"></i> Reporte de Formación Sacramental</a></li>
          <li><a href="#"><i class="fas fa-square-check me-2"></i> Reporte de Asistencias</a></li>
          <li><a href="#"><i class="fas fa-file-invoice-dollar me-2"></i> Reporte de Pagos</a></li>
        </ul>
      </li>

      <!-- Formularios -->
      <li class="has-submenu">
        <div class="submenu-toggle" aria-expanded="false">
          <span><i class="fas fa-file-alt me-2"></i> Formularios</span>
          <i class="fas fa-chevron-right rotate-icon"></i>
        </div>
        <ul class="submenu list-unstyled">
          <li><a href="../cliente/actividades.php"><i class="fas fa-hands-helping me-2"></i> Actividades</a></li>
          <li><a href="../cliente/asistencias.php"><i class="fas fa-user-check me-2"></i> Asistencias</a></li>
          <li><a href="../cliente/eventos.php"><i class="fas fa-calendar-alt me-2"></i> Eventos</a></li>
          <li><a href="../cliente/inscripcion.php"><i class="fas fa-clipboard-list me-2"></i> Inscripción</a></li>
          <li><a href="../cliente/pagos.php"><i class="fas fa-credit-card me-2"></i> Pagos</a></li>
          <li><a href="../cliente/personas.php"><i class="fas fa-user-friends me-2"></i> Personas</a></li>
          <li><a href="../cliente/universidades.php"><i class="fas fa-university me-2"></i> Universidades</a></li>
          <li><a href="../cliente/usuarios_sistema.php"><i class="fas fa-user-cog me-2"></i> Usuario</a></li>
          <li><a href="../cliente/FormacionSacramental.php"><i class="fas fa-book-reader me-2"></i> Formación Sacramental</a></li>
        </ul>
      </li>

      <!-- Mis Eventos -->
      <li>
        <a href="../cliente/MisEventos.php"><i class="fas fa-calendar-check"></i> Mis Eventos</a>
      </li>

      <!-- Participantes -->
      <li data-section="participantes">
        <a href="#"><i class="fas fa-users"></i> Participantes</a>
      </li>

      <!-- Reportes -->
      <li data-section="reportes">
        <a href="#"><i class="fas fa-chart-bar"></i> Reportes</a>
      </li>

      <!-- Ayuda -->
      <li data-section="ayuda">
        <a href="<?= url('/ayuda') ?>"><i class="fas fa-question-circle"></i> Ayuda</a>
      </li>
    </ul>

    <div class="p-3 text-white mt-auto">
      <div class="d-flex align-items-center">
        <img src="https://ui-avatars.com/api/?name=Admin+User&background=0D8ABC&color=fff" class="rounded-circle" width="40" height="40" alt="Usuario">
        <div class="ms-3">
          <h6 class="mb-0">Administrador</h6>
          <small class="small-muted">Favio@gmail.com</small>
        </div>
      </div>
    </div>
  </nav>
 <!-- Sección MIS EVENTOS -->
    <div id="mis-eventos" class="content-section">
      <div class="container-fluid py-3">
        <div class="row align-items-center mb-4">
          <div class="col-md-8">
            <h2 class="section-title">Mis Eventos</h2>
            <p class="small-muted">Gestiona todos tus eventos activos y pasados</p>
          </div>
          <div class="col-md-4 text-end">
            <button class="btn btn-primary" onclick="mostrarFormularioAgregar()"><i class="fas fa-plus me-1"></i> Crear Nuevo Evento</button>
          </div>
        </div>

        <!-- Filtros -->
        <div class="filters-container">
          <div class="row g-3">
            <div class="col-md-4">
              <label class="form-label">Buscar evento</label>
              <input type="text" class="form-control" id="searchMisEventos" placeholder="Nombre del evento...">
            </div>
            <div class="col-md-4">
              <label class="form-label">Filtrar por estado</label>
              <select class="form-select" id="filterEstado">
                <option value="">Todos los eventos</option>
                <option value="activo">Activos</option>
                <option value="proximo">Próximos</option>
                <option value="finalizado">Finalizados</option>
              </select>
            </div>
            <div class="col-md-4">
              <label class="form-label">Ordenar por</label>
              <select class="form-select" id="sortEventos">
                <option value="fecha">Fecha (más reciente)</option>
                <option value="participacion">Participación</option>
                <option value="nombre">Nombre (A-Z)</option>
              </select>
            </div>
          </div>
        </div>

        <!-- Estadísticas rápidas -->
        <div class="quick-stats">
          <div class="quick-stat-item">
            <div class="stat-icon"><i class="fas fa-calendar-check"></i></div>
            <div class="stat-value" id="total-eventos">0</div>
            <div class="stat-label">Total Eventos</div>
          </div>
          <div class="quick-stat-item">
            <div class="stat-icon"><i class="fas fa-calendar-day"></i></div>
            <div class="stat-value" id="eventos-activos">0</div>
            <div class="stat-label">Eventos Activos</div>
          </div>
          <div class="quick-stat-item">
            <div class="stat-icon"><i class="fas fa-calendar-times"></i></div>
            <div class="stat-value" id="eventos-finalizados">0</div>
            <div class="stat-label">Eventos Finalizados</div>
          </div>
          <div class="quick-stat-item">
            <div class="stat-icon"><i class="fas fa-chart-line"></i></div>
            <div class="stat-value" id="participacion-promedio">0%</div>
            <div class="stat-label">Participación Promedio</div>
          </div>
        </div>

        <!-- Tabla de eventos -->
        <div class="card">
          <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="m-0">Lista de Mis Eventos</h5>
            <div class="d-flex gap-2">
              <button class="btn btn-sm btn-outline-secondary" onclick="actualizarListaEventos()"><i class="fas fa-sync-alt"></i></button>
              <div class="dropdown">
                <button class="btn btn-sm btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown">Acciones</button>
                <ul class="dropdown-menu">
                  <li><a class="dropdown-item" href="#" onclick="exportarMisEventos('excel')"><i class="fas fa-file-excel me-2"></i>Exportar Excel</a></li>
                  <li><a class="dropdown-item" href="#" onclick="exportarMisEventos('pdf')"><i class="fas fa-file-pdf me-2"></i>Exportar PDF</a></li>
                  <li>
                    <hr class="dropdown-divider">
                  </li>
                  <li><a class="dropdown-item" href="#" onclick="enviarRecordatorioTodos()"><i class="fas fa-envelope me-2"></i>Enviar Recordatorios</a></li>
                </ul>
              </div>
            </div>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-hover">
                <thead>
                  <tr>
                    <th>Nombre del Evento</th>
                    <th>Fecha</th>
                    <th>Lugar</th>
                    <th>Participación</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                  </tr>
                </thead>
                <tbody id="tabla-mis-eventos">
                  <!-- Los eventos se cargarán aquí dinámicamente -->
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <!-- Detalles del evento seleccionado -->
        <div class="card mt-4" id="evento-detalle-card" style="display: none;">
          <div class="card-header">
            <h5 class="m-0">Detalles del Evento</h5>
          </div>
          <div class="card-body" id="evento-detalle-content">
            <!-- Los detalles se cargarán aquí dinámicamente -->
          </div>
        </div>
      </div>
    </div>
</body>
</html>