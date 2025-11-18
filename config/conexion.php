<?php
class Conectar
{
    protected $conexion_bd;

    protected function conectar_bd()
    {
        try {
            // Variables de entorno de Railway
            $DB_HOST = $_ENV['MYSQLHOST'] ?? null;
            $DB_PORT = $_ENV['MYSQLPORT'] ?? null;
            $DB_NAME = $_ENV['MYSQLDATABASE'] ?? null;
            $DB_USER = $_ENV['MYSQLUSER'] ?? null;
            $DB_PASSWORD = $_ENV['MYSQLPASSWORD'] ?? null;

            // Log para debugging (solo en Railway)
            error_log("DB Connection attempt - Host: $DB_HOST, Port: $DB_PORT, DB: $DB_NAME, User: $DB_USER");

            // Validar conexión
            if (!$DB_HOST || !$DB_PORT || !$DB_NAME || !$DB_USER) {
                throw new Exception("Faltan variables de entorno para la conexión a la BD");
            }

            $dsn = "mysql:host=$DB_HOST;port=$DB_PORT;dbname=$DB_NAME;charset=utf8";

            $this->conexion_bd = new PDO(
                $dsn,
                $DB_USER,
                $DB_PASSWORD,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"
                ]
            );

            error_log("Conexión a BD exitosa");
            return $this->conexion_bd;
        } catch (Exception $e) {
            // SOLO log el error, NO uses die()
            error_log("ERROR DB en Railway: " . $e->getMessage());

            // Lanza la excepción para que el controlador la maneje
            throw new Exception("Error de conexión a la base de datos: " . $e->getMessage());
        }
    }

    public function establecer_codificacion()
    {
        if ($this->conexion_bd) {
            return $this->conexion_bd->query("SET NAMES 'utf8'");
        }
        return false;
    }
}
