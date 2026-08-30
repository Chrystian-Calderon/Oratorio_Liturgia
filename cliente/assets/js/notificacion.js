document.addEventListener('DOMContentLoaded', () => {
  document
    .querySelectorAll('.notificacion')
    .forEach(configurarNotificacion);

});


function configurarNotificacion(notificacion) {
  const cerrar = () => {
    if (notificacion.classList.contains('saliendo')) {
      return;
    }

    notificacion.classList.add('saliendo');
    setTimeout(() => {
      notificacion.remove();
    }, 400);
  };

  setTimeout(cerrar, 3000);
  const botonCerrar = notificacion.querySelector('.notificacion-cerrar');
  if (botonCerrar) {
    botonCerrar.addEventListener(
      'click',
      cerrar
    );
  }
}


function mostrarNotificacion(mensaje, tipo = 'success') {

  const notificacion = document.createElement('div');
  notificacion.className = `notificacion notificacion-${tipo}`;

  notificacion.setAttribute('role', 'alert');
  notificacion.innerHTML = `
        <span class="notificacion-mensaje"></span>

        <button
            type="button"
            class="notificacion-cerrar"
            aria-label="Cerrar notificación"
        >
            ×
        </button>
    `;

  // Evita insertar el mensaje como HTML
  notificacion.querySelector('.notificacion-mensaje').textContent = mensaje;
  document.body.appendChild(notificacion);
  configurarNotificacion(notificacion);
}