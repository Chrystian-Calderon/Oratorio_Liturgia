<?php
session_start();

if (
  !isset($_SESSION['usuario']) ||
  $_SESSION['tipo_persona'] != 'Administrativo'
) {
  header("Location: IniciarSesion.php");
  exit();
}
?>
<!-- CONEXION CON LA BASE DE DATOS EL DASCHBOARD -->
<?php
include("../servidor/conexionBD.php");

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
      <li data-section="dashboard" class="active">
        <a href="#"><i class="fas fa-home"></i> Dashboard</a>
      </li>

       

    

      <!-- Panel de Actividades -->
      <li data-section="panel-actividades">
        <a href="../cliente/Panel_actividades.php"><i class="fas fa-tachometer-alt"></i> Panel de Actividades</a>
      </li>

      <!-- Gestión - Tablas -->
      <li class="has-submenu">
        <div class="submenu-toggle" aria-expanded="false">
          <span><i class="fas fa-table me-2"></i> Tablas</span>
          <i class="fas fa-chevron-right rotate-icon"></i>
        </div>
        <ul class="submenu list-unstyled">
          <li><a href="../cliente/usuarios.php"><i class="fas fa-users me-2"></i> Usuarios</a></li>
          <li><a href="../cliente/personas1.php"><i class="fas fa-id-card me-2"></i> Personas</a></li>
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
      <li data-section="mis-eventos">
        <a href="#"><i class="fas fa-calendar-check"></i> Mis Eventos</a>
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
        <a href="#"><i class="fas fa-question-circle"></i> Ayuda</a>
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

  <!-- MAIN CONTENT -->
  <div id="content">
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
      <div class="container-fluid d-flex align-items-center">
        <button id="sidebarToggle" class="me-3 btn sidebar-toggle-desktop" aria-label="Abrir menú"><i class="fas fa-bars"></i></button>
        <a class="navbar-brand" href="#">
          <i class="fas fa-tachometer-alt me-2"></i>Panel Administrativo
        </a>

        <div class="ms-auto d-flex align-items-center gap-2">
          <input id="searchGlobal" class="form-control form-control-sm search-input" placeholder="Buscar eventos/usuarios..." title="Buscar en tablas">
          <div class="dropdown export-dropdown">
            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">Exportar</button>
            <ul class="dropdown-menu dropdown-menu-end">
              <li><a class="dropdown-item" href="#" id="expExcel"><i class="fas fa-file-excel"></i> Exportar Excel</a></li>
              <li><a class="dropdown-item" href="#" id="expPdf"><i class="fas fa-file-pdf"></i> Exportar PDF</a></li>
              <li>
                <hr class="dropdown-divider">
              </li>
              <li><a class="dropdown-item" href="#" id="expAll"><i class="fas fa-file-archive"></i> Exportar Todo</a></li>
            </ul>
          </div>
          <div class="vr d-none d-md-block"></div>
          <div class="dropdown">
            <a class="nav-link dropdown-toggle p-0" href="#" data-bs-toggle="dropdown"><i class="fas fa-user-circle fa-2x"></i></a>
            <ul class="dropdown-menu dropdown-menu-end">
              <li><a class="dropdown-item" href="#"><i class="fas fa-user me-2"></i> Perfil</a></li>
              <li><a class="dropdown-item" href="../cliente/login.php"><i class="fas fa-sign-out-alt me-2"></i> Cerrar Sesión</a></li>
            </ul>
          </div>
        </div>
      </div>
    </nav>

    <!-- Sección Dashboard (Actual) -->
    <div id="dashboard" class="content-section active">
      <div class="container-fluid py-3">
        <!-- RESUMEN ESTADÍSTICO Panel Administrativo -->
        <div class="row mb-4">
          <!-- Personas -->
          <div class="col-md-3 mb-3">
            <div class="card stats-card bg-custom-primary">
              <div class="d-flex justify-content-between align-items-center">
                <div>
                  <h6 class="mb-0">Personas</h6>
                  <div class="stats-number">
                    <?php echo $totalPersonas; ?>
                  </div>
                </div>
                <i class="fas fa-user-friends fa-2x opacity-75"></i>
              </div>
            </div>
          </div>

          <!-- Usuarios -->
          <div class="col-md-3 mb-3">
            <div class="card stats-card bg-custom-success">
              <div class="d-flex justify-content-between align-items-center">
                <div>
                  <h6 class="mb-0">Usuarios</h6>
                  <div class="stats-number">
                    <?php echo $totalUsuarios; ?>
                  </div>
                </div>
                <i class="fas fa-users fa-2x opacity-75"></i>
              </div>
            </div>
          </div>

          <!-- Universidades -->
          <div class="col-md-3 mb-3">
            <div class="card stats-card bg-custom-warning">
              <div class="d-flex justify-content-between align-items-center">
                <div>
                  <h6 class="mb-0">Universidades</h6>
                  <div class="stats-number">
                    <?php echo $totalUniversidades; ?>
                  </div>
                </div>
                <i class="fas fa-university fa-2x opacity-75"></i>
              </div>
            </div>
          </div>

          <!-- Eventos -->
          <div class="col-md-3 mb-3">
            <div class="card stats-card bg-custom-info">
              <div class="d-flex justify-content-between align-items-center">
                <div>
                  <h6 class="mb-0">Eventos</h6>
                  <div class="stats-number">
                    <?php echo $totalEventos; ?>
                  </div>
                </div>
                <i class="fas fa-calendar-alt fa-2x opacity-75"></i>
              </div>
            </div>
          </div>
        </div>

        <!-- SEGUNDA FILA DE INDICADORES -->
        <div class="row mb-4">
          <!-- Actividades -->
          <div class="col-md-3 mb-3">
            <div class="card stats-card bg-secondary">
              <div class="d-flex justify-content-between align-items-center">
                <div>
                  <h6 class="mb-0">Actividades</h6>
                  <div class="stats-number">
                    <?php echo $totalActividades; ?>
                  </div>
                </div>
                <i class="fas fa-book fa-2x opacity-75"></i>
              </div>
            </div>
          </div>

          <!-- Inscripciones -->
          <div class="col-md-3 mb-3">
            <div class="card stats-card bg-success">
              <div class="d-flex justify-content-between align-items-center">
                <div>
                  <h6 class="mb-0">Inscripciones</h6>
                  <div class="stats-number">
                    <?php echo $totalInscripciones; ?>
                  </div>
                </div>
                <i class="fas fa-user-check fa-2x opacity-75"></i>
              </div>
            </div>
          </div>

          <!-- Pagos -->
          <div class="col-md-3 mb-3">
            <div class="card stats-card bg-danger">
              <div class="d-flex justify-content-between align-items-center">
                <div>
                  <h6 class="mb-0">Pagos</h6>
                  <div class="stats-number">
                    <?php echo $totalPagos; ?>
                  </div>
                </div>
                <i class="fas fa-money-bill-wave fa-2x opacity-75"></i>
              </div>
            </div>
          </div>

          <!-- Asistencias -->
          <div class="col-md-3 mb-3">
            <div class="card stats-card bg-dark">
              <div class="d-flex justify-content-between align-items-center">
                <div>
                  <h6 class="mb-0">Asistencias</h6>
                  <div class="stats-number">
                    <?php echo $totalAsistencias; ?>
                  </div>
                </div>
                <i class="fas fa-clipboard-check fa-2x opacity-75"></i>
              </div>
            </div>
          </div>
        </div>

        <!-- PERSONAS POR TIPO Y EVENTOS POR MES -->
        <div class="row mb-4">
          <!-- Gráfico 1 -->
          <div class="col-lg-6">
            <div class="card shadow-sm">
              <div class="card-header">
                <h5 class="mb-0">Personas por Tipo</h5>
              </div>
              <div class="card-body">
                <canvas id="graficoPersonas"></canvas>
              </div>
            </div>
          </div>

          <!-- Gráfico 2 -->
          <div class="col-lg-6">
            <div class="card shadow-sm">
              <div class="card-header">
                <h5 class="mb-0">Eventos por Mes</h5>
              </div>
              <div class="card-body">
                <canvas id="graficoEventosMes"></canvas>
              </div>
            </div>
          </div>
        </div>

        <!-- Gráfico 3 - INSCRIPCIONES POR ACTIVIDAD -->
        <div class="row mb-4">
          <div class="col-lg-12">
            <div class="card shadow-sm">
              <div class="card-header">
                <h5 class="mb-0">Inscripciones por Actividad</h5>
              </div>
              <div class="card-body">
                <canvas id="graficoInscripcionesActividad"></canvas>
              </div>
            </div>
          </div>
        </div>

        <!-- TABLA ÚLTIMAS PERSONAS REGISTRADAS -->
        <div class="row mb-4">
          <div class="col-12">
            <div class="card shadow-sm">
              <div class="card-header bg-success text-white">
                <h5 class="mb-0">
                  <i class="fas fa-users"></i>
                  Últimas Personas Registradas
                </h5>
              </div>
              <div class="card-body">
                <div class="table-responsive">
                  <table class="table table-hover table-bordered align-middle">
                    <thead class="table-light">
                      <tr>
                        <th>CI</th>
                        <th>Nombre Completo</th>
                        <th>Universidad</th>
                        <th>Estado</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php while ($persona = $ultimasPersonas->fetch_assoc()) { ?>
                        <tr>
                          <td><?php echo $persona['ci']; ?></td>
                          <td><?php echo $persona['nombre']; ?></td>
                          <td><?php echo $persona['universidad']; ?></td>
                          <td>
                            <?php
                            if ($persona['estado'] == "Activo") {
                              echo "<span class='badge bg-success'>Activo</span>";
                            } else {
                              echo "<span class='badge bg-danger'>Inactivo</span>";
                            }
                            ?>
                          </td>
                        </tr>
                      <?php } ?>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- PANEL DE TRABAJO -->
        <div class="row mb-4">
          <div class="col-12">
            <div class="card shadow-sm">
              <div class="card-header bg-primary text-white">
                <h5 class="mb-0">
                  <i class="fas fa-desktop"></i>
                  Panel de Trabajo
                </h5>
              </div>
              <div class="card-body">
                <div id="panel-trabajo" class="text-center py-5">
                  <i class="fas fa-mouse-pointer fa-4x text-secondary mb-3"></i>
                  <h4>Seleccione una opción del menú lateral</h4>
                  <p class="text-muted">
                    Aquí se cargará Personas, Usuarios, Eventos, Universidades y demás módulos sin salir del Dashboard.
                  </p>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- CALENDARIO -->
        <div class="row mb-4">
          <div class="col-12">
            <div class="card shadow-sm">
              <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                  <i class="fas fa-calendar-alt me-2"></i>
                  Calendario de Eventos
                </h5>
                <div>
                  <button class="btn btn-sm btn-light me-2" onclick="cambiarVistaCalendario('mes')">
                    <i class="fas fa-calendar-alt"></i> Mes
                  </button>
                  <button class="btn btn-sm btn-light me-2" onclick="cambiarVistaCalendario('semana')">
                    <i class="fas fa-calendar-week"></i> Semana
                  </button>
                  <button class="btn btn-sm btn-light" onclick="cambiarVistaCalendario('dia')">
                    <i class="fas fa-calendar-day"></i> Día
                  </button>
                </div>
              </div>
              <div class="card-body">
                <div id="calendario-container">
                  <div class="d-flex justify-content-between align-items-center mb-3">
                    <button class="btn btn-outline-secondary btn-sm" onclick="cambiarMesCalendario(-1)">
                      <i class="fas fa-chevron-left"></i>
                    </button>
                    <h4 class="mb-0" id="calendario-titulo">Enero 2026</h4>
                    <button class="btn btn-outline-secondary btn-sm" onclick="cambiarMesCalendario(1)">
                      <i class="fas fa-chevron-right"></i>
                    </button>
                  </div>
                  <div id="calendario-grid" class="table-responsive">
                    <!-- El calendario se generará con JavaScript -->
                  </div>
                </div>
                <div id="eventos-del-dia" class="mt-3">
                  <h6>Eventos del día seleccionado</h6>
                  <div id="lista-eventos-dia" class="list-group">
                    <p class="text-muted">Selecciona un día para ver los eventos</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>


        <!-- CHART -->
        <div class="row mb-4">
          <div class="col-12">
            <div class="card">
              <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="m-0">Participación en Eventos</h5>
                <div class="small-muted">Últimos eventos</div>
              </div>
              <div class="card-body">
                <div class="chart-container" style="height:360px"><canvas id="grafico-participacion"></canvas></div>
              </div>
            </div>
          </div>
        </div>

        <!-- ACTIONS -->
        <div class="row mb-3">
          <div class="col-12 d-flex flex-wrap justify-content-between gap-2">
            <div>
              <button class="btn btn-primary" onclick="mostrarFormularioAgregar()"><i class="fas fa-plus me-1"></i> Agregar Evento</button>
              <button class="btn btn-success" onclick="mostrarFormularioInscripcion()"><i class="fas fa-user-plus me-1"></i> Nueva Inscripción</button>
            </div>
            <div>
              <input id="filterEventos" class="form-control form-control-sm d-inline-block search-input" placeholder="Filtrar eventos..." style="width:260px">
            </div>
          </div>
        </div>

        <!-- TABS: Eventos / Inscripciones -->
        <ul class="nav nav-tabs mb-3" id="myTab" role="tablist">
          <li class="nav-item"><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#eventos">Eventos</button></li>
          <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#inscripciones">Inscripciones</button></li>
        </ul>

        <div class="tab-content">
          <!-- EVENTOS -->
          <div class="tab-pane fade show active" id="eventos">
            <div class="card">
              <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="m-0">Lista de Eventos</h5>
                <div class="export-section">
                  <div class="export-buttons">
                    <button class="btn btn-outline-success btn-sm" onclick="exportEventos('excel')"><i class="fas fa-file-excel me-1"></i>Excel</button>
                    <button class="btn btn-outline-danger btn-sm" onclick="exportEventos('pdf')"><i class="fas fa-file-pdf me-1"></i>PDF</button>
                  </div>
                </div>
              </div>
              <div class="card-body">
                <div class="table-responsive">
                  <table class="table table-hover" id="table-eventos">
                    <thead>
                      <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Fecha</th>
                        <th>Lugar</th>
                        <th>Participación</th>
                        <th>Acciones</th>
                      </tr>
                    </thead>
                    <tbody id="tabla-eventos">
                      <tr>
                        <td colspan="6" class="text-center">No hay eventos registrados</td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>

          <!-- INSCRIPCIONES -->
          <div class="tab-pane fade" id="inscripciones">
            <div class="card">
              <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="m-0">Lista de Inscripciones</h5>
                <div class="export-section">
                  <div class="export-buttons">
                    <button class="btn btn-outline-success btn-sm" onclick="exportInscripciones('excel')"><i class="fas fa-file-excel me-1"></i>Excel</button>
                    <button class="btn btn-outline-danger btn-sm" onclick="exportInscripciones('pdf')"><i class="fas fa-file-pdf me-1"></i>PDF</button>
                  </div>
                </div>
              </div>
              <div class="card-body">
                <div class="table-responsive">
                  <table class="table table-hover" id="table-inscripciones">
                    <thead>
                      <tr>
                        <th>ID</th>
                        <th>Evento</th>
                        <th>Usuario</th>
                        <th>Fecha</th>
                        <th>Acciones</th>
                      </tr>
                    </thead>
                    <tbody id="tabla-inscripciones">
                      <tr>
                        <td colspan="5" class="text-center">No hay inscripciones registradas</td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div> <!-- end dashboard -->

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
                    <th>ID</th>
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

    <!-- Sección PARTICIPANTES -->
    <div id="participantes" class="content-section">
      <div class="container-fluid py-3">
        <div class="row align-items-center mb-4">
          <div class="col-md-8">
            <h2 class="section-title">Gestión de Participantes</h2>
            <p class="small-muted">Administra los participantes de todos tus eventos</p>
          </div>
          <div class="col-md-4 text-end">
            <button class="btn btn-primary" onclick="mostrarFormularioInscripcion()"><i class="fas fa-user-plus me-1"></i> Agregar Participante</button>
          </div>
        </div>

        <!-- Filtros -->
        <div class="filters-container">
          <div class="row g-3">
            <div class="col-md-4">
              <label class="form-label">Buscar participante</label>
              <input type="text" class="form-control" id="searchParticipantes" placeholder="Nombre o email...">
            </div>
            <div class="col-md-4">
              <label class="form-label">Filtrar por evento</label>
              <select class="form-select" id="filterEventoParticipante">
                <option value="">Todos los eventos</option>
              </select>
            </div>
            <div class="col-md-4">
              <label class="form-label">Estado</label>
              <select class="form-select" id="filterEstadoParticipante">
                <option value="">Todos los estados</option>
                <option value="confirmado">Confirmado</option>
                <option value="pendiente">Pendiente</option>
                <option value="asistio">Asistió</option>
                <option value="cancelado">Cancelado</option>
              </select>
            </div>
          </div>
        </div>

        <!-- Estadísticas -->
        <div class="row mb-4">
          <div class="col-md-3 mb-3">
            <div class="card stats-card bg-custom-primary">
              <div class="d-flex justify-content-between align-items-center">
                <div>
                  <h6 class="mb-0">Total Participantes</h6>
                  <div class="stats-number" id="total-participantes">0</div>
                </div>
                <i class="fas fa-users fa-2x opacity-75"></i>
              </div>
            </div>
          </div>
          <div class="col-md-3 mb-3">
            <div class="card stats-card bg-custom-success">
              <div class="d-flex justify-content-between align-items-center">
                <div>
                  <h6 class="mb-0">Confirmados</h6>
                  <div class="stats-number" id="participantes-confirmados">0</div>
                </div>
                <i class="fas fa-user-check fa-2x opacity-75"></i>
              </div>
            </div>
          </div>
          <div class="col-md-3 mb-3">
            <div class="card stats-card bg-custom-warning">
              <div class="d-flex justify-content-between align-items-center">
                <div>
                  <h6 class="mb-0">Pendientes</h6>
                  <div class="stats-number" id="participantes-pendientes">0</div>
                </div>
                <i class="fas fa-user-clock fa-2x opacity-75"></i>
              </div>
            </div>
          </div>
          <div class="col-md-3 mb-3">
            <div class="card stats-card bg-custom-purple">
              <div class="d-flex justify-content-between align-items-center">
                <div>
                  <h6 class="mb-0">Asistieron</h6>
                  <div class="stats-number" id="participantes-asistieron">0</div>
                </div>
                <i class="fas fa-calendar-check fa-2x opacity-75"></i>
              </div>
            </div>
          </div>
        </div>

        <!-- Tabla de participantes -->
        <div class="card">
          <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="m-0">Lista de Participantes</h5>
            <div class="d-flex gap-2">
              <button class="btn btn-sm btn-outline-secondary" onclick="actualizarListaParticipantes()"><i class="fas fa-sync-alt"></i></button>
              <div class="dropdown">
                <button class="btn btn-sm btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown">Acciones</button>
                <ul class="dropdown-menu">
                  <li><a class="dropdown-item" href="#" onclick="exportarParticipantes('excel')"><i class="fas fa-file-excel me-2"></i>Exportar Excel</a></li>
                  <li><a class="dropdown-item" href="#" onclick="exportarParticipantes('pdf')"><i class="fas fa-file-pdf me-2"></i>Exportar PDF</a></li>
                  <li>
                    <hr class="dropdown-divider">
                  </li>
                  <li><a class="dropdown-item" href="#" onclick="enviarEmailMasivo()"><i class="fas fa-envelope me-2"></i>Enviar Email Masivo</a></li>
                  <li><a class="dropdown-item" href="#" onclick="marcarAsistenciaMasiva()"><i class="fas fa-user-check me-2"></i>Marcar Asistencia</a></li>
                </ul>
              </div>
            </div>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-hover">
                <thead>
                  <tr>
                    <th><input type="checkbox" id="selectAllParticipantes"></th>
                    <th>ID</th>
                    <th>Nombre Completo</th>
                    <th>Email</th>
                    <th>Evento</th>
                    <th>Fecha Inscripción</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                  </tr>
                </thead>
                <tbody id="tabla-participantes">
                  <!-- Los participantes se cargarán aquí dinámicamente -->
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <!-- Acciones masivas -->
        <div class="card mt-4" id="acciones-masivas" style="display: none;">
          <div class="card-body">
            <h6>Acciones para participantes seleccionados (<span id="count-selected">0</span>)</h6>
            <div class="d-flex gap-2 mt-3">
              <button class="btn btn-sm btn-outline-primary" onclick="cambiarEstadoSeleccionados('confirmado')"><i class="fas fa-check me-1"></i>Confirmar</button>
              <button class="btn btn-sm btn-outline-success" onclick="cambiarEstadoSeleccionados('asistio')"><i class="fas fa-user-check me-1"></i>Marcar Asistencia</button>
              <button class="btn btn-sm btn-outline-warning" onclick="cambiarEstadoSeleccionados('pendiente')"><i class="fas fa-clock me-1"></i>Marcar Pendiente</button>
              <button class="btn btn-sm btn-outline-danger" onclick="eliminarParticipantesSeleccionados()"><i class="fas fa-trash me-1"></i>Eliminar</button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Sección REPORTES -->
    <div id="reportes" class="content-section">
      <div class="container-fluid py-3">
        <div class="row align-items-center mb-4">
          <div class="col-md-8">
            <h2 class="section-title">Reportes y Análisis</h2>
            <p class="small-muted">Visualiza métricas y análisis de desempeño de tus eventos</p>
          </div>
          <div class="col-md-4 text-end">
            <div class="dropdown">
              <button class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown"><i class="fas fa-download me-1"></i> Exportar Reportes</button>
              <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="#" onclick="exportarReporteCompleto()"><i class="fas fa-file-archive me-2"></i>Reporte Completo</a></li>
                <li><a class="dropdown-item" href="#" onclick="generarReportePDF()"><i class="fas fa-file-pdf me-2"></i>Reporte en PDF</a></li>
                <li><a class="dropdown-item" href="#" onclick="exportarDatosAnaliticos()"><i class="fas fa-file-excel me-2"></i>Datos Analíticos</a></li>
              </ul>
            </div>
          </div>
        </div>

        <!-- Filtros de reportes -->
        <div class="filters-container mb-4">
          <div class="row g-3">
            <div class="col-md-3">
              <label class="form-label">Período</label>
              <select class="form-select" id="reportPeriod">
                <option value="7">Últimos 7 días</option>
                <option value="30" selected>Últimos 30 días</option>
                <option value="90">Últimos 3 meses</option>
                <option value="365">Último año</option>
                <option value="custom">Personalizado</option>
              </select>
            </div>
            <div class="col-md-3">
              <label class="form-label">Tipo de Evento</label>
              <select class="form-select" id="reportEventType">
                <option value="">Todos los eventos</option>
                <option value="conferencia">Conferencias</option>
                <option value="taller">Talleres</option>
                <option value="seminario">Seminarios</option>
                <option value="webinar">Webinars</option>
              </select>
            </div>
            <div class="col-md-3">
              <label class="form-label">Métrica Principal</label>
              <select class="form-select" id="reportMetric">
                <option value="participacion">Participación</option>
                <option value="asistencia">Asistencia</option>
                <option value="ingresos">Ingresos</option>
                <option value="satisfaccion">Satisfacción</option>
              </select>
            </div>
            <div class="col-md-3 d-flex align-items-end">
              <button class="btn btn-primary w-100" onclick="generarReporte()"><i class="fas fa-chart-bar me-1"></i> Generar Reporte</button>
            </div>
          </div>
        </div>

        <!-- Gráficos principales -->
        <div class="row mb-4">
          <div class="col-md-8">
            <div class="card">
              <div class="card-header">
                <h5 class="m-0">Tendencias de Participación</h5>
              </div>
              <div class="card-body">
                <div class="chart-container" style="height: 350px;">
                  <canvas id="grafico-tendencias"></canvas>
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="card h-100">
              <div class="card-header">
                <h5 class="m-0">Distribución por Evento</h5>
              </div>
              <div class="card-body">
                <div class="chart-container" style="height: 300px;">
                  <canvas id="grafico-distribucion"></canvas>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Métricas clave -->
        <div class="row mb-4">
          <div class="col-md-3 mb-3">
            <div class="card stats-card bg-custom-primary">
              <div class="d-flex justify-content-between align-items-center">
                <div>
                  <h6 class="mb-0">Tasa de Participación</h6>
                  <div class="stats-number" id="tasa-participacion">0%</div>
                </div>
                <i class="fas fa-chart-line fa-2x opacity-75"></i>
              </div>
            </div>
          </div>
          <div class="col-md-3 mb-3">
            <div class="card stats-card bg-custom-success">
              <div class="d-flex justify-content-between align-items-center">
                <div>
                  <h6 class="mb-0">Participantes Únicos</h6>
                  <div class="stats-number" id="participantes-unicos">0</div>
                </div>
                <i class="fas fa-user-friends fa-2x opacity-75"></i>
              </div>
            </div>
          </div>
          <div class="col-md-3 mb-3">
            <div class="card stats-card bg-custom-warning">
              <div class="d-flex justify-content-between align-items-center">
                <div>
                  <h6 class="mb-0">Tasa de Asistencia</h6>
                  <div class="stats-number" id="tasa-asistencia">0%</div>
                </div>
                <i class="fas fa-user-check fa-2x opacity-75"></i>
              </div>
            </div>
          </div>
          <div class="col-md-3 mb-3">
            <div class="card stats-card bg-custom-purple">
              <div class="d-flex justify-content-between align-items-center">
                <div>
                  <h6 class="mb-0">Evento Más Popular</h6>
                  <div class="stats-number" id="evento-popular">-</div>
                </div>
                <i class="fas fa-star fa-2x opacity-75"></i>
              </div>
            </div>
          </div>
        </div>

        <!-- Tabla de eventos con métricas -->
        <div class="card">
          <div class="card-header">
            <h5 class="m-0">Métricas por Evento</h5>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-hover">
                <thead>
                  <tr>
                    <th>Evento</th>
                    <th>Participantes</th>
                    <th>Tasa Participación</th>
                    <th>Tasa Asistencia</th>
                    <th>Satisfacción</th>
                    <th>Ingresos</th>
                  </tr>
                </thead>
                <tbody id="tabla-metricas-eventos">
                  <!-- Las métricas se cargarán aquí dinámicamente -->
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Sección AYUDA -->
    <div id="ayuda" class="content-section">
      <div class="container-fluid py-3">
        <div class="row mb-4">
          <div class="col-12">
            <h2 class="section-title">Centro de Ayuda</h2>
            <p class="small-muted">Encuentra respuestas a tus preguntas y soporte técnico</p>
          </div>
        </div>

        <!-- Tarjetas de ayuda -->
        <div class="row mb-4">
          <div class="col-md-4 mb-3">
            <div class="card h-100 text-center">
              <div class="card-body d-flex flex-column">
                <div class="mb-3">
                  <i class="fas fa-book fa-3x text-primary mb-3"></i>
                  <h5>Base de Conocimiento</h5>
                  <p class="small-muted">Artículos y tutoriales para aprender a usar la plataforma</p>
                </div>
                <div class="mt-auto">
                  <button class="btn btn-outline-primary w-100" onclick="abrirBaseConocimiento()"><i class="fas fa-external-link-alt me-1"></i> Acceder</button>
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-4 mb-3">
            <div class="card h-100 text-center">
              <div class="card-body d-flex flex-column">
                <div class="mb-3">
                  <i class="fas fa-question-circle fa-3x text-success mb-3"></i>
                  <h5>Preguntas Frecuentes</h5>
                  <p class="small-muted">Respuestas a las preguntas más comunes de los usuarios</p>
                </div>
                <div class="mt-auto">
                  <button class="btn btn-outline-success w-100" onclick="mostrarFAQs()"><i class="fas fa-search me-1"></i> Ver FAQs</button>
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-4 mb-3">
            <div class="card h-100 text-center">
              <div class="card-body d-flex flex-column">
                <div class="mb-3">
                  <i class="fas fa-headset fa-3x text-warning mb-3"></i>
                  <h5>Soporte en Vivo</h5>
                  <p class="small-muted">Chatea con nuestro equipo de soporte técnico</p>
                </div>
                <div class="mt-auto">
                  <button class="btn btn-outline-warning w-100" onclick="iniciarChatSoporte()"><i class="fas fa-comments me-1"></i> Iniciar Chat</button>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Categorías de ayuda -->
        <div class="row mb-4">
          <div class="col-md-6">
            <div class="help-category">
              <h5><i class="fas fa-calendar-alt me-2"></i> Gestión de Eventos</h5>
              <div class="help-item" onclick="mostrarAyuda('crear-evento')">
                <strong>¿Cómo crear un nuevo evento?</strong>
                <p class="small-muted mb-0">Aprende a crear y configurar eventos paso a paso</p>
              </div>
              <div class="help-item" onclick="mostrarAyuda('invitar-participantes')">
                <strong>¿Cómo invitar participantes?</strong>
                <p class="small-muted mb-0">Métodos para invitar y gestionar participantes</p>
              </div>
              <div class="help-item" onclick="mostrarAyuda('exportar-datos')">
                <strong>¿Cómo exportar datos de eventos?</strong>
                <p class="small-muted mb-0">Guía para exportar información en diferentes formatos</p>
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="help-category">
              <h5><i class="fas fa-chart-bar me-2"></i> Reportes y Análisis</h5>
              <div class="help-item" onclick="mostrarAyuda('generar-reportes')">
                <strong>¿Cómo generar reportes?</strong>
                <p class="small-muted mb-0">Crea reportes personalizados con tus métricas</p>
              </div>
              <div class="help-item" onclick="mostrarAyuda('interpretar-metricas')">
                <strong>¿Cómo interpretar las métricas?</strong>
                <p class="small-muted mb-0">Guía para entender los datos y gráficos</p>
              </div>
              <div class="help-item" onclick="mostrarAyuda('compartir-reportes')">
                <strong>¿Cómo compartir reportes?</strong>
                <p class="small-muted mb-0">Comparte reportes con tu equipo o clientes</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Formulario de contacto -->
        <div class="row">
          <div class="col-md-8">
            <div class="card">
              <div class="card-header">
                <h5 class="m-0">Contactar Soporte</h5>
              </div>
              <div class="card-body">
                <form id="formSoporte">
                  <div class="row">
                    <div class="col-md-6 mb-3">
                      <label class="form-label">Nombre</label>
                      <input type="text" class="form-control" id="nombreSoporte" required>
                    </div>
                    <div class="col-md-6 mb-3">
                      <label class="form-label">Email</label>
                      <input type="email" class="form-control" id="emailSoporte" required>
                    </div>
                  </div>
                  <div class="mb-3">
                    <label class="form-label">Asunto</label>
                    <select class="form-select" id="asuntoSoporte">
                      <option>Problema Técnico</option>
                      <option>Pregunta sobre Facturación</option>
                      <option>Sugerencia</option>
                      <option>Reportar un Error</option>
                      <option>Otro</option>
                    </select>
                  </div>
                  <div class="mb-3">
                    <label class="form-label">Mensaje</label>
                    <textarea class="form-control" id="mensajeSoporte" rows="4" placeholder="Describe tu consulta en detalle..." required></textarea>
                  </div>
                  <div class="d-flex justify-content-between align-items-center">
                    <div>
                      <small class="text-muted">Respuesta en menos de 24 horas</small>
                    </div>
                    <button type="button" class="btn btn-primary" onclick="enviarSolicitudSoporte()"><i class="fas fa-paper-plane me-1"></i> Enviar Mensaje</button>
                  </div>
                </form>
              </div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="card">
              <div class="card-header">
                <h5 class="m-0">Información de Contacto</h5>
              </div>
              <div class="card-body">
                <div class="mb-3">
                  <h6><i class="fas fa-phone me-2 text-primary"></i> Teléfono</h6>
                  <p class="small-muted mb-0">+1 (555) 123-4567</p>
                  <small class="text-muted">Lunes a Viernes, 9am - 6pm</small>
                </div>
                <div class="mb-3">
                  <h6><i class="fas fa-envelope me-2 text-success"></i> Email</h6>
                  <p class="small-muted mb-0">soporte@eventos.com</p>
                  <small class="text-muted">Respuesta en 24 horas</small>
                </div>
                <div>
                  <h6><i class="fas fa-comments me-2 text-warning"></i> Chat en Vivo</h6>
                  <p class="small-muted mb-0">Disponible 24/7</p>
                  <small class="text-muted">Haz clic en "Iniciar Chat" arriba</small>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div> <!-- end content -->

  <!-- MODALES -->
  <div class="modal fade" id="eventoModal" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title" id="modalTitle">Agregar Evento</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        <div class="modal-body">
          <form id="formEvento" class="needs-validation" novalidate>
            <input type="hidden" id="eventoId">
            <div class="mb-3"><label class="form-label">Nombre</label><input type="text" id="nombre" class="form-control" required></div>
            <div class="mb-3"><label class="form-label">Fecha</label><input type="date" id="fecha" class="form-control" required></div>
            <div class="mb-3"><label class="form-label">Lugar</label><input type="text" id="lugar" class="form-control" required></div>
            <div class="mb-3"><label class="form-label">Participación (%)</label><input type="number" id="participacionInput" class="form-control" min="0" max="100" value="0" required></div>
          </form>
        </div>
        <div class="modal-footer">
          <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button class="btn btn-primary" id="saveEventoBtn">Guardar</button>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="inscripcionModal" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title">Nueva Inscripción</h5>
          <button class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <form id="formInscripcion" class="needs-validation" novalidate>
            <div class="mb-3">
              <label class="form-label">Evento</label>
              <select id="id_evento" class="form-select" required>
                <option value="" disabled selected>Seleccionar evento...</option>
              </select>
            </div>
            <div class="mb-3"><label class="form-label">Nombre de Usuario</label><input id="nombre_usuario" class="form-control" required></div>
          </form>
        </div>
        <div class="modal-footer">
          <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button class="btn btn-primary" id="saveInscripcionBtn">Guardar</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal de ayuda -->
  <div class="modal fade" id="ayudaModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title" id="ayudaModalTitle">Ayuda</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        <div class="modal-body" id="ayudaModalContent">
          <!-- El contenido de ayuda se cargará aquí -->
        </div>
        <div class="modal-footer">
          <button class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Toast container -->
  <div id="toasts" class="position-fixed top-0 end-0 p-3"></div>

  <!-- Bootstrap bundle -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

  <script>
    /****************************
     *  Variables & references
     ****************************/
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('overlaySidebar');
    const sidebarToggle = document.getElementById('sidebarToggle');
    const closeSidebarBtn = document.getElementById('closeSidebar');
    const themeToggle = document.getElementById('themeToggle');
    const toastContainer = document.getElementById('toasts');

    const modalEvento = new bootstrap.Modal(document.getElementById('eventoModal'));
    const modalInscripcion = new bootstrap.Modal(document.getElementById('inscripcionModal'));
    const modalAyuda = new bootstrap.Modal(document.getElementById('ayudaModal'));
    const saveEventoBtn = document.getElementById('saveEventoBtn');
    const saveInscripcionBtn = document.getElementById('saveInscripcionBtn');

    let eventosData = JSON.parse(localStorage.getItem('eventosData')) || [];
    let inscripcionesData = JSON.parse(localStorage.getItem('inscripcionesData')) || [];
    let participantesData = JSON.parse(localStorage.getItem('participantesData')) || [];

    // Si no hay participantesData, crearlo a partir de inscripcionesData
    if (!participantesData || participantesData.length === 0) {
      participantesData = inscripcionesData.map(ins => ({
        id: ins.id,
        nombre: ins.nombre_usuario,
        email: `${ins.nombre_usuario.toLowerCase().replace(/\s+/g, '.')}@email.com`,
        telefono: '55' + Math.floor(10000000 + Math.random() * 90000000),
        id_evento: ins.id_evento,
        evento_nombre: ins.evento_nombre,
        fecha_inscripcion: ins.fecha_inscripcion,
        estado: ['confirmado', 'pendiente', 'asistio', 'cancelado'][Math.floor(Math.random() * 4)]
      }));
      localStorage.setItem('participantesData', JSON.stringify(participantesData));
    }

    let chart = null;
    let chartTendencias = null;
    let chartDistribucion = null;

    // Estado inicial del sidebar - visible por defecto en desktop
    let sidebarState = localStorage.getItem('sidebarState') || 'open';

    /****************************
     * Navegación entre secciones
     ****************************/
    function initNavigation() {
      const navItems = document.querySelectorAll('#sidebar .components li[data-section]');
      const contentSections = document.querySelectorAll('.content-section');

      navItems.forEach(item => {
        item.addEventListener('click', function(e) {
          e.preventDefault();

          // Obtener la sección objetivo
          const targetSection = this.getAttribute('data-section');

          // Remover clase active de todos los items
          navItems.forEach(nav => nav.classList.remove('active'));

          // Añadir clase active al item clickeado
          this.classList.add('active');

          // Ocultar todas las secciones
          contentSections.forEach(section => {
            section.classList.remove('active');
          });

          // Mostrar la sección objetivo
          document.getElementById(targetSection).classList.add('active');

          // Actualizar datos de la sección activa
          switch (targetSection) {
            case 'dashboard':
              cargarResumen();
              cargarEventos();
              cargarInscripciones();
              break;
            case 'mis-eventos':
              actualizarListaEventos();
              actualizarEstadisticasMisEventos();
              break;
            case 'participantes':
              actualizarListaParticipantes();
              actualizarEstadisticasParticipantes();
              cargarSelectEventosParticipantes();
              break;
            case 'reportes':
              generarReporte();
              break;
            case 'ayuda':
              // No necesita actualización
              break;
          }

          // Cerrar sidebar en móvil
          if (window.innerWidth < 992) {
            closeSidebarMobile();
          }
        });
      });
    }

    /****************************
     * Utility: Toasts
     ****************************/
    function showToast(message, type = 'success', timeout = 3000) {
      const id = 't' + Date.now();
      const klass = type === 'error' ? 'bg-danger text-white' : type === 'warning' ? 'bg-warning text-dark' : 'bg-dark text-white';
      const icon = type === 'success' ? 'fa-check-circle' : type === 'error' ? 'fa-exclamation-circle' : type === 'warning' ? 'fa-exclamation-triangle' : 'fa-info-circle';
      const el = document.createElement('div');
      el.className = `toast ${klass}`;
      el.role = 'alert';
      el.ariaLive = 'polite';
      el.ariaAtomic = 'true';
      el.innerHTML = `<div class="d-flex align-items-center"><div class="toast-body"><i class="fas ${icon} me-2"></i>${message}</div><button type="button" class="btn-close btn-close-white ms-auto me-2" data-bs-dismiss="toast" aria-label="Close"></button></div>`;
      toastContainer.appendChild(el);
      const bs = new bootstrap.Toast(el, {
        delay: timeout
      });
      bs.show();
      el.addEventListener('hidden.bs.toast', () => el.remove());
    }

    /****************************
     * Sidebar open/close behavior
     ****************************/
    function toggleSidebar() {
      if (window.innerWidth < 992) {
        // Comportamiento móvil
        sidebar.classList.toggle('visible');
        overlay.classList.toggle('show');
      } else {
        // Comportamiento desktop
        sidebar.classList.toggle('hidden');
        document.getElementById('content').classList.toggle('fullwidth');
        sidebarToggle.classList.toggle('rotated');

        // Guardar estado
        sidebarState = sidebar.classList.contains('hidden') ? 'closed' : 'open';
        localStorage.setItem('sidebarState', sidebarState);
      }
    }

    function closeSidebarMobile() {
      sidebar.classList.remove('visible');
      overlay.classList.remove('show');
    }

    sidebarToggle.addEventListener('click', toggleSidebar);
    closeSidebarBtn.addEventListener('click', closeSidebarMobile);
    overlay.addEventListener('click', closeSidebarMobile);

    /****************************
     * Theme: dark / light toggle
     ****************************/
    // Restore theme
    const savedTheme = localStorage.getItem('panelTheme') || 'light';
    if (savedTheme === 'dark') document.body.classList.add('dark');
    updateThemeIcon();

    themeToggle.addEventListener('click', () => {
      document.body.classList.toggle('dark');
      localStorage.setItem('panelTheme', document.body.classList.contains('dark') ? 'dark' : 'light');
      updateThemeIcon();
      showToast('Tema cambiado', 'success', 1500);
    });

    function updateThemeIcon() {
      themeToggle.innerHTML = document.body.classList.contains('dark') ? '<i class="fas fa-moon"></i>' : '<i class="fas fa-sun"></i>';
    }

    /****************************
     * Submenu improved
     ****************************/
    document.querySelectorAll('.has-submenu').forEach(li => {
      const toggle = li.querySelector('.submenu-toggle');
      const submenu = li.querySelector('.submenu');
      toggle.addEventListener('click', (e) => {
        e.preventDefault();
        e.stopPropagation();
        const isOpen = submenu.classList.contains('show');
        // close others
        document.querySelectorAll('.submenu.show').forEach(s => {
          if (s !== submenu) {
            s.classList.remove('show');
            s.parentElement.classList.remove('open');
          }
        });
        submenu.classList.toggle('show', !isOpen);
        li.classList.toggle('open', !isOpen);
      });
    });

    /****************************
     * LocalStorage helpers
     ****************************/
    function guardarEnLocalStorage() {
      localStorage.setItem('eventosData', JSON.stringify(eventosData));
      localStorage.setItem('inscripcionesData', JSON.stringify(inscripcionesData));
      localStorage.setItem('participantesData', JSON.stringify(participantesData));
    }

    /****************************
     * Funciones para MIS EVENTOS
     ****************************/
    function actualizarEstadisticasMisEventos() {
      const totalEventos = eventosData.length;
      const hoy = new Date();

      const eventosActivos = eventosData.filter(e => {
        const fechaEvento = new Date(e.fecha);
        return fechaEvento >= hoy;
      }).length;

      const eventosFinalizados = eventosData.filter(e => {
        const fechaEvento = new Date(e.fecha);
        return fechaEvento < hoy;
      }).length;

      const participacionPromedio = eventosData.length > 0 ?
        (eventosData.reduce((sum, e) => sum + (e.participacion || 0), 0) / eventosData.length).toFixed(1) :
        0;

      document.getElementById('total-eventos').textContent = totalEventos;
      document.getElementById('eventos-activos').textContent = eventosActivos;
      document.getElementById('eventos-finalizados').textContent = eventosFinalizados;
      document.getElementById('participacion-promedio').textContent = participacionPromedio + '%';
    }

    function actualizarListaEventos() {
      const tabla = document.getElementById('tabla-mis-eventos');
      const searchTerm = document.getElementById('searchMisEventos').value.toLowerCase();
      const filterEstado = document.getElementById('filterEstado').value;
      const sortBy = document.getElementById('sortEventos').value;

      let eventosFiltrados = [...eventosData];

      // Aplicar filtro de búsqueda
      if (searchTerm) {
        eventosFiltrados = eventosFiltrados.filter(e =>
          e.nombre.toLowerCase().includes(searchTerm) ||
          e.lugar.toLowerCase().includes(searchTerm)
        );
      }

      // Aplicar filtro de estado
      if (filterEstado) {
        const hoy = new Date();
        eventosFiltrados = eventosFiltrados.filter(e => {
          const fechaEvento = new Date(e.fecha);
          if (filterEstado === 'activo') return fechaEvento >= hoy;
          if (filterEstado === 'proximo') {
            const diferenciaDias = (fechaEvento - hoy) / (1000 * 60 * 60 * 24);
            return fechaEvento >= hoy && diferenciaDias <= 7;
          }
          if (filterEstado === 'finalizado') return fechaEvento < hoy;
          return true;
        });
      }

      // Aplicar ordenamiento
      eventosFiltrados.sort((a, b) => {
        if (sortBy === 'fecha') {
          return new Date(b.fecha) - new Date(a.fecha);
        } else if (sortBy === 'participacion') {
          return (b.participacion || 0) - (a.participacion || 0);
        } else if (sortBy === 'nombre') {
          return a.nombre.localeCompare(b.nombre);
        }
        return 0;
      });

      // Renderizar tabla
      tabla.innerHTML = '';

      if (eventosFiltrados.length === 0) {
        tabla.innerHTML = `<tr><td colspan="7" class="text-center">No hay eventos que coincidan con los filtros</td></tr>`;
      } else {
        eventosFiltrados.forEach(ev => {
          const fechaEvento = new Date(ev.fecha);
          const hoy = new Date();
          const diferenciaDias = (fechaEvento - hoy) / (1000 * 60 * 60 * 24);

          let estado = '';
          let estadoClass = '';

          if (fechaEvento < hoy) {
            estado = 'Finalizado';
            estadoClass = 'completed';
          } else if (diferenciaDias <= 7) {
            estado = 'Próximo';
            estadoClass = 'upcoming';
          } else {
            estado = 'Activo';
            estadoClass = 'active';
          }

          const tr = document.createElement('tr');
          tr.setAttribute('data-event-id', ev.id);
          tr.addEventListener('click', (e) => {
            if (!e.target.closest('td:last-child') && !e.target.closest('td:nth-child(6)')) {
              mostrarDetallesEvento(ev.id);
            }
          });

          tr.innerHTML = `
            <td>${ev.id}</td>
            <td><strong>${ev.nombre}</strong></td>
            <td>${ev.fecha}</td>
            <td>${ev.lugar}</td>
            <td>
              <div class="d-flex align-items-center">
                <div class="progress flex-grow-1 me-2" style="height: 8px;">
                  <div class="progress-bar" role="progressbar" style="width: ${ev.participacion || 0}%"></div>
                </div>
                <span>${ev.participacion || 0}%</span>
              </div>
            </td>
            <td><span class="badge-status ${estadoClass}">${estado}</span></td>
            <td>
              <div class="d-flex gap-1">
                <button class="btn btn-sm btn-outline-primary btn-action" onclick="event.stopPropagation();editarEvento(${ev.id})"><i class="fas fa-edit"></i></button>
                <button class="btn btn-sm btn-outline-success btn-action" onclick="event.stopPropagation();verParticipantesEvento(${ev.id})"><i class="fas fa-users"></i></button>
                <button class="btn btn-sm btn-outline-info btn-action" onclick="event.stopPropagation();enviarRecordatorio(${ev.id})"><i class="fas fa-envelope"></i></button>
                <button class="btn btn-sm btn-outline-danger btn-action" onclick="event.stopPropagation();eliminarEvento(${ev.id})"><i class="fas fa-trash"></i></button>
              </div>
            </td>
          `;
          tabla.appendChild(tr);
        });
      }
    }

    function mostrarDetallesEvento(id) {
      const ev = eventosData.find(e => e.id == id);
      if (!ev) return;

      const participantesEvento = participantesData.filter(p => p.id_evento == id);
      const confirmados = participantesEvento.filter(p => p.estado === 'confirmado').length;
      const asistieron = participantesEvento.filter(p => p.estado === 'asistio').length;

      const contenido = `
        <div class="row">
          <div class="col-md-6">
            <h6>Información del Evento</h6>
            <p><strong>Nombre:</strong> ${ev.nombre}</p>
            <p><strong>Fecha:</strong> ${ev.fecha}</p>
            <p><strong>Lugar:</strong> ${ev.lugar}</p>
            <p><strong>Participación:</strong> ${ev.participacion || 0}%</p>
          </div>
          <div class="col-md-6">
            <h6>Estadísticas</h6>
            <p><strong>Total Participantes:</strong> ${participantesEvento.length}</p>
            <p><strong>Confirmados:</strong> ${confirmados}</p>
            <p><strong>Asistieron:</strong> ${asistieron}</p>
            <p><strong>Tasa de Asistencia:</strong> ${participantesEvento.length > 0 ? ((asistieron / participantesEvento.length) * 100).toFixed(1) : 0}%</p>
          </div>
        </div>
        <div class="mt-3">
          <h6>Acciones</h6>
          <div class="d-flex gap-2 flex-wrap">
            <button class="btn btn-sm btn-primary" onclick="verParticipantesEvento(${ev.id})"><i class="fas fa-users me-1"></i>Ver Participantes</button>
            <button class="btn btn-sm btn-success" onclick="editarEvento(${ev.id})"><i class="fas fa-edit me-1"></i>Editar Evento</button>
            <button class="btn btn-sm btn-info" onclick="enviarRecordatorio(${ev.id})"><i class="fas fa-envelope me-1"></i>Enviar Recordatorio</button>
            <button class="btn btn-sm btn-warning" onclick="exportarListaParticipantes(${ev.id})"><i class="fas fa-download me-1"></i>Exportar Lista</button>
          </div>
        </div>
      `;

      document.getElementById('evento-detalle-content').innerHTML = contenido;
      document.getElementById('evento-detalle-card').style.display = 'block';
    }

    function verParticipantesEvento(id) {
      const ev = eventosData.find(e => e.id == id);
      if (!ev) return;

      const participantesEvento = participantesData.filter(p => p.id_evento == id);

      if (participantesEvento.length === 0) {
        showToast('No hay participantes para este evento', 'info');
        return;
      }

      // Cambiar a sección de participantes y filtrar por este evento
      document.querySelector('#sidebar li[data-section="participantes"]').click();
      setTimeout(() => {
        document.getElementById('filterEventoParticipante').value = id;
        actualizarListaParticipantes();
      }, 100);
    }

    function enviarRecordatorio(id) {
      const ev = eventosData.find(e => e.id == id);
      if (!ev) return;

      showToast(`Recordatorio enviado para el evento: ${ev.nombre}`, 'success');
    }

    function enviarRecordatorioTodos() {
      const eventosActivos = eventosData.filter(e => {
        const fechaEvento = new Date(e.fecha);
        return fechaEvento >= new Date();
      });

      if (eventosActivos.length === 0) {
        showToast('No hay eventos activos para enviar recordatorios', 'info');
        return;
      }

      showToast(`Recordatorios enviados para ${eventosActivos.length} eventos activos`, 'success');
    }

    function exportarListaParticipantes(id) {
      const ev = eventosData.find(e => e.id == id);
      if (!ev) return;

      const participantesEvento = participantesData.filter(p => p.id_evento == id);

      if (participantesEvento.length === 0) {
        showToast('No hay participantes para exportar', 'info');
        return;
      }

      const data = participantesEvento.map(p => ({
        ID: p.id,
        Nombre: p.nombre,
        Email: p.email,
        Teléfono: p.telefono,
        Estado: p.estado,
        'Fecha Inscripción': p.fecha_inscripcion
      }));

      exportToExcel(data, `Participantes_${ev.nombre}`, `Participantes_${ev.nombre}.xlsx`);
    }

    function exportarMisEventos(format) {
      const data = eventosData.map(ev => {
        const fechaEvento = new Date(ev.fecha);
        const hoy = new Date();
        let estado = '';

        if (fechaEvento < hoy) {
          estado = 'Finalizado';
        } else if ((fechaEvento - hoy) / (1000 * 60 * 60 * 24) <= 7) {
          estado = 'Próximo';
        } else {
          estado = 'Activo';
        }

        return {
          ID: ev.id,
          Nombre: ev.nombre,
          Fecha: ev.fecha,
          Lugar: ev.lugar,
          Participación: `${ev.participacion || 0}%`,
          Estado: estado
        };
      });

      if (format === 'excel') {
        exportToExcel(data, 'Mis_Eventos', 'Mis_Eventos.xlsx');
      } else if (format === 'pdf') {
        exportToPDF(data, 'Mis_Eventos', 'Mis_Eventos.pdf');
      }
    }

    /****************************
     * Funciones para PARTICIPANTES
     ****************************/
    function actualizarEstadisticasParticipantes() {
      const totalParticipantes = participantesData.length;
      const confirmados = participantesData.filter(p => p.estado === 'confirmado').length;
      const pendientes = participantesData.filter(p => p.estado === 'pendiente').length;
      const asistieron = participantesData.filter(p => p.estado === 'asistio').length;

      document.getElementById('total-participantes').textContent = totalParticipantes;
      document.getElementById('participantes-confirmados').textContent = confirmados;
      document.getElementById('participantes-pendientes').textContent = pendientes;
      document.getElementById('participantes-asistieron').textContent = asistieron;
    }

    function cargarSelectEventosParticipantes() {
      const select = document.getElementById('filterEventoParticipante');
      select.innerHTML = '<option value="">Todos los eventos</option>';

      eventosData.forEach(ev => {
        const option = document.createElement('option');
        option.value = ev.id;
        option.textContent = ev.nombre;
        select.appendChild(option);
      });
    }

    function actualizarListaParticipantes() {
      const tabla = document.getElementById('tabla-participantes');
      const searchTerm = document.getElementById('searchParticipantes').value.toLowerCase();
      const filterEvento = document.getElementById('filterEventoParticipante').value;
      const filterEstado = document.getElementById('filterEstadoParticipante').value;

      let participantesFiltrados = [...participantesData];

      // Aplicar filtro de búsqueda
      if (searchTerm) {
        participantesFiltrados = participantesFiltrados.filter(p =>
          p.nombre.toLowerCase().includes(searchTerm) ||
          p.email.toLowerCase().includes(searchTerm)
        );
      }

      // Aplicar filtro de evento
      if (filterEvento) {
        participantesFiltrados = participantesFiltrados.filter(p => p.id_evento == filterEvento);
      }

      // Aplicar filtro de estado
      if (filterEstado) {
        participantesFiltrados = participantesFiltrados.filter(p => p.estado === filterEstado);
      }

      // Renderizar tabla
      tabla.innerHTML = '';

      if (participantesFiltrados.length === 0) {
        tabla.innerHTML = `<tr><td colspan="8" class="text-center">No hay participantes que coincidan con los filtros</td></tr>`;
        document.getElementById('acciones-masivas').style.display = 'none';
      } else {
        participantesFiltrados.forEach(p => {
          const ev = eventosData.find(e => e.id == p.id_evento);
          const eventoNombre = ev ? ev.nombre : `Evento ${p.id_evento}`;

          const tr = document.createElement('tr');
          tr.setAttribute('data-participante-id', p.id);

          tr.innerHTML = `
            <td><input type="checkbox" class="participante-checkbox" value="${p.id}" onchange="actualizarSeleccionParticipantes()"></td>
            <td>${p.id}</td>
            <td>${p.nombre}</td>
            <td>${p.email}</td>
            <td>${eventoNombre}</td>
            <td>${p.fecha_inscripcion}</td>
            <td>
              <span class="badge-status ${p.estado}">
                ${p.estado.charAt(0).toUpperCase() + p.estado.slice(1)}
              </span>
            </td>
            <td>
              <div class="d-flex gap-1 flex-wrap">
                <button class="btn btn-sm btn-outline-primary btn-action" onclick="editarParticipante(${p.id})"><i class="fas fa-edit"></i></button>
                <button class="btn btn-sm btn-outline-success btn-action" onclick="cambiarEstadoParticipante(${p.id}, 'confirmado')"><i class="fas fa-check"></i></button>
                <button class="btn btn-sm btn-outline-warning btn-action" onclick="cambiarEstadoParticipante(${p.id}, 'pendiente')"><i class="fas fa-clock"></i></button>
                <button class="btn btn-sm btn-outline-danger btn-action" onclick="eliminarParticipante(${p.id})"><i class="fas fa-trash"></i></button>
              </div>
            </td>
          `;
          tabla.appendChild(tr);
        });

        // Actualizar selección
        actualizarSeleccionParticipantes();
      }
    }

    function actualizarSeleccionParticipantes() {
      const checkboxes = document.querySelectorAll('.participante-checkbox');
      const selectAll = document.getElementById('selectAllParticipantes');
      const countSelected = document.getElementById('count-selected');
      const accionesMasivas = document.getElementById('acciones-masivas');

      let selectedCount = 0;
      checkboxes.forEach(cb => {
        if (cb.checked) selectedCount++;
      });

      // Actualizar contador
      countSelected.textContent = selectedCount;

      // Mostrar/ocultar panel de acciones masivas
      if (selectedCount > 0) {
        accionesMasivas.style.display = 'block';
      } else {
        accionesMasivas.style.display = 'none';
      }

      // Actualizar estado de "Seleccionar todos"
      selectAll.checked = selectedCount > 0 && selectedCount === checkboxes.length;
      selectAll.indeterminate = selectedCount > 0 && selectedCount < checkboxes.length;
    }

    document.getElementById('selectAllParticipantes').addEventListener('change', function() {
      const checkboxes = document.querySelectorAll('.participante-checkbox');
      checkboxes.forEach(cb => cb.checked = this.checked);
      actualizarSeleccionParticipantes();
    });

    function editarParticipante(id) {
      const p = participantesData.find(part => part.id == id);
      if (!p) return;

      // Aquí podrías mostrar un modal para editar el participante
      showToast(`Editando participante: ${p.nombre}`, 'info');
    }

    function cambiarEstadoParticipante(id, nuevoEstado) {
      const p = participantesData.find(part => part.id == id);
      if (!p) return;

      p.estado = nuevoEstado;
      guardarEnLocalStorage();
      actualizarListaParticipantes();
      actualizarEstadisticasParticipantes();

      showToast(`Estado cambiado a: ${nuevoEstado}`, 'success');
    }

    function eliminarParticipante(id) {
      if (!confirm('¿Seguro quieres eliminar este participante?')) return;

      const idx = participantesData.findIndex(p => p.id === id);
      if (idx !== -1) {
        participantesData.splice(idx, 1);
        guardarEnLocalStorage();
        actualizarListaParticipantes();
        actualizarEstadisticasParticipantes();
        showToast('Participante eliminado', 'success');
      }
    }

    function cambiarEstadoSeleccionados(nuevoEstado) {
      const checkboxes = document.querySelectorAll('.participante-checkbox:checked');
      if (checkboxes.length === 0) return;

      checkboxes.forEach(cb => {
        const id = parseInt(cb.value);
        const p = participantesData.find(part => part.id == id);
        if (p) p.estado = nuevoEstado;
      });

      guardarEnLocalStorage();
      actualizarListaParticipantes();
      actualizarEstadisticasParticipantes();

      showToast(`${checkboxes.length} participantes actualizados a: ${nuevoEstado}`, 'success');
    }

    function eliminarParticipantesSeleccionados() {
      const checkboxes = document.querySelectorAll('.participante-checkbox:checked');
      if (checkboxes.length === 0) return;

      if (!confirm(`¿Seguro quieres eliminar ${checkboxes.length} participantes?`)) return;

      const ids = Array.from(checkboxes).map(cb => parseInt(cb.value));
      participantesData = participantesData.filter(p => !ids.includes(p.id));

      guardarEnLocalStorage();
      actualizarListaParticipantes();
      actualizarEstadisticasParticipantes();

      showToast(`${ids.length} participantes eliminados`, 'success');
    }

    function enviarEmailMasivo() {
      const checkboxes = document.querySelectorAll('.participante-checkbox:checked');
      if (checkboxes.length === 0) {
        showToast('Selecciona al menos un participante', 'warning');
        return;
      }

      showToast(`Enviando email a ${checkboxes.length} participantes...`, 'info');

      // Simular envío
      setTimeout(() => {
        showToast('Emails enviados correctamente', 'success');
      }, 1500);
    }

    function marcarAsistenciaMasiva() {
      const checkboxes = document.querySelectorAll('.participante-checkbox:checked');
      if (checkboxes.length === 0) {
        showToast('Selecciona al menos un participante', 'warning');
        return;
      }

      checkboxes.forEach(cb => {
        const id = parseInt(cb.value);
        cambiarEstadoParticipante(id, 'asistio');
      });
    }

    function exportarParticipantes(format) {
      const data = participantesData.map(p => {
        const ev = eventosData.find(e => e.id == p.id_evento);
        return {
          ID: p.id,
          Nombre: p.nombre,
          Email: p.email,
          Teléfono: p.telefono,
          Evento: ev ? ev.nombre : `Evento ${p.id_evento}`,
          'Fecha Inscripción': p.fecha_inscripcion,
          Estado: p.estado.charAt(0).toUpperCase() + p.estado.slice(1)
        };
      });

      if (format === 'excel') {
        exportToExcel(data, 'Participantes', 'Participantes.xlsx');
      } else if (format === 'pdf') {
        exportToPDF(data, 'Participantes', 'Participantes.pdf');
      }
    }

    /****************************
     * Funciones para REPORTES
     ****************************/
    function generarReporte() {
      const periodo = document.getElementById('reportPeriod').value;
      const tipoEvento = document.getElementById('reportEventType').value;
      const metrica = document.getElementById('reportMetric').value;

      // Calcular métricas
      const totalParticipantes = participantesData.length;
      const participantesUnicos = new Set(participantesData.map(p => p.email)).size;

      const eventosConMetricas = eventosData.map(ev => {
        const participantesEvento = participantesData.filter(p => p.id_evento == ev.id);
        const asistieron = participantesEvento.filter(p => p.estado === 'asistio').length;
        const tasaAsistencia = participantesEvento.length > 0 ? (asistieron / participantesEvento.length) * 100 : 0;

        return {
          evento: ev.nombre,
          participantes: participantesEvento.length,
          tasaParticipacion: ev.participacion || 0,
          tasaAsistencia: tasaAsistencia,
          satisfaccion: 70 + Math.random() * 25, // Simulado
          ingresos: participantesEvento.length * 50 // Simulado
        };
      });

      // Actualizar métricas clave
      document.getElementById('tasa-participacion').textContent =
        eventosConMetricas.length > 0 ?
        (eventosConMetricas.reduce((sum, e) => sum + e.tasaParticipacion, 0) / eventosConMetricas.length).toFixed(1) + '%' :
        '0%';

      document.getElementById('participantes-unicos').textContent = participantesUnicos;

      document.getElementById('tasa-asistencia').textContent =
        eventosConMetricas.length > 0 ?
        (eventosConMetricas.reduce((sum, e) => sum + e.tasaAsistencia, 0) / eventosConMetricas.length).toFixed(1) + '%' :
        '0%';

      const eventoMasPopular = eventosConMetricas.reduce((max, e) => e.participantes > max.participantes ? e : max, {
        participantes: 0
      });
      document.getElementById('evento-popular').textContent = eventoMasPopular.participantes > 0 ? eventoMasPopular.evento.substring(0, 15) + '...' : '-';

      // Actualizar tabla de métricas
      const tablaMetricas = document.getElementById('tabla-metricas-eventos');
      tablaMetricas.innerHTML = '';

      eventosConMetricas.forEach(em => {
        const tr = document.createElement('tr');
        tr.innerHTML = `
          <td>${em.evento}</td>
          <td>${em.participantes}</td>
          <td>${em.tasaParticipacion.toFixed(1)}%</td>
          <td>${em.tasaAsistencia.toFixed(1)}%</td>
          <td>${em.satisfaccion.toFixed(1)}/100</td>
          <td>$${em.ingresos}</td>
        `;
        tablaMetricas.appendChild(tr);
      });

      // Generar gráficos
      generarGraficoTendencias(eventosConMetricas);
      generarGraficoDistribucion(eventosConMetricas);
    }

    function generarGraficoTendencias(eventosConMetricas) {
      const ctx = document.getElementById('grafico-tendencias').getContext('2d');

      if (chartTendencias) chartTendencias.destroy();

      // Datos para el gráfico de tendencias
      const nombres = eventosConMetricas.map(e => e.evento.substring(0, 10) + '...');
      const participantes = eventosConMetricas.map(e => e.participantes);
      const participacion = eventosConMetricas.map(e => e.tasaParticipacion);
      const asistencia = eventosConMetricas.map(e => e.tasaAsistencia);

      chartTendencias = new Chart(ctx, {
        type: 'line',
        data: {
          labels: nombres,
          datasets: [{
              label: 'Participantes',
              data: participantes,
              borderColor: 'rgb(78, 115, 223)',
              backgroundColor: 'rgba(78, 115, 223, 0.1)',
              tension: 0.4,
              fill: true
            },
            {
              label: 'Participación (%)',
              data: participacion,
              borderColor: 'rgb(28, 200, 138)',
              backgroundColor: 'rgba(28, 200, 138, 0.1)',
              tension: 0.4,
              fill: true
            },
            {
              label: 'Asistencia (%)',
              data: asistencia,
              borderColor: 'rgb(246, 194, 62)',
              backgroundColor: 'rgba(246, 194, 62, 0.1)',
              tension: 0.4,
              fill: true
            }
          ]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          scales: {
            y: {
              beginAtZero: true,
              title: {
                display: true,
                text: 'Valores'
              }
            }
          }
        }
      });
    }

    function generarGraficoDistribucion(eventosConMetricas) {
      const ctx = document.getElementById('grafico-distribucion').getContext('2d');

      if (chartDistribucion) chartDistribucion.destroy();

      // Datos para el gráfico de distribución
      const nombres = eventosConMetricas.map(e => e.evento.substring(0, 10) + '...');
      const participantes = eventosConMetricas.map(e => e.participantes);

      // Colores aleatorios para cada barra
      const backgroundColors = nombres.map(() =>
        `rgba(${Math.floor(Math.random() * 255)}, ${Math.floor(Math.random() * 255)}, ${Math.floor(Math.random() * 255)}, 0.7)`
      );

      chartDistribucion = new Chart(ctx, {
        type: 'bar',
        data: {
          labels: nombres,
          datasets: [{
            label: 'Participantes por Evento',
            data: participantes,
            backgroundColor: backgroundColors,
            borderColor: backgroundColors.map(c => c.replace('0.7', '1')),
            borderWidth: 1
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          scales: {
            y: {
              beginAtZero: true,
              title: {
                display: true,
                text: 'Número de Participantes'
              }
            }
          }
        }
      });
    }

    function exportarReporteCompleto() {
      const data = eventosData.map(ev => {
        const participantesEvento = participantesData.filter(p => p.id_evento == ev.id);
        const asistieron = participantesEvento.filter(p => p.estado === 'asistio').length;
        const tasaAsistencia = participantesEvento.length > 0 ? (asistieron / participantesEvento.length) * 100 : 0;

        return {
          ID: ev.id,
          Evento: ev.nombre,
          Fecha: ev.fecha,
          Lugar: ev.lugar,
          'Participación (%)': ev.participacion || 0,
          'Total Participantes': participantesEvento.length,
          'Asistieron': asistieron,
          'Tasa Asistencia (%)': tasaAsistencia.toFixed(1),
          'Ingresos Estimados': `$${participantesEvento.length * 50}`
        };
      });

      exportToExcel(data, 'Reporte_Completo', 'Reporte_Completo.xlsx');
    }

    function generarReportePDF() {
      const {
        jsPDF
      } = window.jspdf;
      const doc = new jsPDF();

      // Título
      doc.setFontSize(16);
      doc.text('Reporte de Eventos', 14, 16);

      // Fecha
      doc.setFontSize(10);
      const fecha = new Date().toLocaleDateString('es-ES');
      doc.text(`Generado el: ${fecha}`, 14, 24);

      // Resumen
      doc.setFontSize(12);
      doc.text('Resumen Estadístico', 14, 36);

      const totalEventos = eventosData.length;
      const totalParticipantes = participantesData.length;
      const participacionPromedio = eventosData.length > 0 ?
        (eventosData.reduce((sum, e) => sum + (e.participacion || 0), 0) / eventosData.length).toFixed(1) :
        0;

      doc.setFontSize(10);
      doc.text(`- Total Eventos: ${totalEventos}`, 20, 46);
      doc.text(`- Total Participantes: ${totalParticipantes}`, 20, 54);
      doc.text(`- Participación Promedio: ${participacionPromedio}%`, 20, 62);

      // Tabla de eventos
      let startY = 72;
      const headers = [
        ['Evento', 'Fecha', 'Participantes', 'Participación']
      ];
      const body = eventosData.map(ev => {
        const participantesEvento = participantesData.filter(p => p.id_evento == ev.id);
        return [
          ev.nombre.substring(0, 20),
          ev.fecha,
          participantesEvento.length.toString(),
          `${ev.participacion || 0}%`
        ];
      });

      doc.autoTable({
        startY: startY,
        head: headers,
        body: body,
        styles: {
          fontSize: 9,
          cellPadding: 3
        },
        headStyles: {
          fillColor: [78, 115, 223],
          textColor: 255,
          fontStyle: 'bold'
        }
      });

      doc.save('Reporte_Eventos.pdf');
      showToast('Reporte PDF generado', 'success');
    }

    function exportarDatosAnaliticos() {
      const data = participantesData.map(p => {
        const ev = eventosData.find(e => e.id == p.id_evento);
        return {
          'ID Participante': p.id,
          Nombre: p.nombre,
          Email: p.email,
          Teléfono: p.telefono,
          Evento: ev ? ev.nombre : `Evento ${p.id_evento}`,
          'ID Evento': p.id_evento,
          'Fecha Inscripción': p.fecha_inscripcion,
          Estado: p.estado.charAt(0).toUpperCase() + p.estado.slice(1),
          'Fecha Estado': new Date().toISOString().split('T')[0]
        };
      });

      exportToExcel(data, 'Datos_Analiticos', 'Datos_Analiticos.xlsx');
    }

    /****************************
     * Funciones para AYUDA
     ****************************/
    function abrirBaseConocimiento() {
      window.open('https://ejemplo.com/base-conocimiento', '_blank');
    }

    function mostrarFAQs() {
      const contenido = `
        <h5>Preguntas Frecuentes</h5>
        <div class="accordion mt-3" id="faqAccordion">
          <div class="accordion-item">
            <h2 class="accordion-header">
              <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                ¿Cómo creo un nuevo evento?
              </button>
            </h2>
            <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
              <div class="accordion-body">
                Para crear un nuevo evento, ve a la sección "Dashboard" y haz clic en el botón "Agregar Evento". Completa el formulario con la información del evento y haz clic en "Guardar".
              </div>
            </div>
          </div>
          <div class="accordion-item">
            <h2 class="accordion-header">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                ¿Cómo invito participantes a un evento?
              </button>
            </h2>
            <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
              <div class="accordion-body">
                Puedes agregar participantes individualmente desde la sección "Participantes" o importar una lista completa usando la opción "Importar Lista". También puedes enviar invitaciones por email usando la función "Enviar Email Masivo".
              </div>
            </div>
          </div>
          <div class="accordion-item">
            <h2 class="accordion-header">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                ¿Cómo genero reportes?
              </button>
            </h2>
            <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
              <div class="accordion-body">
                Ve a la sección "Reportes" y selecciona los filtros deseados (período, tipo de evento, métrica). Luego haz clic en "Generar Reporte". Puedes exportar los reportes en diferentes formatos usando las opciones de exportación.
              </div>
            </div>
          </div>
        </div>
      `;

      document.getElementById('ayudaModalTitle').textContent = 'Preguntas Frecuentes';
      document.getElementById('ayudaModalContent').innerHTML = contenido;
      modalAyuda.show();
    }

    function iniciarChatSoporte() {
      showToast('Iniciando chat con soporte...', 'info');
      // Aquí integrarías con tu servicio de chat en vivo
      setTimeout(() => {
        showToast('Chat iniciado. Un agente te atenderá pronto.', 'success');
      }, 1000);
    }

    function mostrarAyuda(tema) {
      const temas = {
        'crear-evento': {
          titulo: 'Cómo Crear un Nuevo Evento',
          contenido: `
            <h5>Paso a paso para crear un evento</h5>
            <ol>
              <li>Ve a la sección "Dashboard" o "Mis Eventos"</li>
              <li>Haz clic en el botón "Agregar Evento" o "Crear Nuevo Evento"</li>
              <li>Completa el formulario con:
                <ul>
                  <li>Nombre del evento</li>
                  <li>Fecha y hora</li>
                  <li>Lugar (presencial o virtual)</li>
                  <li>Descripción (opcional)</li>
                </ul>
              </li>
              <li>Configura las opciones adicionales si es necesario</li>
              <li>Haz clic en "Guardar"</li>
            </ol>
            <p class="mt-3"><strong>Consejo:</strong> Puedes duplicar eventos existentes para ahorrar tiempo.</p>
          `
        },
        'invitar-participantes': {
          titulo: 'Cómo Invitar Participantes',
          contenido: `
            <h5>Métodos para invitar participantes</h5>
            <div class="row mt-3">
              <div class="col-md-6">
                <h6>Individualmente</h6>
                <p>Ve a "Participantes" → "Agregar Participante"</p>
                <p>Completa los datos del participante manualmente</p>
              </div>
              <div class="col-md-6">
                <h6>Importar Lista</h6>
                <p>Prepara un archivo Excel o CSV con los datos</p>
                <p>Usa la opción "Importar Lista" para cargarlo</p>
              </div>
            </div>
            <div class="mt-3">
              <h6>Envío de Invitaciones</h6>
              <p>Puedes enviar invitaciones por email usando:</p>
              <ul>
                <li>Invitaciones individuales</li>
                <li>Email masivo a todos los participantes</li>
                <li>Recordatorios automáticos</li>
              </ul>
            </div>
          `
        },
        'exportar-datos': {
          titulo: 'Cómo Exportar Datos',
          contenido: `
            <h5>Formatos de exportación disponibles</h5>
            <div class="row mt-3">
              <div class="col-md-6">
                <div class="card">
                  <div class="card-body">
                    <h6><i class="fas fa-file-excel text-success"></i> Excel (.xlsx)</h6>
                    <p>Ideal para análisis y manipulación de datos</p>
                    <p>Mantiene formatos y fórmulas</p>
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="card">
                  <div class="card-body">
                    <h6><i class="fas fa-file-pdf text-danger"></i> PDF</h6>
                    <p>Perfecto para compartir y imprimir</p>
                    <p>Mantiene el formato visual</p>
                  </div>
                </div>
              </div>
            </div>
            <div class="mt-3">
              <h6>Qué datos puedes exportar:</h6>
              <ul>
                <li>Lista completa de eventos</li>
                <li>Participantes por evento</li>
                <li>Reportes estadísticos</li>
                <li>Datos analíticos completos</li>
              </ul>
            </div>
          `
        }
      };

      if (temas[tema]) {
        document.getElementById('ayudaModalTitle').textContent = temas[tema].titulo;
        document.getElementById('ayudaModalContent').innerHTML = temas[tema].contenido;
        modalAyuda.show();
      }
    }

    function enviarSolicitudSoporte() {
      const nombre = document.getElementById('nombreSoporte').value;
      const email = document.getElementById('emailSoporte').value;
      const asunto = document.getElementById('asuntoSoporte').value;
      const mensaje = document.getElementById('mensajeSoporte').value;

      if (!nombre || !email || !mensaje) {
        showToast('Por favor completa todos los campos requeridos', 'error');
        return;
      }

      // Aquí normalmente enviarías los datos a tu backend
      showToast('Solicitud de soporte enviada. Te contactaremos pronto.', 'success');

      // Limpiar formulario
      document.getElementById('formSoporte').reset();
    }

    /****************************
     * Funciones existentes (CRUD Eventos & Inscripciones)
     ****************************/
    function mostrarFormularioAgregar() {
      document.getElementById('formEvento').reset();
      document.getElementById('eventoId').value = '';
      document.getElementById('modalTitle').textContent = 'Agregar Evento';
      modalEvento.show();
    }

    function editarEvento(id) {
      const ev = eventosData.find(e => e.id == id);
      if (!ev) return;
      document.getElementById('eventoId').value = ev.id;
      document.getElementById('nombre').value = ev.nombre;
      document.getElementById('fecha').value = ev.fecha;
      document.getElementById('lugar').value = ev.lugar;
      document.getElementById('participacionInput').value = ev.participacion || 0;
      document.getElementById('modalTitle').textContent = 'Editar Evento';
      modalEvento.show();
    }

    function eliminarEvento(id) {
      if (!confirm('¿Seguro quieres eliminar este evento?')) return;
      const idx = eventosData.findIndex(e => e.id === id);
      if (idx !== -1) eventosData.splice(idx, 1);
      guardarEnLocalStorage();
      cargarEventos();
      cargarResumen();
      actualizarListaEventos();
      actualizarEstadisticasMisEventos();
      showToast('Evento eliminado', 'success');
    }

    function actualizarParticipacion(id, nuevoValor) {
      const idx = eventosData.findIndex(e => e.id == id);
      if (idx !== -1) {
        eventosData[idx].participacion = parseInt(nuevoValor) || 0;
        guardarEnLocalStorage();
        cargarResumen();
        cargarEventos();
        actualizarListaEventos();
        actualizarEstadisticasMisEventos();
      }
    }

    saveEventoBtn.addEventListener('click', () => {
      const form = document.getElementById('formEvento');
      if (!form.checkValidity()) {
        form.reportValidity();
        return;
      }
      const id = document.getElementById('eventoId').value;
      const nombre = document.getElementById('nombre').value.trim();
      const fecha = document.getElementById('fecha').value;
      const lugar = document.getElementById('lugar').value.trim();
      const participacion = parseInt(document.getElementById('participacionInput').value) || 0;

      if (id) {
        const i = eventosData.findIndex(e => e.id == id);
        if (i !== -1) eventosData[i] = {
          id: Number(id),
          nombre,
          fecha,
          lugar,
          participacion
        };
        showToast('Evento actualizado', 'success');
      } else {
        const newId = eventosData.length > 0 ? Math.max(...eventosData.map(e => e.id)) + 1 : 1;
        eventosData.push({
          id: newId,
          nombre,
          fecha,
          lugar,
          participacion
        });
        showToast('Evento creado', 'success');
      }
      guardarEnLocalStorage();
      modalEvento.hide();
      cargarEventos();
      cargarResumen();
      actualizarListaEventos();
      actualizarEstadisticasMisEventos();
    });

    function mostrarFormularioInscripcion() {
      document.getElementById('formInscripcion').reset();
      cargarSelectEventos();
      modalInscripcion.show();
    }

    saveInscripcionBtn.addEventListener('click', () => {
      const form = document.getElementById('formInscripcion');
      if (!form.checkValidity()) {
        form.reportValidity();
        return;
      }
      const id_evento = document.getElementById('id_evento').value;
      const ev = eventosData.find(e => e.id == id_evento);
      if (!ev) {
        alert('Evento no válido');
        return;
      }
      const nombre_usuario = document.getElementById('nombre_usuario').value.trim();
      const newId = participantesData.length > 0 ? Math.max(...participantesData.map(i => i.id)) + 1 : 1;
      const fecha_inscripcion = new Date().toISOString().split('T')[0];

      // Crear nuevo participante
      const nuevoParticipante = {
        id: newId,
        nombre: nombre_usuario,
        email: `${nombre_usuario.toLowerCase().replace(/\s+/g, '.')}@email.com`,
        telefono: '55' + Math.floor(10000000 + Math.random() * 90000000),
        id_evento: parseInt(id_evento),
        evento_nombre: ev.nombre,
        fecha_inscripcion: fecha_inscripcion,
        estado: 'pendiente'
      };

      participantesData.push(nuevoParticipante);

      // También agregar a inscripcionesData para compatibilidad
      inscripcionesData.push({
        id: newId,
        id_evento,
        evento_nombre: ev.nombre,
        nombre_usuario,
        fecha_inscripcion
      });

      guardarEnLocalStorage();
      modalInscripcion.hide();
      cargarInscripciones();
      cargarResumen();
      actualizarListaParticipantes();
      actualizarEstadisticasParticipantes();
      showToast('Inscripción creada', 'success');
    });

    function eliminarInscripcion(id) {
      if (!confirm('¿Seguro quieres eliminar esta inscripción?')) return;
      const idx = inscripcionesData.findIndex(i => i.id === id);
      if (idx !== -1) inscripcionesData.splice(idx, 1);

      // También eliminar de participantesData
      const idxPart = participantesData.findIndex(p => p.id === id);
      if (idxPart !== -1) participantesData.splice(idxPart, 1);

      guardarEnLocalStorage();
      cargarInscripciones();
      cargarResumen();
      actualizarListaParticipantes();
      actualizarEstadisticasParticipantes();
      showToast('Inscripción eliminada', 'success');
    }

    /****************************
     * Funciones existentes (Load / render)
     ****************************/
    function cargarResumen() {
      const pe = document.getElementById('proximos-eventos');
      const ui = document.getElementById('usuarios-inscritos');
      const part = document.getElementById('participacion');

      const totalEventos = eventosData.length;
      const totalIns = participantesData.length;
      const promedio = totalEventos > 0 ? (eventosData.reduce((a, e) => a + Number(e.participacion || 0), 0) / totalEventos).toFixed(1) : 0;

      if (pe) pe.textContent = totalEventos;
      if (ui) ui.textContent = totalIns;
      if (part) part.textContent = promedio + '%';
    }

    function cargarSelectEventos() {
      const select = document.getElementById('id_evento');
      if (!select) return;
      select.innerHTML = `<option value="" disabled selected>Seleccionar evento...</option>`;
      eventosData.forEach(ev => {
        const opt = document.createElement('option');
        opt.value = ev.id;
        opt.textContent = ev.nombre;
        select.appendChild(opt);
      });
    }

    function actualizarGrafico(labels, data) {
      const ctx = document.getElementById('grafico-participacion').getContext('2d');
      if (chart) chart.destroy();
      chart = new Chart(ctx, {
        type: 'bar',
        data: {
          labels,
          datasets: [{
            label: 'Participación (%)',
            data,
            backgroundColor: 'rgba(78,115,223,0.8)',
            borderColor: 'rgba(78,115,223,1)',
            borderWidth: 1
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          scales: {
            y: {
              beginAtZero: true,
              max: 100,
              title: {
                display: true,
                text: 'Porcentaje'
              }
            }
          },
          plugins: {
            legend: {
              display: false
            }
          }
        }
      });
    }

    function cargarEventos() {
      const tabla = document.getElementById('tabla-eventos');
      if (!tabla) return;
      tabla.innerHTML = '';
      const nombres = [];
      const participaciones = [];
      if (eventosData.length === 0) {
        tabla.innerHTML = `<tr><td colspan="6" class="text-center">No hay eventos registrados</td></tr>`;
      } else {
        eventosData.forEach(ev => {
          nombres.push(ev.nombre);
          participaciones.push(ev.participacion || 0);
          const tr = document.createElement('tr');
          tr.innerHTML = `
          <td>${ev.id}</td>
          <td>${ev.nombre}</td>
          <td>${ev.fecha}</td>
          <td>${ev.lugar}</td>
          <td>
            <div class="d-flex align-items-center">
              <input type="number" min="0" max="100" value="${ev.participacion||0}" class="form-control form-control-sm w-auto me-2" onchange="actualizarParticipacion(${ev.id}, this.value)">
              <span class="small">%</span>
            </div>
          </td>
          <td>
            <button class="btn btn-sm btn-primary btn-action" onclick="editarEvento(${ev.id})"><i class="fas fa-edit"></i></button>
            <button class="btn btn-sm btn-danger btn-action" onclick="eliminarEvento(${ev.id})"><i class="fas fa-trash"></i></button>
          </td>`;
          tabla.appendChild(tr);
        });
      }
      actualizarGrafico(nombres, participaciones);
      cargarSelectEventos();
    }

    function cargarInscripciones() {
      const tabla = document.getElementById('tabla-inscripciones');
      if (!tabla) return;
      tabla.innerHTML = '';
      if (inscripcionesData.length === 0) tabla.innerHTML = `<tr><td colspan="5" class="text-center">No hay inscripciones registradas</td></tr>`;
      else inscripcionesData.forEach(ins => {
        const tr = document.createElement('tr');
        tr.innerHTML = `<td>${ins.id}</td><td>${ins.evento_nombre}</td><td>${ins.nombre_usuario}</td><td>${ins.fecha_inscripcion}</td>
        <td><button class="btn btn-sm btn-danger btn-action" onclick="eliminarInscripcion(${ins.id})"><i class="fas fa-trash"></i></button></td>`;
        tabla.appendChild(tr);
      });
    }

    /****************************
     * Funciones de exportación
     ****************************/
    function exportEventos(format) {
      if (format === 'excel') {
        exportToExcel(eventosData, 'Eventos', 'Eventos.xlsx');
      } else if (format === 'pdf') {
        exportToPDF(eventosData, 'Eventos', 'Eventos.pdf');
      }
    }

    function exportInscripciones(format) {
      if (format === 'excel') {
        exportToExcel(inscripcionesData, 'Inscripciones', 'Inscripciones.xlsx');
      } else if (format === 'pdf') {
        exportToPDF(inscripcionesData, 'Inscripciones', 'Inscripciones.pdf');
      }
    }

    function exportAll() {
      showToast('Exportando todos los datos...', 'success', 2000);

      const wb = XLSX.utils.book_new();

      if (eventosData.length > 0) {
        const wsEventos = createFormattedWorksheet(eventosData, 'Eventos');
        XLSX.utils.book_append_sheet(wb, wsEventos, 'Eventos');
      }

      if (inscripcionesData.length > 0) {
        const wsInscripciones = createFormattedWorksheet(inscripcionesData, 'Inscripciones');
        XLSX.utils.book_append_sheet(wb, wsInscripciones, 'Inscripciones');
      }

      XLSX.writeFile(wb, 'Datos_Completos.xlsx');
      showToast('Archivo Excel con todos los datos descargado', 'success');
    }

    function createFormattedWorksheet(data, title) {
      const ws = XLSX.utils.json_to_sheet(data);
      const range = XLSX.utils.decode_range(ws['!ref']);

      for (let R = range.s.r; R <= range.e.r; R++) {
        for (let C = range.s.c; C <= range.e.c; C++) {
          const cell_address = {
            c: C,
            r: R
          };
          const cell_ref = XLSX.utils.encode_cell(cell_address);

          if (!ws[cell_ref]) continue;

          if (R === 0) {
            ws[cell_ref].s = {
              fill: {
                fgColor: {
                  rgb: "4E73DF"
                }
              },
              font: {
                bold: true,
                color: {
                  rgb: "FFFFFF"
                }
              },
              alignment: {
                horizontal: "center",
                vertical: "center"
              },
              border: {
                top: {
                  style: "thin",
                  color: {
                    rgb: "000000"
                  }
                },
                left: {
                  style: "thin",
                  color: {
                    rgb: "000000"
                  }
                },
                bottom: {
                  style: "thin",
                  color: {
                    rgb: "000000"
                  }
                },
                right: {
                  style: "thin",
                  color: {
                    rgb: "000000"
                  }
                }
              }
            };
          } else {
            ws[cell_ref].s = {
              font: {
                color: {
                  rgb: "2E2E2E"
                }
              },
              alignment: {
                vertical: "center"
              },
              border: {
                top: {
                  style: "thin",
                  color: {
                    rgb: "D0D0D0"
                  }
                },
                left: {
                  style: "thin",
                  color: {
                    rgb: "D0D0D0"
                  }
                },
                bottom: {
                  style: "thin",
                  color: {
                    rgb: "D0D0D0"
                  }
                },
                right: {
                  style: "thin",
                  color: {
                    rgb: "D0D0D0"
                  }
                }
              }
            };

            if (R % 2 === 0) {
              ws[cell_ref].s.fill = {
                fgColor: {
                  rgb: "F8F9FA"
                }
              };
            } else {
              ws[cell_ref].s.fill = {
                fgColor: {
                  rgb: "FFFFFF"
                }
              };
            }
          }
        }
      }

      const colWidths = [];
      const headers = Object.keys(data[0]);

      headers.forEach(header => {
        let maxLength = header.length;
        data.forEach(row => {
          const cellValue = row[header] ? String(row[header]) : '';
          if (cellValue.length > maxLength) maxLength = cellValue.length;
        });
        colWidths.push({
          wch: Math.min(maxLength + 2, 50)
        });
      });

      ws['!cols'] = colWidths;
      ws['!freeze'] = {
        x: 0,
        y: 1
      };

      return ws;
    }

    function exportToExcel(data, title, filename = 'export.xlsx') {
      if (!data || data.length === 0) {
        showToast(`No hay datos de ${title} para exportar`, 'error');
        return;
      }

      const wb = XLSX.utils.book_new();
      const ws = createFormattedWorksheet(data, title);
      XLSX.utils.book_append_sheet(wb, ws, title);
      XLSX.writeFile(wb, filename);
      showToast(`${title} Excel descargado`, 'success');
    }

    function exportToPDF(data, title, filename = 'export.pdf') {
      if (!data || data.length === 0) {
        showToast(`No hay datos de ${title} para exportar`, 'error');
        return;
      }

      const {
        jsPDF
      } = window.jspdf;
      const doc = new jsPDF();

      doc.setFontSize(16);
      doc.setTextColor(40, 40, 40);
      doc.text(title, 14, 16);

      doc.setFontSize(10);
      doc.setTextColor(100, 100, 100);
      const exportDate = new Date().toLocaleDateString('es-ES');
      doc.text(`Exportado el: ${exportDate}`, 14, 24);

      const headers = Object.keys(data[0]);
      const body = data.map(item => headers.map(header => item[header]));

      doc.autoTable({
        startY: 30,
        head: [headers],
        body: body,
        styles: {
          fontSize: 9,
          cellPadding: 3,
          lineColor: [78, 115, 223],
          lineWidth: 0.1
        },
        headStyles: {
          fillColor: [78, 115, 223],
          textColor: 255,
          fontStyle: 'bold'
        },
        alternateRowStyles: {
          fillColor: [245, 245, 245]
        },
        margin: {
          top: 30
        }
      });

      doc.save(filename);
      showToast(`${title} PDF descargado`, 'success');
    }

    // Event listeners para exportación general
    document.getElementById('expExcel').addEventListener('click', (e) => {
      e.preventDefault();
      exportEventos('excel');
    });
    document.getElementById('expPdf').addEventListener('click', (e) => {
      e.preventDefault();
      exportEventos('pdf');
    });
    document.getElementById('expAll').addEventListener('click', (e) => {
      e.preventDefault();
      exportAll();
    });

    /****************************
     * Search & Filter
     ****************************/
    document.getElementById('searchGlobal').addEventListener('input', (e) => {
      const q = e.target.value.toLowerCase().trim();
      filterTables(q);
    });

    document.getElementById('filterEventos').addEventListener('input', (e) => {
      const q = e.target.value.toLowerCase().trim();
      filterEventos(q);
    });

    // Agregar event listeners para filtros de nuevas secciones
    document.getElementById('searchMisEventos')?.addEventListener('input', () => actualizarListaEventos());
    document.getElementById('filterEstado')?.addEventListener('change', () => actualizarListaEventos());
    document.getElementById('sortEventos')?.addEventListener('change', () => actualizarListaEventos());

    document.getElementById('searchParticipantes')?.addEventListener('input', () => actualizarListaParticipantes());
    document.getElementById('filterEventoParticipante')?.addEventListener('change', () => actualizarListaParticipantes());
    document.getElementById('filterEstadoParticipante')?.addEventListener('change', () => actualizarListaParticipantes());

    function filterTables(query) {
      filterEventos(query);
      filterInscripciones(query);
    }

    function filterEventos(query) {
      const tbody = document.getElementById('tabla-eventos');
      if (!tbody) return;
      Array.from(tbody.children).forEach(tr => {
        const text = tr.textContent.toLowerCase();
        tr.style.display = text.includes(query) ? '' : 'none';
      });
    }

    function filterInscripciones(query) {
      const tbody = document.getElementById('tabla-inscripciones');
      if (!tbody) return;
      Array.from(tbody.children).forEach(tr => {
        const text = tr.textContent.toLowerCase();
        tr.style.display = text.includes(query) ? '' : 'none';
      });
    }

    /****************************
     * Initialization & window resize handlers
     ****************************/
    function init() {
      // Aplicar estado del sidebar según el tamaño de pantalla
      if (window.innerWidth >= 992) {
        // Desktop - aplicar estado guardado
        if (sidebarState === 'closed') {
          sidebar.classList.add('hidden');
          document.getElementById('content').classList.add('fullwidth');
          sidebarToggle.classList.add('rotated');
        } else {
          sidebar.classList.remove('hidden');
          document.getElementById('content').classList.remove('fullwidth');
          sidebarToggle.classList.remove('rotated');
        }
      } else {
        // Mobile - sidebar siempre oculto inicialmente
        sidebar.classList.add('hidden');
      }

      // Inicializar navegación
      initNavigation();

      // Cargar datos existentes
      cargarResumen();
      cargarEventos();
      cargarInscripciones();
      cargarSelectEventos();

      // Inicializar nuevas secciones
      actualizarEstadisticasMisEventos();
      actualizarListaEventos();
      actualizarEstadisticasParticipantes();
      actualizarListaParticipantes();
      cargarSelectEventosParticipantes();

      // Generar reporte inicial
      generarReporte();
    }

    window.addEventListener('resize', () => {
      if (window.innerWidth >= 992) {
        // Desktop
        overlay.classList.remove('show');
        sidebar.classList.remove('visible');
        // Aplicar estado guardado
        if (sidebarState === 'closed') {
          sidebar.classList.add('hidden');
          document.getElementById('content').classList.add('fullwidth');
          sidebarToggle.classList.add('rotated');
        } else {
          sidebar.classList.remove('hidden');
          document.getElementById('content').classList.remove('fullwidth');
          sidebarToggle.classList.remove('rotated');
        }
      } else {
        // Mobile
        sidebar.classList.add('hidden');
        document.getElementById('content').classList.remove('fullwidth');
        sidebarToggle.classList.remove('rotated');
      }
    });

    // Exponer funciones globalmente
    window.mostrarFormularioAgregar = mostrarFormularioAgregar;
    window.mostrarFormularioInscripcion = mostrarFormularioInscripcion;
    window.actualizarParticipacion = actualizarParticipacion;
    window.editarEvento = editarEvento;
    window.eliminarEvento = eliminarEvento;
    window.eliminarInscripcion = eliminarInscripcion;
    window.exportEventos = exportEventos;
    window.exportInscripciones = exportInscripciones;

    /****************************
     * FUNCIONES DEL CALENDARIO
     ****************************/
    let fechaCalendario = new Date();
    let vistaCalendario = 'mes';

    function generarCalendario(fecha, vista = 'mes') {
      const year = fecha.getFullYear();
      const month = fecha.getMonth();

      const titulo = document.getElementById('calendario-titulo');
      const grid = document.getElementById('calendario-grid');
      const listaEventos = document.getElementById('lista-eventos-dia');

      // Actualizar título
      const nombresMeses = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
      titulo.textContent = `${nombresMeses[month]} ${year}`;

      if (vista === 'mes') {
        // Primera línea: días de la semana
        const diasSemana = ['Dom', 'Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb'];
        let html = '<table class="table table-bordered table-hover">';
        html += '<thead><tr>';
        diasSemana.forEach(dia => {
          html += `<th class="text-center">${dia}</th>`;
        });
        html += '</tr></thead><tbody>';

        // Obtener primer día del mes
        const primerDia = new Date(year, month, 1);
        const ultimoDia = new Date(year, month + 1, 0);
        const diaInicio = primerDia.getDay(); // 0=domingo

        // Crear celdas
        html += '<tr>';
        // Espacios en blanco antes del primer día
        for (let i = 0; i < diaInicio; i++) {
          html += '<td class="text-center text-muted"></td>';
        }

        // Días del mes
        for (let dia = 1; dia <= ultimoDia.getDate(); dia++) {
          const fechaActual = new Date(year, month, dia);
          const fechaStr = fechaActual.toISOString().split('T')[0];

          // Verificar si hay eventos en este día
          const eventosDelDia = eventosData.filter(ev => ev.fecha === fechaStr);
          const tieneEvento = eventosDelDia.length > 0;
          const esHoy = fechaActual.toDateString() === new Date().toDateString();

          html += `<td class="text-center ${esHoy ? 'table-primary' : ''}" 
                          style="cursor: pointer; position: relative; height: 60px;"
                          onclick="mostrarEventosDelDia('${fechaStr}')">`;
          html += `<div>${dia}</div>`;

          if (tieneEvento) {
            html += `<div class="mt-1">`;
            eventosDelDia.slice(0, 2).forEach(ev => {
              html += `<span class="badge bg-primary me-1" style="font-size: 0.6rem;">${ev.nombre.substring(0, 10)}</span>`;
            });
            if (eventosDelDia.length > 2) {
              html += `<span class="badge bg-secondary" style="font-size: 0.6rem;">+${eventosDelDia.length - 2}</span>`;
            }
            html += `</div>`;
          }

          html += '</td>';

          // Salto de línea cada 7 días
          if ((dia + diaInicio) % 7 === 0 && dia < ultimoDia.getDate()) {
            html += '</tr><tr>';
          }
        }

        // Completar última fila
        const ultimoDiaSemana = ultimoDia.getDay();
        for (let i = ultimoDiaSemana + 1; i < 7; i++) {
          html += '<td class="text-center text-muted"></td>';
        }

        html += '</tr></tbody></table>';
        grid.innerHTML = html;
      } else if (vista === 'semana') {
        // Implementación para vista semanal
        const inicioSemana = new Date(fecha);
        inicioSemana.setDate(fecha.getDate() - fecha.getDay());
        const finSemana = new Date(inicioSemana);
        finSemana.setDate(inicioSemana.getDate() + 6);

        let html = '<table class="table table-bordered">';
        html += '<thead><tr>';
        const diasSemana = ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];
        diasSemana.forEach((dia, idx) => {
          const fechaDia = new Date(inicioSemana);
          fechaDia.setDate(inicioSemana.getDate() + idx);
          const esHoy = fechaDia.toDateString() === new Date().toDateString();
          html += `<th class="text-center ${esHoy ? 'table-primary' : ''}">
                        ${dia}<br>
                        <small>${fechaDia.getDate()}/${fechaDia.getMonth() + 1}</small>
                    </th>`;
        });
        html += '</tr></thead><tbody>';
        html += '<tr>';

        for (let i = 0; i < 7; i++) {
          const fechaDia = new Date(inicioSemana);
          fechaDia.setDate(inicioSemana.getDate() + i);
          const fechaStr = fechaDia.toISOString().split('T')[0];
          const eventosDelDia = eventosData.filter(ev => ev.fecha === fechaStr);

          html += `<td style="height: 100px; vertical-align: top; cursor: pointer;" 
                       onclick="mostrarEventosDelDia('${fechaStr}')">`;
          if (eventosDelDia.length > 0) {
            eventosDelDia.forEach(ev => {
              html += `<div class="badge bg-primary mb-1 d-block" style="font-size: 0.7rem;">${ev.nombre}</div>`;
            });
          } else {
            html += '<span class="text-muted">Sin eventos</span>';
          }
          html += '</td>';
        }

        html += '</tr></tbody></table>';
        grid.innerHTML = html;
      }
    }

    function cambiarMesCalendario(delta) {
      fechaCalendario.setMonth(fechaCalendario.getMonth() + delta);
      generarCalendario(fechaCalendario, vistaCalendario);
    }

    function cambiarVistaCalendario(vista) {
      vistaCalendario = vista;
      generarCalendario(fechaCalendario, vista);
    }

    function mostrarEventosDelDia(fechaStr) {
      const eventosDelDia = eventosData.filter(ev => ev.fecha === fechaStr);
      const lista = document.getElementById('lista-eventos-dia');

      if (eventosDelDia.length === 0) {
        lista.innerHTML = `<p class="text-muted">No hay eventos para esta fecha</p>`;
        return;
      }

      let html = '';
      eventosDelDia.forEach(ev => {
        html += `
            <div class="list-group-item d-flex justify-content-between align-items-center">
                <div>
                    <strong>${ev.nombre}</strong>
                    <span class="text-muted ms-2">${ev.lugar}</span>
                </div>
                <div>
                    <span class="badge bg-primary">${ev.participacion || 0}%</span>
                    <button class="btn btn-sm btn-outline-secondary ms-2" onclick="editarEvento(${ev.id})">
                        <i class="fas fa-edit"></i>
                    </button>
                </div>
            </div>
        `;
      });
      lista.innerHTML = html;
    }

    // Inicializar calendario
    document.addEventListener('DOMContentLoaded', function() {
      generarCalendario(fechaCalendario, 'mes');
    });





    // Inicializar
    init();

    /****************************
     * Accessibility: close sidebar with ESC
     ****************************/
    window.addEventListener('keydown', (e) => {
      if (e.key === 'Escape') {
        if (sidebar.classList.contains('visible')) closeSidebarMobile();
      }
    });
  </script>

  <!-- Gráfico 1 -->
  <script>
    document.addEventListener("DOMContentLoaded", function() {

      const ctx = document.getElementById("graficoPersonas");

      if (!ctx) return;

      new Chart(ctx, {
        type: "pie",
        data: {
          labels: <?php echo json_encode($tiposPersona); ?>,
          datasets: [{
            label: "Personas por Tipo",
            data: <?php echo json_encode($totalesPersona); ?>,
            borderWidth: 1
          }]
        },
        options: {
          responsive: true,
          plugins: {
            legend: {
              position: "bottom"
            },
            title: {
              display: true,
              text: "Distribución de Personas"
            }
          }
        }
      });

    });
  </script>

  <!-- Gráfico 2 -->
  <script>
    document.addEventListener("DOMContentLoaded", function() {
      const ctxEventos = document.getElementById("graficoEventosMes");
      if (!ctxEventos) return;
      new Chart(ctxEventos, {
        type: "bar",
        data: {
          labels: <?php echo json_encode($meses); ?>,
          datasets: [{
            label: "Eventos registrados",
            data: <?php echo json_encode($datosEventos); ?>,
            backgroundColor: "rgba(54, 162, 235, 0.7)",
            borderColor: "rgba(54, 162, 235, 1)",
            borderWidth: 1
          }]
        },

        options: {
          responsive: true,
          scales: {

            y: {

              beginAtZero: true,
              ticks: {
                precision: 0
              }
            }
          },

          plugins: {
            legend: {
              display: true
            },

            title: {
              display: true,
              text: "Eventos registrados por mes"
            }
          }
        }
      });

    });
  </script>

  <!-- Gráfico 3 -->
  <script>
    document.addEventListener("DOMContentLoaded", function() {
      const ctxInscripciones = document.getElementById('graficoInscripcionesActividad');
      if (!ctxInscripciones) return;
      new Chart(ctxInscripciones, {
        type: 'bar',
        data: {
          labels: <?php echo json_encode($actividades); ?>,
          datasets: [{
            label: 'Inscripciones',
            data: <?php echo json_encode($totalInscripcionesActividad); ?>,
            backgroundColor: 'rgba(255, 159, 64, 0.7)',
            borderColor: 'rgba(255, 159, 64, 1)',
            borderWidth: 1

          }]

        },

        options: {
          responsive: true,
          plugins: {
            legend: {
              display: true
            }
          },

          scales: {
            y: {
              beginAtZero: true,
              ticks: {
                precision: 0
              }
            }
          }
        }
      });
    });
  </script>






</body>

</html>