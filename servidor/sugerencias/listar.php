<?php
declare(strict_types=1);
require_once appPath('servidor/config/database.php');
require_once appPath('servidor/helpers/respuesta.php');

if (!isset($_SESSION['usuario']) || !in_array($_SESSION['tipo_persona'], ['Administrativo', 'Encargado'])) {
    respuestaJson(false, 'No autorizado.', null, 401);
}

$conexion = conectar();

try {
    $tipo     = trim($_GET['tipo'] ?? 'sugerencias');
    $buscar   = trim($_GET['buscar'] ?? '');
    $estado   = trim($_GET['estado'] ?? '');
    $porPagina = 10;

    $tabla = ($tipo === 'contacto') ? 'contacto' : 'sugerencias';
    $idCampo = ($tipo === 'contacto') ? 'id_contacto' : 'id_sugerencia';

    $where = [];
    $parametros = [];
    $tipos = '';

    if ($buscar !== '') {
        $where[] = "(nombre LIKE ? OR apellido LIKE ? OR correo LIKE ? OR asunto LIKE ? OR mensaje LIKE ?)";
        array_push($parametros, "%{$buscar}%", "%{$buscar}%", "%{$buscar}%", "%{$buscar}%", "%{$buscar}%");
        $tipos .= 'sssss';
    }

    if ($estado !== '') {
        $where[] = 'estado = ?';
        $parametros[] = $estado;
        $tipos .= 's';
    }

    $whereSql = $where ? 'WHERE ' . implode(' AND ', $where) : '';

    // Conteo total
    $stmt = $conexion->prepare("SELECT COUNT(*) AS total FROM {$tabla} {$whereSql}");
    if ($parametros) {
        $stmt->bind_param($tipos, ...$parametros);
    }
    $stmt->execute();
    $total = (int) $stmt->get_result()->fetch_assoc()['total'];
    $stmt->close();

    $totalPaginas = max(1, (int) ceil($total / $porPagina));

    $paginaActual = isset($_GET['pagina']) ? (int) $_GET['pagina'] : 1;
    if ($paginaActual < 1) $paginaActual = 1;
    if ($paginaActual > $totalPaginas) $paginaActual = $totalPaginas;

    $inicio = ($paginaActual - 1) * $porPagina;

    $stmt = $conexion->prepare(
        "SELECT {$idCampo} AS id, nombre, apellido, correo, telefono, asunto, mensaje, estado, fecha_creacion
         FROM {$tabla}
         {$whereSql}
         ORDER BY fecha_creacion DESC
         LIMIT {$porPagina} OFFSET {$inicio}"
    );
    if ($parametros) {
        $stmt->bind_param($tipos, ...$parametros);
    }
    $stmt->execute();
    $registros = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();

    // Conteo de nuevos
    $nuevos = $conexion->query("SELECT COUNT(*) AS total FROM {$tabla} WHERE estado = 'Nuevo'")->fetch_assoc()['total'];

    $conexion->close();

    respuestaJson(true, 'Registros obtenidos.', [
        'registros'      => $registros,
        'paginaActual'   => $paginaActual,
        'totalPaginas'   => $totalPaginas,
        'total'          => $total,
        'nuevos'         => (int) $nuevos,
    ]);
} catch (Throwable $e) {
    error_log('Error listar sugerencias/contacto: ' . $e->getMessage());
    respuestaJson(false, 'Error al obtener los registros.', null, 500);
}
