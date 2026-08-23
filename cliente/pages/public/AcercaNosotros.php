<?php
ob_start();
$pageTitle = "Acerca de Nosotros";
$pageStyles = [
    'cliente/assets/css/acerca_nosotros.css',
];
?>

    <!-- Header -->
    <header class="text-center py-5 shadow-lg">
      <h1 class="fw-bold">
        <i class="fas fa-users"></i> Acerca de Nosotros
      </h1>
      <p class="lead mb-0">
        Oratorio y Liturgia – Universidad Salesiana de Bolivia
      </p>
    </header>

    <!-- Main container -->
    <main class="container py-5">
      <!-- Imagen: Organigrama -->
      <div class="text-center mb-5">
        <img
          src="<?php echo url('cliente/assets/img/Organigrama.png') ?>"
          alt="Organigrama"
          class="img-fluid organigrama shadow-lg"
        />
      </div>

      <!-- Sección: Acerca de nosotros -->
      <section class="mb-5">
        <div class="card shadow border-0">
          <div class="card-body p-4">
            <h3 class="card-title text-primary mb-3">
              <i class="fas fa-info-circle"></i> Acerca de Nosotros
            </h3>
            <p class="mb-3">
              <strong>¿Qué es un oratorio?</strong><br />
              El Oratorio Salesiano es la experiencia juvenil educativa e
              informal inspirada en el sistema preventivo y en la persona de Don
              Bosco. Puede estar dentro de una obra salesiana o no tener relación
              directa, siempre que mantenga la inspiración en los valores
              salesianos.
            </p>
          </div>
        </div>
      </section>

      <!-- Horarios en formato horizontal -->
      <section class="mb-5">
        <div class="horario-horizontal">
          <div class="horario-item">
            <i class="fas fa-sun text-warning"></i>
            <div class="horario-texto">8:30 am - 16:30 pm</div>
          </div>
          <div class="horario-item">
            <i class="fas fa-cloud-moon text-primary"></i>
            <div class="horario-texto">16:30 pm - 18:00 pm</div>
          </div>
        </div>
      </section>

      <!-- Nuestro enfoque -->
      <section class="mb-5">
        <div class="card shadow border-0">
          <div class="card-body p-4">
            <h3 class="card-title text-danger mb-3">
              <i class="fas fa-bullseye"></i> Nuestro Enfoque
            </h3>
            <p class="fs-5">
              El Oratorio Pastoral de la Universidad Salesiana de Bolivia tiene
              como enfoque la <strong>formación integral</strong> de los jóvenes
              universitarios, promoviendo valores humanos, cristianos y
              salesianos a través de espacios de encuentro, acompañamiento
              espiritual y actividades culturales y sociales.
            </p>
          </div>
        </div>
      </section>

      <!-- Dimensiones -->
      <section class="mb-5">
        <div class="card shadow border-0">
          <div class="card-body p-4">
            <h3 class="card-title text-primary mb-4">
              <i class="fas fa-layer-group"></i> Dimensiones del Oratorio
            </h3>
            <div class="row g-4">
              <div class="col-md-6">
                <div class="dim-card p-4 rounded h-100">
                  <h5 class="text-success">
                    <i class="fas fa-graduation-cap"></i> Cultural
                  </h5>
                  <p>
                    Crecimiento cultural y educativo, formando jóvenes que se
                    insertan y repercuten positivamente en la sociedad.
                  </p>
                </div>
              </div>
              <div class="col-md-6">
                <div class="dim-card p-4 rounded h-100">
                  <h5 class="text-danger">
                    <i class="fas fa-church"></i> Evangelizadora
                  </h5>
                  <p>
                    Orientada a la madurez de los jóvenes en la fe y su
                    crecimiento dentro de la Iglesia, con Cristo como centro.
                  </p>
                </div>
              </div>
              <div class="col-md-6">
                <div class="dim-card p-4 rounded h-100">
                  <h5 class="text-primary">
                    <i class="fas fa-hands-helping"></i> Vocacional
                  </h5>
                  <p>
                    Presente en todas las actividades educativas y pastorales,
                    guiando a los jóvenes en su proyecto de vida.
                  </p>
                </div>
              </div>
              <div class="col-md-6">
                <div class="dim-card p-4 rounded h-100">
                  <h5 class="text-warning">
                    <i class="fas fa-users"></i> Asociativa
                  </h5>
                  <p>
                    Promueve la expresión social, la confianza y los valores
                    salesianos en un ambiente de familia.
                  </p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- Historia -->
      <section class="mb-5">
        <div class="card shadow border-0 bg-light">
          <div class="card-body p-4">
            <h3 class="card-title text-dark mb-3">
              <i class="fas fa-book"></i> Historia
            </h3>
            <p>
              El Oratorio Pastoral nació inspirado en el carisma de Don Bosco,
              como un espacio juvenil que une la fe, la educación y la
              recreación. En la Universidad Salesiana de Bolivia se consolidó
              como un lugar de acompañamiento espiritual y formación integral,
              promoviendo la participación en actividades pastorales, culturales,
              artísticas, académicas, litúrgicas y sociales.
            </p>
          </div>
        </div>
      </section>
    </main>

    <!-- Footer profesional con redes sociales y colores -->
    <footer class="bg-dark text-white pt-5 pb-3">
      <div class="container">
        <div class="row align-items-center">
          <!-- Información -->
          <div class="col-md-6 mb-3 mb-md-0 text-center text-md-start">
            <p class="mb-1">&copy; 2026 Oratorio y Liturgia - Universidad Salesiana de Bolivia</p>
          </div>

          <!-- Redes Sociales -->
          <div class="col-md-6 text-center text-md-end">
            <a href="#" class="fs-4 mx-2 social-icon whatsapp" aria-label="WhatsApp">
              <i class="fab fa-whatsapp"></i>
            </a>
            <a href="https://www.tiktok.com/@pastoraluniversitariausb?_t=ZM-8zZknjhgwL8&_r=1" class="fs-4 mx-2 social-icon tiktok" aria-label="TikTok">
              <i class="fab fa-tiktok"></i>
            </a>
            <a href="https://www.facebook.com/share/19hQUo9Yht/" class="fs-4 mx-2 social-icon facebook" aria-label="Facebook">
              <i class="fab fa-facebook-f"></i>
            </a>
            <a href="https://www.instagram.com/pastoraluniversitariausb?igsh=YzVlcW9uNDM3aHJm" class="fs-4 mx-2 social-icon instagram" aria-label="Instagram">
              <i class="fab fa-instagram"></i>
            </a>
          </div>
        </div>
      </div>
    </footer>
<?php
$content = ob_get_clean();
require appPath('cliente/layouts/PublicLayout.php');