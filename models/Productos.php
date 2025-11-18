<?php
class Productos extends Conectar
{

    public function obtener_productos()
    {
        $conexion = parent::conectar_bd();
        parent::establecer_codificacion();

        $sql = "SELECT * FROM productos";
        $consulta = $conexion->prepare($sql);
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtener_producto_por_id($id)
    {
        $conexion = parent::conectar_bd();
        parent::establecer_codificacion();

        $sql = "SELECT * FROM productos WHERE id = ?";
        $consulta = $conexion->prepare($sql);
        $consulta->bindValue(1, $id);
        $consulta->execute();

        return $consulta->fetch(PDO::FETCH_ASSOC);
    }

    public function buscar_por_nombre($nombre)
    {
        $conexion = parent::conectar_bd();
        parent::establecer_codificacion();

        $sql = "SELECT * FROM productos WHERE nombre LIKE ?";
        $consulta = $conexion->prepare($sql);
        $consulta->bindValue(1, "%$nombre%");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_ASSOC);
    }

    public function insertar_producto($nombre, $precio_unit, $cantidad)
    {
        $conexion = parent::conectar_bd();
        parent::establecer_codificacion();

        $sql = "INSERT INTO productos (nombre, precio_unit, cantidad)
                VALUES (?, ?, ?)";
        $sentencia = $conexion->prepare($sql);

        $sentencia->bindValue(1, $nombre);
        $sentencia->bindValue(2, $precio_unit);
        $sentencia->bindValue(3, $cantidad);

        $sentencia->execute();
    }

    public function actualizar_producto($id, $nombre, $precio_unit, $cantidad)
    {
        $conexion = parent::conectar_bd();
        parent::establecer_codificacion();

        $sql = "UPDATE productos SET nombre=?, precio_unit=?, cantidad=? WHERE id=?";
        $sentencia = $conexion->prepare($sql);

        $sentencia->bindValue(1, $nombre);
        $sentencia->bindValue(2, $precio_unit);
        $sentencia->bindValue(3, $cantidad);
        $sentencia->bindValue(4, $id);

        $sentencia->execute();
    }

    public function eliminar_producto($id)
    {
        $conexion = parent::conectar_bd();
        parent::establecer_codificacion();

        $sql = "DELETE FROM productos WHERE id = ?";
        $sentencia = $conexion->prepare($sql);
        $sentencia->bindValue(1, $id);

        $sentencia->execute();
    }
}
?>