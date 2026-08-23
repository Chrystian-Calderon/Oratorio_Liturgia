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
        // Estado del calendario
        let currentDate = new Date(2025, 0, 1); // Enero 2025
        let events = JSON.parse(localStorage.getItem('liturgicalEvents2025')) || {};
        
        // Elementos del DOM
        const calendarGrid = document.getElementById('calendar-grid');
        const currentMonthYear = document.getElementById('current-month-year');
        const monthEvents = document.getElementById('month-events');
        const eventModal = document.getElementById('event-modal');
        const eventForm = document.getElementById('event-form');
        const modalTitle = document.getElementById('modal-title');
        const closeModal = document.getElementById('close-modal');
        const cancelEvent = document.getElementById('cancel-event');
        const deleteEvent = document.getElementById('delete-event');
        
        // Inicializar el calendario
        document.addEventListener('DOMContentLoaded', function() {
            renderCalendar();
            setupEventListeners();
        });
        
        // Configurar event listeners
        function setupEventListeners() {
            document.getElementById('prev-month').addEventListener('click', prevMonth);
            document.getElementById('next-month').addEventListener('click', nextMonth);
            document.getElementById('prev-year').addEventListener('click', prevYear);
            document.getElementById('next-year').addEventListener('click', nextYear);
            document.getElementById('today').addEventListener('click', goToToday);
            
            closeModal.addEventListener('click', closeEventModal);
            cancelEvent.addEventListener('click', closeEventModal);
            eventForm.addEventListener('submit', saveEvent);
            deleteEvent.addEventListener('click', deleteCurrentEvent);
        }
        
        // Renderizar el calendario
        function renderCalendar() {
            // Limpiar el calendario
            calendarGrid.innerHTML = '';
            
            // Añadir días de la semana
            const daysOfWeek = ['Dom', 'Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb'];
            daysOfWeek.forEach(day => {
                const dayElement = document.createElement('div');
                dayElement.className = 'calendar-day';
                dayElement.textContent = day;
                calendarGrid.appendChild(dayElement);
            });
            
            // Obtener información del mes actual
            const year = currentDate.getFullYear();
            const month = currentDate.getMonth();
            
            // Actualizar el título
            currentMonthYear.textContent = `${getMonthName(month)} ${year}`;
            
            // Obtener primer día del mes y último día del mes
            const firstDay = new Date(year, month, 1);
            const lastDay = new Date(year, month + 1, 0);
            
            // Obtener día de la semana del primer día (0 = Domingo, 6 = Sábado)
            const firstDayOfWeek = firstDay.getDay();
            
            // Obtener último día del mes anterior
            const prevMonthLastDay = new Date(year, month, 0).getDate();
            
            // Añadir días del mes anterior
            for (let i = firstDayOfWeek - 1; i >= 0; i--) {
                const dateElement = document.createElement('div');
                dateElement.className = 'calendar-date other-month';
                dateElement.textContent = prevMonthLastDay - i;
                calendarGrid.appendChild(dateElement);
            }
            
            // Añadir días del mes actual
            const today = new Date();
            for (let day = 1; day <= lastDay.getDate(); day++) {
                const dateElement = document.createElement('div');
                dateElement.className = 'calendar-date';
                
                // Marcar si es hoy
                if (year === today.getFullYear() && month === today.getMonth() && day === today.getDate()) {
                    dateElement.classList.add('today');
                }
                
                dateElement.textContent = day;
                
                // Añadir eventos si existen
                const dateKey = `${year}-${month + 1}-${day}`;
                if (events[dateKey]) {
                    events[dateKey].forEach(event => {
                        const eventIndicator = document.createElement('div');
                        eventIndicator.className = 'event-indicator';
                        eventIndicator.textContent = event.title;
                        eventIndicator.addEventListener('click', (e) => {
                            e.stopPropagation();
                            editEvent(dateKey, event.id);
                        });
                        dateElement.appendChild(eventIndicator);
                    });
                }
                
                // Añadir evento para abrir modal al hacer clic
                dateElement.addEventListener('click', () => {
                    openEventModal(dateKey);
                });
                
                calendarGrid.appendChild(dateElement);
            }
            
            // Añadir días del siguiente mes para completar la cuadrícula
            const totalCells = 42; // 6 filas de 7 días
            const daysInCalendar = firstDayOfWeek + lastDay.getDate();
            const nextMonthDays = totalCells - daysInCalendar;
            
            for (let day = 1; day <= nextMonthDays; day++) {
                const dateElement = document.createElement('div');
                dateElement.className = 'calendar-date other-month';
                dateElement.textContent = day;
                calendarGrid.appendChild(dateElement);
            }
            
            // Actualizar lista de eventos del mes
            renderMonthEvents();
        }
        
        // Renderizar lista de eventos del mes
        function renderMonthEvents() {
            monthEvents.innerHTML = '';
            
            const year = currentDate.getFullYear();
            const month = currentDate.getMonth() + 1;
            
            // Obtener eventos del mes actual
            const monthEventsList = [];
            
            for (const dateKey in events) {
                const [eventYear, eventMonth, eventDay] = dateKey.split('-').map(Number);
                
                if (eventYear === year && eventMonth === month) {
                    events[dateKey].forEach(event => {
                        monthEventsList.push({
                            date: dateKey,
                            ...event
                        });
                    });
                }
            }
            
            // Ordenar eventos por fecha y hora
            monthEventsList.sort((a, b) => {
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