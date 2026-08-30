<?php
$pageTitle = "Calendario de Actividades";
ob_start();
$pageStyles = [
    'cliente/assets/css/calendario.css',
];
?>

    <div class="container">
        <h1 class="section-title">Calendario de Actividades 2026 - Oratorio y Liturgia</h1>
        
        <div class="calendar-section">
            <div class="calendar-header">
                <h2 id="current-month-year">Enero 2025</h2>
                <div class="calendar-nav">
                    <button class="btn" id="prev-year">Año Anterior</button>
                    <button class="btn" id="prev-month">Mes Anterior</button>
                    <button class="btn" id="today">Hoy</button>
                    <button class="btn" id="next-month">Mes Siguiente</button>
                    <button class="btn" id="next-year">Año Siguiente</button>
                </div>
            </div>
            
            <div class="calendar-grid" id="calendar-grid">
                <!-- Los días de la semana y fechas se generarán con JavaScript -->
            </div>
            
            <div class="events-list" id="events-list">
                <h3>Eventos del Mes</h3>
                <div id="month-events">
                    <!-- Los eventos del mes se mostrarán aquí -->
                </div>
            </div>
        </div>
    </div>
    
    <!-- Modal para agregar/editar eventos -->
    <div class="modal" id="event-modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 id="modal-title">Agregar Nuevo Evento</h3>
                <span class="close-modal" id="close-modal">&times;</span>
            </div>
            <form id="event-form">
                <input type="hidden" id="event-id">
                <input type="hidden" id="event-date">
                
                <div class="form-group">
                    <label for="event-title">Título del Evento</label>
                    <input type="text" id="event-title" required>
                </div>
                
                <div class="form-group">
                    <label for="event-description">Descripción</label>
                    <textarea id="event-description"></textarea>
                </div>
                
                <div class="form-group">
                    <label for="event-time">Hora</label>
                    <input type="time" id="event-time">
                </div>
                
                <div class="form-group">
                    <label for="event-type">Tipo de Evento</label>
                    <select id="event-type">
                        <option value="Misa">Misa</option>
                        <option value="Bautizo">Bautizo</option>
                        <option value="Comunión">Comunión</option>
                        <option value="Confirmación">Confirmación</option>
                        <option value="Matrimonio">Matrimonio</option>
                        <option value="Retiro">Retiro</option>
                        <option value="Conferencia">Conferencia</option>
                        <option value="Otro">Otro</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="event-location">Lugar</label>
                    <input type="text" id="event-location">
                </div>
                
                <div class="form-actions">
                    <button type="button" class="btn btn-danger" id="delete-event" style="display: none;">Eliminar</button>
                    <button type="button" class="btn" id="cancel-event">Cancelar</button>
                    <button type="submit" class="btn">Guardar Evento</button>
                </div>
            </form>
        </div>
    </div>

<script>

/* ================================================================
   EVENTOS PROCEDENTES DE PHP / MYSQL
   ================================================================ */

const events = <?php echo json_encode(
    $eventos,
    JSON_UNESCAPED_UNICODE |
    JSON_UNESCAPED_SLASHES
); ?>;


/* ================================================================
   FECHA ACTUAL
   ================================================================ */

let currentDate = new Date();


/* ================================================================
   ELEMENTOS
   ================================================================ */

const calendarGrid =
    document.getElementById('calendar-grid');

const currentMonthYear =
    document.getElementById('current-month-year');

const monthEvents =
    document.getElementById('month-events');

const eventModal =
    document.getElementById('event-modal');

const modalTitle =
    document.getElementById('modal-title');

const modalInfo =
    document.getElementById('modal-info');

const closeModal =
    document.getElementById('close-modal');


/* ================================================================
   INICIAR
   ================================================================ */

document.addEventListener(
    'DOMContentLoaded',
    function() {

        renderCalendar();

        setupEventListeners();

    }
);


/* ================================================================
   BOTONES
   ================================================================ */

function setupEventListeners() {

    document
        .getElementById('prev-month')
        .addEventListener(
            'click',
            prevMonth
        );

    document
        .getElementById('next-month')
        .addEventListener(
            'click',
            nextMonth
        );

    document
        .getElementById('prev-year')
        .addEventListener(
            'click',
            prevYear
        );

    document
        .getElementById('next-year')
        .addEventListener(
            'click',
            nextYear
        );

    document
        .getElementById('today')
        .addEventListener(
            'click',
            goToToday
        );

    closeModal.addEventListener(
        'click',
        closeEventModal
    );

    eventModal.addEventListener(
        'click',
        function(e) {

            if (e.target === eventModal) {

                closeEventModal();

            }

        }
    );

}


/* ================================================================
   RENDERIZAR CALENDARIO
   ================================================================ */

