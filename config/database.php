<?php
class Database {
    // Parámetros de la conexión a la BD
    private $host = 'localhost';
    private $db_name = 'vivero_app';
    private $username = 'root';
    private $password = '';
    private $conn;

    // Método de conexión a la BD
    public function connect() {
        $this->conn = null;

        try {
            $this->conn = new PDO('mysql:host=' . $this->host . ';dbname=' . $this->db_name, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            echo 'Error de Conexión: ' . $e->getMessage();
        }

        return $this->conn;
    }
}
?>