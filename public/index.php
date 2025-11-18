<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header("Content-Type: application/json; charset=utf-8");

// Obtener el path de la solicitud - CORREGIDO para Laragon
$request_uri = $_SERVER['REQUEST_URI'];
$path = parse_url($request_uri, PHP_URL_PATH);

// Eliminar la base path de Laragon
$base_path = '/CrisXJohan/public';
if (strpos($path, $base_path) === 0) {
    $path = substr($path, strlen($base_path));
}

// Eliminar cualquier barra extra al final
$path = rtrim($path, "/");

// Si está vacío, es la raíz
if ($path === '') {
    $path = '/';
}

error_log("Path procesado: " . $path);

// Manejar rutas
switch ($path) {
    case '/':
        echo json_encode([
            "status" => "success",
            "message" => "API funcionando en Laragon",
            "timestamp" => date('Y-m-d H:i:s'),
            "endpoints" => [
                "/clientes",
                "/productos",
                "/facturas",
                "/usuarios"
            ]
        ]);
        break;

    case '/clientes':
        require_once __DIR__ . '/../controllers/clientes.php';
        break;

    case '/productos':
        require_once __DIR__ . '/../controllers/productos.php';
        break;

    case '/facturas':
        require_once __DIR__ . '/../controllers/facturas.php';
        break;

    case '/usuarios':
        require_once __DIR__ . '/../controllers/usuarios.php';
        break;

    case '/health':
        echo json_encode([
            "status" => "success",
            "message" => "API saludable",
            "timestamp" => date('Y-m-d H:i:s')
        ]);
        break;

    default:
        http_response_code(404);
        echo json_encode([
            "error" => "Ruta no encontrada",
            "ruta_solicitada" => $path,
            "rutas_disponibles" => [
                "/",
                "/clientes",
                "/productos",
                "/facturas",
                "/usuarios"
            ]
        ]);
        break;
}
