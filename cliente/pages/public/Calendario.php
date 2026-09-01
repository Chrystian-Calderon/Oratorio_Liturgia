<?php
$pageTitle = "Calendario de Actividades";
$eventosDb = require appPath('servidor/calendario/eventos.php');
$eventosJson = array_map(fn($e) => [
    'id'          => $e['id_evento'],
    'title'       => $e['nombre_evento'],
    'description' => $e['descripcion'] ?? '',
    'date'        => $e['fecha_evento'] ?? '',
    'time'        => $e['hora_evento'] ?? '',
    'location'    => $e['lugar'] ?? '',
    'estado'      => $e['estado'] ?? '',
], $eventosDb);
ob_start();
$pageStyles = [
    'cliente/assets/css/calendario.css',
];
?>
<div class="container">
    <h1 class="section-title">Calendario de Actividades 2026 - Oratorio y Liturgia</h1>

    <div class="calendar-section">
        <div class="calendar-header">
            <h2 id="current-month-year"></h2>
            <div class="calendar-nav">
                <button class="btn" id="prev-year">Año Anterior</button>
                <button class="btn" id="prev-month">Mes Anterior</button>
                <button class="btn" id="today">Hoy</button>
                <button class="btn" id="next-month">Mes Siguiente</button>
                <button class="btn" id="next-year">Año Siguiente</button>
            </div>
        </div>

        <div class="calendar-grid" id="calendar-grid"></div>

        <div class="events-list" id="events-list">
            <h3>Eventos del Mes</h3>
            <div id="month-events"></div>
        </div>
    </div>
</div>

<!-- Modal de solo lectura -->
<div class="modal" id="event-modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 id="modal-title">Detalle del Evento</h3>
            <span class="close-modal" id="close-modal">&times;</span>
        </div>
        <div id="event-detail">
            <p><strong>Fecha:</strong> <span id="detail-date"></span></p>
            <p><strong>Hora:</strong> <span id="detail-time"></span></p>
            <p><strong>Lugar:</strong> <span id="detail-location"></span></p>
            <p><strong>Estado:</strong> <span id="detail-estado"></span></p>
            <p><strong>Descripci&oacute;n:</strong></p>
            <p id="detail-description"></p>
        </div>
        <div class="form-actions">
            <button type="button" class="btn" id="close-detail">Cerrar</button>
        </div>
    </div>
</div>

