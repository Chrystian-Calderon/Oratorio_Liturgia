<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Acerca de Nosotros</title>
    <link rel="shortcut icon" href="../assets/img/logo.jpg" />
    <!-- Bootstrap CSS -->
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
    />
    <!-- Font Awesome -->
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
    />
    <!-- Estilos personalizados -->
    <style>
      /* Header */
      header {
        background: linear-gradient(135deg, #0066cc, #00bfff);
        color: white;
        border-bottom: 4px solid #004080;
      }
      header h1 {
        font-size: 2.5rem;
      }
      header p {
        font-size: 1.2rem;
      }

      /* Cards */
      .card {
        border-radius: 15px;
        transition: transform 0.3s, box-shadow 0.3s;
      }
      .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 35px rgba(0,0,0,0.2);
      }
      .card-body h3 {
        font-size: 1.8rem;
      }

      /* Dimensiones */
      .dim-card {
        transition: transform 0.3s, box-shadow 0.3s;
        background: linear-gradient(145deg, #f0f0f0, #ffffff);
      }
      .dim-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.15);
      }

      /* Imagen (organigrama) */
      .organigrama {
        max-height: 500px;
        border-radius: 20px;
        border: 4px solid #0056b3;
        transition: transform 0.3s, box-shadow 0.3s;
      }
      .organigrama:hover {
        transform: scale(1.05);
        box-shadow: 0 20px 40px rgba(0,0,0,0.25);
      }

      /* Iconos y colores */
      .text-primary { color: #0056b3 !important; }
      .text-success { color: #198754 !important; }
      .text-danger { color: #dc3545 !important; }
      .text-warning { color: #ffc107 !important; }

      /* Listas */
      ul li {
        padding: 5px 0;
      }

      /* Footer */
      footer {
        font-size: 0.95rem;
      }
      footer a.social-icon {
        display: inline-block;
        transition: transform 0.3s, filter 0.3s;
      }
      footer a.social-icon:hover {
        transform: translateY(-3px);
        filter: brightness(1.2);
      }

      /* Colores de redes sociales */
      .social-icon.whatsapp { color: #25D366; }
      .social-icon.tiktok { color: #cac1c1; }
      .social-icon.facebook { color: #1877F2; }
      .social-icon.instagram { color: #E1306C; }

      /* Estilos para horario horizontal */
      .horario-horizontal {
        display: flex;
        flex-direction: row;
        justify-content: space-between;
        align-items: center;
        padding: 15px 20px;
        border-radius: 15px;
        background: linear-gradient(145deg, #f0f0f0, #ffffff);
        margin-bottom: 20px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
      }
      .horario-item {
        text-align: center;
        flex: 1;
      }
      .horario-item i {
        font-size: 2rem;
        margin-bottom: 10px;
      }
      .horario-item .horario-texto {
        font-size: 1.2rem;
        font-weight: bold;
      }

      /* Responsive ajustes */
      @media (max-width: 768px) {
        header h1 {
          font-size: 2rem;
        }
        .card-body h3 {
          font-size: 1.5rem;
        }
        .organigrama {
          max-height: 350px;
        }
        .horario-horizontal {
          flex-direction: column;
          gap: 15px;
        }
      }
    </style>
  </head>
  <body class="bg-light">
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
          src="../portafolio/img/Organigrama.png"
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html>