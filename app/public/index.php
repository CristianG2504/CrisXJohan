<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header("Content-Type: application/json; charset=utf-8");

// Obtener la ruta solicitada
$request_uri = $_SERVER['REQUEST_URI'];
$path = parse_url($request_uri, PHP_URL_PATH);
$path = rtrim($path, "/");
if ($path === "") $path = "/";

// Debug opcional
// error_log("Ruta solicitada: " . $path);

switch ($path) {

    case "/":
        echo json_encode([
            "status" => "success",
            "message" => "API en funcionamiento",
            "timestamp" => date("Y-m-d H:i:s"),
            "endpoints" => [
                "/clientes",
                "/productos",
                "/facturas",
                "/usuarios",
                "/health"
            ]
        ]);
        break;

    case "/health":
        echo json_encode([
            "status" => "ok",
            "message" => "API saludable",
            "timestamp" => date("Y-m-d H:i:s")
        ]);
        break;

    case "/clientes":
        require_once __DIR__ . "/../controllers/clientes.php";
        break;

    case "/productos":
        require_once __DIR__ . "/../controllers/productos.php";
        break;

    case "/facturas":
        require_once __DIR__ . "/../controllers/facturas.php";
        break;

    case "/usuarios":
        require_once __DIR__ . "/../controllers/usuarios.php";
        break;

    default:
        http_response_code(404);
        echo json_encode([
            "error" => "Ruta no encontrada",
            "solicitada" => $path
        ]);
}
