<?php
// --- LÍNEAS DE DEPURACIÓN PARA MOSTRAR ERRORES ---
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include_once './config/database.php';
include_once './models/Planta.php';

$database = new Database();
$db = $database->connect();
$planta = new Planta($db);

$items_carrito = isset($_SESSION['carrito']) ? $_SESSION['carrito'] : array();
$plantas_en_carrito = array();
$total = 0;

if (!empty($items_carrito)) {
    $ids = array_keys($items_carrito);
    $stmt = $planta->leerPorIds($ids);

    // Verificamos si la consulta fue exitosa antes de continuar
    if($stmt) {
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $row['cantidad'] = $items_carrito[$row['id']];
            $plantas_en_carrito[] = $row;
            $total += $row['precio'] * $row['cantidad'];
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Carrito de Compras</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-success">
        </nav>

    <div class="container mt-5">
        <h1 class="mb-4">Tu Carrito de Compras</h1>
        <?php if (!empty($plantas_en_carrito)): ?>
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Producto</th>
                        <th>Precio</th>
                        <th>Cantidad</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($plantas_en_carrito as $item): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($item['nombre_comun']); ?></td>
                            <td>$<?php echo htmlspecialchars($item['precio']); ?></td>
                            <td><?php echo htmlspecialchars($item['cantidad']); ?></td>
                            <td>$<?php echo number_format($item['precio'] * $item['cantidad'], 2); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="3" class="text-end">Total:</th>
                        <th>$<?php echo number_format($total, 2); ?></th>
                    </tr>
                </tfoot>
            </table>
            <div class="text-end mt-4">
                <form action="procesar_compra.php" method="POST">
                    <button type="submit" class="btn btn-primary btn-lg">Finalizar Compra</button>
                </form>
            </div>
        <?php else: ?>
            <div class="alert alert-info">Tu carrito está vacío. <a href="cliente_dashboard.php">Vuelve al catálogo</a> para añadir plantas.</div>
        <?php endif; ?>
    </div>
</body>
</html>