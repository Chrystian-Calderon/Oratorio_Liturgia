<?php
declare(strict_types=1);

function conectar(): mysqli
{
    mysqli_report(
        MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT
    );
    try {
        $conexion = new mysqli(
            env('DB_HOST'),
            env('DB_USER'),
            env('DB_PASSWORD'),
            env('DB_NAME'),
            (int) env('DB_PORT', 3306)
        );
        $conexion->set_charset('utf8mb4');
        return $conexion;
    } catch (mysqli_sql_exception $e) {
        error_log(
            'Error de conexión a la base de datos: '
            . $e->getMessage()
        );
        throw new RuntimeException(
            'No se pudo conectar con la base de datos.'
        );
    }
}
