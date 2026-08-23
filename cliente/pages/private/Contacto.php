<?php
$pageTitle = "Contacto";
ob_start();
$pageStyles = [
    'cliente/assets/css/contacto.css',
];
?>

    <!-- Header -->
    <div class="header">
        <div class="container">
            <h1>Oratorio y Liturgia</h1>
        </div>
    </div>

    <!-- Contact Information -->
    <div class="container">
        <div class="contact-info text-center">
            <h4>Contáctanos directamente</h4>
            <div class="row justify-content-center mt-3">
                <div class="col-md-3">
                    <div class="contact-phone">
                        <i class="bi bi-telephone-fill location-icon"></i> 70639589
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="contact-phone">
                        <i class="bi bi-telephone-fill location-icon"></i> 72002192
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content - Map and Form Side by Side -->
    <div class="main-content">
        <div class="container">
            <div class="row">
                <!-- Left Column - Map -->
                <div class="col-lg-6 mb-4">
                    <div class="location-card">
                        <h3 class="location-title">Ubicación - La Paz</h3>
                        <p>Estamos ubicados en la Av.Chacaltaya Nro.1258, Zona Achachicala.</p>
                        <div class="mb-3">
                            <h5><i class="bi bi-geo-alt-fill location-icon"></i> Dirección</h5>
                        </div>

                        <div class="map-container">
                            <iframe
                                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3636.6419600359477!2d-68.15194522508669!3d-16.478240884262092!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x915edffbe7677a7d%3A0xce3ee13e9e12ee12!2sUniversidad%20Salesiana%20de%20Bolivia!5e1!3m2!1ses-419!2sbo!4v1758774140220!5m2!1ses-419!2sbo"
                                width="100%"
                                height="400"
                                style="border:0;"
                                allowfullscreen=""
                                loading="lazy"
                                referrerpolicy="no-referrer-when-downgrade">
                            </iframe>
                        </div>
                    </div>
                </div>

                <!-- Right Column - Form -->
                <div class="col-lg-6 mb-4">
                    <div class="form-container">
                        <h3 class="form-title">Envíanos un mensaje</h3>
                        <p>Completa el formulario y te contactaremos a la brevedad.</p>

                        <form action="https://formspree.io/f/maqqoeby" method="POST">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="nombre" class="form-label">Nombre *</label>
                                    <input type="text" class="form-control" id="nombre" name="nombre" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="apellido" class="form-label">Apellido *</label>
                                    <input type="text" class="form-control" id="apellido" name="apellido" required>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="email" class="form-label">Email *</label>
                                    <input type="email" class="form-control" id="email" name="email" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="telefono" class="form-label">Teléfono</label>
                                    <input type="tel" class="form-control" id="telefono" name="telefono">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="asunto" class="form-label">Asunto *</label>
                                <select class="form-select" id="asunto" name="asunto" required>
                                    <option value="" selected disabled>Selecciona un asunto</option>
                                    <option value="informacion">Información general</option>
                                    <option value="servicios">Consulta sobre servicios</option>
                                    <option value="cita">Solicitud de cita</option>
                                    <option value="otros">Otros</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="mensaje" class="form-label">Mensaje *</label>
                                <textarea class="form-control" id="mensaje" name="mensaje" rows="5" placeholder="Escribe tu mensaje aquí..." required></textarea>
                            </div>

                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" id="privacidad" name="acepta_privacidad" required>
                                <label class="form-check-label" for="privacidad">Acepto la política de privacidad *</label>
                            </div>

                            <div class="text-center">
                                <button type="submit" class="btn btn-primary-custom">Enviar Mensaje</button>
                            </div>

                            <p class="text-muted mt-3" style="font-size: 0.85rem;">* Campos obligatorios</p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <div class="footer">
        <div class="container text-center">
            <h4>Oratorio y Liturgia - USB</h4>
            <p class="mb-0">&copy; 2026. Todos los derechos son reservados.</p>
        </div>
    </div>
<?php
$content = ob_get_clean();
require appPath('cliente/layouts/PublicLayout.php');