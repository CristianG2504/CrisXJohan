<?php
require_once("../config/conexion.php");
require_once("../models/Usuarios.php");

$usuarios = new Usuarios();

header('Content-Type: application/json; charset=utf-8');

switch ($_GET["op"]) {
    case "login":
        $data = json_decode(file_get_contents("php://input"), true);

        if (!isset($data["cedula"]) || !isset($data["password"])) {
            echo json_encode(["error" => "Debe enviar cedula y password"]);
            break;
        }

        $resultado = $usuarios->login_usuario($data["cedula"], $data["password"]);
        echo json_encode($resultado);
        break;

    case "insert":
        $data = json_decode(file_get_contents("php://input"), true);

        if (!isset($data["cedula"]) || !isset($data["nombre"]) || !isset($data["password"])) {
            echo json_encode(["error" => "Faltan datos para insertar"]);
            break;
        }

        $clave = $usuarios->insertar_Usuarios(
            $data["cedula"],
            $data["nombre"],
            $data["password"]
        );

        echo json_encode([
            "mensaje" => "Usuario registrado correctamente",
            "clave_generada" => $clave
        ]);
        break;

    case "delete":
        if (!isset($_GET["cedula"])) {
            echo json_encode(["error" => "Debe enviar la cedula"]);
            break;
        }

        $usuarios->eliminar_Usuarios($_GET["cedula"]);

        echo json_encode([
            "mensaje" => "Usuario eliminado correctamente"
        ]);
        break;

    default:
        echo json_encode([
            "error" => "Operación no válida"
        ]);
        break;
}
?>
