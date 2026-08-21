<?php
require_once("../servidor/conexionBD.php");

$sql = "SELECT id_universidad, nombre
        FROM universidades
        WHERE estado='Activo'
        ORDER BY nombre ASC";

$resultado = mysqli_query($conexion, $sql);

if (!$resultado) {
  die("Error al cargar universidades: " . mysqli_error($conexion));
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Registro de Personas</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      min-height: 100vh;
      padding: 30px 0;
      font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    }

    .main-wrapper {
      max-width: 900px;
      margin: 0 auto;
      padding: 0 20px;
    }

    .card {
      border: none;
      border-radius: 24px;
      background: #ffffff;
      box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
      overflow: hidden;
      transition: transform 0.3s ease;
    }

    .card:hover {
      transform: translateY(-5px);
    }

    .card-header {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      color: white;
      padding: 35px 40px 30px;
      border: none;
      position: relative;
      overflow: hidden;
    }

    .card-header::before {
      content: '';
      position: absolute;
      top: -50%;
      right: -20%;
      width: 300px;
      height: 300px;
      background: rgba(255, 255, 255, 0.05);
      border-radius: 50%;
      pointer-events: none;
    }

    .card-header::after {
      content: '';
      position: absolute;
      bottom: -30%;
      left: -10%;
      width: 200px;
      height: 200px;
      background: rgba(255, 255, 255, 0.03);
      border-radius: 50%;
      pointer-events: none;
    }

    .card-header h3 {
      font-weight: 700;
      font-size: 28px;
      letter-spacing: -0.5px;
      position: relative;
      z-index: 1;
    }

    .card-header p {
      opacity: 0.9;
      font-weight: 400;
      font-size: 15px;
      position: relative;
      z-index: 1;
      margin-top: 5px;
    }

    .card-body {
      padding: 40px 45px 45px;
    }

    .section-title {
      font-size: 16px;
      font-weight: 600;
      color: #4a5568;
      margin-bottom: 25px;
      padding-left: 14px;
      border-left: 4px solid #667eea;
      display: flex;
      align-items: center;
      gap: 10px;
      letter-spacing: 0.3px;
    }

    .section-title i {
      font-size: 20px;
      color: #667eea;
    }

    .form-label {
      font-weight: 500;
      font-size: 14px;
      color: #2d3748;
      margin-bottom: 6px;
    }

    .form-label .text-danger {
      font-weight: 600;
    }

    .form-control,
    .form-select {
      border: 2px solid #e2e8f0;
      border-radius: 12px;
      padding: 12px 16px;
      font-size: 14px;
      transition: all 0.3s ease;
      background: #f7fafc;
      color: #2d3748;
    }

    .form-control:focus,
    .form-select:focus {
      border-color: #667eea;
      box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.15);
      background: #ffffff;
    }

    .form-control::placeholder {
      color: #a0aec0;
      font-size: 13px;
    }

    .form-control.is-invalid,
    .form-select.is-invalid {
      border-color: #fc8181;
      box-shadow: 0 0 0 4px rgba(252, 129, 129, 0.15);
    }

    .form-control.is-valid,
    .form-select.is-valid {
      border-color: #68d391;
      box-shadow: 0 0 0 4px rgba(104, 211, 145, 0.15);
    }

    .form-control[type="file"] {
      padding: 10px 12px;
      background: #f7fafc;
      cursor: pointer;
    }

    .form-control[type="file"]::file-selector-button {
      padding: 8px 20px;
      border: none;
      border-radius: 8px;
      background: #667eea;
      color: white;
      font-weight: 500;
      font-size: 13px;
      cursor: pointer;
      transition: background 0.3s ease;
      margin-right: 15px;
    }

    .form-control[type="file"]::file-selector-button:hover {
      background: #5a67d8;
    }

    .btn {
      border-radius: 12px;
      padding: 12px 32px;
      font-weight: 600;
      font-size: 15px;
      transition: all 0.3s ease;
      letter-spacing: 0.3px;
    }

    .btn-primary {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      border: none;
      color: white;
      box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
    }

    .btn-primary:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 25px rgba(102, 126, 234, 0.5);
      background: linear-gradient(135deg, #5a67d8 0%, #6b46a1 100%);
    }

    .btn-primary:active {
      transform: translateY(0px);
    }

    .btn-outline-secondary {
      border: 2px solid #e2e8f0;
      color: #4a5568;
      background: transparent;
    }

    .btn-outline-secondary:hover {
      background: #f7fafc;
      border-color: #cbd5e0;
      transform: translateY(-2px);
    }

    .btn-group-actions {
      display: flex;
      gap: 15px;
      justify-content: center;
      flex-wrap: wrap;
      padding-top: 10px;
    }

    .form-row {
      margin-bottom: 10px;
    }

    /* Responsive */
    @media (max-width: 768px) {
      .card-body {
        padding: 30px 20px 35px;
      }

      .card-header {
        padding: 25px 20px 20px;
      }

      .card-header h3 {
        font-size: 22px;
      }

      .btn-group-actions {
        flex-direction: column;
        align-items: center;
      }

      .btn {
        width: 100%;
        max-width: 300px;
      }

      .form-control[type="file"]::file-selector-button {
        display: block;
        margin-bottom: 8px;
        width: 100%;
      }
    }

    @media (max-width: 576px) {
      .main-wrapper {
        padding: 0 10px;
      }

      .card-body {
        padding: 20px 15px 25px;
      }

      .section-title {
        font-size: 14px;
      }
    }

    /* Animation for form fields */
    .form-control,
    .form-select {
      animation: fadeInUp 0.5s ease forwards;
      opacity: 0;
    }

    @keyframes fadeInUp {
      from {
        opacity: 0;
        transform: translateY(10px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    /* Stagger animation delay */
    .form-row .col-md-6:nth-child(1) .form-control,
    .form-row .col-md-6:nth-child(1) .form-select { animation-delay: 0.05s; }
    .form-row .col-md-6:nth-child(2) .form-control,
    .form-row .col-md-6:nth-child(2) .form-select { animation-delay: 0.1s; }
    .form-row .col-md-4:nth-child(1) .form-control,
    .form-row .col-md-4:nth-child(1) .form-select { animation-delay: 0.15s; }
    .form-row .col-md-4:nth-child(2) .form-control,
    .form-row .col-md-4:nth-child(2) .form-select { animation-delay: 0.2s; }
    .form-row .col-md-4:nth-child(3) .form-control,
    .form-row .col-md-4:nth-child(3) .form-select { animation-delay: 0.25s; }

    /* Small decorative elements */
    .required-star {
      color: #fc8181;
      font-weight: 600;
      margin-left: 2px;
    }
  </style>
</head>

<body>
  <div class="main-wrapper">
    <div class="card shadow-lg">

      <!-- Header -->
      <div class="card-header text-center">
        <h3>
          <i class="bi bi-person-plus-fill me-2"></i>
          Registro de Personas
        </h3>
        <p class="mb-0">Complete la información solicitada para crear una nueva cuenta</p>
      </div>

      <!-- Body -->
      <div class="card-body">

        <!-- Formulario -->
        <form action="../servidor/validar_personas.php" method="POST" enctype="multipart/form-data" novalidate>

          <!-- Información Personal -->
          <div class="section-title">
            <i class="bi bi-person-circle"></i>
            Información Personal
          </div>

          <div class="row form-row">
            <div class="col-md-6 mb-3">
              <label for="ci" class="form-label">
                <i class="bi bi-credit-card me-1"></i>
                Cédula de Identidad <span class="required-star">*</span>
              </label>
              <input 
                type="text" 
                class="form-control" 
                id="ci" 
                name="txtci" 
                placeholder="Ej: 12345678" 
                required
                pattern="[0-9]{7,8}"
                title="Ingrese un número de cédula válido (7-8 dígitos)"
              >
            </div>

            <div class="col-md-6 mb-3">
              <label for="id_universidad" class="form-label">
                <i class="bi bi-building me-1"></i>
                Universidad <span class="required-star">*</span>
              </label>
              <select
                id="id_universidad"
                name="id_universidad"
                class="form-select"
                required
              >
                <option value="">Seleccione una universidad</option>
                <?php while ($fila = mysqli_fetch_assoc($resultado)) { ?>
                  <option value="<?= $fila['id_universidad']; ?>">
                    <?= htmlspecialchars($fila['nombre']); ?>
                  </option>
                <?php } ?>
              </select>
            </div>
          </div>

          <div class="row form-row">
            <div class="col-md-6 mb-3">
              <label for="nombres" class="form-label">
                <i class="bi bi-person me-1"></i>
                Nombres <span class="required-star">*</span>
              </label>
              <input 
                type="text" 
                class="form-control" 
                id="nombres" 
                name="txtnombres" 
                placeholder="Ingrese sus nombres" 
                required
              >
            </div>

            <div class="col-md-6 mb-3">
              <label for="apellidos" class="form-label">
                <i class="bi bi-person me-1"></i>
                Apellidos <span class="required-star">*</span>
              </label>
              <input 
                type="text" 
                class="form-control" 
                id="apellidos" 
                name="txtapellidos" 
                placeholder="Ingrese sus apellidos" 
                required
              >
            </div>
          </div>

          <div class="row form-row">
            <div class="col-md-4 mb-3">
              <label for="genero" class="form-label">
                <i class="bi bi-gender-ambiguous me-1"></i>
                Género <span class="required-star">*</span>
              </label>
              <select id="genero" name="txtgenero" class="form-select" required>
                <option value="" selected disabled>Seleccione</option>
                <option value="Masculino">Masculino</option>
                <option value="Femenino">Femenino</option>
              </select>
            </div>

            <div class="col-md-4 mb-3">
              <label for="telefono" class="form-label">
                <i class="bi bi-phone me-1"></i>
                Teléfono
              </label>
              <input 
                type="tel" 
                class="form-control" 
                id="telefono" 
                name="txttelefono" 
                placeholder="Ej: 76543210"
                pattern="[0-9]{7,10}"
                title="Ingrese un número de teléfono válido (7-10 dígitos)"
              >
            </div>

            <div class="col-md-4 mb-3">
              <label for="correo" class="form-label">
                <i class="bi bi-envelope me-1"></i>
                Correo electrónico <span class="required-star">*</span>
              </label>
              <input 
                type="email" 
                class="form-control" 
                id="correo" 
                name="txtcorreo" 
                placeholder="ejemplo@correo.com" 
                required
              >
            </div>
          </div>

          <div class="row form-row">
            <div class="col-md-12 mb-3">
              <label for="direccion" class="form-label">
                <i class="bi bi-geo-alt me-1"></i>
                Dirección
              </label>
              <input 
                type="text" 
                class="form-control" 
                id="direccion" 
                name="txtdireccion" 
                placeholder="Ingrese su dirección completa"
              >
            </div>
          </div>

          <!-- Información de la Cuenta -->
          <div class="section-title mt-4">
            <i class="bi bi-shield-lock"></i>
            Información de la Cuenta
          </div>

          <div class="row form-row">
            <div class="col-md-6 mb-3">
              <label for="tipo_persona" class="form-label">
                <i class="bi bi-tag me-1"></i>
                Tipo de Persona <span class="required-star">*</span>
              </label>
              <select id="tipo_persona" name="txttipo_persona" class="form-select" required>
                <option value="" selected disabled>Seleccione</option>
                <option value="Estudiante">Estudiante</option>
                <option value="Docente">Docente</option>
                <option value="Voluntario">Voluntario</option>
                <option value="Sacerdote">Sacerdote</option>
                <option value="Administrativo">Administrativo</option>
                <option value="Externo">Externo</option>
              </select>
            </div>

            <div class="col-md-6 mb-3">
              <!-- Espacio para mantener el diseño -->
            </div>
          </div>

          <div class="row form-row">
            <div class="col-md-6 mb-3">
              <label for="password" class="form-label">
                <i class="bi bi-key me-1"></i>
                Contraseña <span class="required-star">*</span>
              </label>
              <input 
                type="password" 
                class="form-control" 
                id="password" 
                name="txtpassword" 
                placeholder="Mínimo 8 caracteres" 
                required
                minlength="8"
              >
              <small class="text-muted" style="font-size: 12px; margin-top: 4px; display: block;">
                <i class="bi bi-info-circle"></i> La contraseña debe tener al menos 8 caracteres
              </small>
            </div>

            <div class="col-md-6 mb-3">
              <label for="confirmar_password" class="form-label">
                <i class="bi bi-key-fill me-1"></i>
                Confirmar contraseña <span class="required-star">*</span>
              </label>
              <input 
                type="password" 
                class="form-control" 
                id="confirmar_password" 
                name="txtconfirmar_password" 
                placeholder="Repita la contraseña" 
                required
                minlength="8"
              >
            </div>
          </div>

          <!-- Fotografía -->
          <div class="section-title mt-4">
            <i class="bi bi-image"></i>
            Fotografía
          </div>

          <div class="row form-row">
            <div class="col-md-6 mb-3">
              <label for="foto_perfil" class="form-label">
                <i class="bi bi-camera me-1"></i>
                Foto de Perfil
              </label>
              <input 
                type="file" 
                class="form-control" 
                id="foto_perfil" 
                name="txtfoto_perfil" 
                accept="image/*"
              >
              <small class="text-muted" style="font-size: 12px; margin-top: 4px; display: block;">
                <i class="bi bi-info-circle"></i> Formatos permitidos: JPG, PNG, GIF (máx. 5MB)
              </small>
            </div>

            <div class="col-md-6 mb-3">
              <!-- Preview de imagen (opcional) -->
              <div id="imagePreview" style="display: none; margin-top: 5px;">
                <img id="previewImg" src="#" alt="Vista previa" style="max-width: 120px; max-height: 120px; border-radius: 12px; border: 2px solid #e2e8f0; padding: 4px;">
              </div>
            </div>
          </div>

          <!-- Botones de acción -->
          <div class="btn-group-actions mt-4">
            <button 
              type="reset" 
              class="btn btn-outline-secondary px-5"
            >
              <i class="bi bi-eraser me-2"></i>
              Limpiar
            </button>

            <button 
              type="submit" 
              class="btn btn-primary px-5"
            >
              <i class="bi bi-check-circle me-2"></i>
              Registrar Persona
            </button>
          </div>

        </form>

      </div>
    </div>
  </div>

  <!-- Bootstrap JS (opcional para funcionalidades) -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>

  <!-- Script para previsualización de imagen -->
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const fotoInput = document.getElementById('foto_perfil');
      const previewDiv = document.getElementById('imagePreview');
      const previewImg = document.getElementById('previewImg');

      fotoInput.addEventListener('change', function(e) {
        const file = this.files[0];
        if (file) {
          const reader = new FileReader();
          reader.onload = function(e) {
            previewImg.src = e.target.result;
            previewDiv.style.display = 'block';
          }
          reader.readAsDataURL(file);
        } else {
          previewDiv.style.display = 'none';
        }
      });

      // Validación de contraseñas
      const password = document.getElementById('password');
      const confirmPassword = document.getElementById('confirmar_password');

      function validatePasswords() {
        if (password.value !== confirmPassword.value) {
          confirmPassword.setCustomValidity('Las contraseñas no coinciden');
        } else {
          confirmPassword.setCustomValidity('');
        }
      }

      password.addEventListener('change', validatePasswords);
      confirmPassword.addEventListener('keyup', validatePasswords);
    });
  </script>
</body>

</html>