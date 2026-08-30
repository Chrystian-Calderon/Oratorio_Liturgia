<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Usuarios del Sistema</title>
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
            padding: 40px 0;
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

        .form-label .required-star {
            color: #fc8181;
            font-weight: 600;
            margin-left: 2px;
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

        /* PERMISOS - Checkboxes mejorados */
        .permissions-grid {
            background: #f7fafc;
            border-radius: 12px;
            padding: 25px 30px 20px;
            border: 2px solid #e2e8f0;
            transition: all 0.3s ease;
        }

        .permissions-grid:hover {
            border-color: #667eea;
            background: #fafbff;
        }

        .permission-item {
            display: flex;
            align-items: center;
            padding: 10px 12px;
            border-radius: 8px;
            transition: all 0.2s ease;
            margin-bottom: 4px;
            cursor: pointer;
        }

        .permission-item:hover {
            background: rgba(102, 126, 234, 0.06);
        }

        .permission-item .form-check {
            display: flex;
            align-items: center;
            width: 100%;
            margin: 0;
            padding: 0;
            min-height: auto;
        }

        .permission-item .form-check-input {
            position: relative;
            flex-shrink: 0;
            width: 20px;
            height: 20px;
            margin: 0 14px 0 0;
            border: 2px solid #cbd5e0;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.2s ease;
            background: white;
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
        }

        .permission-item .form-check-input:checked {
            background-color: #667eea;
            border-color: #667eea;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3e%3cpath fill='none' stroke='%23fff' stroke-linecap='round' stroke-linejoin='round' stroke-width='3' d='M6 10l3 3l6-6'/%3e%3c/svg%3e");
            background-size: 14px;
            background-position: center;
            background-repeat: no-repeat;
        }

        .permission-item .form-check-input:focus {
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.25);
            border-color: #667eea;
        }

        .permission-item .form-check-input:checked:focus {
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.4);
        }

        .permission-item .form-check-label {
            font-size: 14px;
            font-weight: 500;
            color: #2d3748;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            margin: 0;
            padding: 0;
            line-height: 1.5;
        }

        .permission-item .form-check-label i {
            color: #667eea;
            font-size: 16px;
            width: 20px;
            text-align: center;
        }

        /* Estilo para el contenedor de columnas */
        .permissions-column {
            padding: 0 5px;
        }

        .permissions-column .permission-item {
            padding: 8px 10px;
        }

        /* Contador de permisos */
        .permissions-counter {
            font-size: 13px;
            color: #718096;
            display: flex;
            align-items: center;
            gap: 8px;
            margin-top: 18px;
            padding-top: 16px;
            border-top: 2px dashed #e2e8f0;
            justify-content: center;
        }

        .permissions-counter span {
            font-weight: 700;
            color: #667eea;
        }

        .help-tip {
            color: #a0aec0;
            font-size: 14px;
            cursor: help;
            transition: color 0.2s ease;
        }

        .help-tip:hover {
            color: #667eea;
        }

        .permission-badge {
            display: inline-block;
            padding: 2px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
            background: #ebf4ff;
            color: #667eea;
            margin-left: 6px;
        }

        /* Botones */
        .btn {
            border-radius: 12px;
            padding: 14px 32px;
            font-weight: 600;
            font-size: 16px;
            transition: all 0.3s ease;
            letter-spacing: 0.3px;
            position: relative;
            overflow: hidden;
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

        .btn-primary::after {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 0;
            height: 200%;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            transition: all 0.5s ease;
        }

        .btn-primary:hover::after {
            width: 200%;
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

            .permissions-grid {
                padding: 15px 15px 15px;
            }

            .permission-item {
                padding: 8px 8px;
            }

            .permission-item .form-check-input {
                width: 18px;
                height: 18px;
                margin-right: 10px;
            }

            .btn-group-actions {
                flex-direction: column;
                align-items: center;
            }

            .btn {
                width: 100%;
                max-width: 300px;
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

            .permissions-grid {
                padding: 12px 10px 12px;
            }

            .permission-item {
                padding: 6px 6px;
            }

            .permission-item .form-check-label {
                font-size: 13px;
            }

            .permission-item .form-check-input {
                width: 16px;
                height: 16px;
                margin-right: 8px;
            }

            .permissions-counter {
                font-size: 12px;
                flex-wrap: wrap;
                justify-content: center;
            }
        }

        /* Animaciones */
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

        .row .col-md-6:nth-child(1) .form-control,
        .row .col-md-6:nth-child(1) .form-select { animation-delay: 0.05s; }
        .row .col-md-6:nth-child(2) .form-control,
        .row .col-md-6:nth-child(2) .form-select { animation-delay: 0.1s; }

        /* Estilo para el contenedor de cada columna de permisos */
        .permissions-column-wrapper {
            display: flex;
            flex-direction: column;
        }

        /* Selector de rol mejorado */
        .rol-select-wrapper {
            max-width: 100%;
        }
    </style>
</head>

<body>
    <div class="main-wrapper">
        <div class="card shadow-lg">

            <!-- Header -->
            <div class="card-header">
                <h3>
                    <i class="bi bi-people-fill me-2"></i>
                    Registro de Usuario del Sistema
                </h3>
                <p>Configure los permisos y rol del nuevo usuario</p>
            </div>

            <!-- Body -->
            <div class="card-body">

                <!-- Formulario -->
                <form action="../servidor/validar_usuarios_sistema.php" method="POST" novalidate>

                    <!-- Información del Usuario -->
                    <div class="section-title">
                        <i class="bi bi-person-gear"></i>
                        Información del Usuario
                    </div>

                    <div class="row">
                        <div class="col-md-12 mb-3 rol-select-wrapper">
                            <label for="rol" class="form-label">
                                <i class="bi bi-tag me-1"></i>
                                Rol <span class="required-star">*</span>
                            </label>
                            <select id="rol" name="txtrol" class="form-select" required>
                                <option value="" selected disabled>Seleccione un rol</option>
                                <option value="Administrador">🔐 Administrador</option>
                                <option value="Coordinador">📋 Coordinador</option>
                                <option value="Sacerdote">⛪ Sacerdote</option>
                                <option value="Docente">👨‍🏫 Docente</option>
                                <option value="Estudiante">🎓 Estudiante</option>
                                <option value="Voluntario">🤝 Voluntario</option>
                                <option value="Encargado">📌 Encargado</option>
                                <option value="Externo">🌐 Externo</option>
                            </select>
                        </div>
                    </div>

                    <!-- Permisos -->
                    <div class="section-title mt-4">
                        <i class="bi bi-shield-check"></i>
                        Permisos del Usuario
                        <span class="permission-badge">Seleccione los módulos</span>
                    </div>

                    <div class="permissions-grid" id="permissionsGrid">

                        <div class="row">
                            <!-- Columna Izquierda -->
                            <div class="col-md-6 permissions-column">
                                <div class="permissions-column-wrapper">
                                    <div class="permission-item">
                                        <div class="form-check">
                                            <input class="form-check-input"
                                                type="checkbox"
                                                name="permisos[]"
                                                value="Usuarios"
                                                id="usuarios">
                                            <label class="form-check-label" for="usuarios">
                                                <i class="bi bi-people"></i>
                                                Gestión de Usuarios
                                            </label>
                                        </div>
                                    </div>

                                    <div class="permission-item">
                                        <div class="form-check">
                                            <input class="form-check-input"
                                                type="checkbox"
                                                name="permisos[]"
                                                value="Personas"
                                                id="personas">
                                            <label class="form-check-label" for="personas">
                                                <i class="bi bi-person-badge"></i>
                                                Gestión de Personas
                                            </label>
                                        </div>
                                    </div>

                                    <div class="permission-item">
                                        <div class="form-check">
                                            <input class="form-check-input"
                                                type="checkbox"
                                                name="permisos[]"
                                                value="Universidades"
                                                id="universidades">
                                            <label class="form-check-label" for="universidades">
                                                <i class="bi bi-building"></i>
                                                Gestión de Universidades
                                            </label>
                                        </div>
                                    </div>

                                    <div class="permission-item">
                                        <div class="form-check">
                                            <input class="form-check-input"
                                                type="checkbox"
                                                name="permisos[]"
                                                value="Eventos"
                                                id="eventos">
                                            <label class="form-check-label" for="eventos">
                                                <i class="bi bi-calendar-event"></i>
                                                Gestión de Eventos
                                            </label>
                                        </div>
                                    </div>

                                    <div class="permission-item">
                                        <div class="form-check">
                                            <input class="form-check-input"
                                                type="checkbox"
                                                name="permisos[]"
                                                value="Actividades"
                                                id="actividades">
                                            <label class="form-check-label" for="actividades">
                                                <i class="bi bi-list-task"></i>
                                                Gestión de Actividades
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Columna Derecha -->
                            <div class="col-md-6 permissions-column">
                                <div class="permissions-column-wrapper">
                                    <div class="permission-item">
                                        <div class="form-check">
                                            <input class="form-check-input"
                                                type="checkbox"
                                                name="permisos[]"
                                                value="Inscripciones"
                                                id="inscripciones">
                                            <label class="form-check-label" for="inscripciones">
                                                <i class="bi bi-person-plus"></i>
                                                Gestión de Inscripciones
                                            </label>
                                        </div>
                                    </div>

                                    <div class="permission-item">
                                        <div class="form-check">
                                            <input class="form-check-input"
                                                type="checkbox"
                                                name="permisos[]"
                                                value="Pagos"
                                                id="pagos">
                                            <label class="form-check-label" for="pagos">
                                                <i class="bi bi-credit-card"></i>
                                                Gestión de Pagos
                                            </label>
                                        </div>
                                    </div>

                                    <div class="permission-item">
                                        <div class="form-check">
                                            <input class="form-check-input"
                                                type="checkbox"
                                                name="permisos[]"
                                                value="Asistencias"
                                                id="asistencias">
                                            <label class="form-check-label" for="asistencias">
                                                <i class="bi bi-check2-circle"></i>
                                                Gestión de Asistencias
                                            </label>
                                        </div>
                                    </div>

                                    <div class="permission-item">
                                        <div class="form-check">
                                            <input class="form-check-input"
                                                type="checkbox"
                                                name="permisos[]"
                                                value="Reportes"
                                                id="reportes">
                                            <label class="form-check-label" for="reportes">
                                                <i class="bi bi-graph-up"></i>
                                                Reportes
                                            </label>
                                        </div>
                                    </div>

                                    <div class="permission-item">
                                        <div class="form-check">
                                            <input class="form-check-input"
                                                type="checkbox"
                                                name="permisos[]"
                                                value="Configuracion"
                                                id="configuracion">
                                            <label class="form-check-label" for="configuracion">
                                                <i class="bi bi-gear"></i>
                                                Configuración del Sistema
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Contador de permisos seleccionados -->
                        <div class="permissions-counter" id="permissionsCounter">
                            <i class="bi bi-info-circle"></i>
                            Permisos seleccionados: <span id="selectedCount">0</span> de 10
                            <span class="help-tip" title="Seleccione los módulos a los que el usuario tendrá acceso">
                                <i class="bi bi-question-circle"></i>
                            </span>
                        </div>
                    </div>

                    <!-- Botones de acción -->
                    <div class="btn-group-actions mt-4">
                        <button 
                            type="reset" 
                            class="btn btn-outline-secondary px-5"
                            onclick="resetPermissionsCounter()"
                        >
                            <i class="bi bi-eraser me-2"></i>
                            Limpiar
                        </button>

                        <button 
                            type="submit" 
                            class="btn btn-primary px-5"
                        >
                            <i class="bi bi-check-circle me-2"></i>
                            Registrar Usuario
                        </button>
                    </div>

                </form>

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Contador de permisos seleccionados
        document.addEventListener('DOMContentLoaded', function() {
            const checkboxes = document.querySelectorAll('input[name="permisos[]"]');
            const selectedCount = document.getElementById('selectedCount');

            function updateCounter() {
                const checked = document.querySelectorAll('input[name="permisos[]"]:checked');
                selectedCount.textContent = checked.length;
            }

            checkboxes.forEach(checkbox => {
                checkbox.addEventListener('change', updateCounter);
            });

            // Actualizar contador inicial
            updateCounter();

            // Select de rol con efecto visual
            const rolSelect = document.getElementById('rol');
            rolSelect.addEventListener('change', function() {
                const selectedValue = this.options[this.selectedIndex]?.text || '';
                if (selectedValue.includes('Administrador')) {
                    // Sugerir seleccionar todos los permisos para administrador
                    const allCheckboxes = document.querySelectorAll('input[name="permisos[]"]');
                    const adminTip = document.createElement('div');
                    adminTip.className = 'alert alert-info alert-sm mt-2';
                    adminTip.innerHTML = '<i class="bi bi-info-circle"></i> Sugerencia: El administrador generalmente tiene acceso a todos los módulos.';
                    
                    // Remover sugerencias anteriores
                    const oldTips = document.querySelectorAll('.admin-tip');
                    oldTips.forEach(tip => tip.remove());
                    
                    adminTip.classList.add('admin-tip');
                    rolSelect.parentNode.appendChild(adminTip);
                } else {
                    // Remover sugerencias
                    const oldTips = document.querySelectorAll('.admin-tip');
                    oldTips.forEach(tip => tip.remove());
                }
            });
        });

        // Función para resetear contador al limpiar el formulario
        function resetPermissionsCounter() {
            setTimeout(() => {
                const selectedCount = document.getElementById('selectedCount');
                const checked = document.querySelectorAll('input[name="permisos[]"]:checked');
                selectedCount.textContent = checked.length;
            }, 50);
        }

        // Validación de al menos un permiso seleccionado
        document.querySelector('form').addEventListener('submit', function(e) {
            const checked = document.querySelectorAll('input[name="permisos[]"]:checked');
            if (checked.length === 0) {
                e.preventDefault();
                alert('⚠️ Debe seleccionar al menos un permiso para el usuario.');
                document.getElementById('permissionsGrid').style.borderColor = '#fc8181';
                document.getElementById('permissionsGrid').style.background = '#fff5f5';
                
                setTimeout(() => {
                    document.getElementById('permissionsGrid').style.borderColor = '#e2e8f0';
                    document.getElementById('permissionsGrid').style.background = '#f7fafc';
                }, 3000);
            }
        });
    </script>
</body>

</html>