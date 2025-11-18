<?php
// Clase Conectar para manejar la conexión a la base de datos
class Conectar
{
    protected $conexion_bd;

    protected function conectar_bd()
    {
        try {
            // Railway
            $host = "hopper.proxy.rlwy.net";
            $port = "33613";
            $dbname = "railway";
            $user = "root";
            $password = "fuBvzEdinxeGlsslAGCEXrsgMVllKgLM";
            $DB_HOST = $_ENV['DB_HOST'] ?? 'hopper.proxy.rlwy.net';
            $DB_PORT = $_ENV['DB_PORT'] ?? '33613';
            $DB_NAME = $_ENV['DB_NAME'] ?? 'railway';
            $DB_USER = $_ENV['DB_USER'] ?? 'root';
            $DB_PASSWORD = $_ENV['DB_PASSWORD'] ?? 'fuBvzEdinxeGlsslAGCEXrsgMVllKgLM';

            $this->conexion_bd = new PDO(
                "mysql:host=$DB_HOST;port=$DB_PORT;dbname=$DB_NAME;charset=utf8",
                $DB_USER,
                $DB_PASSWORD,
                [
                    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
                    PDO::MYSQL_ATTR_SSL_CA => null,
                    PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false,
                ]
            );

            return $this->conexion_bd;

        } catch (Exception $e) {
            error_log("ERROR DB: " . $e->getMessage());
            echo json_encode([
                "status" => "error",
                "message" => "Error de conexión: " . $e->getMessage()
            ]);
            exit;
        }
    }

    public function establecer_codificacion()
    {
        return $this->conexion_bd->query("SET NAMES 'utf8'");
    }
}
