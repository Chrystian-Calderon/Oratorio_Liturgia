document.addEventListener('DOMContentLoaded', () => {
  const notificaciones = document.querySelectorAll('.notificacion');

  notificaciones.forEach((notificacion) => {
    const cerrar = () => {
      notificacion.classList.add('saliendo');

      setTimeout(() => {
        notificacion.remove();
      }, 400);

    };

    // Cerrar automáticamente después de 3 segundos
    setTimeout(cerrar, 3000);

    // Cerrar manualmente
    const botonCerrar =
      notificacion.querySelector('.notificacion-cerrar');

    if (botonCerrar) {
      botonCerrar.addEventListener('click', cerrar);
    }

  });

});