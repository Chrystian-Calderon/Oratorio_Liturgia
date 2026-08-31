# Oratorio Liturgia

Sistema de gestión para el Oratorio — eventos, inscripciones, personas, actividades, asistencias, sacramentos y reportes.

---

## Requisitos

- PHP 8.1+
- MySQL 8.0+ o MariaDB 10.5+
- Extensiones: `mysqli`, `json`, `session`
- **No requiere Composer**

---

## Instalación

```bash
# 1. Clonar el repositorio
git clone <url>
cd Oratorio_Liturgia

# 2. Configurar base de datos
cp .env.example .env
# Editar .env con tus credenciales de MySQL

# 3. Importar schema
mysql -u root -p oratorio < oratorio.sql

# 4. Configurar servidor web (Apache con mod_rewrite)
# La carpeta raíz del vhost debe apuntar a Oratorio_Liturgia/
```

### Variables de entorno (`.env`)

```env
DB_HOST=127.0.0.1
DB_PORT=3306
DB_NAME=oratorio
DB_USER=app
DB_PASSWORD=password
BASE_URL=http://localhost:8000
```

---

## Arquitectura

### Flujo de requests

```
index.php → bootstrap.php → router.php → {controller} → {view} → AdminLayout/PublicLayout
```

| Archivo | Función |
|---|---|
| `index.php` | Front controller: inicia sesión, carga bootstrap, delega al router |
| `bootstrap.php` | Define `appPath()`, `url()`, `env()`, carga `.env` |
| `servidor/router.php` | Enruta URLs a controllers/vistas |

### Convenciones de rutas

- **Vistas** (GET): `pagina('cliente/pages/admin/X.php', $datos)` — renderiza HTML
- **APIs** (POST/PUT/DELETE): `despachar($method, [...])` — retorna JSON
- **URLs**: `url('/ruta')` genera la URL completa; `appPath()` genera rutas del filesystem

### Layouts

| Archivo | Uso |
|---|---|
| `AdminLayout.php` | Layout admin: sidebar + navbar + CSS Grid (`280px 1fr`) |
| `PublicLayout.php` | Layout público: navbar + footer |

### Componentes

| Archivo | Descripción |
|---|---|
| `Sidebar.php` | Sidebar autocontenido (HTML + JS IIFE: accordion, active page, mobile, theme) |
| `NavbarAdmin.php` | Navbar admin: toggle sidebar, responsive, ESC key, theme toggle |
| `Navbar.php` | Navbar público |
| `Notificacion.php` | Sistema de notificaciones toast |

### Sidebar (localStorage)

| Key | Valores | Gestiona |
|---|---|---|
| `sidebarVisible` | `'true'` / `'false'` | `NavbarAdmin.php` |
| `theme` | `'dark'` / `'light'` | `Sidebar.php` |

---

## Estructura del proyecto

