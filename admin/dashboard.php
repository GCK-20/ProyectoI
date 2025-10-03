<?php
session_start();

// Guardia de seguridad para el administrador
if (!isset($_SESSION['user_id']) || $_SESSION['user_rol'] !== 'administrador') {
    header("Location: ../login.php");
    exit();
}

// Incluir archivos necesarios con la ruta correcta
include_once '../config/database.php';
include_once '../models/Planta.php';

// Obtener la conexión a la base de datos
$database = new Database();
$db = $database->connect();

// Lógica de búsqueda (opcional, pero útil para el admin)
$planta = new Planta($db);
$keywords = isset($_GET["buscar"]) ? $_GET["buscar"] : "";
if (!empty($keywords)) {
    $stmt = $planta->buscar($keywords);
} else {
    $stmt = $planta->leer();
}
$num = $stmt->rowCount();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - Administración</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">Admin Vivero</a>
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link">Bienvenido, <?php echo htmlspecialchars($_SESSION['user_nombre']); ?></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../logout.php">Cerrar Sesión</a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="row mb-3">
            <div class="col-md-6"><h1>Gestión de Plantas</h1></div>
            <div class="col-md-6">
                <form action="dashboard.php" method="GET" class="d-flex">
                    <input class="form-control me-2" type="search" placeholder="Buscar..." name="buscar">
                    <button class="btn btn-outline-secondary" type="submit">Buscar</button>
                </form>
            </div>
        </div>
        
        <a href="crear_planta.php" class="btn btn-success mb-3">Añadir Nueva Planta</a>

        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>ID</th><th>Nombre</th><th>Tipo</th><th>Precio</th><th>Stock</th><th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($num > 0): ?>
                    <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): extract($row); ?>
                        <tr>
                            <td><?php echo htmlspecialchars($id); ?></td>
                            <td><?php echo htmlspecialchars($nombre_comun); ?></td>
                            <td><?php echo htmlspecialchars($tipo); ?></td>
                            <td>$<?php echo htmlspecialchars($precio); ?></td>
                            <td><?php echo htmlspecialchars($stock); ?></td>
                            <td>
                                <a href="editar_planta.php?id=<?php echo $id; ?>" class="btn btn-primary btn-sm">Editar</a>
                                <a href="eliminar_planta.php?id=<?php echo $id; ?>" class="btn btn-danger btn-sm">Eliminar</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="6" class="text-center">No hay plantas registradas.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>