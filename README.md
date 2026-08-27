# Oratorio Liturgia

Sistema de gestión para el Oratorio — eventos, inscripciones, personas, actividades, asistencias y reportes.

---

## Estructura actual

```
Oratorio_Liturgia/
├── index.php                  # Punto de entrada (front controller)
├── bootstrap.php              # Helpers: appPath(), url(), env()
├── .env / .env.example        # Configuración de entorno
├── docker-compose.yml         # Docker local
├── vercel.json                # Deploy Vercel
├── oratorio.sql               # Schema de base de datos
│
├── cliente/                   # FRONTEND (vistas, assets, componentes)
│   ├── assets/
│   │   ├── css/               # Hojas de estilo por página
│   │   │   ├── sidebar.css    # Sidebar + grid + overlay + mobile
│   │   │   ├── navbar.css     # Navbar admin
│   │   │   ├── Dashboard.css  # Dashboard
│   │   │   └── ...            # acerca_nosotros, calendario, contacto, etc.
│   │   ├── js/                # Scripts JS
│   │   │   ├── navbar.js      # Navbar behavior
│   │   │   ├── carousel.js    # Carousel
│   │   │   ├── Dashboard.js   # cargarModulo()
│   │   │   └── mini_estadisticas.js
│   │   └── img/               # Imágenes estáticas
│   │
│   ├── components/            # Componentes PHP reutilizables
│   │   ├── Sidebar.php        # Sidebar (HTML + JS inline IIFE)
│   │   ├── NavbarAdmin.php    # Navbar administrativo
│   │   ├── Navbar.php         # Navbar público
│   │   └── footer/
│   │       ├── FooterIndex.php
│   │       └── FooterPublic.php
│   │
│   ├── layouts/               # Layouts maestros
│   │   ├── AdminLayout.php    # Layout admin (sidebar + navbar + grid)
│   │   └── PublicLayout.php   # Layout público
│   │
│   ├── pages/                 # Páginas organizadas por rol
│   │   ├── admin/
│   │   │   ├── Dashboard.php
│   │   │   ├── Panel_actividades.php
│   │   │   └── Panel_Eventos.php
│   │   ├── private/
│   │   │   └── IniciarSesion.php
│   │   └── public/
│   │       ├── PaginaInicio.php
│   │       ├── login.php
│   │       ├── Calendario.php
│   │       ├── Contacto.php
│   │       ├── Servicios.php
│   │       ├── Participar.php
│   │       └── AcercaNosotros.php
│   │
│   ├── services/              # (vacío — pendiente de migrar lógica JS del front)
│   │
│   │── ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─
│   │  ARCHIVOS SUELTOS (se moverán a pages/ en la reorganización)
│   │── ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─
│   ├── actividades.php        # → pages/admin/ o pages/private/
│   ├── asistencias.php        # → pages/admin/
│   ├── Equipo.php             # → pages/public/
│   ├── estadisticas_actividades.php  # → pages/admin/
│   ├── eventos.php            # → pages/admin/
│   ├── forget-password.php    # → pages/public/
│   ├── FormacionSacramental.php  # → pages/public/
│   ├── Galeria.php            # → pages/public/
│   ├── inscripcion.php        # → pages/private/
│   ├── listarActividades.php  # → pages/admin/
│   ├── listarEventos.php      # → pages/admin/
│   ├── logout.php             # → pages/private/ o routes/
│   ├── menu.php               # → ELIMINAR (obsoleto, reemplazado por Sidebar)
│   ├── MisEventos.php         # → pages/admin/ (usa su propio sidebar inline)
│   ├── pagos.php              # → pages/admin/
│   ├── personas.php           # → pages/admin/
│   ├── personas1.php          # → pages/admin/ (duplicado?)
│   ├── registrarse.php        # → pages/public/
│   ├── reset.php              # → pages/public/
│   ├── universidades.php      # → pages/admin/
│   ├── usuarios_sistema.php   # → pages/admin/
│   ├── usuarios.php           # → pages/admin/
│   ├── Ver_Actividades.php    # → pages/public/ o pages/private/
│   └── Ver_Eventos.php        # → pages/public/ o pages/private/
│
├── servidor/                  # BACKEND (lógica de negocio, BD, rutas)
│   ├── index.php              # Router alternativo / legacy
│   ├── router.php             # Enrutador principal (incluido por index.php)
│   ├── conexionBD.php         # Conexión PDO a MySQL
│   │
│   ├── modules/               # (vacío — destino de la reorganización)
│   ├── infrastructure/        # (vacío — destino: BD, helpers, middlewares)
│   ├── routes/                # (vacío — destino: definiciones de rutas)
│   ├── configuration/         # (vacío — destino: config centralizada)
│   │
│   │── ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─
│   │  ARCHIVOS SUELTOS (se moverán a modules/, routes/, infrastructure/)
│   │── ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─
│   ├── validar_login.php          # → routes/ o modules/auth/
│   ├── validar_IniciarSesion.php  # → routes/ o modules/auth/
│   ├── registrar_usuario.php      # → modules/auth/
│   ├── actualizar_usuario.php     # → modules/auth/
│   ├── eliminar_usuario.php       # → modules/auth/
│   ├── recuperar.php              # → modules/auth/
│   ├── convertir_passwords.php    # → infrastructure/ (script utilitario)
│   ├── validar_personas.php       # → modules/personas/
│   ├── actualizar_personas1.php   # → modules/personas/
│   ├── eliminar_personas1.php     # → modules/personas/
│   ├── validar_eventos.php        # → modules/eventos/
│   ├── validar_actividades.php    # → modules/actividades/
│   ├── validar_asistencias.php    # → modules/asistencias/
│   ├── validar_pagos.php          # → modules/pagos/
│   ├── validar_universidades.php  # → modules/universidades/
│   ├── validar_usuarios_sistema.php  # → modules/admin/
│   ├── validar_inscripcion.php    # → modules/inscripciones/
│   ├── procesar_inscripcion_evento.php   # → modules/inscripciones/
│   ├── procesar_inscripcion_actividad.php  # → modules/inscripciones/
│   ├── guardar_inscripcion.php    # → modules/inscripciones/
│   ├── Formulario_Inscripcion.php # → modules/inscripciones/
│   └── sacramentos_db.php         # → modules/sacramentos/
│
├── css/                       # CSS LEGACY (se eliminará o migrará a cliente/assets/css/)
│   ├── login.css
│   ├── forget-password.css
│   └── menuu.css              # CSS del menú obsoleto
│
├── js/                        # JS LEGACY (se eliminará o migrará a cliente/assets/js/)
│   ├── bootstrap.min.js
│   ├── popper.min.js
│   └── menu.js                # JS del menú obsoleto
│
└── portafolio/                # Recursos estáticos
    ├── img/
    ├── librerias/
    └── videos/
```

