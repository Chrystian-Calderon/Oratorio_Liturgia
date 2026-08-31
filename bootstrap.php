<?php

define('BASE_PATH', __DIR__);

function appPath(string $path = ''): string
{
    return BASE_PATH . '/' . ltrim($path, '/');
}

function baseUrl(): string
{
    $https = isset($_SERVER['HTTPS'])
        && $_SERVER['HTTPS'] !== 'off';

    $protocol = $https ? 'https' : 'http';

    return $protocol . '://' . $_SERVER['HTTP_HOST'];
}

function url(string $path = ''): string
{
    return rtrim(baseUrl(), '/') . '/' . ltrim($path, '/');
}

function cargarEnv(string $ruta): void
{
    if (!file_exists($ruta)) {
        throw new RuntimeException(
            "No existe el archivo .env: {$ruta}"
        );
    }

    $lineas = file($ruta, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    foreach ($lineas as $linea) {

        $linea = trim($linea);

        // Ignorar comentarios
        if ($linea === '' || str_starts_with($linea, '#')) {
            continue;
        }

        [$nombre, $valor] = array_pad(
            explode('=', $linea, 2),
            2,
            null
        );

        if ($nombre === null) {
            continue;
        }

        $nombre = trim($nombre);
        $valor = trim($valor);

        // Quitar comillas
        $valor = trim($valor, "\"'");

        $_ENV[$nombre] = $valor;
    }
}

cargarEnv(BASE_PATH . '/.env');

function env(
    string $nombre,
    mixed $default = null
): mixed {
    return $_ENV[$nombre] ?? $default;
}