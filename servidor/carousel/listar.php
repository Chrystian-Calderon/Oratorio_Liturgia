<?php
declare(strict_types=1);
require_once appPath('servidor/helpers/respuesta.php');

$jsonPath = appPath('servidor/data/carousel.json');

if (!file_exists($jsonPath)) {
    respuestaJson(false, 'No existe configuración del carrusel.', null, 404);
}

$contenido = file_get_contents($jsonPath);
$datos = json_decode($contenido, true);

if ($datos === null) {
    respuestaJson(false, 'Error al leer la configuración del carrusel.', null, 500);
}

respuestaJson(true, 'Carrusel cargado.', $datos);