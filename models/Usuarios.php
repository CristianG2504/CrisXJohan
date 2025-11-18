<?php
class UsuariosAPI extends Conectar
{
    public function obtener_Usuarios_por_cedula($cedula)
    {
        $conexion = parent::conectar_bd();
        parent::establecer_codificacion();

        $sql = "SELECT cedula, nombre, contraseña, clave
                FROM usuarios
                WHERE cedula = ?
                LIMIT 1";

        $consulta = $conexion->prepare($sql);
        $consulta->bindValue(1, $cedula);
        $consulta->execute();

        return $consulta->fetch(PDO::FETCH_ASSOC);
    }

    public function login_usuario($cedula, $password)
    {
        $conexion = parent::conectar_bd();
        parent::establecer_codificacion();

        $sql = "SELECT cedula, nombre, contraseña, clave
                FROM usuarios
                WHERE cedula = ?
                LIMIT 1";

        $stmt = $conexion->prepare($sql);
        $stmt->bindValue(1, $cedula);
        $stmt->execute();

        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$usuario) {
            return ["error" => "Usuario no encontrado"];
        }

        if (!password_verify($password, $usuario["contraseña"])) {
            return ["error" => "Contraseña incorrecta"];
        }

        return [
            "cedula" => $usuario["cedula"],
            "nombre" => $usuario["nombre"],
            "clave" => $usuario["clave"]
        ];
    }

    public function insertar_Usuarios($cedula, $nombre, $password)
    {
        $conexion = parent::conectar_bd();
        parent::establecer_codificacion();

        $hash_password = password_hash($password, PASSWORD_DEFAULT);

        $clave_generada = bin2hex(random_bytes(16));

        $sql = "INSERT INTO usuarios (cedula, nombre, contraseña, clave)
                VALUES (?, ?, ?, ?)";

        $stmt = $conexion->prepare($sql);
        $stmt->bindValue(1, $cedula);
        $stmt->bindValue(2, $nombre);
        $stmt->bindValue(3, $hash_password);
        $stmt->bindValue(4, $clave_generada);
        $stmt->execute();

        return $clave_generada;
    }

    public function eliminar_Usuarios($cedula)
    {
        $conexion = parent::conectar_bd();
        parent::establecer_codificacion();

        $sql = "DELETE FROM usuarios WHERE cedula = ?";

        $stmt = $conexion->prepare($sql);
        $stmt->bindValue(1, $cedula);
        $stmt->execute();
    }
}
?>