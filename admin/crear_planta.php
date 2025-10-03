<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_rol'] !== 'administrador') {
    header("Location: ../login.php");
    exit();
}

include_once '../config/database.php';
include_once '../models/Planta.php';

$error_message = "";
$imagen_url_db = ""; // Variable para guardar la ruta de la imagen en la BD

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // --- LÓGICA PARA SUBIR LA IMAGEN ---
    if (isset($_FILES["imagen"]) && $_FILES["imagen"]["error"] == 0) {
        $target_dir = "../uploads/"; // Sube un nivel desde 'admin' a la carpeta 'uploads'
        // Crear un nombre de archivo único para evitar sobreescribir
        $image_name = uniqid() . '_' . basename($_FILES["imagen"]["name"]);
        $target_file = $target_dir . $image_name;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Validaciones de la imagen
        $check = getimagesize($_FILES["imagen"]["tmp_name"]);
        if ($check !== false) {
            if (move_uploaded_file($_FILES["imagen"]["tmp_name"], $target_file)) {
                // Si la subida es exitosa, guardamos la ruta relativa
                $imagen_url_db = "uploads/" . $image_name; 
            } else {
                $error_message = "Hubo un error al subir tu archivo.";
            }
        } else {
            $error_message = "El archivo no es una imagen válida.";
        }
    }

    // --- LÓGICA PARA GUARDAR EN LA BASE DE DATOS ---
    if (empty($error_message)) {
        $database = new Database();
        $db = $database->connect();
        $planta = new Planta($db);

        $planta->nombre_comun = $_POST['nombre_comun'];
        $planta->precio = $_POST['precio'];
        $planta->stock = $_POST['stock'];
        $planta->tipo = $_POST['tipo'];
        // Asignar la ruta de la imagen guardada
        $planta->imagen_url = $imagen_url_db; 
        // ... (puedes asignar el resto de los campos aquí si los tienes en el form)
        $planta->descripcion = $_POST['descripcion'];
        
        if ($planta->crear()) {
            header("Location: dashboard.php");
            exit();
        } else {
            $error_message = "No se pudo crear la planta. Intente de nuevo.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Añadir Nueva Planta</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h2>Añadir Nueva Planta</h2>
        <?php if (!empty($error_message)): ?>
            <div class="alert alert-danger"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <form action="crear_planta.php" method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="nombre_comun" class="form-label">Nombre Común</label>
                <input type="text" class="form-control" name="nombre_comun" required>
            </div>
            <div class="mb-3">
                <label for="descripcion" class="form-label">Descripción</label>
                <textarea class="form-control" name="descripcion" rows="3"></textarea>
            </div>
            <div class="mb-3">
                <label for="precio" class="form-label">Precio</label>
                <input type="number" step="0.01" class="form-control" name="precio" required>
            </div>
            <div class="mb-3">
                <label for="stock" class="form-label">Stock</label>
                <input type="number" class="form-control" name="stock" required>
            </div>
             <div class="mb-3">
                <label for="tipo" class="form-label">Tipo</label>
                <input type="text" class="form-control" name="tipo" required>
            </div>

            <div class="mb-3">
                <label for="imagen" class="form-label">Imagen de la Planta</label>
                <input type="file" class="form-control" id="imagen" name="imagen">
            </div>
            
            <button type="submit" class="btn btn-primary">Guardar Planta</button>
            <a href="dashboard.php" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</body>
</html>