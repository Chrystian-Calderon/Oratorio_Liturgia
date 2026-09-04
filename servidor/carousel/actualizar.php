<?php
declare(strict_types=1);
require_once appPath('servidor/helpers/respuesta.php');

$jsonPath = appPath('servidor/data/carousel.json');

if (!file_exists($jsonPath)) {
    respuestaJson(false, 'No existe configuración del carrusel.', null, 404);
}

$input = json_decode(file_get_contents('php://input'), true);

if (!$input || !isset($input['id'])) {
    respuestaJson(false, 'Faltan datos obligatorios.', null, 400);
}

$id = (int) $input['id'];
$titulo = trim($input['titulo'] ?? '');
$subtitulo = trim($input['subtitulo'] ?? '');
$descripcion = trim($input['descripcion'] ?? '');
$activo = $input['activo'] ?? true;

if ($titulo === '') {
    respuestaJson(false, 'El título es obligatorio.', null, 400);
}

$contenido = file_get_contents($jsonPath);
$datos = json_decode($contenido, true);

if ($datos === null) {
    respuestaJson(false, 'Error al leer la configuración.', null, 500);
}

$encontrado = false;
foreach ($datos['slides'] as &$slide) {
    if ((int) $slide['id'] === $id) {
        $slide['titulo'] = $titulo;
        $slide['subtitulo'] = $subtitulo;
        $slide['descripcion'] = $descripcion;
        $slide['activo'] = (bool) $activo;
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

respuestaJson(true, 'Slide actualizado.', $datos);