---

## Arquitectura actual (cómo funciona hoy)

### Flujo de requests

```
index.php → bootstrap.php → router.php → cliente/pages/... → AdminLayout/PublicLayout
```

- `index.php`: front controller, inicia sesión, carga bootstrap, delega al router
- `bootstrap.php`: define `appPath()`, `url()`, `env()`, carga `.env`
- `router.php`: enruta según la URL a la página PHP correspondiente

### Layouts y componentes

| Archivo | Rol |
|---|---|
| `AdminLayout.php` | Layout maestro admin: sidebar + navbar + grid (`280px 1fr`). Incluye `#sidebarOverlay` |
| `PublicLayout.php` | Layout maestro público: navbar + footer |
| `Sidebar.php` | Componente autocontenido: HTML nav + IIFE JS (submenús accordion, active page, mobile close, theme) |
| `NavbarAdmin.php` | Navbar admin: toggle sidebar (`#sidebarToggle`), responsive, ESC key, theme toggle |

### Estado del sidebar (en `localStorage`)

| Key | Valores | Dónde se gestiona |
|---|---|---|
| `sidebarVisible` | `'true'` / `'false'` | `NavbarAdmin.php` (toggle + resize) |
| `theme` | `'dark'` / `'light'` | `Sidebar.php` (theme toggle) |

### CSS del sidebar (`sidebar.css`)

- Desktop: CSS Grid `grid-template-columns: 280px 1fr`
- Mobile (≤990px): sidebar fixed + `translateX(-100%)`, `.sidebar-open #sidebar { translateX(0) }`
- Clases: `.grid.sidebar-hidden` (desktop collapse), `.sidebar-open` (mobile open)
- Submenús: `display: none` / `display: block` via `.show`
- Overlay: `#sidebarOverlay.show`

### Páginas admin (ya limpias de JS duplicado)

- `Panel_actividades.php` — patrón limpio de referencia
- `Dashboard.php` — limpio, usa solo lógica de negocio
- `Panel_Eventos.php` — limpio, usa solo lógica de negocio

### Páginas que aún usan su propio sidebar inline

- `cliente/MisEventos.php` — tiene `<aside id="sidebar">` propio + `<nav id="navbar">` propio. **NO usa AdminLayout**. Pendiente de migrar.

---

## Pendiente (reorganización)

### Archivos sueltos en `cliente/` → mover a `cliente/pages/`
~22 archivos PHP raíz en `cliente/` necesitan clasificarse en `admin/`, `private/` o `public/`. Ver lista arriba con las flechas de destino.

### Archivos sueltos en `servidor/` → mover a `modules/`, `routes/`, `infrastructure/`
~22 archivos PHP de validación/procesamiento necesitan reorganizarse. Ver lista arriba con las flechas de destino.

### CSS/JS legacy en raíz → migrar o eliminar
- `css/login.css`, `css/forget-password.css`, `css/menuu.css` → consolidar en `cliente/assets/css/`
- `js/bootstrap.min.js`, `js/popper.min.js`, `js/menu.js` → eliminar (CDN ya se usa) o consolidar

### `cliente/MisEventos.php` → migrar a AdminLayout
Tiene sidebar/navbar inline. Debe usar `AdminLayout.php` como el resto de páginas admin.

### `cliente/services/` → llenar con lógica JS del frontend
Actualmente vacío. Destinado a helper functions JS reutilizables.
