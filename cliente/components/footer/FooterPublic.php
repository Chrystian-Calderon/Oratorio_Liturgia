<?php
function renderFooterPublic(): void {
?>
<footer class="simple-footer py-5 text-white" style="background: rgba(3, 62, 160, 0.94);">
    <div class="container">
        <div class="row g-4 align-items-start">
            <div class="col-md-4 text-center text-md-start">
                <h5 class="fw-bold mb-3" style="color: #FFD700">Oratorio y Liturgia</h5>
                <p class="mb-0 text-white text-opacity-75">Un espacio dedicado al crecimiento espiritual, la comunidad y el servicio.</p>
            </div>

            <div class="col-md-5">
                <div class="footer-contact d-flex flex-column gap-2 text-center text-md-start text-white text-opacity-75">
                    <div><i class="fas fa-map-marker-alt me-2"></i> La Paz: Av. Chacaltaya Nro. 1258, Zona Achachicala.</div>
                    <div><i class="fas fa-phone me-2"></i> Celular: (591) 72002192</div>
                    <div><i class="fas fa-envelope me-2"></i> www.usalesiana.edu.bo</div>
                </div>
            </div>

            <div class="col-md-3 text-center text-md-end">
              <div class="social-links d-flex justify-content-center justify-content-md-end gap-4">
                  <a href="https://www.facebook.com/share/19hQUo9Yht/" target="_blank" aria-label="Facebook" style="color: #ffffff; font-size: 1.4rem;">
                      <i class="fab fa-facebook-f"></i>
                  </a>
                  <a href="https://www.instagram.com/pastoraluniversitariausb?igsh=YzVlcW9uNDM3aHJm" target="_blank" aria-label="Instagram" style="color: #ffffff; font-size: 1.4rem;">
                      <i class="fab fa-instagram"></i>
                  </a>
                  <a href="https://www.tiktok.com/@pastoraluniversitariausb?_t=ZM-8zZknjhgwL8&_r=1" target="_blank" aria-label="TikTok" style="color: #ffffff; font-size: 1.4rem;">
                      <i class="fab fa-tiktok"></i>
                  </a>
              </div>
          </div>
        </div>

        <hr class="my-4">
        <div class="footer-bottom text-center">
            <p class="mb-0 text-white text-opacity-75">&copy; 2026 Oratorio y Liturgia. Todos los derechos reservados.</p>
        </div>
    </div>
</footer>
<?php
}