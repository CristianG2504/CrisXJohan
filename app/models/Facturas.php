<?php
require_once __DIR__ . '/../config/conexion.php';

class Facturas extends Conectar
{
    public function insertarFactura($fecha, $cedula_cliente, $total, $detalles)
    {
        if (empty($detalles)) {
            return "Error: La factura debe contener al menos un detalle.";
        }

        $conexion = parent::conectar_bd();
        parent::establecer_codificacion();

        $conexion->beginTransaction();

        try {
            $sql = "INSERT INTO encabezado_factura (fecha, cedula_cliente, total)
                    VALUES (?, ?, ?)";
            $stmt = $conexion->prepare($sql);
            $stmt->execute([$fecha, $cedula_cliente, $total]);

            $id_encabezado = $conexion->lastInsertId();

            $sql_det = "INSERT INTO detalle_factura (id_encabezado, id_producto, cantidad, total)
                        VALUES (?, ?, ?, ?)";
            $stmt_det = $conexion->prepare($sql_det);

            foreach ($detalles as $d) {
                if (!isset($d["id_producto"], $d["cantidad"], $d["total"])) {
                    throw new Exception("Datos incompletos en un detalle.");
                }

                $stmt_det->execute([
                    $id_encabezado,
                    $d["id_producto"],
                    $d["cantidad"],
                    $d["total"]
                ]);
            }

            $conexion->commit();
            return true;

        } catch (Exception $e) {
            $conexion->rollback();
            return "Error: " . $e->getMessage();
        }
    }

    public function editarFactura($id_encabezado, $fecha, $cedula_cliente, $total, $detalles)
    {
        if (empty($detalles)) {
            return "Error: La factura debe contener al menos un detalle.";
        }

        $conexion = parent::conectar_bd();
        parent::establecer_codificacion();
        $conexion->beginTransaction();

        try {
            $sql = "UPDATE encabezado_factura
                    SET fecha = ?, cedula_cliente = ?, total = ?
                    WHERE id = ?";
            $stmt = $conexion->prepare($sql);
            $stmt->execute([$fecha, $cedula_cliente, $total, $id_encabezado]);

            $sql_del = "DELETE FROM detalle_factura WHERE id_encabezado = ?";
            $stmt_del = $conexion->prepare($sql_del);
            $stmt_del->execute([$id_encabezado]);

            $sql_det = "INSERT INTO detalle_factura (id_encabezado, id_producto, cantidad, total)
                        VALUES (?, ?, ?, ?)";
            $stmt_det = $conexion->prepare($sql_det);

            foreach ($detalles as $d) {
                $stmt_det->execute([
                    $id_encabezado,
                    $d["id_producto"],
                    $d["cantidad"],
                    $d["total"]
                ]);
            }

            $conexion->commit();
            return true;

        } catch (Exception $e) {
            $conexion->rollback();
            return "Error: " . $e->getMessage();
        }
    }

    public function eliminarFactura($id_encabezado)
    {
        $conexion = parent::conectar_bd();
        parent::establecer_codificacion();

        $conexion->beginTransaction();

        try {
            $sql = "DELETE FROM encabezado_factura WHERE id = ?";
            $stmt = $conexion->prepare($sql);
            $stmt->execute([$id_encabezado]);

            $conexion->commit();
            return true;

        } catch (Exception $e) {
            $conexion->rollback();
            return false;
        }
    }

    public function buscarFacturaPorCedulaCliente($cedula)
    {
        $conexion = parent::conectar_bd();
        parent::establecer_codificacion();

        $sql = "SELECT * FROM encabezado_factura WHERE cedula_cliente = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->execute([$cedula]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function buscarFacturaPorIdEncabezado($id_encabezado)
    {
        $conexion = parent::conectar_bd();
        parent::establecer_codificacion();

        // Encabezado
        $sql1 = "SELECT * FROM encabezado_factura WHERE id = ?";
        $stmt1 = $conexion->prepare($sql1);
        $stmt1->execute([$id_encabezado]);
        $encabezado = $stmt1->fetch(PDO::FETCH_ASSOC);

        // Detalles
        $sql2 = "SELECT df.*, p.nombre AS producto
                 FROM detalle_factura df
                 INNER JOIN productos p ON p.id = df.id_producto
                 WHERE df.id_encabezado = ?";
        $stmt2 = $conexion->prepare($sql2);
        $stmt2->execute([$id_encabezado]);
        $detalles = $stmt2->fetchAll(PDO::FETCH_ASSOC);

        return [
            "encabezado" => $encabezado,
            "detalles"   => $detalles
        ];
    }

    public function obtenerFacturas()
    {
        $conexion = parent::conectar_bd();
        parent::establecer_codificacion();

        $sql = "SELECT * FROM encabezado_factura ORDER BY fecha DESC";
        $stmt = $conexion->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function buscarFacturaPorNombreCliente($nombre)
    {
        $conexion = parent::conectar_bd();
        parent::establecer_codificacion();

        $sql = "SELECT ef.*, c.nombre AS cliente
                FROM encabezado_factura ef
                INNER JOIN clientes c ON ef.cedula_cliente = c.cedula_unica
                WHERE c.nombre LIKE ?";
        $stmt = $conexion->prepare($sql);
        $stmt->execute(["%$nombre%"]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>