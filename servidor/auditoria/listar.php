<?php
declare(strict_types=1);
require_once appPath('servidor/config/database.php');
require_once appPath('servidor/helpers/respuesta.php');

if (!isset($_SESSION['usuario']) || !in_array($_SESSION['tipo_persona'], ['Administrativo', 'Encargado'])) {
    respuestaJson(false, 'No autorizado.', null, 401);
}

$conexion = conectar();

try {
    $buscar = trim($_GET['buscar'] ?? '');
    $accion = trim($_GET['accion'] ?? '');
    $tabla = trim($_GET['tabla'] ?? '');

    $where = [];
    $parametros = [];
    $tipos = '';

    if ($buscar !== '') {
        $where[] = "(a.descripcion LIKE ? OR a.usuario_mysql LIKE ? OR a.tabla_afectada LIKE ?
                    OR p.nombres LIKE ? OR p.apellidos LIKE ?)";
        array_push($parametros, "%{$buscar}%", "%{$buscar}%", "%{$buscar}%", "%{$buscar}%", "%{$buscar}%");
        $tipos .= 'sssss';
    }

    if ($accion !== '') {
        $where[] = 'a.accion = ?';
        $parametros[] = $accion;
        $tipos .= 's';
    }

    if ($tabla !== '') {
        $where[] = 'a.tabla_afectada = ?';
        $parametros[] = $tabla;
        $tipos .= 's';
    }

    $whereSql = $where ? 'WHERE ' . implode(' AND ', $where) : '';

    // Totales por acción
    $totales = $conexion->query(
        "SELECT
            COUNT(*) AS total,
            SUM(CASE WHEN accion = 'INSERT' THEN 1 ELSE 0 END) AS total_insert,
            SUM(CASE WHEN accion = 'UPDATE' THEN 1 ELSE 0 END) AS total_update,
            SUM(CASE WHEN accion = 'DELETE' THEN 1 ELSE 0 END) AS total_delete
         FROM auditoria"
    )->fetch_assoc();

    // Registros paginados
    $porPagina = 15;
    $nombreUsuarioSql = "TRIM(CONCAT(COALESCE(p.nombres, ''), ' ', COALESCE(p.apellidos, '')))";
    $paginaActual = isset($_GET['pagina']) ? (int) $_GET['pagina'] : 1;
    if ($paginaActual < 1) $paginaActual = 1;

    $stmt = $conexion->prepare(
        "SELECT COUNT(*) AS total
         FROM auditoria a
         LEFT JOIN personas p ON p.id_persona = a.id_usuario
         {$whereSql}"
    );
    if ($parametros) {
        $stmt->bind_param($tipos, ...$parametros);
    }
    $stmt->execute();
    $totalRegistros = (int) $stmt->get_result()->fetch_assoc()['total'];
    $stmt->close();

    $totalPaginas = max(1, (int) ceil($totalRegistros / $porPagina));
    if ($paginaActual > $totalPaginas) $paginaActual = $totalPaginas;

    $inicio = ($paginaActual - 1) * $porPagina;

    $stmt = $conexion->prepare(
        "SELECT a.id_auditoria, a.id_usuario, a.usuario_mysql, a.accion,
                a.tabla_afectada, a.registro_id, a.fecha_hora, a.descripcion,
                a.valores_anteriores, a.valores_nuevos,
                {$nombreUsuarioSql} AS nombre_real_usuario
         FROM auditoria a
         LEFT JOIN personas p ON p.id_persona = a.id_usuario
         {$whereSql}
         ORDER BY a.fecha_hora DESC
         LIMIT {$porPagina} OFFSET {$inicio}"
    );
    if ($parametros) {
        $stmt->bind_param($tipos, ...$parametros);
    }
    $stmt->execute();
    $registros = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();

    // Tablas disponibles para el filtro
    $tablas = $conexion->query(
        "SELECT DISTINCT tabla_afectada FROM auditoria ORDER BY tabla_afectada ASC"
    )->fetch_all(MYSQLI_ASSOC);

    $conexion->close();

    respuestaJson(true, 'Auditoría obtenida.', [
        'registros'      => $registros,
        'totales'        => $totales,
        'paginaActual'   => $paginaActual,
        'totalPaginas'   => $totalPaginas,
        'totalRegistros' => $totalRegistros,
        'tablas'         => $tablas,
    ]);
} catch (Throwable $e) {
    error_log('Error auditoría listar: ' . $e->getMessage());
    respuestaJson(false, 'Error al obtener la auditoría.', null, 500);
}
