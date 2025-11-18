<?php
class Clientes extends Conectar
{
    public function obtener_cliente_por_cedula($cedula)
    {
        $conexion = parent::conectar_bd();
        parent::establecer_codificacion();

        $sql = "SELECT * FROM clientes WHERE cedula_unica = ?";
        $consulta = $conexion->prepare($sql);
        $consulta->bindValue(1, $cedula);
        $consulta->execute();

        return $consulta->fetch(PDO::FETCH_ASSOC);
    }

    public function obtener_clientes()
    {
        $conexion = parent::conectar_bd();
        parent::establecer_codificacion();

        $sql = "SELECT * FROM clientes";
        $consulta = $conexion->prepare($sql);
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_ASSOC);
    }

    public function buscar_por_nombre($nombre)
    {
        $conexion = parent::conectar_bd();
        parent::establecer_codificacion();

        $sql = "SELECT * FROM clientes WHERE nombre LIKE ?";
        $consulta = $conexion->prepare($sql);
        $consulta->bindValue(1, "%$nombre%");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_ASSOC);
    }

    public function insertar_cliente($cedula, $nombre, $fecha_nac, $telefono, $correo)
    {
        $conexion = parent::conectar_bd();
        parent::establecer_codificacion();

        $sql = "INSERT INTO clientes (cedula_unica, nombre, fecha_nacimiento, telefono, correo)
                VALUES (?, ?, ?, ?, ?)";

        $sentencia = $conexion->prepare($sql);
        $sentencia->bindValue(1, $cedula);
        $sentencia->bindValue(2, $nombre);
        $sentencia->bindValue(3, $fecha_nac);
        $sentencia->bindValue(4, $telefono);
        $sentencia->bindValue(5, $correo);

        $sentencia->execute();
    }

    public function actualizar_cliente($cedula, $nombre, $fecha_nac, $telefono, $correo)
    {
        $conexion = parent::conectar_bd();
        parent::establecer_codificacion();

        $sql = "UPDATE clientes 
                SET nombre = ?, fecha_nacimiento = ?, telefono = ?, correo = ?
                WHERE cedula_unica = ?";

        $sentencia = $conexion->prepare($sql);

        $sentencia->bindValue(1, $nombre);
        $sentencia->bindValue(2, $fecha_nac);
        $sentencia->bindValue(3, $telefono);
        $sentencia->bindValue(4, $correo);
        $sentencia->bindValue(5, $cedula);

        $sentencia->execute();
    }

    public function eliminar_cliente($cedula)
    {
        $conexion = parent::conectar_bd();
        parent::establecer_codificacion();

        $sql = "DELETE FROM clientes WHERE cedula_unica = ?";
        $sentencia = $conexion->prepare($sql);
        $sentencia->bindValue(1, $cedula);

        $sentencia->execute();
    }
}
?>