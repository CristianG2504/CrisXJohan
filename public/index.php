<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Log simple para verificar que el script se ejecuta
error_log("=== INDEX.PHP STARTED ===");

header("Content-Type: application/json; charset=utf-8");

try {
    error_log("=== TRY BLOCK STARTED ===");
    
    echo json_encode([
        "status" => "success",
        "message" => "API funcionando - Versión mínima",
        "timestamp" => date('Y-m-d H:i:s'),
        "php_version" => PHP_VERSION
    ]);
    
    error_log("=== RESPONSE SENT ===");
    
} catch (Exception $e) {
    error_log("=== EXCEPTION CAUGHT: " . $e->getMessage() . " ===");
    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "message" => "Exception: " . $e->getMessage()
    ]);
}

error_log("=== INDEX.PHP FINISHED ===");
?>