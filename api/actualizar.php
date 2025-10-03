<?php
// Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: PUT');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

include_once '../config/database.php';
include_once '../models/Planta.php';

$database = new Database();
$db = $database->connect();

$planta = new Planta($db);

// Obtener los datos enviados
$data = json_decode(file_get_contents("php://input"));

// Asegurarse que el ID de la planta a actualizar es enviado
if (!empty($data->id)) {
    // Asignar el ID a la planta
    $planta->id = $data->id;

    // Asignar el resto de los valores
    $planta->nombre_comun = $data->nombre_comun;
    $planta->precio = $data->precio;
    $planta->stock = $data->stock;
    $planta->tipo = $data->tipo;
    $planta->descripcion = $data->descripcion;
    $planta->nombre_cientifico = $data->nombre_cientifico;
    $planta->imagen_url = $data->imagen_url;

    // Actualizar la planta
    if($planta->actualizar()) {
        // Código de respuesta - 200 OK
        http_response_code(200);
        echo json_encode(array("message" => "La planta fue actualizada."));
    } else {
        // Código de respuesta - 503 Servicio no disponible
        http_response_code(503);
        echo json_encode(array("message" => "No se pudo actualizar la planta."));
    }
} else {
    // Código de respuesta - 400 Solicitud incorrecta
    http_response_code(400);
    echo json_encode(array("message" => "No se pudo actualizar la planta. El ID está ausente."));
}
?>