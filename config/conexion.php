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

            $this->conexion_bd = new PDO(
                "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8",
                $user,
                $password,
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
