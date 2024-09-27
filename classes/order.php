<?php
class Order {
    private $conn;

    public function __construct($dbConnection) {
        $this->conn = $dbConnection;
    }

    public function processOrder($customer_name, $special_instructions, $dine_or_takeaway, $total_price) {
        // Get the user_id from the session
        $user_id = $_SESSION['user_id']; // Ensure you set this when the user logs in

        // Insert order into the customer_orders table
        $stmt = $this->conn->prepare("INSERT INTO customer_orders (customer_name, special_instructions, dine_or_takeaway, order_date, total_price, user_id) VALUES (?, ?, ?, NOW(), ?, ?)");
        
        if (!$stmt) {
            return false; // Return false if preparing the statement fails
        }

        $stmt->bind_param("ssssi", $customer_name, $special_instructions, $dine_or_takeaway, $total_price, $user_id);
        
        if (!$stmt->execute()) {
            $stmt->close();
            return false; // Return false if the execution fails
        }

        $order_id = $stmt->insert_id; // Get the last inserted order ID
        $stmt->close();

        // Fetch cart items for the order
        return $this->addOrderItems($order_id);
    }

    private function addOrderItems($order_id) {
        // Fetch cart items
        $cart_stmt = $this->conn->prepare("SELECT cart.menu_item_id, cart.quantity, menu_items.price FROM cart JOIN menu_items ON cart.menu_item_id = menu_items.id");
        
        if (!$cart_stmt) {
            return false; // Return false if preparing the statement fails
        }

        $cart_stmt->execute();
        $cart_result = $cart_stmt->get_result();

        // Insert each cart item into order_items
        while ($cart_item = $cart_result->fetch_assoc()) {
            $item_id = $cart_item['menu_item_id'];
            $quantity = $cart_item['quantity'];
            $price = $cart_item['price'];

            $order_item_stmt = $this->conn->prepare("INSERT INTO order_items (order_id, menu_item_id, quantity, price) VALUES (?, ?, ?, ?)");
            
            if ($order_item_stmt) {
                $order_item_stmt->bind_param("iiid", $order_id, $item_id, $quantity, $price);
                if (!$order_item_stmt->execute()) {
                    $order_item_stmt->close();
                    continue; // Continue with the next item on failure
                }
                $order_item_stmt->close();
            }
        }

        $cart_stmt->close();

        // Clear the cart after successful order processing
        return $this->clearCart();
    }

    private function clearCart() {
        $clear_cart_stmt = $this->conn->prepare("DELETE FROM cart");
        
        if (!$clear_cart_stmt) {
            return false; // Return false if preparing the statement fails
        }

        $result = $clear_cart_stmt->execute();
        $clear_cart_stmt->close();

        return $result; // Return the result of the delete operation
    }
}
?>
