<?php

header("Content-Type: application/json");

// Incluye conexión y modelo
require_once("../config/conexion.php");
require_once("../models/Clientes.php");

// Instancia del modelo
$Clientes = new Clientes();

// Leer JSON solo si existe
$inputJSON = file_get_contents("php://input");
$body = json_decode($inputJSON, true);

// Validar si JSON es inválido (solo si hay contenido)
if ($inputJSON !== "" && $body === null) {
    echo json_encode(["error" => "JSON inválido o con formato incorrecto."]);
    exit();
}

// Validar parámetro 'op'
if (!isset($_GET["op"])) {
    echo json_encode(["error" => "Debe especificar la operación ('op')."]);
    exit();
}

switch ($_GET["op"]) {

    case "ObtenerTodos":
        echo json_encode($Clientes->obtener_clientes());
        break;

    case "ObtenerPorCedula":
        if (!isset($body["Cedula"])) {
            echo json_encode(["error" => "Debe enviar 'Cedula'."]);
            exit();
        }

        echo json_encode($Clientes->obtener_cliente_por_cedula($body["Cedula"]));
        break;

    case "BuscarPorNombre":
        if (!isset($body["Nombre"])) {
            echo json_encode(["error" => "Debe enviar 'Nombre'."]);
            exit();
        }

        echo json_encode($Clientes->buscar_por_nombre($body["Nombre"]));
        break;

    case "Insertar":

        if (!isset($body["Cedula"], $body["Nombre"], $body["FechaNacimiento"])) {
            echo json_encode(["error" => "Datos incompletos. Debe enviar Cedula, Nombre, FechaNacimiento y KeyCliente."]);
            exit();
        }

        $Clientes->insertar_cliente(
            $body["Cedula"],
            $body["Nombre"],
            $body["FechaNacimiento"],
            $body["Telefono"] ?? "",
            $body["Correo"] ?? ""
        );

        echo json_encode(["Correcto" => "Cliente registrado correctamente."]);
        break;

    case "Actualizar":

        if (!isset($body["Cedula"], $body["Nombre"], $body["FechaNacimiento"])) {
            echo json_encode(["error" => "Datos incompletos para actualizar cliente."]);
            exit();
        }

        $Clientes->actualizar_cliente(
            $body["Cedula"],
            $body["Nombre"],
            $body["FechaNacimiento"],
            $body["Telefono"] ?? "",
            $body["Correo"] ?? ""
        );

        echo json_encode(["Correcto" => "Cliente actualizado correctamente."]);
        break;

    case "Eliminar":

        if (!isset($body["Cedula"])) {
            echo json_encode(["error" => "Debe enviar 'Cedula'."]);
            exit();
        }

        $Clientes->eliminar_cliente($body["Cedula"]);

        echo json_encode(["Correcto" => "Cliente eliminado correctamente."]);
        break;

    default:
        echo json_encode(["error" => "Operación no válida."]);
        break;
}

?>