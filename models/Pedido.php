<?php
class Pedido {
    private $conn;
    private $table_name = "pedidos";

    public $id;
    public $id_usuario;
    public $total;
    public $fecha_pedido;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Crear un nuevo pedido (¡usando una transacción!)
    function crear($carrito, $id_usuario, $total) {
        // Una transacción asegura que todas las operaciones se completen con éxito, o ninguna lo hará.
        // Esto evita que se descuente stock si el pedido no se puede guardar, y viceversa.
        $this->conn->beginTransaction();

        try {
            // 1. Insertar el pedido principal en la tabla `pedidos`
            $query_pedido = "INSERT INTO " . $this->table_name . " SET id_usuario=:id_usuario, total=:total";
            $stmt_pedido = $this->conn->prepare($query_pedido);
            
            $stmt_pedido->bindParam(":id_usuario", $id_usuario);
            $stmt_pedido->bindParam(":total", $total);
            $stmt_pedido->execute();
            
            // Obtener el ID del pedido que acabamos de crear
            $id_pedido = $this->conn->lastInsertId();

            // 2. Insertar cada producto del carrito en `detalle_pedidos` y actualizar stock
            $query_detalle = "INSERT INTO detalle_pedidos SET id_pedido=:id_pedido, id_planta=:id_planta, cantidad=:cantidad, precio_unitario=:precio_unitario";
            $query_stock = "UPDATE plantas SET stock = stock - :cantidad WHERE id = :id_planta";

            foreach ($carrito as $planta) {
                // Insertar en detalle_pedidos
                $stmt_detalle = $this->conn->prepare($query_detalle);
                $stmt_detalle->bindParam(":id_pedido", $id_pedido);
                $stmt_detalle->bindParam(":id_planta", $planta['id']);
                $stmt_detalle->bindParam(":cantidad", $planta['cantidad']);
                $stmt_detalle->bindParam(":precio_unitario", $planta['precio']);
                $stmt_detalle->execute();

                // Actualizar stock en la tabla plantas
                $stmt_stock = $this->conn->prepare($query_stock);
                $stmt_stock->bindParam(":cantidad", $planta['cantidad']);
                $stmt_stock->bindParam(":id_planta", $planta['id']);
                $stmt_stock->execute();
            }

            // Si todo fue bien, confirmar los cambios
            $this->conn->commit();
            return true;

        } catch (Exception $e) {
            // Si algo falló, revertir todos los cambios
            $this->conn->rollBack();
            return false;
        }
    }
}
?>