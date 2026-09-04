<?php

function despachar(string $metodo, array $rutas): void
{
    if (array_key_exists($metodo, $rutas)) {
        require appPath($rutas[$metodo]);
        return;
    }

    http_response_code(405);
    echo 'Método no permitido';
}

function pagina(string $vista, array $datos = []): void
{
    extract($datos);
    require appPath($vista);
}

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

switch ($uri) {

    case '/':
    case '/inicio':
        pagina(
            'cliente/pages/public/PaginaInicio.php'
        );
        break;

    case '/servicios':
        pagina(
            'cliente/pages/public/Servicios.php'
        );
        break;

    case '/nosotros':
        pagina(
            'cliente/pages/public/AcercaNosotros.php'
        );
        break;

    case '/noticias':
        pagina(
            'cliente/pages/public/Noticias.php'
        );
        break;

    case '/carousel-detalle':
        pagina(
            'cliente/pages/public/CarouselDetalle.php'
        );
        break;

    case '/contacto':
        pagina(
            'cliente/pages/public/Contacto.php'
        );
        break;

    case '/calendario':
        pagina(
            'cliente/pages/public/Calendario.php'
        );
        break;

    case '/participar':
        pagina(
            'cliente/pages/public/Participar.php'
        );
        break;

    case '/ver-actividades':
        pagina(
            'cliente/pages/public/Ver_Actividades.php'
        );
        break;

    case '/ver-eventos':
        pagina(
            'cliente/pages/public/Ver_Eventos.php'
        );
        break;

    case '/detalle-actividad':
        pagina(
            'cliente/pages/public/detalle_actividad.php'
        );
        break;

    case '/detalle-evento':
        pagina(
            'cliente/pages/public/detalle_evento.php'
        );
        break;

    case '/inscripcion/registrar':
        pagina(
            'cliente/pages/public/registrarse_actividad.php'
        );
        break;

    case '/validar-inscripcion':
        despachar($method, [
            'POST' => 'servidor/validar_inscripcion.php',
        ]);
        break;

    case '/login':
        despachar($method, [
            'POST' => 'servidor/validar_login.php',
            'GET'  => 'cliente/pages/public/login.php',
        ]);
        break;

    case '/registrarse':
        despachar($method, [
            'POST' => 'servidor/registrar_usuario.php',
            'GET'  => 'cliente/pages/public/registrarse.php',
        ]);
        break;

    case '/recuperar-password':
        despachar($method, [
            'POST' => 'servidor/recuperar.php',
            'GET'  => 'cliente/pages/public/forget-password.php',
        ]);
        break;
    
    case '/reset-password':
        despachar($method, [
            'POST' => 'servidor/reset.php',
            'GET'  => 'cliente/pages/public/reset.php',
        ]);
        break;

    case '/login-admin':
        despachar($method, [
            'POST' => 'servidor/validar_IniciarSesion.php',
            'GET'  => 'cliente/pages/private/IniciarSesion.php',
        ]);
        break;
    
    case '/logout':
        despachar($method, [
            'POST' => 'servidor/logout.php',
        ]);
        break;

    case '/logout-admin':
        despachar($method, [
            'POST' => 'servidor/logout_admin.php',
        ]);
        break;

    case '/dashboard':
        pagina(
            'cliente/pages/admin/Dashboard.php'
        );
        break;

    case '/dashboard-data':
        despachar($method, [
            'GET' => 'servidor/dashboard/datos.php',
        ]);
        break;

    case '/panel-actividades':
        pagina(
            'cliente/pages/admin/Panel_actividades.php'
        );
        break;

    case '/panel-actividades-data':
        despachar($method, [
            'GET' => 'servidor/panel/actividades.php',
        ]);
        break;

    case '/panel-eventos':
        pagina(
            'cliente/pages/admin/Panel_Eventos.php'
        );
        break;

    case '/panel-eventos-data':
        despachar($method, [
            'GET' => 'servidor/panel/eventos.php',
        ]);
        break;

    // roles (usuarios_sistema)
    case '/roles':
        despachar($method, [
            'GET' => 'servidor/roles/listar.php',
        ]);
        break;

    case '/roles/nuevo':
        despachar($method, [
            'GET' => 'servidor/roles/formulario.php',
            'POST' => 'servidor/roles/guardar.php',
        ]);
        break;

    case '/roles/editar':
        despachar($method, [
            'GET' => 'servidor/roles/formulario.php',
            'PUT' => 'servidor/roles/actualizar.php',
        ]);
        break;

    case '/roles/guardar':
        despachar($method, [
            'POST' => 'servidor/roles/guardar.php',
        ]);
        break;

    case '/roles/actualizar':
        despachar($method, [
            'PUT' => 'servidor/roles/actualizar.php',
        ]);
        break;

    case '/roles/eliminar':
        despachar($method, [
            'DELETE' => 'servidor/roles/eliminar.php',
        ]);
        break;

    // personas
    case '/personas':
        despachar($method, [
            'GET' => 'servidor/personas/listar.php',
        ]);
        break;

    case '/personas/nuevo':
        despachar($method, [
            'GET' => 'servidor/personas/formulario.php',
            'POST' => 'servidor/personas/guardar.php',
        ]);
        break;

    case '/personas/editar':
        despachar($method, [
            'GET' => 'servidor/personas/formulario.php',
            'PUT' => 'servidor/personas/actualizar.php',
        ]);
        break;

    case '/personas/guardar':
        despachar($method, [
            'POST' => 'servidor/personas/guardar.php',
        ]);
        break;

    case '/personas/actualizar':
        despachar($method, [
            'PUT' => 'servidor/personas/actualizar.php',
        ]);
        break;

    case '/personas/eliminar':
        despachar($method, [
            'DELETE' => 'servidor/personas/eliminar.php',
        ]);
        break;

    // participantes

    // actividades
    case '/actividades':
        despachar($method, [
            'GET' => 'servidor/actividades/listar.php',
        ]);
        break;

    case '/actividades/nuevo':
        despachar($method, [
            'GET' => 'servidor/actividades/formulario.php',
            'POST' => 'servidor/actividades/guardar.php',
        ]);
        break;

    case '/actividades/editar':
        despachar($method, [
            'GET' => 'servidor/actividades/formulario.php',
            'PUT' => 'servidor/actividades/actualizar.php',
        ]);
        break;

    case '/actividades/guardar':
        despachar($method, [
            'POST' => 'servidor/actividades/guardar.php',
        ]);
        break;

    case '/actividades/actualizar':
        despachar($method, [
            'PUT' => 'servidor/actividades/actualizar.php',
        ]);
        break;

    case '/actividades/eliminar':
        despachar($method, [
            'DELETE' => 'servidor/actividades/eliminar.php',
        ]);
        break;

    // asistencias
    case '/asistencias':
        despachar($method, [
            'GET' => 'servidor/asistencias/listar.php',
        ]);
        break;

    case '/asistencias/guardar':
        despachar($method, [
            'POST' => 'servidor/asistencias/guardar.php',
        ]);
        break;

    case '/asistencias/eliminar':
        despachar($method, [
            'DELETE' => 'servidor/asistencias/eliminar.php',
        ]);
        break;

    case '/eventos':
        despachar($method, [
            'GET' => 'servidor/eventos/listar.php',
        ]);
        break;

    case '/eventos/nuevo':
        despachar($method, [
            'GET' => 'servidor/eventos/formulario.php',
            'POST' => 'servidor/eventos/guardar.php',
        ]);
        break;

    case '/eventos/editar':
        despachar($method, [
            'GET' => 'servidor/eventos/formulario.php',
            'PUT' => 'servidor/eventos/actualizar.php',
        ]);
        break;

    case '/eventos/guardar':
        despachar($method, [
            'POST' => 'servidor/eventos/guardar.php',
        ]);
        break;

    case '/eventos/actualizar':
        despachar($method, [
            'PUT' => 'servidor/eventos/actualizar.php',
        ]);
        break;

    case '/eventos/eliminar':
        despachar($method, [
            'DELETE' => 'servidor/eventos/eliminar.php',
        ]);
        break;

    // inscripcion
    case '/inscripcion':
        despachar($method, [
            'GET' => 'servidor/inscripcion/listar.php',
        ]);
        break;

    case '/inscripcion/editar':
        despachar($method, [
            'GET' => 'servidor/inscripcion/formulario.php',
            'PUT' => 'servidor/inscripcion/actualizar.php',
        ]);
        break;

    case '/inscripcion/actualizar':
        despachar($method, [
            'PUT' => 'servidor/inscripcion/actualizar.php',
        ]);
        break;

    case '/inscripcion/eliminar':
        despachar($method, [
            'DELETE' => 'servidor/inscripcion/eliminar.php',
        ]);
        break;

    case '/pagos/guardar':
        despachar($method, [
            'POST' => 'servidor/pagos/guardar.php',
        ]);
        break;

    case '/pagos':
        pagina(
            'cliente/pages/admin/pagos.php'
        );
        break;

    case '/universidades':
        despachar($method, [
            'GET' => 'servidor/universidades/listar.php',
        ]);
        break;

    case '/universidades/nuevo':
        despachar($method, [
            'GET' => 'servidor/universidades/formulario.php',
            'POST' => 'servidor/universidades/guardar.php',
        ]);
        break;

    case '/universidades/editar':
        despachar($method, [
            'GET' => 'servidor/universidades/formulario.php',
            'PUT' => 'servidor/universidades/actualizar.php',
        ]);
        break;

    case '/universidades/guardar':
        despachar($method, [
            'POST' => 'servidor/universidades/guardar.php',
        ]);
        break;

    case '/universidades/actualizar':
        despachar($method, [
            'PUT' => 'servidor/universidades/actualizar.php',
        ]);
        break;

    case '/universidades/eliminar':
        despachar($method, [
            'DELETE' => 'servidor/universidades/eliminar.php',
        ]);
        break;
    
    // sacramentos
    case '/sacramentos':
        despachar($method, [
            'GET' => 'servidor/sacramentos/listar.php',
        ]);
        break;

    case '/sacramentos/nuevo':
        despachar($method, [
            'GET' => 'servidor/sacramentos/formulario.php',
            'POST' => 'servidor/sacramentos/guardar.php',
        ]);
        break;

    case '/sacramentos/editar':
        despachar($method, [
            'GET' => 'servidor/sacramentos/formulario.php',
            'PUT' => 'servidor/sacramentos/actualizar.php',
        ]);
        break;

    case '/sacramentos/guardar':
        despachar($method, [
            'POST' => 'servidor/sacramentos/guardar.php',
        ]);
        break;

    case '/sacramentos/actualizar':
        despachar($method, [
            'PUT' => 'servidor/sacramentos/actualizar.php',
        ]);
        break;

    case '/sacramentos/eliminar':
        despachar($method, [
            'DELETE' => 'servidor/sacramentos/eliminar.php',
        ]);
        break;

    // reportes - vistas HTML
    case '/reportes/eventos':
        despachar($method, ['GET' => 'cliente/pages/admin/reportes/eventos.php']);
        break;
    case '/reportes/actividades':
        despachar($method, ['GET' => 'cliente/pages/admin/reportes/actividades.php']);
        break;
    case '/reportes/participantes':
        despachar($method, ['GET' => 'cliente/pages/admin/reportes/participantes.php']);
        break;
    case '/reportes/formacion-sacramental':
        despachar($method, ['GET' => 'cliente/pages/admin/reportes/sacramentos.php']);
        break;
    case '/reportes/asistencias':
        despachar($method, ['GET' => 'cliente/pages/admin/reportes/asistencias.php']);
        break;
    case '/reportes/pagos':
        despachar($method, ['GET' => 'cliente/pages/admin/reportes/pagos.php']);
        break;

    // reportes - datos JSON
    case '/reportes/eventos-data':
        despachar($method, ['GET' => 'servidor/reportes/eventos.php']);
        break;
    case '/reportes/actividades-data':
        despachar($method, ['GET' => 'servidor/reportes/actividades.php']);
        break;
    case '/reportes/participantes-data':
        despachar($method, ['GET' => 'servidor/reportes/participantes.php']);
        break;
    case '/reportes/sacramentos-data':
        despachar($method, ['GET' => 'servidor/reportes/sacramentos.php']);
        break;
    case '/reportes/asistencias-data':
        despachar($method, ['GET' => 'servidor/reportes/asistencias.php']);
        break;
    case '/reportes/pagos-data':
        despachar($method, ['GET' => 'servidor/reportes/pagos.php']);
        break;

    case '/mis-eventos':
        pagina(
            'cliente/pages/admin/MisEventos.php'
        );
        break;

    // ayuda
    case '/ayuda':
        pagina(
            'cliente/pages/admin/ayuda.php'
        );
        break;

    // auditoría (visual - demo)
    case '/auditoria':
        pagina(
            'cliente/pages/admin/auditoria.php'
        );
        break;

    case '/auditoria-data':
        despachar($method, [
            'GET' => 'servidor/auditoria/listar.php',
        ]);
        break;

    // carrusel panel
    case '/panel-carousel':
        pagina(
            'cliente/pages/admin/Panel_Carousel.php'
        );
        break;

    case '/panel-carousel-data':
        despachar($method, [
            'GET' => 'servidor/carousel/listar.php',
        ]);
        break;

    case '/panel-carousel/actualizar':
        despachar($method, [
            'PUT' => 'servidor/carousel/actualizar.php',
        ]);
        break;

    case '/panel-carousel/subir-imagen':
        despachar($method, [
            'POST' => 'servidor/carousel/guardar-imagen.php',
        ]);
        break;

    case '/panel-carousel/eliminar-imagen':
        despachar($method, [
            'DELETE' => 'servidor/carousel/eliminar-imagen.php',
        ]);
        break;

    // perfil
    case '/mi-perfil':
        pagina(
            'cliente/pages/public/perfil.php'
        );
        break;

    case '/perfil':
        pagina(
            'cliente/pages/admin/perfil.php'
        );
        break;

    case '/perfil/actualizar':
        despachar($method, [
            'PUT' => 'servidor/perfil/actualizar.php',
        ]);
        break;

    // sugerencias y contacto
    case '/sugerencias':
        pagina(
            'cliente/pages/admin/Sugerencias.php'
        );
        break;

    case '/sugerencias/guardar':
        despachar($method, [
            'POST' => 'servidor/sugerencias/guardar.php',
        ]);
        break;

    case '/sugerencias/listar':
        despachar($method, [
            'GET' => 'servidor/sugerencias/listar.php',
        ]);
        break;

    case '/sugerencias/contador':
        despachar($method, [
            'GET' => 'servidor/sugerencias/contador.php',
        ]);
        break;

    case '/sugerencias/actualizar-estado':
        despachar($method, [
            'PUT' => 'servidor/sugerencias/actualizar_estado.php',
        ]);
        break;

    case '/contacto-admin':
        pagina(
            'cliente/pages/admin/ContactoAdmin.php'
        );
        break;

    case '/contacto/guardar':
        despachar($method, [
            'POST' => 'servidor/contacto/guardar.php',
        ]);
        break;

    case '/inicio-eventos-data':
        despachar($method, [
            'GET' => 'servidor/inicio/eventos.php',
        ]);
        break;

    default:
        http_response_code(404);
        echo 'Página no encontrada';
}