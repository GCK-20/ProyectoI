<?php
// 1. Iniciar la sesión para poder acceder a ella
session_start();

// 2. Destruir todas las variables de la sesión
$_SESSION = array();

// 3. Finalmente, destruir la sesión del servidor
session_destroy();

// 4. Redirigir al usuario a la página de login
header("Location: login.php");
exit();
?>