<?php

include_once '../db_connect.php';

class Cart {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }


    // Retrieve cart items for a specific user
    public function getCartItems($userId) {
        $stmt = $this->conn->prepare("SELECT cart.id, menu_items.name, menu_items.price, cart.quantity 
                                      FROM cart 
                                      JOIN menu_items ON cart.menu_item_id = menu_items.id
                                      WHERE cart.table_id = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        return $stmt->get_result();
    }

    // Calculate total price for the cart items
    public function getTotalPrice($userId) {
        $total_price = 0;
        $result = $this->getCartItems($userId);
        while ($cart_item = $result->fetch_assoc()) {
            $total_price += $cart_item['price'] * $cart_item['quantity'];
        }
        return $total_price;
    }

    // Add an item to the cart
    public function addToCart($userId, $menuItemId, $quantity = 1) {
        // Check if the item is already in the cart
        $stmt = $this->conn->prepare("SELECT quantity FROM cart WHERE table_id = ? AND menu_item_id = ?");
        $stmt->bind_param("ii", $userId, $menuItemId);
        $stmt->execute();
        $stmt->store_result();
        
        if ($stmt->num_rows > 0) {
            // If item exists, update the quantity
            $stmt->bind_result($existingQuantity);
            $stmt->fetch();
            $newQuantity = $existingQuantity + $quantity;
            $updateStmt = $this->conn->prepare("UPDATE cart SET quantity = ? WHERE table_id = ? AND menu_item_id = ?");
            $updateStmt->bind_param("iii", $newQuantity, $userId, $menuItemId);
            $updateStmt->execute();
            $updateStmt->close();
        } else {
            // Add new item to the cart
            $insertStmt = $this->conn->prepare("INSERT INTO cart (table_id, menu_item_id, quantity) VALUES (?, ?, ?)");
            $insertStmt->bind_param("iii", $userId, $menuItemId, $quantity);
            $insertStmt->execute();
            $insertStmt->close();
        }

        $stmt->close();
    }

    // Remove an item from the cart
    public function removeFromCart($userId, $cartId) {
        $stmt = $this->conn->prepare("DELETE FROM cart WHERE id = ? AND table_id = ?");
        $stmt->bind_param("ii", $cartId, $userId);
        $stmt->execute();
        $stmt->close();
    }

    // Clear the entire cart for a user
    public function clearCart($userId) {
        $stmt = $this->conn->prepare("DELETE FROM cart WHERE table_id = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $stmt->close();
    }
}
?>