function renderCalendar() {

    calendarGrid.innerHTML = '';


    /* ------------------------------------------------------------
       DIAS DE LA SEMANA
       ------------------------------------------------------------ */

    const daysOfWeek = [
        'Dom',
        'Lun',
        'Mar',
        'Mié',
        'Jue',
        'Vie',
        'Sáb'
    ];

    daysOfWeek.forEach(day => {

        const dayElement =
            document.createElement('div');

        dayElement.className =
            'calendar-day';

        dayElement.textContent =
            day;

        calendarGrid.appendChild(
            dayElement
        );

    });


    /* ------------------------------------------------------------
       INFORMACION DEL MES
       ------------------------------------------------------------ */

    const year =
        currentDate.getFullYear();

    const month =
        currentDate.getMonth();


    currentMonthYear.textContent =
        `${getMonthName(month)} ${year}`;


    const firstDay =
        new Date(year, month, 1);

    const lastDay =
        new Date(year, month + 1, 0);


    const firstDayOfWeek =
        firstDay.getDay();


    const prevMonthLastDay =
        new Date(year, month, 0).getDate();


    /* ------------------------------------------------------------
       DIAS DEL MES ANTERIOR
       ------------------------------------------------------------ */

    for (
        let i = firstDayOfWeek - 1;
        i >= 0;
        i--
    ) {

        const dateElement =
            document.createElement('div');

        dateElement.className =
            'calendar-date other-month';

        dateElement.textContent =
            prevMonthLastDay - i;

        calendarGrid.appendChild(
            dateElement
        );

    }


    /* ------------------------------------------------------------
       DIAS DEL MES ACTUAL
       ------------------------------------------------------------ */

    const today =
        new Date();


    for (
        let day = 1;
        day <= lastDay.getDate();
        day++
    ) {

        const dateElement =
            document.createElement('div');

        dateElement.className =
            'calendar-date';


        /* Numero del día */

        const dayNumber =
            document.createElement('div');

        dayNumber.className =
            'day-number';

        dayNumber.textContent =
            day;

        dateElement.appendChild(
            dayNumber
        );


        /* Marcar hoy */

        if (
            year === today.getFullYear() &&
            month === today.getMonth() &&
            day === today.getDate()
        ) {

            dateElement.classList.add(
                'today'
            );

        }


        /* Fecha en formato YYYY-MM-DD */

        const dateKey =
            `${year}-${String(month + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;


        /* Buscar eventos */

        const dayEvents =
            events.filter(
                event => event.date === dateKey
            );


        /* Mostrar eventos */

        dayEvents.forEach(event => {

            const eventIndicator =
                document.createElement('div');

            eventIndicator.className =
                'event-indicator';

            eventIndicator.textContent =
                event.title;

            eventIndicator.title =
                'Ver información del evento';


            eventIndicator.addEventListener(
                'click',
                function(e) {

                    e.stopPropagation();

                    showEvent(event);

                }
            );


            dateElement.appendChild(
                eventIndicator
            );

        });


        calendarGrid.appendChild(
            dateElement
        );

    }


    /* ------------------------------------------------------------
       COMPLETAR CALENDARIO
       ------------------------------------------------------------ */

    const totalCells = 42;

    const daysInCalendar =
        firstDayOfWeek +
        lastDay.getDate();

    const nextMonthDays =
        totalCells -
        daysInCalendar;


    for (
        let day = 1;
        day <= nextMonthDays;
        day++
    ) {

        const dateElement =
            document.createElement('div');

        dateElement.className =
            'calendar-date other-month';

        dateElement.textContent =
            day;

        calendarGrid.appendChild(
            dateElement
        );

    }


    /* ------------------------------------------------------------
       LISTA DE EVENTOS
       ------------------------------------------------------------ */

    renderMonthEvents();

}


/* ================================================================
   MOSTRAR EVENTOS DEL MES
   ================================================================ */

function renderMonthEvents() {

    monthEvents.innerHTML = '';


    const year =
        currentDate.getFullYear();

    const month =
        currentDate.getMonth();


    const monthEventsList =
        events
            .filter(event => {

                const eventDate =
                    new Date(
                        event.date + 'T00:00:00'
                    );

                return (
                    eventDate.getFullYear() === year &&
                    eventDate.getMonth() === month
                );

            })
            .sort((a, b) => {

                if (a.date !== b.date) {
                    return a.date.localeCompare(b.date);
                }
                return (a.time || '').localeCompare(b.time || '');
            });
            
            // Mostrar eventos
            if (monthEventsList.length === 0) {
                monthEvents.innerHTML = '<p>No hay eventos programados para este mes.</p>';
                return;
            }
            
            monthEventsList.forEach(event => {
                const eventItem = document.createElement('div');
                eventItem.className = 'event-item';
                
                const eventDate = new Date(event.date);
                const formattedDate = `${eventDate.getDate()} de ${getMonthName(eventDate.getMonth())}`;
                
                eventItem.innerHTML = `
                    <div class="event-info">
                        <h4>${event.title}</h4>
                        <p><strong>Fecha:</strong> ${formattedDate} ${event.time ? `- ${event.time}` : ''}</p>
                        <p><strong>Tipo:</strong> ${event.type}</p>
                        ${event.location ? `<p><strong>Lugar:</strong> ${event.location}</p>` : ''}
                        ${event.description ? `<p>${event.description}</p>` : ''}
                    </div>
                    <div class="event-actions">
                        <button class="btn" onclick="editEvent('${event.date}', '${event.id}')">Editar</button>
                        <button class="btn btn-danger" onclick="deleteSpecificEvent('${event.date}', '${event.id}')">Eliminar</button>
                    </div>
                `;
                
                monthEvents.appendChild(eventItem);
            });
        }
        
        // Navegación del calendario
        function prevMonth() {
            currentDate.setMonth(currentDate.getMonth() - 1);
            renderCalendar();
        }
        
        function nextMonth() {
            currentDate.setMonth(currentDate.getMonth() + 1);
            renderCalendar();
        }
        
        function prevYear() {
            currentDate.setFullYear(currentDate.getFullYear() - 1);
            renderCalendar();
        }
        
        function nextYear() {
            currentDate.setFullYear(currentDate.getFullYear() + 1);
            renderCalendar();
        }
        
        function goToToday() {
            currentDate = new Date();
            renderCalendar();
        }
        
        // Funciones del modal de eventos
        function openEventModal(dateKey, isNew = true) {
            document.getElementById('event-date').value = dateKey;
            
            if (isNew) {
                modalTitle.textContent = 'Agregar Nuevo Evento';
                eventForm.reset();
                deleteEvent.style.display = 'none';
            }
            
            eventModal.style.display = 'flex';
        }
        
        function closeEventModal() {
            eventModal.style.display = 'none';
        }
        
        function editEvent(dateKey, eventId) {
            const event = events[dateKey].find(e => e.id === eventId);
            
            if (event) {
                modalTitle.textContent = 'Editar Evento';
                document.getElementById('event-id').value = eventId;
                document.getElementById('event-date').value = dateKey;
                document.getElementById('event-title').value = event.title;
                document.getElementById('event-description').value = event.description || '';
                document.getElementById('event-time').value = event.time || '';
                document.getElementById('event-type').value = event.type || 'Misa';
                document.getElementById('event-location').value = event.location || '';
                
                deleteEvent.style.display = 'inline-block';
                eventModal.style.display = 'flex';
            }
        }
        
        function saveEvent(e) {
            e.preventDefault();
            
            const eventId = document.getElementById('event-id').value || generateId();
            const dateKey = document.getElementById('event-date').value;
            const title = document.getElementById('event-title').value;
            const description = document.getElementById('event-description').value;
            const time = document.getElementById('event-time').value;
            const type = document.getElementById('event-type').value;
            const location = document.getElementById('event-location').value;
            
            // Crear o actualizar evento
            if (!events[dateKey]) {
                events[dateKey] = [];
            }
            
            const existingIndex = events[dateKey].findIndex(e => e.id === eventId);
            
            if (existingIndex >= 0) {
                // Actualizar evento existente
                events[dateKey][existingIndex] = {
                    id: eventId,
                    title,
                    description,
                    time,
                    type,
                    location
                };
            } else {
                // Agregar nuevo evento
                events[dateKey].push({
                    id: eventId,
                    title,
                    description,
                    time,
                    type,
                    location
                });
            }
            
            // Guardar en localStorage
            localStorage.setItem('liturgicalEvents2025', JSON.stringify(events));
            
            // Actualizar calendario
            renderCalendar();
            closeEventModal();
        }
        
        function deleteCurrentEvent() {
            const eventId = document.getElementById('event-id').value;
            const dateKey = document.getElementById('event-date').value;
            
            deleteSpecificEvent(dateKey, eventId);
        }
        
        function deleteSpecificEvent(dateKey, eventId) {
            if (confirm('¿Estás seguro de que quieres eliminar este evento?')) {
                if (events[dateKey]) {
                    events[dateKey] = events[dateKey].filter(e => e.id !== eventId);
                    
                    // Si no hay más eventos en esta fecha, eliminar la clave
                    if (events[dateKey].length === 0) {
                        delete events[dateKey];
                    }
                    
                    // Guardar en localStorage
                    localStorage.setItem('liturgicalEvents2025', JSON.stringify(events));
                    
                    // Actualizar calendario
                    renderCalendar();
                    closeEventModal();
                }
            }
        }
        
        // Funciones auxiliares
        function getMonthName(monthIndex) {
            const months = [
                'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
                'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'
            ];
            return months[monthIndex];
        }
        
        function generateId() {
            return Date.now().toString(36) + Math.random().toString(36).substr(2);
        }

        goToToday(); // Inicializar en la fecha actual al cargar la página
    </script>
<?php
$content = ob_get_clean();
require appPath('cliente/layouts/PublicLayout.php');