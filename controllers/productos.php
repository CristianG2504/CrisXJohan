<?php

header("Content-Type: application/json");

require_once("../config/conexion.php");
require_once("../models/Productos.php");
require_once("../models/Usuarios.php");

$Productos = new Productos();
$UsuariosAPI = new Usuarios();

$encabezados = getallheaders();

if (!isset($encabezados["CEDULA"])) {
    echo json_encode(["error" => "No se envió la cédula del usuario"]);
    exit();
}

$cedulaUsuario = $encabezados["CEDULA"];

// -------------------------------------------------------------
// 2) Buscar usuario por cédula
// -------------------------------------------------------------
$infoUsuario = $UsuariosAPI->obtener_Usuarios_por_cedula($cedulaUsuario);

if (!$infoUsuario) {
    echo json_encode(["error" => "Usuario no encontrado con esa cédula."]);
    exit();
}

// LA CLAVE DEL USUARIO SE USA PARA DESCIFRAR
define("CLAVE_SECRETA", $infoUsuario["clave"]);

// -------------------------------------------------------------
// 3) Función para desencriptar cuerpo
// -------------------------------------------------------------
function Desencriptar_BODY($JSON)
{
    $cifrado = "aes-256-ecb";

    $texto = openssl_decrypt(
        base64_decode($JSON),
        $cifrado,
        CLAVE_SECRETA,
        OPENSSL_RAW_DATA
    );

    if ($texto === false) {
        return null;
    }

    return $texto;
}

// -------------------------------------------------------------
// 4) Recibir el BODY ENCRIPTADO
// -------------------------------------------------------------
$body_encriptado = file_get_contents("php://input");

if (!$body_encriptado || trim($body_encriptado) == "") {
    echo json_encode(["error" => "No se envió BODY encriptado."]);
    exit();
}

// -------------------------------------------------------------
// 5) Desencriptar BODY
// -------------------------------------------------------------
$body_desencriptado = Desencriptar_BODY($body_encriptado);

if ($body_desencriptado === null) {
    echo json_encode(["error" => "No se pudo desencriptar el BODY."]);
    exit();
}

// Convertir JSON desencriptado a array
$body = json_decode($body_desencriptado, true);

if (!is_array($body)) {
    echo json_encode(["error" => "BODY desencriptado no posee formato JSON válido."]);
    exit();
}

// -------------------------------------------------------------
// 6) Validar operación requerida
// -------------------------------------------------------------
if (!isset($body["op"])) {
    echo json_encode(["error" => "Debe incluir 'op' dentro del BODY."]);
    exit();
}

$op = $body["op"];


switch ($op) {

    case "ObtenerTodos":
        echo json_encode($Productos->obtener_productos());
        break;

    case "BuscarPorNombre":
        if (!isset($body["Nombre"])) {
            echo json_encode(["error" => "Debe enviar 'Nombre'."]);
            exit();
        }
        echo json_encode($Productos->buscar_por_nombre($body["Nombre"]));
        break;

    case "ObtenerPorID":
        if (!isset($body["ID"])) {
            echo json_encode(["error" => "Debe enviar 'ID'."]);
            exit();
        }
        echo json_encode($Productos->obtener_producto_por_id($body["ID"]));
        break;

    case "Insertar":
        if (!isset($body["Nombre"], $body["PrecioUnit"], $body["Cantidad"])) {
            echo json_encode(["error" => "Datos incompletos para insertar."]);
            exit();
        }

        $Productos->insertar_producto(
            $body["Nombre"],
            $body["PrecioUnit"],
            $body["Cantidad"]
        );

        echo json_encode(["Correcto" => "Producto insertado correctamente."]);
        break;

    case "Actualizar":
        if (!isset($body["ID"], $body["Nombre"], $body["PrecioUnit"], $body["Cantidad"])) {
            echo json_encode(["error" => "Datos incompletos para actualizar."]);
            exit();
        }

        $Productos->actualizar_producto(
            $body["ID"],
            $body["Nombre"],
            $body["PrecioUnit"],
            $body["Cantidad"]
        );

        echo json_encode(["Correcto" => "Producto actualizado correctamente."]);
        break;

    case "Eliminar":
        if (!isset($body["ID"])) {
            echo json_encode(["error" => "Debe enviar 'ID'."]);
            exit();
        }

        $Productos->eliminar_producto($body["ID"]);

        echo json_encode(["Correcto" => "Producto eliminado."]);
        break;

    default:
        echo json_encode(["error" => "Operación no válida."]);
        break;
}

?>