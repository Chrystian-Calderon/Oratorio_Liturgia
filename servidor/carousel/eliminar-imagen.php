<?php
declare(strict_types=1);
require_once appPath('servidor/helpers/respuesta.php');

$jsonPath = appPath('servidor/data/carousel.json');

if (!file_exists($jsonPath)) {
    respuestaJson(false, 'No existe configuración del carrusel.', null, 404);
}

$input = json_decode(file_get_contents('php://input'), true);

if (!$input || !isset($input['slide_id'])) {
    respuestaJson(false, 'Falta el ID del slide.', null, 400);
}

$slideId = (int) $input['slide_id'];

$contenido = file_get_contents($jsonPath);
$datos = json_decode($contenido, true);

if ($datos === null) {
    respuestaJson(false, 'Error al leer la configuración.', null, 500);
}

$encontrado = false;
foreach ($datos['slides'] as &$slide) {
    if ((int) $slide['id'] === $slideId) {
        if (empty($slide['imagen'])) {
            respuestaJson(false, 'Este slide no tiene imagen para eliminar.', null, 400);
        }

        $rutaImagen = appPath('cliente/assets/img/carusel/' . $slide['imagen']);
        if (file_exists($rutaImagen)) {
            unlink($rutaImagen);
        }

        $slide['imagen'] = '';
        $encontrado = true;
        break;
    }
}
unset($slide);

if (!$encontrado) {
    respuestaJson(false, 'Slide no encontrado.', null, 404);
}

$guardado = file_put_contents($jsonPath, json_encode($datos, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

if ($guardado === false) {
    respuestaJson(false, 'Error al guardar la configuración.', null, 500);
}

respuestaJson(true, 'Imagen eliminada.', $datos);