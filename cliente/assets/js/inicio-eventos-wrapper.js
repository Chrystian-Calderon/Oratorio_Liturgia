document.addEventListener('DOMContentLoaded', () => {
  const scrollContainer = document.getElementById('eventosScroll');
  const prevButton = document.querySelector('.eventos-prev');
  const nextButton = document.querySelector('.eventos-next');

  if (!scrollContainer) return;

  const coloresHeader = ['bg-primary text-white', 'bg-success text-white', 'bg-warning text-dark'];
  const coloresBoton = ['btn-primary', 'btn-success', 'btn-warning text-dark'];

  function formatearFecha(fecha) {
    if (!fecha) return 'Fecha no definida';
    const d = new Date(fecha + 'T00:00:00');
    if (isNaN(d)) return fecha;
    const opciones = { day: 'numeric', month: 'long', year: 'numeric' };
    return d.toLocaleDateString('es-ES', opciones);
  }

  function formatearHora(hora) {
    if (!hora) return 'Hora no definida';
    const partes = hora.split(':');
    if (partes.length < 2) return hora;
    let h = parseInt(partes[0], 10);
    const m = partes[1];
    const sufijo = h >= 12 ? 'PM' : 'AM';
    h = h % 12 || 12;
    return h + ':' + m + ' ' + sufijo;
  }

  function recortarTexto(texto, max = 110) {
    if (!texto) return '';
    if (texto.length > max) return texto.substring(0, max) + '...';
    return texto;
  }

  function crearTarjeta(evento, indice) {
    const colorHeader = coloresHeader[indice % coloresHeader.length];
    const colorBoton = coloresBoton[indice % coloresBoton.length];
    const nombre = evento.nombre_evento || 'Evento';

    const item = document.createElement('div');
    item.className = 'evento-item';

    const card = document.createElement('div');
    card.className = 'card h-100 border-0 shadow';

    const header = document.createElement('div');
    header.className = 'card-header ' + colorHeader;
    const titulo = document.createElement('h5');
    titulo.className = 'mb-0';
    titulo.textContent = nombre;
    header.appendChild(titulo);

    const body = document.createElement('div');
    body.className = 'card-body d-flex flex-column';

    const pFecha = document.createElement('p');
    pFecha.className = 'card-text';
    pFecha.innerHTML = '<i class="bi bi-calendar-event me-2"></i>' + formatearFecha(evento.fecha_evento);
    body.appendChild(pFecha);

    const pHora = document.createElement('p');
    pHora.className = 'card-text';
    pHora.innerHTML = '<i class="bi bi-clock me-2"></i>' + formatearHora(evento.hora_evento);
    body.appendChild(pHora);

    const pLugar = document.createElement('p');
    pLugar.className = 'card-text';
    pLugar.innerHTML = '<i class="bi bi-geo-alt me-2"></i>' + (evento.lugar || 'Lugar no definido');
    body.appendChild(pLugar);

    const pDesc = document.createElement('p');
    pDesc.className = 'card-text flex-grow-1';
    pDesc.textContent = recortarTexto(evento.descripcion);
    body.appendChild(pDesc);

    const mtAuto = document.createElement('div');
    mtAuto.className = 'mt-auto';
    const dGrid = document.createElement('div');
    dGrid.className = 'd-grid';
    const enlace = document.createElement('a');
    enlace.className = 'btn ' + colorBoton;
    if (evento.id_evento) {
      enlace.href = '/detalle-evento?id=' + evento.id_evento;
    }
    enlace.textContent = 'Ver actividades';
    dGrid.appendChild(enlace);
    mtAuto.appendChild(dGrid);
    body.appendChild(mtAuto);

    card.appendChild(header);
    card.appendChild(body);
    item.appendChild(card);
    return item;
  }

  function obtenerEventos() {
    const urlEventos = scrollContainer.getAttribute('data-url') || '/inicio-eventos-data';

    fetch(urlEventos)
      .then(function (r) { return r.json(); })
      .then(function (res) {
        scrollContainer.innerHTML = '';
        if (!res.success || !Array.isArray(res.data) || res.data.length === 0) {
          const vacio = document.createElement('div');
          vacio.className = 'text-center w-100 py-5';
          vacio.innerHTML = '<p class="mb-0"><i class="bi bi-calendar-x me-2"></i>Pronto tendremos nuevos eventos para ti.</p>';
          scrollContainer.appendChild(vacio);
          return;
        }
        res.data.forEach(function (evento, i) {
          scrollContainer.appendChild(crearTarjeta(evento, i));
        });
      })
      .catch(function () {
        scrollContainer.innerHTML = '<div class="text-center w-100 py-5"><p class="mb-0"><i class="bi bi-exclamation-triangle me-2"></i>No se pudieron cargar los eventos. Inténtalo de nuevo más tarde.</p></div>';
      });
  }

  obtenerEventos();

  function obtenerDistanciaCard() {
    const card = scrollContainer.querySelector('.evento-item');

    if (!card) return 0;
    const estilos = window.getComputedStyle(scrollContainer);
    const gap = parseFloat(estilos.gap) || 0;
    return card.offsetWidth + gap;
  }

  nextButton.addEventListener('click', () => {
    scrollContainer.scrollBy({
      left: obtenerDistanciaCard(),
      behavior: 'smooth'
    });
  });

  prevButton.addEventListener('click', () => {
    scrollContainer.scrollBy({
      left: -obtenerDistanciaCard(),
      behavior: 'smooth'
    });
  });

  let isDown = false;
  let startX;
  let scrollLeft;

  scrollContainer.addEventListener('mousedown', (e) => {
    isDown = true;
    scrollContainer.classList.add('dragging');
    startX = e.pageX - scrollContainer.offsetLeft;
    scrollLeft = scrollContainer.scrollLeft;
  });

  scrollContainer.addEventListener('mouseleave', () => {
    isDown = false;
    scrollContainer.classList.remove('dragging');
  });

  scrollContainer.addEventListener('mouseup', () => {
    isDown = false;
    scrollContainer.classList.remove('dragging');
  });

  scrollContainer.addEventListener('mousemove', (e) => {
    if (!isDown) return;
    e.preventDefault();
    const x = e.pageX - scrollContainer.offsetLeft;
    const walk = (x - startX) * 1.5;
    scrollContainer.scrollLeft = scrollLeft - walk;
  });
});
