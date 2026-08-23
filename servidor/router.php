<?php

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

switch ($uri) {

    case '/':
    case '/inicio':
        require appPath(
            'cliente/pages/public/PaginaInicio.php'
        );
        break;
      
    case '/servicios':
        require appPath(
            'cliente/pages/public/Servicios.php'
        );
        break;

    case '/nosotros':
        require appPath(
            'cliente/pages/public/AcercaNosotros.php'
        );
        break;

    case '/contacto':
        require appPath(
            'cliente/pages/public/Contacto.php'
        );
        break;

    case '/calendario':
        require appPath(
            'cliente/pages/public/Calendario.php'
        );
        break;

    case '/participar':
        require appPath(
            'cliente/pages/public/Participar.php'
        );
        break;

    case '/dashboard':
        require appPath(
            'cliente/Dashboard.php'
        );
        break;

    case '/login':
        if ($method === 'POST') {
            require appPath(
                'servidor/validar_login.php'
            );
            break;
        }
        if ($method === 'GET') {
          require appPath(
              'cliente/pages/public/login.php'
          );
          break;
        }
        http_response_code(405);
        echo 'Método no permitido';
        break;

    case '/login-admin':
        if ($method === 'POST') {
            require appPath(
                'servidor/validar_IniciarSesion.php'
            );
            break;
        }
        if ($method === 'GET') {
            require appPath(
                'cliente/pages/private/IniciarSesion.php'
            );
            break;
        }

        http_response_code(405);
        echo 'Método no permitido';
        break;

    default:
        http_response_code(404);
        echo 'Página no encontrada';
}