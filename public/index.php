<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Log simple
error_log("=== SERVER STARTED ===");

header("Content-Type: application/json; charset=utf-8");

// Respuesta MUY simple SIN includes
echo json_encode([
    "status" => "success",
    "message" => "TEST - Solo respuesta básica",
    "timestamp" => date('Y-m-d H:i:s')
]);

error_log("=== RESPONSE SENT ===");