```
Oratorio_Liturgia/
├── index.php                    # Front controller
├── bootstrap.php                # Helpers: appPath(), url(), env()
├── .env / .env.example          # Configuración de entorno
├── .htaccess                    # Apache mod_rewrite
├── docker-compose.yml           # MySQL 8.4
├── oratorio.sql                 # Schema de base de datos
│
├── cliente/                     # FRONTEND
│   ├── assets/
│   │   ├── css/                 # 31 hojas de estilo (una por página/módulo)
│   │   ├── js/                  # Scripts: navbar, carousel, Dashboard, notificaciones
│   │   └── img/                 # Imágenes estáticas + carousel
│   │
│   ├── components/              # Componentes PHP reutilizables
│   │   ├── Sidebar.php
│   │   ├── NavbarAdmin.php
│   │   ├── Navbar.php
│   │   ├── Notificacion.php
│   │   └── footer/              # FooterIndex.php, FooterPublic.php
│   │
│   ├── layouts/                 # Layouts maestros
│   │   ├── AdminLayout.php
│   │   └── PublicLayout.php
│   │
│   └── pages/                   # Páginas por rol
│       ├── admin/               # 20+ páginas admin (Dashboard, CRUD, Reportes, Paneles)
│       │   ├── reportes/        # 6 vistas de reportes
│       │   ├── actividades/     # index.php + form.php
│       │   ├── eventos/         # index.php + form.php
│       │   ├── inscripcion/     # index.php + form.php
│       │   ├── personas/        # index.php + form.php
│       │   ├── roles/           # index.php + form.php
│       │   ├── universidades/   # index.php + form.php
│       │   └── sacramentos/     # index.php + form.php
│       ├── private/             # Páginas autenticadas
│       │   └── IniciarSesion.php
│       └── public/              # 14 páginas públicas
│           ├── PaginaInicio.php
│           ├── Ver_Actividades.php
│           ├── Ver_Eventos.php
│           ├── detalle_actividad.php
│           ├── detalle_evento.php
│           ├── registrarse_actividad.php
│           ├── login.php
│           ├── registrarse.php
│           └── ...
│
└── servidor/                    # BACKEND
    ├── router.php               # Enrutador principal
    ├── config/database.php      # Conexión MySQL (conectar())
    ├── helpers/respuesta.php    # respuestaJson()
    │
    ├── actividades/             # CRUD: listar, guardar, actualizar, eliminar, formulario, detalle, ver
    ├── eventos/                 # CRUD: listar, guardar, actualizar, eliminar, formulario, detalle, ver
    ├── personas/                # CRUD: listar, guardar, actualizar, eliminar, formulario
    ├── roles/                   # CRUD: listar, guardar, actualizar, eliminar, formulario
    ├── universidades/           # CRUD: listar, guardar, actualizar, eliminar, formulario
    ├── sacramentos/             # CRUD: listar, guardar, actualizar, eliminar, formulario
    ├── inscripcion/             # listar, actualizar, eliminar, formulario, registrar
    ├── pagos/                   # guardar
    ├── panel/                   # Endpoints JSON para gráficos (actividades, eventos)
    ├── reportes/                # 6 endpoints JSON (eventos, actividades, participantes, sacramentos, asistencias, pagos)
    ├── carousel/                # listar, actualizar, guardar-imagen, eliminar-imagen
    ├── perfil/                  # actualizar
    │
    ├── validar_login.php        # Login público → session
    ├── validar_IniciarSesion.php # Login admin (solo Administrativo)
    ├── validar_inscripcion.php   # POST inscripción pública
    ├── validar_actividades.php   # Form admin actividades (legacy)
    ├── validar_asistencias.php   # Form admin asistencias (legacy)
    ├── validar_pagos.php         # Form admin pagos (legacy)
    ├── validar_personas.php      # Form admin personas (legacy)
    ├── validar_usuarios_sistema.php # Form admin usuarios (legacy)
    ├── registrar_usuario.php     # Registro público
    ├── recuperar.php             # Recuperar contraseña
    ├── reset.php                 # Reset contraseña
    ├── logout.php                # Cerrar sesión
    ├── conexionBD.php            # Conexión legacy (used by older admin forms)
    │
    └── data/carousel.json        # Configuración del carousel (JSON)
```

---

## Módulos

### Admin (requieren login `Administrativo`)

| Ruta | Módulo | Descripción |
|---|---|---|
| `/dashboard` | Dashboard | Estadísticas generales con gráficos |
| `/panel-eventos` | Panel Eventos | Gráficos + tabla de eventos |
| `/panel-actividades` | Panel Actividades | Gráficos + tabla de actividades |
| `/panel-carousel` | Panel Carousel | Administrar imágenes del carousel |
| `/eventos` | Eventos | CRUD completo de eventos |
| `/actividades` | Actividades | CRUD completo de actividades |
| `/inscripcion` | Inscripciones | Editar/eliminar inscripciones |
| `/personas` | Personas | CRUD completo de personas |
| `/roles` | Roles | CRUD de roles del sistema |
| `/universidades` | Universidades | CRUD de universidades |
| `/sacramentos` | Sacramentos | CRUD de formularios de sacramentos |
| `/asistencias` | Asistencias | Control de asistencia |
| `/pagos` | Pagos | Gestión de pagos |
| `/mis-eventos` | Mis Eventos | Eventos del usuario actual |
| `/reportes/*` | Reportes | 6 vistas: eventos, actividades, participantes, sacramentos, asistencias, pagos |
| `/perfil` | Perfil | Editar perfil del usuario |
| `/ayuda` | Ayuda | Centro de ayuda con 14 secciones |

