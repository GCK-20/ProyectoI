<?php
// Headers requeridos
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// Incluir archivos de conexión y modelos
include_once '../config/database.php';
include_once '../models/Planta.php';

// Instanciar la base de datos y el objeto planta
$database = new Database();
$db = $database->connect();
$planta = new Planta($db);

// Obtener keywords de la URL (si existen)
$keywords = isset($_GET["buscar"]) ? $_GET["buscar"] : "";

// Si hay keywords de búsqueda, usar la función de búsqueda
if (!empty($keywords)) {
    $stmt = $planta->buscar($keywords);
} else {
    // Si no hay keywords, usar la función de leer todo
    $stmt = $planta->leer();
}

$num = $stmt->rowCount();

// Verificar si se encontraron registros
if ($num > 0) {
    $plantas_arr = array();
    $plantas_arr["records"] = array();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $planta_item = array(
            "id" => $id,
            "nombre_comun" => $nombre_comun,
            "nombre_cientifico" => $nombre_cientifico,
            "descripcion" => html_entity_decode($descripcion),
            "tipo" => $tipo,
            "precio" => $precio,
            "stock" => $stock,
            "imagen_url" => $imagen_url
        );
        array_push($plantas_arr["records"], $planta_item);
    }

    http_response_code(200);
    echo json_encode($plantas_arr);
} else {
    http_response_code(404);
    echo json_encode(array("message" => "No se encontraron plantas."));
}
?>