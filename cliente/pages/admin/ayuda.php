<?php
declare(strict_types=1);
if (!isset($_SESSION['usuario']) || !in_array($_SESSION['tipo_persona'], ['Administrativo', 'Encargado'])) {
    header("Location: " . url('/login-admin'));
    exit();
}
$pageTitle = "Ayuda - Centro de Ayuda";
$pageStyles = ['cliente/assets/css/ayuda.css'];
ob_start();
?>
<div class="container-fluid py-3 ayuda-page">
    <div class="page-head mb-4">
        <h3><i class="fas fa-question-circle me-2"></i>Centro de Ayuda</h3>
        <p class="text-muted mb-0">Guía completa del sistema administrativo del Oratorio Litúrgico</p>
    </div>

    <!-- NAVEGACIÓN POR SECCIONES -->
    <ul class="nav ayuda-nav" id="ayudaNav">
        <li><a class="nav-link active" data-target="general">General</a></li>
        <li><a class="nav-link" data-target="dashboard">Dashboard</a></li>
        <li><a class="nav-link" data-target="eventos">Eventos</a></li>
        <li><a class="nav-link" data-target="actividades">Actividades</a></li>
        <li><a class="nav-link" data-target="inscripciones">Inscripciones</a></li>
        <li><a class="nav-link" data-target="asistencias">Asistencias</a></li>
        <li><a class="nav-link" data-target="personas">Personas</a></li>
        <li><a class="nav-link" data-target="roles">Roles</a></li>
        <li><a class="nav-link" data-target="universidades">Universidades</a></li>
        <li><a class="nav-link" data-target="sacramentos">Sacramentos</a></li>
        <li><a class="nav-link" data-target="paneles">Paneles</a></li>
        <li><a class="nav-link" data-target="reportes">Reportes</a></li>
        <li><a class="nav-link" data-target="faq">Preguntas Frecuentes</a></li>
        <li><a class="nav-link" data-target="contacto">Contacto</a></li>
    </ul>

    <!-- ========== GENERAL ========== -->
    <div class="ayuda-seccion active" id="sec-general">
        <div class="section-card">
            <h5><i class="fas fa-info-circle"></i> ¿Qué es este sistema?</h5>
            <p>El <strong>Sistema de Gestión del Oratorio Litúrgico</strong> es una plataforma web administrativa diseñada para gestionar eventos, actividades, inscripciones, asistencias, pagos, personas, sacramentos y reportes de un oratorio religioso.</p>
        </div>

        <div class="section-card">
            <h5><i class="fas fa-bolt"></i> Accesos Rápidos</h5>
            <div class="atajos-grid">
                <a href="<?= url('/dashboard') ?>" class="atajo-card"><i class="fas fa-chart-line"></i><h6>Dashboard</h6><p>Resumen general</p></a>
                <a href="<?= url('/eventos') ?>" class="atajo-card"><i class="fas fa-calendar-days"></i><h6>Eventos</h6><p>Gestionar eventos</p></a>
                <a href="<?= url('/actividades') ?>" class="atajo-card"><i class="fas fa-tasks"></i><h6>Actividades</h6><p>Gestionar actividades</p></a>
                <a href="<?= url('/inscripcion') ?>" class="atajo-card"><i class="fas fa-clipboard-list"></i><h6>Inscripciones</h6><p>Inscribir participantes</p></a>
                <a href="<?= url('/personas') ?>" class="atajo-card"><i class="fas fa-id-card"></i><h6>Personas</h6><p>Administrar personas</p></a>
                <a href="<?= url('/sacramentos') ?>" class="atajo-card"><i class="fas fa-church"></i><h6>Sacramentos</h6><p>Formación sacramental</p></a>
                <a href="<?= url('/reportes/eventos') ?>" class="atajo-card"><i class="fas fa-file-invoice"></i><h6>Reportes</h6><p>Generar reportes</p></a>
            </div>
        </div>

        <div class="section-card">
            <h5><i class="fas fa-lightbulb"></i> Consejos Generales</h5>
            <ul>
                <li>Use la <strong>barra de búsqueda</strong> en cada módulo para encontrar registros rápidamente.</li>
                <li>Los <strong>filtros</strong> le permiten refinar la información por estado, categoría u otros criterios.</li>
                <li>Antes de eliminar un registro, verifique que no tenga datos dependientes (inscripciones, pagos, etc.).</li>
                <li>Los campos marcados con <span class="text-danger">*</span> son <strong>obligatorios</strong>.</li>
                <li>Puede cambiar entre <strong>tema claro y oscuro</strong> usando el botón de sol/luna en el sidebar.</li>
            </ul>
        </div>
    </div>

    <!-- ========== DASHBOARD ========== -->
    <div class="ayuda-seccion" id="sec-dashboard">
        <div class="section-card">
            <h5><i class="fas fa-chart-line"></i> Dashboard Principal</h5>
            <p>El Dashboard es la página principal que muestra un resumen completo del estado del sistema.</p>
        </div>
        <div class="section-card">
            <h5><i class="fas fa-chart-bar"></i> Métricas Principales</h5>
            <ul>
                <li><strong>Total Personas:</strong> Cantidad de personas registradas en el sistema.</li>
                <li><strong>Eventos Activos:</strong> Eventos con estado "Activo" vigentes.</li>
                <li><strong>Inscripciones:</strong> Total de inscripciones realizadas.</li>
                <li><strong>Pagos Recientes:</strong> Pagos registrados en los últimos 30 días.</li>
            </ul>
        </div>
        <div class="section-card">
            <h5><i class="fas fa-chart-pie"></i> Gráficos del Dashboard</h5>
            <ul>
                <li><strong>Distribución por Género:</strong> Gráfico circular que muestra la proporción de personas por género.</li>
                <li><strong>Inscripciones por Mes:</strong> Gráfico de barras con la tendencia de inscripciones.</li>
                <li><strong>Top 5 Actividades:</strong> Las 5 actividades con más inscritos.</li>
                <li><strong>Pagos Recientes:</strong> Tabla con los últimos pagos registrados.</li>
            </ul>
        </div>
    </div>

    <!-- ========== EVENTOS ========== -->
    <div class="ayuda-seccion" id="sec-eventos">
        <div class="section-card">
            <h5><i class="fas fa-calendar-days"></i> Gestión de Eventos</h5>
            <p>Los eventos representan las actividades principales del oratorio: retiros, convenciones, actividades parroquiales, etc.</p>
        </div>
        <div class="section-card">
            <h5><i class="fas fa-list"></i> Listar Eventos</h5>
            <div class="paso"><span class="paso-num">1</span><span class="paso-text">Acceda a <strong>Gestión → Eventos</strong> en el menú lateral.</span></div>
            <div class="paso"><span class="paso-num">2</span><span class="paso-text">Use el <strong>buscador</strong> para filtrar por nombre o descripción.</span></div>
            <div class="paso"><span class="paso-num">3</span><span class="paso-text">Haga clic en <strong>"Nuevo Evento"</strong> para crear uno nuevo.</span></div>
            <div class="paso"><span class="paso-num">4</span><span class="paso-text">Use los botones de <strong>editar (lápiz)</strong> o <strong>eliminar (basura)</strong> en cada fila.</span></div>
        </div>
        <div class="section-card">
            <h5><i class="fas fa-plus-circle"></i> Crear / Editar Evento</h5>
            <ul>
                <li><strong>Nombre del evento *</strong>: Nombre descriptivo del evento.</li>
                <li><strong>Descripción</strong>: Detalles adicionales del evento.</li>
                <li><strong>Estado *</strong>: Activo, Inactivo o Cancelado.</li>
                <li><strong>Fecha del evento *</strong>: Fecha programada del evento.</li>
            </ul>
        </div>
    </div>

    <!-- ========== ACTIVIDADES ========== -->
    <div class="ayuda-seccion" id="sec-actividades">
        <div class="section-card">
            <h5><i class="fas fa-tasks"></i> Gestión de Actividades</h5>
            <p>Las actividades son las sesiones o componentes dentro de un evento. Cada actividad puede tener su propio horario, cupo y requisitos.</p>
        </div>
        <div class="section-card">
            <h5><i class="fas fa-list"></i> Listar Actividades</h5>
            <div class="paso"><span class="paso-num">1</span><span class="paso-text">Acceda a <strong>Gestión → Actividades</strong>.</span></div>
            <div class="paso"><span class="paso-num">2</span><span class="paso-text">Filtre por nombre de actividad o evento asociado.</span></div>
            <div class="paso"><span class="paso-num">3</span><span class="paso-text">Cree una nueva actividad con <strong>"Nueva Actividad"</strong>.</span></div>
        </div>
        <div class="section-card">
            <h5><i class="fas fa-plus-circle"></i> Crear / Editar Actividad</h5>
            <ul>
                <li><strong>Nombre *</strong>: Nombre de la actividad.</li>
                <li><strong>Evento *</strong>: Evento al que pertenece.</li>
                <li><strong>Tipo</strong>: Tipo de actividad (Taller, Charla, etc.).</li>
                <li><strong>Fecha Inicio / Fin *</strong>: Período de duración.</li>
                <li><strong>Días de la semana</strong>: Seleccione los días (checkboxes).</li>
                <li><strong>Cupo máximo</strong>: Número máximo de participantes.</li>
                <li><strong>Precio *</strong>: Costo de la actividad (0 si es gratuita).</li>
                <li><strong>Requisitos</strong>: Requisitos para participar.</li>
                <li><strong>Estado *</strong>: Activo, Cancelado, Completado o En espera.</li>
            </ul>
        </div>
    </div>

    <!-- ========== INSCRIPCIONES ========== -->
    <div class="ayuda-seccion" id="sec-inscripciones">
        <div class="section-card">
            <h5><i class="fas fa-clipboard-list"></i> Gestión de Inscripciones</h5>
            <p>Las inscripciones registran la participación de personas en actividades. Desde aquí puede editar o cancelar inscripciones existentes.</p>
        </div>
        <div class="section-card">
            <h5><i class="fas fa-list"></i> Listar Inscripciones</h5>
            <div class="paso"><span class="paso-num">1</span><span class="paso-text">Acceda a <strong>Gestión → Inscripciones</strong>.</span></div>
            <div class="paso"><span class="paso-num">2</span><span class="paso-text">Filtre por nombre de persona o actividad.</span></div>
            <div class="paso"><span class="paso-num">3</span><span class="paso-text">Haga clic en <strong>editar</strong> para modificar el estado o la asistencia.</span></div>
        </div>
        <div class="section-card">
            <h5><i class="fas fa-edit"></i> Editar Inscripción</h5>
            <ul>
                <li><strong>Estado</strong>: Pre-inscrito, Inscrito, En espera, Cancelado o Completado.</li>
                <li><strong>Cumple requisitos</strong>: Si, No o En revisión.</li>
                <li><strong>Asistencia</strong>: Número de asistencias registradas.</li>
                <li><strong>Observaciones</strong>: Notas adicionales sobre la inscripción.</li>
            </ul>
            <p class="mt-2"><small class="text-muted"><i class="fas fa-info-circle me-1"></i>Las inscripciones se crean automáticamente al registrar una persona en una actividad desde el formulario de la actividad.</small></p>
        </div>
    </div>

    <!-- ========== ASISTENCIAS ========== -->
    <div class="ayuda-seccion" id="sec-asistencias">
        <div class="section-card">
            <h5><i class="fas fa-user-check"></i> Gestión de Asistencias</h5>
            <p>Registre y administre la asistencia de los participantes a las actividades del oratorio.</p>
        </div>
        <div class="section-card">
            <h5><i class="fas fa-list"></i> Listar Asistencias</h5>
            <div class="paso"><span class="paso-num">1</span><span class="paso-text">Acceda a <strong>Gestión → Asistencias</strong>.</span></div>
            <div class="paso"><span class="paso-num">2</span><span class="paso-text">Filtre por nombre de participante o actividad.</span></div>
            <div class="paso"><span class="paso-num">3</span><span class="paso-text">Cree una nueva asistencia con <strong>"Nueva Asistencia"</strong>.</span></div>
        </div>
        <div class="section-card">
            <h5><i class="fas fa-plus-circle"></i> Registrar Asistencia</h5>
            <ul>
                <li><strong>Persona *</strong>: Seleccione la persona que asistió.</li>
                <li><strong>Actividad *</strong>: Actividad a la que asistió.</li>
                <li><strong>Fecha *</strong>: Fecha de la asistencia.</li>
                <li><strong>Asistió *</strong>: Si, No o Justificado.</li>
                <li><strong>Observaciones</strong>: Notas adicionales.</li>
            </ul>
        </div>
    </div>

    <!-- ========== PERSONAS ========== -->
    <div class="ayuda-seccion" id="sec-personas">
        <div class="section-card">
            <h5><i class="fas fa-id-card"></i> Gestión de Personas</h5>
            <p>Administre el registro de personas que interactúan con el oratorio: participantes, docentes, voluntarios, etc.</p>
        </div>
        <div class="section-card">
            <h5><i class="fas fa-list"></i> Listar Personas</h5>
            <div class="paso"><span class="paso-num">1</span><span class="paso-text">Acceda a <strong>Personas y Usuarios → Personas</strong>.</span></div>
            <div class="paso"><span class="paso-num">2</span><span class="paso-text">Filtre por <strong>nombre/CI</strong> o seleccione un <strong>rol</strong> del menú desplegable.</span></div>
            <div class="paso"><span class="paso-num">3</span><span class="paso-text">Cree una nueva persona con <strong>"Nueva Persona"</strong>.</span></div>
        </div>
        <div class="section-card">
            <h5><i class="fas fa-plus-circle"></i> Crear / Editar Persona</h5>
            <ul>
                <li><strong>Nombres *</strong>: Nombre completo.</li>
                <li><strong>Apellidos *</strong>: Apellidos completos.</li>
                <li><strong>CI *</strong>: Cédula de identidad (único).</li>
                <li><strong>Género *</strong>: Masculino, Femenino o No binario.</li>
                <li><strong>Fecha de nacimiento *</strong>: Debe ser mayor de 12 años.</li>
                <li><strong>Lugar de nacimiento</strong>: Ciudad de nacimiento.</li>
                <li><strong>Estado civil *</strong>: Soltero, Casado, Divorciado o Viudo.</li>
                <li><strong>Estudios *</strong>: Secundaria, Universitario, Técnico, Doctorado, Maestría.</li>
                <li><strong>Profesión</strong>: Ocupación actual.</li>
                <li><strong>Correo electrónico *</strong>: Debe contener @.</li>
                <li><strong>Teléfono *</strong>: Mínimo 8 dígitos.</li>
                <li><strong>Parroquia *</strong>: Parroquia a la que pertenece.</li>
                <li><strong>Universidad</strong>: Universidad asociada (opcional).</li>
                <li><strong>Dirección</strong>: Dirección de residencia.</li>
                <li><strong>Rol del sistema</strong>: Asigne un rol si la persona será usuario del sistema (opcional).</li>
            </ul>
        </div>
        <div class="section-card">
            <h5><i class="fas fa-exclamation-triangle"></i> Notas Importantes</h5>
            <ul>
                <li>El <strong>CI</strong> debe ser único. Si ya existe otro registro con ese CI, se mostrará un error.</li>
                <li>El <strong>rol</strong> es opcional. Si no se asigna, la persona no podrá acceder al sistema.</li>
                <li>Si asigna un rol existente, se actualizará; si asigna uno nuevo, se creará un registro en <strong>usuarios_sistema</strong>.</li>
            </ul>
        </div>
    </div>

    <!-- ========== ROLES ========== -->
    <div class="ayuda-seccion" id="sec-roles">
        <div class="section-card">
            <h5><i class="fas fa-user-cog"></i> Gestión de Roles del Sistema</h5>
            <p>Los roles controlan qué puede hacer cada usuario dentro del sistema. Cada rol tiene permisos específicos.</p>
        </div>
        <div class="section-card">
            <h5><i class="fas fa-list"></i> Roles Disponibles</h5>
            <ul>
                <li><strong>Administrador</strong>: Acceso total al sistema. Puede crear, editar, eliminar y ver todo.</li>
                <li><strong>Coordinador</strong>: Puede gestionar eventos, actividades, inscripciones y asistencias.</li>
                <li><strong>Estudiante</strong>: Acceso limitado. Puede inscribirse en actividades y ver su información.</li>
                <li><strong>Docente</strong>: Puede gestionar actividades y ver asistencias de sus cursos.</li>
                <li><strong>Voluntario</strong>: Acceso básico para participación en eventos.</li>
                <li><strong>Sacerdote</strong>: Acceso para gestión de sacramentos y eventos litúrgicos.</li>
                <li><strong>Externo</strong>: Acceso mínimo para consultas generales.</li>
            </ul>
        </div>
        <div class="section-card">
            <h5><i class="fas fa-plus-circle"></i> Crear / Editar Rol</h5>
            <div class="paso"><span class="paso-num">1</span><span class="paso-text">Acceda a <strong>Personas y Usuarios → Roles del Sistema</strong>.</span></div>
            <div class="paso"><span class="paso-num">2</span><span class="paso-text">Haga clic en <strong>"Nuevo Rol"</strong> para crear uno nuevo.</span></div>
            <div class="paso"><span class="paso-num">3</span><span class="paso-text">Complete los campos: <strong>Nombre *</strong>, <strong>Estado *</strong> (Activo/Inactivo), <strong>Contraseña *</strong>.</span></div>
        </div>
        <div class="section-card">
            <h5><i class="fas fa-exclamation-triangle"></i> Notas Importantes</h5>
            <ul>
                <li>Los roles son <strong>estándar</strong> del sistema (no se pueden crear roles personalizados).</li>
                <li>No puede eliminar un rol que esté siendo usado por personas en el sistema.</li>
                <li>La contraseña se almacena de forma segura (hash). No se puede recuperar, solo restablecer.</li>
            </ul>
        </div>
    </div>

    <!-- ========== UNIVERSIDADES ========== -->
    <div class="ayuda-seccion" id="sec-universidades">
        <div class="section-card">
            <h5><i class="fas fa-university"></i> Gestión de Universidades</h5>
            <p>Administre las universidades asociadas al oratorio. Las universidades se vinculan a las personas como parte de su información académica.</p>
        </div>
        <div class="section-card">
            <h5><i class="fas fa-list"></i> Listar Universidades</h5>
            <div class="paso"><span class="paso-num">1</span><span class="paso-text">Acceda a <strong>Personas y Usuarios → Universidades</strong>.</span></div>
            <div class="paso"><span class="paso-num">2</span><span class="paso-text">Filtre por nombre de universidad.</span></div>
            <div class="paso"><span class="paso-num">3</span><span class="paso-text">Cree una nueva universidad con <strong>"Nueva Universidad"</strong>.</span></div>
        </div>
        <div class="section-card">
            <h5><i class="fas fa-plus-circle"></i> Crear / Editar Universidad</h5>
            <ul>
                <li><strong>Nombre *</strong>: Nombre completo de la universidad.</li>
                <li><strong>Siglas</strong>: Acrónimo o siglas (ej: UCB, UMSA).</li>
                <li><strong>País *</strong>: País donde se encuentra.</li>
                <li><strong>Ciudad *</strong>: Ciudad de la universidad.</li>
                <li><strong>Estado *</strong>: Activo o Inactivo.</li>
            </ul>
        </div>
    </div>

    <!-- ========== SACRAMENTOS ========== -->
    <div class="ayuda-seccion" id="sec-sacramentos">
        <div class="section-card">
            <h5><i class="fas fa-church"></i> Gestión de Sacramentos</h5>
            <p>Administre las inscripciones de formación sacramental: Bautizo, Primera Comunión, Confirmación, Matrimonio, Penitencia y Unción de los Enfermos.</p>
        </div>
        <div class="section-card">
            <h5><i class="fas fa-list"></i> Listar Sacramentos</h5>
            <div class="paso"><span class="paso-num">1</span><span class="paso-text">Acceda a <strong>Sacramentos</strong> en el menú lateral.</span></div>
            <div class="paso"><span class="paso-num">2</span><span class="paso-text">Filtre por nombre del solicitante.</span></div>
            <div class="paso"><span class="paso-num">3</span><span class="paso-text">Cree un nuevo registro con <strong>"Nuevo Sacramento"</strong>.</span></div>
        </div>
        <div class="section-card">
            <h5><i class="fas fa-plus-circle"></i> Crear / Editar Registro</h5>
            <ul>
                <li><strong>Sacramento *</strong>: Bautizo, Primera Comunión, Confirmación, Matrimonio, Penitencia o Unción de los Enfermos.</li>
                <li><strong>Nombre solicitante *</strong>: Nombre completo del solicitante.</li>
                <li><strong>Fecha de nacimiento *</strong>: Fecha de nacimiento del solicitante.</li>
                <li><strong>Lugar de nacimiento *</strong>: Ciudad de nacimiento.</li>
                <li><strong>Nombre del padre</strong>: Nombre del padre (opcional).</li>
                <li><strong>Nombre de la madre</strong>: Nombre de la madre (opcional).</li>
                <li><strong>Nombre del padrino</strong>: Padrino (opcional, para bautizo/confirmación).</li>
                <li><strong>Nombre de la madrina</strong>: Madrina (opcional, para bautizo/confirmación).</li>
                <li><strong>Parroquia *</strong>: Parroquia de residencia.</li>
                <li><strong>Dirección</strong>: Dirección de residencia.</li>
                <li><strong>Teléfono *</strong>: Número de teléfono.</li>
                <li><strong>Email *</strong>: Correo electrónico.</li>
            </ul>
        </div>
        <div class="section-card">
            <h5><i class="fas fa-exclamation-triangle"></i> Notas Importantes</h5>
            <ul>
                <li>Para <strong>Bautizo</strong>, es obligatorio completar los datos del padre y la madre.</li>
                <li>Para <strong>Matrimonio</strong>, se recomienda incluir padrino y madrina.</li>
                <li>Los sacramentos no se pueden eliminar si ya tienen un certificado generado.</li>
            </ul>
        </div>
    </div>

    <!-- ========== PANELES ========== -->
    <div class="ayuda-seccion" id="sec-paneles">
        <div class="section-card">
            <h5><i class="fas fa-table"></i> Paneles de Control</h5>
            <p>Los paneles ofrecen una vista visual con gráficos y estadísticas de diferentes áreas del sistema.</p>
        </div>
        <div class="section-card">
            <h5><i class="fas fa-calendar-check"></i> Panel de Eventos</h5>
            <ul>
                <li><strong>Estadísticas</strong>: Total de eventos, próximos, realizados y total de inscripciones.</li>
                <li><strong>Gráfico de Eventos por Mes</strong>: Muestra la tendencia de creación de eventos a lo largo del año.</li>
                <li><strong>Gráfico de Participación por Evento</strong>: Distribución de inscripciones entre los eventos más populares.</li>
                <li><strong>Próximos Eventos</strong>: Lista de eventos próximos con fecha y estado.</li>
            </ul>
        </div>
        <div class="section-card">
            <h5><i class="fas fa-tasks"></i> Panel de Actividades</h5>
            <ul>
                <li><strong>Estadísticas</strong>: Total de actividades, próximas, realizadas e inscripciones totales.</li>
                <li><strong>Gráfico de Actividades por Mes</strong>: Tendencia de actividades programadas.</li>
                <li><strong>Gráfico de Inscripciones por Actividad</strong>: Cuáles actividades tienen más participación.</li>
                <li><strong>Próximas Actividades</strong>: Lista de actividades próximas.</li>
            </ul>
        </div>
    </div>

    <!-- ========== REPORTES ========== -->
    <div class="ayuda-seccion" id="sec-reportes">
        <div class="section-card">
            <h5><i class="fas fa-chart-pie"></i> Módulo de Reportes</h5>
            <p>Genere reportes detallados de diferentes áreas del sistema con opciones de filtrado y exportación.</p>
        </div>
        <div class="section-card">
            <h5><i class="fas fa-file-excel me-1"></i> Tipos de Reporte</h5>
            <ul>
                <li><strong>Reporte de Eventos</strong>: Lista completa de eventos con filtros por estado.</li>
                <li><strong>Reporte de Actividades</strong>: Actividades con filtros por evento, estado y fechas.</li>
                <li><strong>Reporte de Participantes</strong>: Personas inscritas con filtros por actividad y estado.</li>
                <li><strong>Reporte de Formación Sacramental</strong>: Registros de sacramentos con filtro por tipo.</li>
                <li><strong>Reporte de Asistencias</strong>: Registro de asistencias con filtros por actividad.</li>
                <li><strong>Reporte de Pagos</strong>: Pagos con filtros por estado y método de pago.</li>
            </ul>
        </div>
        <div class="section-card">
            <h5><i class="fas fa-download"></i> Exportar Datos</h5>
            <ul>
                <li><strong>Excel (XLSX)</strong>: Descarga un archivo de Excel con los datos filtrados.</li>
                <li><strong>PDF</strong>: Genera un documento PDF con los datos y un resumen.</li>
            </ul>
            <div class="paso"><span class="paso-num">1</span><span class="paso-text">Aplique los <strong>filtros</strong> deseados y haga clic en <strong>"Filtrar"</strong>.</span></div>
            <div class="paso"><span class="paso-num">2</span><span class="paso-text">Haga clic en <strong>"Excel"</strong> o <strong>"PDF"</strong> para exportar.</span></div>
        </div>
    </div>

    <!-- ========== FAQ ========== -->
    <div class="ayuda-seccion" id="sec-faq">
        <div class="section-card">
            <h5><i class="fas fa-question-circle"></i> Preguntas Frecuentes</h5>
        </div>

        <div class="faq-item">
            <button class="faq-question" onclick="toggleFaq(this)">
                ¿Cómo creo un nuevo evento en el sistema?
                <i class="fas fa-chevron-down"></i>
            </button>
            <div class="faq-answer">
                <p>Vaya a <strong>Gestión → Eventos</strong>, haga clic en el botón <strong>"Nuevo Evento"</strong>, complete los campos obligatorios (nombre, estado, fecha) y haga clic en <strong>"Guardar"</strong>.</p>
            </div>
        </div>

        <div class="faq-item">
            <button class="faq-question" onclick="toggleFaq(this)">
                ¿Cómo inscribo a una persona en una actividad?
                <i class="fas fa-chevron-down"></i>
            </button>
            <div class="faq-answer">
                <p>Para inscribir a una persona en una actividad, primero asegúrese de que la persona esté registrada en el sistema. Luego vaya a la actividad correspondiente y edite la inscripción. El sistema creará automáticamente el registro de inscripción con estado <strong>"Pre-inscrito"</strong>.</p>
            </div>
        </div>

        <div class="faq-item">
            <button class="faq-question" onclick="toggleFaq(this)">
                ¿Puedo eliminar un evento que tiene actividades?
                <i class="fas fa-chevron-down"></i>
            </button>
            <div class="faq-answer">
                <p>No se puede eliminar un evento que tenga actividades asociadas. Primero debe eliminar o reasignar todas las actividades del evento antes de eliminarlo.</p>
            </div>
        </div>

        <div class="faq-item">
            <button class="faq-question" onclick="toggleFaq(this)">
                ¿Cómo cambio el estado de una inscripción?
                <i class="fas fa-chevron-down"></i>
            </button>
            <div class="faq-answer">
                <p>Vaya a <strong>Gestión → Inscripciones</strong>, busque la inscripción, haga clic en el ícono de <strong>editar (lápiz)</strong> y seleccione el nuevo estado en el campo <strong>"Estado"</strong>. Los estados disponibles son: Pre-inscrito, Inscrito, En espera, Cancelado y Completado.</p>
            </div>
        </div>

        <div class="faq-item">
            <button class="faq-question" onclick="toggleFaq(this)">
                ¿Cómo registro una persona que ya tiene un rol en el sistema?
                <i class="fas fa-chevron-down"></i>
            </button>
            <div class="faq-answer">
                <p>Cuando cree o edite una persona, seleccione el <strong>Rol del sistema</strong> en el formulario. Si la persona ya tiene un rol, se actualizará. Si no tiene uno, se creará un registro nuevo en <strong>usuarios_sistema</strong>.</p>
            </div>
        </div>

        <div class="faq-item">
            <button class="faq-question" onclick="toggleFaq(this)">
                ¿Cómo genero un reporte en PDF?
                <i class="fas fa-chevron-down"></i>
            </button>
            <div class="faq-answer">
                <p>Vaya a la sección de <strong>Reportes</strong> que le interese, aplique los filtros deseados y haga clic en el botón <strong>"PDF"</strong>. Se descargará automáticamente un archivo PDF con los datos filtrados.</p>
            </div>
        </div>

        <div class="faq-item">
            <button class="faq-question" onclick="toggleFaq(this)">
                ¿Qué hago si olvidé mi contraseña?
                <i class="fas fa-chevron-down"></i>
            </button>
            <div class="faq-answer">
                <p>Contacte al administrador del sistema para que restablezca su contraseña desde la sección de <strong>Roles del Sistema</strong>.</p>
            </div>
        </div>

        <div class="faq-item">
            <button class="faq-question" onclick="toggleFaq(this)">
                ¿Puedo ver el sistema en modo oscuro?
                <i class="fas fa-chevron-down"></i>
            </button>
            <div class="faq-answer">
                <p>Sí, haga clic en el ícono de <strong>sol/luna</strong> en la parte superior del sidebar para alternar entre el tema claro y oscuro. La preferencia se guarda automáticamente.</p>
            </div>
        </div>

        <div class="faq-item">
            <button class="faq-question" onclick="toggleFaq(this)">
                ¿Cómo registro el pago de una inscripción?
                <i class="fas fa-chevron-down"></i>
            </button>
            <div class="faq-answer">
                <p>Los pagos se registran desde la sección de <strong>Inscripciones</strong>. Al editar una inscripción, puede agregar un pago con el monto, método y concepto correspondiente.</p>
            </div>
        </div>

        <div class="faq-item">
            <button class="faq-question" onclick="toggleFaq(this)">
                ¿Cómo cambio entre tema claro y oscuro?
                <i class="fas fa-chevron-down"></i>
            </button>
            <div class="faq-answer">
                <p>Haga clic en el botón con el ícono de <strong>sol ☀️</strong> o <strong>luna 🌙</strong> ubicado en la parte superior del sidebar de navegación. Su preferencia se guarda en el navegador para futuras sesiones.</p>
            </div>
        </div>
    </div>

    <!-- ========== CONTACTO ========== -->
    <div class="ayuda-seccion" id="sec-contacto">
        <div class="section-card">
            <h5><i class="fas fa-headset"></i> Contacto y Soporte</h5>
            <p>Si necesita ayuda adicional o tiene problemas con el sistema, puede contactarnos a través de los siguientes medios.</p>
        </div>

        <div class="contacto-grid">
            <div class="contacto-card">
                <div class="icono bg-primary"><i class="fas fa-envelope"></i></div>
                <h6>Correo Electrónico</h6>
                <p>soporte@oratorio-liturgico.com</p>
                <small class="text-muted">Respuesta en 24-48 horas</small>
            </div>
            <div class="contacto-card">
                <div class="icono bg-success"><i class="fas fa-phone"></i></div>
                <h6>Teléfono</h6>
                <p>+591 2 123 4567</p>
                <small class="text-muted">Lunes a Viernes, 8:00 - 17:00</small>
            </div>
            <div class="contacto-card">
                <div class="icono bg-info"><i class="fas fa-map-marker-alt"></i></div>
                <h6>Ubicación</h6>
                <p>Oratorio Litúrgico</p>
                <small class="text-muted">Santa Cruz, Bolivia</small>
            </div>
            <div class="contacto-card">
                <div class="icono bg-warning"><i class="fas fa-comments"></i></div>
                <h6>Chat en Línea</h6>
                <p>Disponible en horario laboral</p>
                <small class="text-muted">Lunes a Viernes, 8:00 - 17:00</small>
            </div>
        </div>

        <div class="section-card mt-4">
            <h5><i class="fas fa-bug"></i> Reportar un Problema</h5>
            <p>Si encuentra un error o comportamiento inesperado en el sistema, por favor envíe un correo con:</p>
            <ul>
                <li><strong>Descripción del problema</strong>: Explique qué sucedió y qué esperaba que pasara.</li>
                <li><strong>Pasos para reproducir</strong>: Indique los pasos exactos que realizó antes del error.</li>
                <li><strong>Captura de pantalla</strong>: Si es posible, adjunte una imagen del error.</li>
                <li><strong>Navegador y dispositivo</strong>: Indique qué navegador y dispositivo está usando.</li>
            </ul>
        </div>
    </div>
