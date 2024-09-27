<?php
// classes/Menu.php

include_once('../db_connect.php');

class Menu {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAvailableMenuItems($category_id = null) {
        $query = "SELECT id, name, description, price, image FROM menu_items WHERE status = 'available'";
        
        if ($category_id) {
            $query .= " AND category_id = ?";
        }

        // Prepare the statement
        $stmt = $this->conn->prepare($query);
        if (!$stmt) {
            die('Prepare failed: ' . $this->conn->error);
        }

        // Bind parameters if category_id is provided
        if ($category_id) {
            $stmt->bind_param('i', $category_id);
        }

        // Execute the statement
        if (!$stmt->execute()) {
            die('Execute failed: ' . $stmt->error);
        }

        // Fetch the results
        $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        
        // Close the statement
        $stmt->close();

        return $result;
    }

    public function getCategories() {
        $query = "SELECT id, name FROM categories"; // You can add an image field here if needed
        
        // Prepare the statement
        $stmt = $this->conn->prepare($query);
        if (!$stmt) {
            die('Prepare failed: ' . $this->conn->error);
        }

        // Execute the statement
        if (!$stmt->execute()) {
            die('Execute failed: ' . $stmt->error);
        }

        // Fetch the results
        $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        
        // Close the statement
        $stmt->close();

        return $result;
    }
    
    
}
?>
