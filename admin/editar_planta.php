<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_rol'] !== 'administrador') {
    header("Location: ../login.php");
    exit();
}

include_once '../config/database.php';
include_once '../models/Planta.php';

$database = new Database();
$db = $database->connect();
$planta = new Planta($db);
$error_message = "";

// --- LÓGICA PARA PROCESAR EL FORMULARIO (POST) ---
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $imagen_url_db = $_POST['imagen_actual']; // Por defecto, mantenemos la imagen actual

    // Si se sube un nuevo archivo, procesarlo
    if (isset($_FILES["imagen"]) && $_FILES["imagen"]["error"] == 0) {
        $target_dir = "../uploads/";
        $image_name = uniqid() . '_' . basename($_FILES["imagen"]["name"]);
        $target_file = $target_dir . $image_name;
        
        // Mover el archivo subido
        if (move_uploaded_file($_FILES["imagen"]["tmp_name"], $target_file)) {
            $imagen_url_db = "uploads/" . $image_name; // Actualizamos a la nueva ruta
            // Opcional: si existe una imagen antigua y no es un placeholder, borrarla
            if (!empty($_POST['imagen_actual'])) {
                unlink('../' . $_POST['imagen_actual']);
            }
        }
    }

    // Asignar todos los valores del formulario al objeto y actualizar
    $planta->id = $_POST['id'];
    $planta->nombre_comun = $_POST['nombre_comun'];
    $planta->nombre_cientifico = $_POST['nombre_cientifico'];
    $planta->descripcion = $_POST['descripcion'];
    $planta->tipo = $_POST['tipo'];
    $planta->cuidados_luz = $_POST['cuidados_luz'];
    $planta->cuidados_riego = $_POST['cuidados_riego'];
    $planta->precio = $_POST['precio'];
    $planta->stock = $_POST['stock'];
    $planta->imagen_url = $imagen_url_db;

    if ($planta->actualizar()) {
        header("Location: dashboard.php");
        exit();
    } else {
        $error_message = "No se pudo actualizar la planta.";
    }
} 
// --- LÓGICA PARA OBTENER DATOS DE LA PLANTA (GET) ---
else if (isset($_GET['id'])) {
    $planta->id = $_GET['id'];
    $planta->leerUno();
} else {
    // Si no se proporciona un ID, redirigir al dashboard
    header("Location: dashboard.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Planta</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h2>Editar Planta del Catálogo</h2>

        <?php if (!empty($error_message)): ?>
            <div class="alert alert-danger"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <form action="editar_planta.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($planta->id); ?>">
            <input type="hidden" name="imagen_actual" value="<?php echo htmlspecialchars($planta->imagen_url); ?>">
            
            <div class="mb-3">
                <label for="nombre_comun" class="form-label">Nombre Común</label>
                <input type="text" class="form-control" id="nombre_comun" name="nombre_comun" value="<?php echo htmlspecialchars($planta->nombre_comun); ?>" required>
            </div>
            <div class="mb-3">
                <label for="nombre_cientifico" class="form-label">Nombre Científico</label>
                <input type="text" class="form-control" id="nombre_cientifico" name="nombre_cientifico" value="<?php echo htmlspecialchars($planta->nombre_cientifico); ?>">
            </div>
            <div class="mb-3">
                <label for="descripcion" class="form-label">Descripción</label>
                <textarea class="form-control" id="descripcion" name="descripcion" rows="3"><?php echo htmlspecialchars($planta->descripcion); ?></textarea>
            </div>
             <div class="mb-3">
                <label for="tipo" class="form-label">Tipo</label>
                <input type="text" class="form-control" id="tipo" name="tipo" value="<?php echo htmlspecialchars($planta->tipo); ?>" required>
            </div>
            <div class="mb-3">
                <label for="cuidados_luz" class="form-label">Cuidados de Luz</label>
                <input type="text" class="form-control" id="cuidados_luz" name="cuidados_luz" value="<?php echo htmlspecialchars($planta->cuidados_luz); ?>">
            </div>
            <div class="mb-3">
                <label for="cuidados_riego" class="form-label">Cuidados de Riego</label>
                <input type="text" class="form-control" id="cuidados_riego" name="cuidados_riego" value="<?php echo htmlspecialchars($planta->cuidados_riego); ?>">
            </div>
             <div class="mb-3">
                <label for="precio" class="form-label">Precio</label>
                <input type="number" step="0.01" class="form-control" id="precio" name="precio" value="<?php echo htmlspecialchars($planta->precio); ?>" required>
            </div>
             <div class="mb-3">
                <label for="stock" class="form-label">Stock</label>
                <input type="number" class="form-control" id="stock" name="stock" value="<?php echo htmlspecialchars($planta->stock); ?>" required>
            </div>
            
            <div class="mb-3">
                <h5>Imagen Actual</h5>
                <?php if(!empty($planta->imagen_url)): ?>
                    <img src="../<?php echo htmlspecialchars($planta->imagen_url); ?>" alt="Imagen actual" class="img-thumbnail" style="max-width: 200px;">
                <?php else: ?>
                    <p>No hay imagen asignada.</p>
                <?php endif; ?>
            </div>
             <div class="mb-3">
                <label for="imagen" class="form-label">Cambiar Imagen (opcional)</label>
                <input type="file" class="form-control" id="imagen" name="imagen">
            </div>
            
            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
            <a href="dashboard.php" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</body>
</html>