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

    case '/dashboard':
        pagina(
            'cliente/pages/admin/Dashboard.php'
        );
        break;

    case '/panel-actividades':
        pagina(
            'cliente/pages/admin/Panel_actividades.php'
        );
        break;

    case '/panel-eventos':
        pagina(
            'cliente/pages/admin/Panel_Eventos.php'
        );
        break;

    // usuarios 
    case '/usuarios':
        despachar($method, [
            'GET' => 'servidor/usuarios/listar.php',
            'PUT' => 'servidor/usuarios/actualizar.php',
            'DELETE' => 'servidor/usuarios/eliminar.php',
        ]);
        break;

    case '/personas':
        pagina(
            'cliente/pages/admin/personas1.php'
        );
        break;

    // participantes

    case '/actividades':
        pagina(
            'cliente/pages/admin/actividades.php'
        );
        break;

    case '/asistencias':
        pagina(
            'cliente/pages/admin/asistencias.php'
        );
        break;

    case '/eventos':
        pagina(
            'cliente/pages/admin/eventos.php'
        );
        break;

    case '/inscripcion':
        pagina(
            'cliente/pages/admin/inscripcion.php'
        );
        break;

    case '/pagos':
        pagina(
            'cliente/pages/admin/pagos.php'
        );
        break;

    case '/personas-form':
        pagina(
            'cliente/pages/admin/personas.php'
        );
        break;

    case '/universidades':
        pagina(
            'cliente/pages/admin/universidades.php'
        );
        break;
    
    case '/usuarios-form':
        pagina(
            'cliente/pages/admin/usuarios_sistema.php'
        );
        break;

    case '/formacion-sacramental':
        pagina(
            'cliente/pages/admin/FormacionSacramental.php'
        );
        break;

    case '/mis-eventos':
        pagina(
            'cliente/pages/admin/MisEventos.php'
        );
        break;

    default:
        http_response_code(404);
        echo 'Página no encontrada';
}