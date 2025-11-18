<?php
// Clase Conectar para manejar la conexión a la base de datos
class Conectar
{
    // Variable protegida para almacenar la instancia de la conexión
    protected $conexion_bd;

    // Método protegido para establecer la conexión con la base de datos
    protected function conectar_bd()
    {
        try {
            // Establece la conexión utilizando PDO
            $DB_HOST = $_ENV['MYSQLHOST'] ?? $_ENV['DB_HOST'] ?? null;
            $DB_PORT = $_ENV['MYSQLPORT'] ?? $_ENV['DB_PORT'] ?? null;
            $DB_NAME = $_ENV['MYSQLDATABASE'] ?? $_ENV['DB_NAME'] ?? null;
            $DB_USER = $_ENV['MYSQLUSER'] ?? $_ENV['DB_USER'] ?? null;
            $DB_PASSWORD = $_ENV['MYSQLPASSWORD'] ?? $_ENV['DB_PASSWORD'] ?? null;

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
            // Si ocurre un error, muestra el mensaje de error y detiene la ejecución
            print "Error en la base de datos: " . $e->getMessage() . "<br/>";
            die();  // Detiene la ejecución
        }
    }

    // Método público para establecer la codificación de caracteres a UTF-8
    public function establecer_codificacion()
    {
        // Ejecuta la sentencia SQL para configurar la codificación de caracteres a UTF-8
        return $this->conexion_bd->query("SET NAMES 'utf8'");
    }
}
