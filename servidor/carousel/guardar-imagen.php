<?php
declare(strict_types=1);
require_once appPath('servidor/helpers/respuesta.php');

$jsonPath = appPath('servidor/data/carousel.json');
$uploadDir = appPath('cliente/assets/img/carusel');

if (!file_exists($jsonPath)) {
    respuestaJson(false, 'No existe configuración del carrusel.', null, 404);
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    respuestaJson(false, 'Método no permitido.', null, 405);
}

if (!isset($_FILES['imagen']) || $_FILES['imagen']['error'] !== UPLOAD_ERR_OK) {
    $errorCode = $_FILES['imagen']['error'] ?? -1;
    $messages = [
        UPLOAD_ERR_INI_SIZE => 'La imagen excede el tamaño máximo del servidor.',
        UPLOAD_ERR_FORM_SIZE => 'La imagen excede el tamaño máximo permitido.',
        UPLOAD_ERR_PARTIAL => 'La imagen se subió parcialmente.',
        UPLOAD_ERR_NO_FILE => 'No se seleccionó ninguna imagen.',
        UPLOAD_ERR_NO_TMP_DIR => 'Error del servidor: directorio temporal no encontrado.',
        UPLOAD_ERR_CANT_WRITE => 'Error del servidor: no se pudo escribir el archivo.',
    ];
    $msg = $messages[$errorCode] ?? 'Error al subir la imagen.';
    respuestaJson(false, $msg, null, 400);
}

if (!isset($_POST['slide_id'])) {
    respuestaJson(false, 'Falta el ID del slide.', null, 400);
}

$slideId = (int) $_POST['slide_id'];
$archivo = $_FILES['imagen'];

// Validar tipo MIME
$tiposPermitidos = [
    'image/jpeg' => 'jpg',
    'image/png' => 'png',
    'image/webp' => 'webp',
];

$finfo = new finfo(FILEINFO_MIME_TYPE);
$mime = $finfo->file($archivo['tmp_name']);

if (!isset($tiposPermitidos[$mime])) {
    respuestaJson(false, 'Tipo de imagen no permitido. Use JPG, PNG o WebP.', null, 400);
}

// Validar tamaño (5MB max)
if ($archivo['size'] > 5 * 1024 * 1024) {
    respuestaJson(false, 'La imagen no debe exceder 5 MB.', null, 400);
}

// Leer JSON para obtener imagen anterior
$contenido = file_get_contents($jsonPath);
$datos = json_decode($contenido, true);

if ($datos === null) {
    respuestaJson(false, 'Error al leer la configuración.', null, 500);
}

// Eliminar imagen anterior si existe
foreach ($datos['slides'] as &$slide) {
    if ((int) $slide['id'] === $slideId && !empty($slide['imagen'])) {
        $imagenAnterior = appPath('cliente/assets/img/carusel/' . $slide['imagen']);
        if (file_exists($imagenAnterior)) {
            unlink($imagenAnterior);
        }
        break;
    }
}
unset($slide);

// Crear directorio si no existe
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

// Generar nombre único
$extension = $tiposPermitidos[$mime];
$nombreArchivo = 'slide_' . $slideId . '_' . time() . '.' . $extension;
$rutaDestino = $uploadDir . '/' . $nombreArchivo;

// Mover archivo
if (!move_uploaded_file($archivo['tmp_name'], $rutaDestino)) {
    respuestaJson(false, 'Error al guardar la imagen en el servidor.', null, 500);
}

// Actualizar JSON con nueva imagen
foreach ($datos['slides'] as &$slide) {
    if ((int) $slide['id'] === $slideId) {
        $slide['imagen'] = $nombreArchivo;
        break;
    }
}
unset($slide);

$guardado = file_put_contents($jsonPath, json_encode($datos, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

if ($guardado === false) {
    respuestaJson(false, 'Error al guardar la configuración.', null, 500);
}

$imagenUrl = 'cliente/assets/img/carusel/' . $nombreArchivo;

respuestaJson(true, 'Imagen subida correctamente.', [
    'imagen' => $nombreArchivo,
    'url' => $imagenUrl,
    'slides' => $datos,
]);