<script>
const events = <?= json_encode($eventosJson, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>;

let currentDate = new Date();

const calendarGrid = document.getElementById('calendar-grid');
const currentMonthYear = document.getElementById('current-month-year');
const monthEvents = document.getElementById('month-events');
const eventModal = document.getElementById('event-modal');
const closeModalBtn = document.getElementById('close-modal');
const closeDetailBtn = document.getElementById('close-detail');

document.addEventListener('DOMContentLoaded', function() {
    setupEventListeners();
    renderCalendar();
});

function setupEventListeners() {
    document.getElementById('prev-month').addEventListener('click', prevMonth);
    document.getElementById('next-month').addEventListener('click', nextMonth);
    document.getElementById('prev-year').addEventListener('click', prevYear);
    document.getElementById('next-year').addEventListener('click', nextYear);
    document.getElementById('today').addEventListener('click', goToToday);
    closeModalBtn.addEventListener('click', closeEventModal);
    closeDetailBtn.addEventListener('click', closeEventModal);
    eventModal.addEventListener('click', function(e) {
        if (e.target === eventModal) closeEventModal();
    });
}

function renderCalendar() {
    calendarGrid.innerHTML = '';

    const daysOfWeek = ['Dom', 'Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb'];
    daysOfWeek.forEach(function(day) {
        const el = document.createElement('div');
        el.className = 'calendar-day';
        el.textContent = day;
        calendarGrid.appendChild(el);
    });

    const year = currentDate.getFullYear();
    const month = currentDate.getMonth();
    currentMonthYear.textContent = getMonthName(month) + ' ' + year;

    const firstDay = new Date(year, month, 1);
    const lastDay = new Date(year, month + 1, 0);
    const firstDayOfWeek = firstDay.getDay();
    const prevMonthLastDay = new Date(year, month, 0).getDate();
    const today = new Date();

    for (let i = firstDayOfWeek - 1; i >= 0; i--) {
        const el = document.createElement('div');
        el.className = 'calendar-date other-month';
        el.textContent = prevMonthLastDay - i;
        calendarGrid.appendChild(el);
    }

    for (let day = 1; day <= lastDay.getDate(); day++) {
        const el = document.createElement('div');
        el.className = 'calendar-date';

        const dayNumber = document.createElement('div');
        dayNumber.className = 'day-number';
        dayNumber.textContent = day;
        el.appendChild(dayNumber);

        if (year === today.getFullYear() && month === today.getMonth() && day === today.getDate()) {
            el.classList.add('today');
        }

        const dateKey = year + '-' + String(month + 1).padStart(2, '0') + '-' + String(day).padStart(2, '0');

        const dayEvents = events.filter(function(ev) { return ev.date === dateKey; });

        dayEvents.forEach(function(ev) {
            const indicator = document.createElement('div');
            indicator.className = 'event-indicator';
            indicator.textContent = ev.title;
            indicator.title = 'Ver información del evento';
            indicator.addEventListener('click', function(e) {
                e.stopPropagation();
                showEvent(ev);
            });
            el.appendChild(indicator);
        });

        calendarGrid.appendChild(el);
    }

    const totalCells = 42;
    const daysInCalendar = firstDayOfWeek + lastDay.getDate();
    const nextMonthDays = totalCells - daysInCalendar;

    for (let day = 1; day <= nextMonthDays; day++) {
        const el = document.createElement('div');
        el.className = 'calendar-date other-month';
        el.textContent = day;
        calendarGrid.appendChild(el);
    }

    renderMonthEvents();
}

function renderMonthEvents() {
    monthEvents.innerHTML = '';
    const year = currentDate.getFullYear();
    const month = currentDate.getMonth();

    const list = events
        .filter(function(ev) {
            if (!ev.date) return false;
            const d = new Date(ev.date + 'T00:00:00');
            return d.getFullYear() === year && d.getMonth() === month;
        })
        .sort(function(a, b) {
            if (a.date !== b.date) return a.date.localeCompare(b.date);
            return (a.time || '').localeCompare(b.time || '');
        });

    if (list.length === 0) {
        monthEvents.innerHTML = '<p>No hay eventos programados para este mes.</p>';
        return;
    }

    list.forEach(function(ev) {
        const item = document.createElement('div');
        item.className = 'event-item';
        item.style.cursor = 'pointer';
        item.addEventListener('click', function() { showEvent(ev); });

        const d = new Date(ev.date + 'T00:00:00');
        const formattedDate = d.getDate() + ' de ' + getMonthName(d.getMonth());

        const badge = ev.estado === 'Activo' ? 'success' : ev.estado === 'Cancelado' ? 'danger' : 'secondary';

        item.innerHTML =
            '<div class="event-info">' +
                '<h4>' + ev.title + '</h4>' +
                '<p><strong>Fecha:</strong> ' + formattedDate + (ev.time ? ' - ' + ev.time : '') + '</p>' +
                (ev.location ? '<p><strong>Lugar:</strong> ' + ev.location + '</p>' : '') +
                (ev.description ? '<p>' + ev.description + '</p>' : '') +
            '</div>' +
            '<div class="event-actions">' +
                '<span class="badge bg-' + badge + '">' + ev.estado + '</span>' +
            '</div>';

        monthEvents.appendChild(item);
    });
}

function showEvent(ev) {
    document.getElementById('modal-title').textContent = ev.title;
    document.getElementById('detail-date').textContent = ev.date || '—';
    document.getElementById('detail-time').textContent = ev.time || '—';
    document.getElementById('detail-location').textContent = ev.location || '—';
    document.getElementById('detail-estado').textContent = ev.estado || '—';
    document.getElementById('detail-description').textContent = ev.description || 'Sin descripción.';
    eventModal.style.display = 'flex';
}

function closeEventModal() {
    eventModal.style.display = 'none';
}

function prevMonth() { currentDate.setMonth(currentDate.getMonth() - 1); renderCalendar(); }
function nextMonth() { currentDate.setMonth(currentDate.getMonth() + 1); renderCalendar(); }
function prevYear() { currentDate.setFullYear(currentDate.getFullYear() - 1); renderCalendar(); }
function nextYear() { currentDate.setFullYear(currentDate.getFullYear() + 1); renderCalendar(); }
function goToToday() { currentDate = new Date(); renderCalendar(); }

function getMonthName(monthIndex) {
    var months = ['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'];
    return months[monthIndex];
}
</script>
<?php
$content = ob_get_clean();
require appPath('cliente/layouts/PublicLayout.php');
