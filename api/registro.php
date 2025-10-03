<?php
// Headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Incluir archivos
include_once '../config/database.php';
include_once '../models/Usuario.php';

$database = new Database();
$db = $database->connect();

$usuario = new Usuario($db);

// Obtener los datos enviados
$data = json_decode(file_get_contents("php://input"));

// Asegurarse que los datos no están vacíos
if (
    !empty($data->nombre) &&
    !empty($data->email) &&
    !empty($data->password)
) {
    // Asignar valores
    $usuario->nombre = $data->nombre;
    $usuario->email = $data->email;
    $usuario->password = $data->password;
    $usuario->rol = 'cliente'; // Rol por defecto al registrarse

    // Crear el usuario
    if($usuario->crear()) {
        // Código de respuesta - 201 Creado
        http_response_code(201);
        echo json_encode(array("message" => "El usuario fue creado exitosamente."));
    } else {
        // Código de respuesta - 503 Servicio no disponible
        http_response_code(503);
        echo json_encode(array("message" => "No se pudo crear el usuario."));
    }
} else {
    // Código de respuesta - 400 Solicitud incorrecta
    http_response_code(400);
    echo json_encode(array("message" => "No se pudo crear el usuario. Los datos están incompletos."));
}
?>