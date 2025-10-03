<?php
include_once './config/database.php';
include_once './models/Usuario.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $database = new Database();
    $db = $database->connect();
    $usuario = new Usuario($db);

    $usuario->nombre = $_POST['nombre'];
    $usuario->email = $_POST['email'];
    $usuario->password = $_POST['password'];
    $usuario->rol = 'cliente'; // Todos los registros desde aquí son clientes

    if ($usuario->crear()) {
        // Redirigir al login con mensaje de éxito
        header("Location: login.php?exito=1");
        exit();
    } else {
        // Redirigir de vuelta con un error (email duplicado)
        header("Location: registro_form.php?error=1");
        exit();
    }
}
?>