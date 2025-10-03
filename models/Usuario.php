<?php
class Usuario {
    // Conexión a la BD y nombre de la tabla
    private $conn;
    private $table_name = "usuarios";

    // Propiedades del objeto
    public $id;
    public $nombre;
    public $email;
    public $password;
    public $rol;

    // Constructor
    public function __construct($db) {
        $this->conn = $db;
    }

    // Crear un nuevo usuario (VERSIÓN INSEGURA SIN HASHING)
    function crear() {
        // Consulta para insertar un registro
        $query = "INSERT INTO " . $this->table_name . " SET nombre=:nombre, email=:email, password=:password, rol=:rol";

        // Preparar la consulta
        $stmt = $this->conn->prepare($query);

        // Limpiar los datos
        $this->nombre = htmlspecialchars(strip_tags($this->nombre));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->password = htmlspecialchars(strip_tags($this->password));
        $this->rol = htmlspecialchars(strip_tags($this->rol));

        // --- CAMBIO CLAVE ---
        // Ya no se hashea la contraseña. Se vincula directamente.
        // $password_hash = password_hash($this->password, PASSWORD_BCRYPT); <-- LÍNEA ELIMINADA

        // Vincular los valores
        $stmt->bindParam(":nombre", $this->nombre);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":password", $this->password); // <-- Se usa $this->password directamente
        $stmt->bindParam(":rol", $this->rol);

        // Ejecutar la consulta
        if ($stmt->execute()) {
            return true;
        }

        return false;
    }
    // Login de usuario (VERSIÓN INSEGURA SIN HASHING)
    function login() {
        // Consulta para buscar el email
        $query = "SELECT id, nombre, email, password, rol FROM " . $this->table_name . " WHERE email = ? LIMIT 1";

        // Preparar la consulta
        $stmt = $this->conn->prepare($query);

        // Vincular el email
        $stmt->bindParam(1, $this->email);

        // Ejecutar la consulta
        $stmt->execute();

        // Verificar si el usuario existe
        if ($stmt->rowCount() == 1) {
            // Obtener la fila
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            // --- CAMBIO CLAVE ---
            // Verificar la contraseña con una comparación simple
            if ($this->password === $row['password']) { // <-- Se reemplazó password_verify()
                // La contraseña es correcta, devolver los datos del usuario
                return $row;
            }
        }
        
        // Si el usuario no existe o la contraseña es incorrecta
        return false;
    }
}
?>