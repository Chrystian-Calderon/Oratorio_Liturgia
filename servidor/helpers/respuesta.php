<?php

declare(strict_types=1);

function respuestaJson(
    bool $success,
    string $message,
    mixed $data = null,
    int $status = 200
): never {

    http_response_code($status);

    header(
        'Content-Type: application/json; charset=utf-8'
    );

    echo json_encode([
        'success' => $success,
        'message' => $message,
        'data' => $data,
    ]);

    exit;
}