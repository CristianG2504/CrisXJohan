<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header("Content-Type: application/json; charset=utf-8");

echo json_encode([
    "status" => "success",
    "message" => "Test endpoint working",
    "structure_ok" => file_exists(__DIR__ . '/../controllers/clientes.php'),
    "env_vars" => [
        'MYSQLHOST' => $_ENV['MYSQLHOST'] ?? 'NOT_SET',
        'MYSQLPORT' => $_ENV['MYSQLPORT'] ?? 'NOT_SET',
        'MYSQLDATABASE' => $_ENV['MYSQLDATABASE'] ?? 'NOT_SET',
        'MYSQLUSER' => $_ENV['MYSQLUSER'] ?? 'NOT_SET',
        'MYSQLPASSWORD' => $_ENV['MYSQLPASSWORD'] ? 'SET' : 'NOT_SET'
    ]
]);
