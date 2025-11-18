<?php
header("Content-Type: application/json; charset=UTF-8");

try {
    require_once("../config/conexion.php");
    require_once("../models/Facturas.php");
    require_once("../models/Productos.php");
    require_once("../models/Clientes.php");

    // Instancia del modelo
    $Clientes = new Clientes();
    $factura = new Facturas();
    $Productos = new Productos();

    // Obtener operación desde GET
    $op = isset($_GET["op"]) ? $_GET["op"] : "";

    // Obtener JSON desde POST
    $inputJSON = file_get_contents("php://input");
    $data = json_decode($inputJSON, true);

    switch ($op) {
        case "insertar":
            if (!$data) {
                echo json_encode(["error" => "JSON inválido"]);
                break;
            }
            $fecha = $data["fecha"] ?? null;
            $cedula_cliente = $data["cedula_cliente"] ?? null;
            $total = $data["total"] ?? 0;
            $detalles = $data["detalles"] ?? [];
            $resultado = $factura->insertarFactura($fecha, $cedula_cliente, $total, $detalles);
            echo json_encode([
                "status" => $resultado === true ? "success" : "error",
                "mensaje" => $resultado
            ]);
            break;

        case "editar":
            $id_encabezado = $data["id_encabezado"] ?? null;
            $fecha = $data["fecha"] ?? null;
            $cedula_cliente = $data["cedula_cliente"] ?? null;
            $total = $data["total"] ?? 0;
            $detalles = $data["detalles"] ?? [];
            $resultado = $factura->editarFactura($id_encabezado, $fecha, $cedula_cliente, $total, $detalles);
            echo json_encode([
                "status" => $resultado === true ? "success" : "error",
                "mensaje" => $resultado
            ]);
            break;

        case "eliminar":
            $id_encabezado = $data["id_encabezado"] ?? null;
            $resultado = $factura->eliminarFactura($id_encabezado);
            echo json_encode([
                "status" => $resultado === true ? "success" : "error"
            ]);
            break;

        case "buscar_cedula":
            $cedula = $_GET["cedula"] ?? null;
            $resultado = $factura->buscarFacturaPorCedulaCliente($cedula);
            echo json_encode($resultado);
            break;

        case "buscar_id":
            $id_encabezado = $_GET["id_encabezado"] ?? null;
            $resultado = $factura->buscarFacturaPorIdEncabezado($id_encabezado);
            echo json_encode($resultado);
            break;

        case "buscar_cliente":
            $nombre = $_GET["nombre"] ?? "";
            $resultado = $factura->buscarFacturaPorNombreCliente($nombre);
            echo json_encode($resultado);
            break;

        case "listar":
            echo json_encode($factura->obtenerFacturas());
            break;

        case "ObtenerTodosProductos":
            echo json_encode($Productos->obtener_productos());
            break;

        case "BuscarPorNombreProductos":
            $nombre = $data["Nombre"] ?? $_GET["Nombre"] ?? "";
            echo json_encode($Productos->buscar_por_nombre($nombre));
            break;

        case "ObtenerTodosClientes":
            echo json_encode($Clientes->obtener_clientes());
            break;

        default:
            echo json_encode(["error" => "Operación no válida"]);
            break;
    }
} catch (Exception $e) {
    error_log("Error en facturas.php: " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "message" => "Error interno del servidor",
        "error" => $e->getMessage()
    ]);
}
