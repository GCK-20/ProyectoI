<?php
session_start();

include_once './config/database.php';
include_once './models/Usuario.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $database = new Database();
    $db = $database->connect();
    $usuario = new Usuario($db);

    $usuario->email = $_POST['email'];
    $usuario->password = $_POST['password'];

    $datos_usuario = $usuario->login();

    if ($datos_usuario) {
        // Login exitoso, guardar datos en sesión
        $_SESSION['user_id'] = $datos_usuario['id'];
        $_SESSION['user_nombre'] = $datos_usuario['nombre'];
        $_SESSION['user_rol'] = $datos_usuario['rol'];
        
        // Redirigir según el rol
        if ($datos_usuario['rol'] == 'administrador') {
            header("Location: admin/dashboard.php");
            exit();
        } else if ($datos_usuario['rol'] == 'cliente') {
            header("Location: cliente_dashboard.php");
            exit();
        }
    }
    
    // Si el login falla o el rol no es reconocido, redirigir con error
    header("Location: login.php?error=1");
    exit();
}
?>