<?php
session_start();

// Inicializar el carrito en la sesión si no existe
if (!isset($_SESSION['carrito'])) {
    $_SESSION['carrito'] = array();
}

// Verificar que se haya enviado una acción y un ID de planta
if (isset($_POST['action']) && isset($_POST['planta_id'])) {
    $action = $_POST['action'];
    $planta_id = $_POST['planta_id'];

    switch ($action) {
        case 'agregar':
            // Si la planta ya está en el carrito, aumenta la cantidad. Si no, la añade.
            if (isset($_SESSION['carrito'][$planta_id])) {
                $_SESSION['carrito'][$planta_id]++; // Aumenta la cantidad
            } else {
                $_SESSION['carrito'][$planta_id] = 1; // Añade con cantidad 1
            }
            break;

        // Aquí se podrían añadir más casos como 'eliminar', 'vaciar', etc.
    }
}

// Redirigir de vuelta al dashboard con un mensaje de éxito
header('Location: cliente_dashboard.php?status=agregado');
exit();
?>