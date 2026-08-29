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

function pagina(string $vista): void
{
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
        if ($method === 'POST') {
            require appPath(
                'servidor/reset.php'
            );
            break;
        }
        if ($method === 'GET') {
            require appPath(
                'servidor/reset.php'
            );
            require appPath(
                'cliente/pages/public/reset.php'
            );
            break;
        }
        http_response_code(405);
        echo 'Método no permitido';
        break;

    case '/login-admin':
        despachar($method, [
            'POST' => 'servidor/validar_IniciarSesion.php',
            'GET'  => 'cliente/pages/private/IniciarSesion.php',
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

    default:
        http_response_code(404);
        echo 'Página no encontrada';
}