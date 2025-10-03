<?php
class Planta {
    // Conexión a la BD y nombre de la tabla
    private $conn;
    private $table_name = "plantas";

    // Propiedades de la planta
    public $id;
    public $nombre_comun;
    public $nombre_cientifico;
    public $descripcion;
    public $tipo;
    public $cuidados_luz;
    public $cuidados_riego;
    public $precio;
    public $stock;
    public $imagen_url;

    // Constructor con la conexión a la BD
    public function __construct($db) {
        $this->conn = $db;
    }
    // Leer una sola planta por ID
    function leerUno() {
        // Consulta para leer un solo registro
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = ? LIMIT 1";

        // Preparar la consulta
        $stmt = $this->conn->prepare($query);

        // Vincular el ID
        $stmt->bindParam(1, $this->id);

        // Ejecutar la consulta
        $stmt->execute();

        // Obtener la fila
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // Asignar los valores a las propiedades del objeto
        if($row) {
            $this->nombre_comun = $row['nombre_comun'];
            $this->nombre_cientifico = $row['nombre_cientifico'];
            $this->descripcion = $row['descripcion'];
            $this->tipo = $row['tipo'];
            $this->cuidados_luz = $row['cuidados_luz'];
            $this->cuidados_riego = $row['cuidados_riego'];
            $this->precio = $row['precio'];
            $this->stock = $row['stock'];
            $this->imagen_url = $row['imagen_url'];
        }
    }

    // Leer todas las plantas
    function leer() {
        // Crear la consulta
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY nombre_comun ASC";

        // Preparar la declaración de la consulta
        $stmt = $this->conn->prepare($query);

        // Ejecutar la consulta
        $stmt->execute();

        return $stmt;
    }
    // Leer plantas por sus IDs (para el carrito)
    function leerPorIds($ids) {
        // Si el array de IDs está vacío, no se puede continuar.
        if (empty($ids)) {
            return false;
        }

        // Crear placeholders (?) para cada ID. Ej: (?,?,?)
        $placeholders = implode(',', array_fill(0, count($ids), '?'));

        // Crear la consulta SQL usando la cláusula IN
        $query = "SELECT * FROM " . $this->table_name . " WHERE id IN (" . $placeholders . ")";
        
        $stmt = $this->conn->prepare($query);
        
        // Vincular cada ID al statement de forma segura
        $i = 1;
        foreach ($ids as $id) {
            $stmt->bindValue($i++, $id, PDO::PARAM_INT);
        }

        $stmt->execute();
        return $stmt;
    }
    // Reducir el stock de una planta
    function reducirStock($cantidad) {
        $query = "UPDATE " . $this->table_name . " SET stock = stock - :cantidad WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);

        // Limpiar y vincular datos
        $cantidad = htmlspecialchars(strip_tags($cantidad));
        $this->id = htmlspecialchars(strip_tags($this->id));

        $stmt->bindParam(':cantidad', $cantidad);
        $stmt->bindParam(':id', 'this->id');

        if($stmt->execute()) {
            return true;
        }
        return false;
    }
    // Crear una nueva planta
    function crear() {
        // Consulta para insertar un registro
        $query = "INSERT INTO " . $this->table_name . " SET nombre_comun=:nombre_comun, precio=:precio, descripcion=:descripcion, tipo=:tipo, stock=:stock, nombre_cientifico=:nombre_cientifico, imagen_url=:imagen_url";

        // Preparar la consulta
        $stmt = $this->conn->prepare($query);

        // Limpiar los datos (sanitización)
        $this->nombre_comun = htmlspecialchars(strip_tags($this->nombre_comun));
        $this->precio = htmlspecialchars(strip_tags($this->precio));
        $this->descripcion = htmlspecialchars(strip_tags($this->descripcion));
        $this->tipo = htmlspecialchars(strip_tags($this->tipo));
        $this->stock = htmlspecialchars(strip_tags($this->stock));
        $this->nombre_cientifico = htmlspecialchars(strip_tags($this->nombre_cientifico));
        $this->imagen_url = htmlspecialchars(strip_tags($this->imagen_url));
        
        // Vincular los valores
        $stmt->bindParam(":nombre_comun", $this->nombre_comun);
        $stmt->bindParam(":precio", $this->precio);
        $stmt->bindParam(":descripcion", $this->descripcion);
        $stmt->bindParam(":tipo", $this->tipo);
        $stmt->bindParam(":stock", $this->stock);
        $stmt->bindParam(":nombre_cientifico", $this->nombre_cientifico);
        $stmt->bindParam(":imagen_url", $this->imagen_url);
        
        // Ejecutar la consulta
        if($stmt->execute()) {
            return true;
        }
        
        return false;
    }
    // Actualizar una planta existente
    function actualizar() {
        // Consulta de actualización
        $query = "UPDATE " . $this->table_name . "
                SET
                    nombre_comun = :nombre_comun,
                    precio = :precio,
                    descripcion = :descripcion,
                    tipo = :tipo,
                    stock = :stock,
                    nombre_cientifico = :nombre_cientifico,
                    imagen_url = :imagen_url
                WHERE
                    id = :id";
    
        // Preparar la consulta
        $stmt = $this->conn->prepare($query);
    
        // Limpiar los datos
        $this->nombre_comun=htmlspecialchars(strip_tags($this->nombre_comun));
        $this->precio=htmlspecialchars(strip_tags($this->precio));
        $this->descripcion=htmlspecialchars(strip_tags($this->descripcion));
        $this->tipo=htmlspecialchars(strip_tags($this->tipo));
        $this->stock=htmlspecialchars(strip_tags($this->stock));
        $this->nombre_cientifico=htmlspecialchars(strip_tags($this->nombre_cientifico));
        $this->imagen_url=htmlspecialchars(strip_tags($this->imagen_url));
        $this->id=htmlspecialchars(strip_tags($this->id));
    
        // Vincular los valores
        $stmt->bindParam(':nombre_comun', $this->nombre_comun);
        $stmt->bindParam(':precio', $this->precio);
        $stmt->bindParam(':descripcion', $this->descripcion);
        $stmt->bindParam(':tipo', $this->tipo);
        $stmt->bindParam(':stock', $this->stock);
        $stmt->bindParam(':nombre_cientifico', $this->nombre_cientifico);
        $stmt->bindParam(':imagen_url', $this->imagen_url);
        $stmt->bindParam(':id', $this->id);
    
        // Ejecutar la consulta
        if($stmt->execute()) {
            return true;
        }
    
        return false;
    }

    // Eliminar una planta
    function eliminar() {
        // Consulta para eliminar
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";

        // Preparar la consulta
        $stmt = $this->conn->prepare($query);

        // Limpiar el ID
        $this->id=htmlspecialchars(strip_tags($this->id));

        // Vincular el ID
        $stmt->bindParam(':id', $this->id);

        // Ejecutar la consulta
        if($stmt->execute()) {
            return true;
        }

        return false;
    }
    // Buscar plantas por nombre
    function buscar($keywords) {
        // Consulta para buscar
        $query = "SELECT * FROM " . $this->table_name . " WHERE nombre_comun LIKE ? ORDER BY nombre_comun ASC";

        // Preparar la consulta
        $stmt = $this->conn->prepare($query);

        // Limpiar y vincular los keywords
        $keywords = htmlspecialchars(strip_tags($keywords));
        $keywords = "%{$keywords}%"; // Añadir % para buscar coincidencias
        $stmt->bindParam(1, $keywords);

        // Ejecutar la consulta
        $stmt->execute();

        return $stmt;
    }
}
?>