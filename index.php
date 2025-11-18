<?php
header("Content-Type: application/json; charset=utf-8");

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Eliminar cualquier barra extra al final
$path = rtrim($path, "/");

switch ($path) {

    case '/clientes':
        require_once __DIR__ . '/controllers/clientes.php';
        break;

    case '/productos':
        require_once __DIR__ . '/controllers/productos.php';
        break;

    case '/facturas':
        require_once __DIR__ . '/controllers/facturas.php';
        break;

    case '/usuarios':
        require_once __DIR__ . '/controllers/usuarios.php';
        break;

    default:
        http_response_code(404);
        echo json_encode([
            "error" => "Ruta no encontrada",
            "ruta" => $path
        ]);
        break;
}