### Públicas (no requieren login)

| Ruta | Página |
|---|---|
| `/` o `/inicio` | Página principal |
| `/ver-actividades` | Lista de actividades públicas |
| `/ver-eventos` | Lista de eventos públicos |
| `/detalle-actividad?id=X` | Detalle de actividad |
| `/detalle-evento?id=X` | Detalle de evento |
| `/inscripcion/registrar?id=X` | Inscripción a actividad (requiere login) |
| `/servicios` | Servicios |
| `/nosotros` | Acerca de nosotros |
| `/contacto` | Contacto |
| `/calendario` | Calendario |
| `/participar` | Participar |
| `/login` | Login público |
| `/registrarse` | Registro de usuario |
| `/recuperar-password` | Recuperar contraseña |
| `/reset-password` | Reset contraseña |

---

## Cómo agregar una nueva ruta

### 1. Vista (página HTML)

```php
// En servidor/router.php, agregar dentro del switch:
case '/mi-nueva-pagina':
    pagina('cliente/pages/admin/mi_pagina.php', [
        'titulo' => 'Mi Página',
    ]);
    break;
```

### 2. Endpoint JSON (API)

```php
// En servidor/router.php:
case '/mi-api':
    despachar($method, [
        'GET'  => 'servidor/mi_modulo/listar.php',
        'POST' => 'servidor/mi_modulo/guardar.php',
    ]);
    break;
```

### 3. Crear el archivo del controller

```php
<?php
// servidor/mi_modulo/listar.php
declare(strict_types=1);
require_once appPath('servidor/config/database.php');
require_once appPath('servidor/helpers/respuesta.php');

$conexion = conectar();
$datos = $conexion->query("SELECT * FROM mi_tabla")->fetch_all(MYSQLI_ASSOC);
$conexion->close();

respuestaJson(true, 'Datos obtenidos.', $datos);
```

### 4. Crear la vista

```php
<?php
$pageTitle = 'Mi Página';
$pageStyles = ['<link rel="stylesheet" href="' . url('/assets/css/mi_pagina.css') . '">'];
?>
<?php ob_start(); ?>
<!-- Contenido HTML aquí -->
<?php
$content = ob_get_clean();
require_once appPath('cliente/layouts/AdminLayout.php');
```

---

## Base de datos

Tablas principales:

| Tabla | Descripción |
|---|---|
| `personas` | Usuarios del sistema (nombres, apellidos, ci, correo, tipo_persona) |
| `usuarios_sistema` | Roles y permisos |
| `eventos` | Eventos del oratorio |
| `actividades` | Actividades de cada evento (FK → eventos) |
| `inscripcion` | Inscripciones de personas a actividades |
| `asistencias` | Control de asistencia |
| `universidades` | Universidades asociadas |
| `formulario_sacramentos` | Formularios de sacramentos |

---

## Tecnologías

- **Backend**: PHP 8.1 (sin framework, sin Composer)
- **Frontend**: HTML5, CSS3, JavaScript vanilla
- **UI**: Bootstrap 5.3.7, Font Awesome 6.5.0
- **Gráficos**: Chart.js (via CDN)
- **Exportación**: SheetJS (XLSX), jsPDF + jsPDF-AutoTable
- **Base de datos**: MySQL 8.0+ / MariaDB
- **Servidor web**: Apache con mod_rewrite
