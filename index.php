<?php
header("Content-Type: application/json; charset=utf-8");

// Obtener la ruta solicitada
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Rutas de tu API
switch ($path) {

    // CLIENTES
    case '/clientes':
        require_once __DIR__ . '/controllers/clientes.php';
        break;

    // PRODUCTOS
    case '/productos':
        require_once __DIR__ . '/controllers/productos.php';
        break;

    // FACTURAS
    case '/facturas':
        require_once __DIR__ . '/controllers/facturas.php';
        break;
        
    case '/usuarios':
        require_once __DIR__ . '/controllers/usuarios.php';
        break;

    default:
        // 404 si no existe la ruta
        http_response_code(404);
        echo json_encode([
            "error" => "Ruta no encontrada",
            "ruta" => $path
        ]);
        break;
}
