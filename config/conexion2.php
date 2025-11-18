<?php
class Conectar
{
    protected $conexion_bd;

    protected function conectar_bd()
    {
        try {
            // Detectar si estamos en Laragon (local) o Railway (producción)
            if ($_SERVER['HTTP_HOST'] === 'localhost' || strpos($_SERVER['HTTP_HOST'], 'localhost:81') !== false) {
                // Configuración para LARAGON
                $DB_HOST = 'localhost';
                $DB_PORT = '3306';  // ← Laragon usa 3306 por defecto
                $DB_NAME = 'db_proyecto_1';
                $DB_USER = 'root';
                $DB_PASSWORD = '';  // ← Laragon NO tiene contraseña por defecto
            } else {
                // Configuración para RAILWAY
                $DB_HOST = $_ENV['MYSQLHOST'] ?? $_ENV['DB_HOST'] ?? null;
                $DB_PORT = $_ENV['MYSQLPORT'] ?? $_ENV['DB_PORT'] ?? null;
                $DB_NAME = $_ENV['MYSQLDATABASE'] ?? $_ENV['DB_NAME'] ?? null;
                $DB_USER = $_ENV['MYSQLUSER'] ?? $_ENV['DB_USER'] ?? null;
                $DB_PASSWORD = $_ENV['MYSQLPASSWORD'] ?? $_ENV['DB_PASSWORD'] ?? null;
            }

            // Validar conexión
            if (!$DB_HOST || !$DB_PORT || !$DB_NAME || !$DB_USER) {
                throw new Exception("Faltan configuraciones para la conexión a la BD");
            }

            $dsn = "mysql:host=$DB_HOST;port=$DB_PORT;dbname=$DB_NAME;charset=utf8";

            $this->conexion_bd = new PDO(
                $dsn,
                $DB_USER,
                $DB_PASSWORD,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                ]
            );

            return $this->conexion_bd;
        } catch (Exception $e) {
            error_log("ERROR DB: " . $e->getMessage());
            throw new Exception("Error de conexión: " . $e->getMessage());
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
