<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Compra Exitosa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-success"></nav>
    <div class="container text-center mt-5">
        <h1 class="display-4 text-success">¡Gracias por tu compra!</h1>
        <p class="lead">Tu pedido ha sido procesado exitosamente.</p>
        <p>Pronto podrás ver los detalles en tu historial de pedidos.</p>
        <a href="cliente_dashboard.php" class="btn btn-primary mt-3">Volver al Catálogo</a>
    </div>
</body>
</html>