<?php

require_once __DIR__ . '/configuration/env.php';

cargarEnv(__DIR__ . '/../.env');

function env(string $nombre, mixed $default = null): mixed
{
    return $_ENV[$nombre] ?? $default;
}