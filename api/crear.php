<?php
// Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

include_once '../config/database.php';
include_once '../models/Planta.php';

$database = new Database();
$db = $database->connect();

$planta = new Planta($db);

// Obtener los datos enviados (posted data)
$data = json_decode(file_get_contents("php://input"));

// Asegurarse que los datos no estén vacíos
if (
    !empty($data->nombre_comun) &&
    !empty($data->precio) &&
    !empty($data->stock) &&
    !empty($data->tipo)
) {
    // Asignar valores a las propiedades de la planta
    $planta->nombre_comun = $data->nombre_comun;
    $planta->precio = $data->precio;
    $planta->stock = $data->stock;
    $planta->tipo = $data->tipo;
    $planta->descripcion = isset($data->descripcion) ? $data->descripcion : '';
    $planta->nombre_cientifico = isset($data->nombre_cientifico) ? $data->nombre_cientifico : '';
    $planta->imagen_url = isset($data->imagen_url) ? $data->imagen_url : '';

    // Crear la planta
    if($planta->crear()) {
        // Código de respuesta - 201 Creado
        http_response_code(201);
        echo json_encode(array("message" => "La planta fue creada."));
    } else {
        // Código de respuesta - 503 Servicio no disponible
        http_response_code(503);
        echo json_encode(array("message" => "No se pudo crear la planta."));
    }
} else {
    // Código de respuesta - 400 Solicitud incorrecta
    http_response_code(400);
    echo json_encode(array("message" => "No se pudo crear la planta. Los datos están incompletos."));
}
?>