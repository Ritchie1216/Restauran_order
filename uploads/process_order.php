<?php
session_start(); // Start a session to manage user login state
include 'db_connect.php'; // Include the database connection file

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php'); // Redirect to login page if user is not logged in
    exit();
}

$user_id = $_SESSION['user_id']; // Assuming user is logged in and user_id is set in the session

// Check if form data is set
if (isset($_POST['total_price'], $_POST['customer_name'], $_POST['special_instructions'], $_POST['dine_or_takeaway'])) {
    // Sanitize input data
    $total_price = $_POST['total_price'];
    $customer_name = $_POST['customer_name'];
    $special_instructions = $_POST['special_instructions'];
    $dine_or_takeaway = $_POST['dine_or_takeaway']; // Add this line

    if (empty($total_price) || empty($customer_name) || empty($dine_or_takeaway)) {
        echo "Error: Please fill out all required fields.";
        exit();
    }

    // Insert order into the customer_orders table
    $stmt = $conn->prepare("INSERT INTO customer_orders (customer_name, special_instructions, dine_or_takeaway, order_date, total_price) VALUES (?, ?, ?, NOW(), ?)");
    if ($stmt) {
        $stmt->bind_param("sssd", $customer_name, $special_instructions, $dine_or_takeaway, $total_price);
        if ($stmt->execute()) {
            $order_id = $stmt->insert_id; // Get the last inserted order ID

            // Fetch cart items for the order
            $cart_stmt = $conn->prepare("SELECT cart.menu_item_id, cart.quantity, menu_items.price FROM cart JOIN menu_items ON cart.menu_item_id = menu_items.id WHERE cart.user_id = ?");
            $cart_stmt->bind_param("i", $user_id);
            $cart_stmt->execute();
            $cart_result = $cart_stmt->get_result();

            // Insert each cart item into order_items
            while ($cart_item = $cart_result->fetch_assoc()) {
                $item_id = $cart_item['menu_item_id'];
                $quantity = $cart_item['quantity'];
                $price = $cart_item['price'];

                $order_item_stmt = $conn->prepare("INSERT INTO order_items (order_id, menu_item_id, quantity, price) VALUES (?, ?, ?, ?)");
                if ($order_item_stmt) {
                    $order_item_stmt->bind_param("iiid", $order_id, $item_id, $quantity, $price);
                    $order_item_stmt->execute();
                    $order_item_stmt->close();
                }
            }

            // Clear the cart after successful order processing
            $clear_cart_stmt = $conn->prepare("DELETE FROM cart WHERE user_id = ?");
            if ($clear_cart_stmt) {
                $clear_cart_stmt->bind_param("i", $user_id);
                $clear_cart_stmt->execute();
                $clear_cart_stmt->close();
            }

            // Set a success message in session and redirect to index.php
            $_SESSION['success_message'] = "Order successfully placed!";
            header('Location: index.php');
            exit();
        } else {
            echo "Sorry, there was an issue processing your order. Please try again later.";
        }
        $stmt->close();
    } else {
        echo "Sorry, there was an issue preparing your order. Please try again later.";
    }
} else {
    echo "Error: Missing required form data.";
}

// Close the database connection
$conn->close();
?>
