<?php

function mostrarNotificacion(
    string $mensaje,
    string $tipo = 'success'
): void {
?>
    <div
        class="notificacion notificacion-<?php echo htmlspecialchars($tipo); ?>"
        role="alert"
    >
        <span class="notificacion-mensaje">
            <?php echo htmlspecialchars($mensaje); ?>
        </span>

        <button
            type="button"
            class="notificacion-cerrar"
            aria-label="Cerrar notificación"
        >
            ×
        </button>
    </div>
<?php
}