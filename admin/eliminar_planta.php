<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_rol'] !== 'administrador') {
    header("Location: ../login.php");
    exit();
}

include_once '../config/database.php';
include_once '../models/Planta.php';

// Verificar si se recibió un ID
if (isset($_GET['id'])) {
    $database = new Database();
    $db = $database->connect();
    $planta = new Planta($db);

    $planta->id = $_GET['id'];

    // Intentar eliminar la planta
    $planta->eliminar();
}

// Redirigir siempre al dashboard
header("Location: dashboard.php");
exit();
?>