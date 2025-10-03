<?php
session_start();
if (!isset($_SESSION['user_id']) || !isset($_SESSION['carrito']) || empty($_SESSION['carrito'])) {
    // Si no hay sesión, no hay carrito o está vacío, no se puede procesar
    header("Location: carrito.php");
    exit();
}

include_once './config/database.php';
include_once './models/Planta.php';
include_once './models/Pedido.php';

$database = new Database();
$db = $database->connect();
$planta = new Planta($db);
$pedido = new Pedido($db);

// 1. Recalcular el total y obtener detalles de productos desde la BD
// Esto es una medida de seguridad para asegurar que los precios son los correctos.
$items_carrito = $_SESSION['carrito'];
$ids = array_keys($items_carrito);
$stmt = $planta->leerPorIds($ids);

$total = 0;
$carrito_con_detalles = [];
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $row['cantidad'] = $items_carrito[$row['id']];
    $carrito_con_detalles[] = $row;
    $total += $row['precio'] * $row['cantidad'];
}

// 2. Intentar crear el pedido
$id_usuario = $_SESSION['user_id'];
if ($pedido->crear($carrito_con_detalles, $id_usuario, $total)) {
    // 3. Si el pedido se crea con éxito, vaciar el carrito
    unset($_SESSION['carrito']);
    // 4. Redirigir a una página de éxito
    header("Location: compra_exitosa.php");
    exit();
} else {
    // Si algo falló durante la transacción, redirigir de vuelta al carrito con un error
    header("Location: carrito.php?error=1");
    exit();
}
?>