</div>

<div id="toasts" class="position-fixed top-0 end-0 p-3"></div>

<script>
function showToast(msg, type) {
    const el = document.createElement('div');
    el.className = 'toast ' + (type === 'error' ? 'bg-danger text-white' : 'bg-success text-white');
    el.innerHTML = '<div class="toast-body">' + msg + '</div>';
    document.getElementById('toasts').appendChild(el);
    new bootstrap.Toast(el, {delay:3000}).show();
    el.addEventListener('hidden.bs.toast', () => el.remove());
}

// Navegación entre secciones
document.querySelectorAll('.ayuda-nav .nav-link').forEach(function(link) {
    link.addEventListener('click', function() {
        var target = this.getAttribute('data-target');
        document.querySelectorAll('.ayuda-nav .nav-link').forEach(function(l) { l.classList.remove('active'); });
        this.classList.add('active');
        document.querySelectorAll('.ayuda-seccion').forEach(function(s) { s.classList.remove('active'); });
        var sec = document.getElementById('sec-' + target);
        if (sec) sec.classList.add('active');
    });
});

// FAQ toggle
function toggleFaq(btn) {
    var answer = btn.nextElementSibling;
    var isOpen = btn.classList.contains('open');
    // Cerrar otros
    document.querySelectorAll('.faq-question.open').forEach(function(q) {
        q.classList.remove('open');
        q.nextElementSibling.classList.remove('show');
    });
    if (!isOpen) {
        btn.classList.add('open');
        answer.classList.add('show');
    }
}
</script>
<?php $content = ob_get_clean(); require_once appPath('cliente/layouts/AdminLayout.php');