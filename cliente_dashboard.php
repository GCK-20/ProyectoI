<?php
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

// Lógica de búsqueda
$keywords = isset($_GET["buscar"]) ? $_GET["buscar"] : "";
if (!empty($keywords)) {
    $stmt = $planta->buscar($keywords);
} else {
    $stmt = $planta->leer();
}
$plantas = $stmt->fetchAll(PDO::FETCH_ASSOC);

$total_carrito = 0;
if (isset($_SESSION['carrito'])) {
    $total_carrito = array_sum($_SESSION['carrito']);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Catálogo del Vivero</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        .card-img-top { height: 200px; object-fit: cover; }
        .carousel-item img { height: 400px; object-fit: cover; filter: brightness(0.7); }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-success sticky-top">
        <div class="container">
            <a class="navbar-brand" href="index.php">Vivero El Edén</a>
            <ul class="navbar-nav ms-auto">
                 <li class="nav-item">
                    <a class="nav-link">¡Hola, <?php echo htmlspecialchars($_SESSION['user_nombre']); ?>!</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="carrito.php"><i class="bi bi-cart"></i> Carrito (<?php echo $total_carrito; ?>)</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="logout.php">Cerrar Sesión</a>
                </li>
            </ul>
        </div>
    </nav>
    
    <div class="container mt-5">
        <div class="row mb-4">
            <div class="col-md-8 offset-md-2">
                <form action="cliente_dashboard.php" method="GET" class="d-flex">
                    <input class="form-control me-2" type="search" placeholder="Buscar por nombre..." name="buscar" value="<?php echo htmlspecialchars($keywords); ?>">
                    <button class="btn btn-outline-success" type="submit">Buscar</button>
                </form>
            </div>
        </div>

        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4">
            <?php foreach ($plantas as $planta_item): ?>
                <div class="col">
                    <div class="card h-100 shadow-sm">
                        <img src="<?php echo !empty($planta_item['imagen_url']) ? htmlspecialchars($planta_item['imagen_url']) : 'https://via.placeholder.com/300x200'; ?>" class="card-img-top" alt="...">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($planta_item['nombre_comun']); ?></h5>
                            <p class="card-text text-success fs-5">$<?php echo htmlspecialchars($planta_item['precio']); ?></p>
                        </div>
                        <div class="card-footer bg-transparent border-0 pb-3">
                            <div class="d-grid gap-2 d-md-flex justify-content-md-between">
                                <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#plantaModal" 
                                    data-nombre="<?php echo htmlspecialchars($planta_item['nombre_comun']); ?>" 
                                    data-descripcion="<?php echo htmlspecialchars($planta_item['descripcion']); ?>" 
                                    data-cientifico="<?php echo htmlspecialchars($planta_item['nombre_cientifico']); ?>" 
                                    data-tipo="<?php echo htmlspecialchars($planta_item['tipo']); ?>" 
                                    data-luz="<?php echo htmlspecialchars($planta_item['cuidados_luz']); ?>" 
                                    data-riego="<?php echo htmlspecialchars($planta_item['cuidados_riego']); ?>" 
                                    data-precio="$<?php echo htmlspecialchars($planta_item['precio']); ?>">
                                    Ver Detalles
                                </button>
                                <form action="gestion_carrito.php" method="POST" class="d-inline">
                                    <input type="hidden" name="planta_id" value="<?php echo $planta_item['id']; ?>">
                                    <input type="hidden" name="action" value="agregar">
                                    <button type="submit" class="btn btn-success btn-sm"><i class="bi bi-cart-plus"></i> Añadir</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="modal fade" id="plantaModal" tabindex="-1" aria-labelledby="plantaModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="plantaModalLabel">Detalles de la Planta</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <h3 id="modal-nombre"></h3>
            <p><strong class="text-muted">Nombre científico:</strong> <span id="modal-cientifico"></span></p>
            <p id="modal-descripcion"></p>
            <hr>
            <h5>Cuidados:</h5>
            <ul>
                <li><strong>Luz:</strong> <span id="modal-luz"></span></li>
                <li><strong>Riego:</strong> <span id="modal-riego"></span></li>
                <li><strong>Tipo:</strong> <span id="modal-tipo"></span></li>
            </ul>
            <h4 class="text-end text-success mt-4" id="modal-precio"></h4>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
          </div>
        </div>
      </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        const plantaModal = document.getElementById('plantaModal');
        if (plantaModal) {
            plantaModal.addEventListener('show.bs.modal', event => {
              // Botón que activó el modal
              const button = event.relatedTarget;
              
              // Extraer información de los atributos data-*
              const nombre = button.getAttribute('data-nombre');
              const descripcion = button.getAttribute('data-descripcion');
              const cientifico = button.getAttribute('data-cientifico');
              const tipo = button.getAttribute('data-tipo');
              const luz = button.getAttribute('data-luz');
              const riego = button.getAttribute('data-riego');
              const precio = button.getAttribute('data-precio');

              // Actualizar el contenido del modal
              plantaModal.querySelector('#modal-nombre').textContent = nombre;
              plantaModal.querySelector('#modal-descripcion').textContent = descripcion;
              plantaModal.querySelector('#modal-cientifico').textContent = cientifico;
              plantaModal.querySelector('#modal-tipo').textContent = tipo;
              plantaModal.querySelector('#modal-luz').textContent = luz;
              plantaModal.querySelector('#modal-riego').textContent = riego;
              plantaModal.querySelector('#modal-precio').textContent = precio;
            });
        }
    </script>
    </body>
</html>
