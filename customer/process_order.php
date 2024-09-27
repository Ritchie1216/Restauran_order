<?php
session_start(); // Start a session to manage user login state
include '../db_connect.php'; // Include the database connection file
include '../classes/Order.php'; // Include the Order class

// Create an instance of the Order class
$order = new Order($conn);

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['total_price'], $_POST['customer_name'], $_POST['special_instructions'], $_POST['dine_or_takeaway'])) {
    // Sanitize input data
    $total_price = $_POST['total_price'];
    $customer_name = $_POST['customer_name'];
    $special_instructions = $_POST['special_instructions'];
    $dine_or_takeaway = $_POST['dine_or_takeaway'];

    if (empty($total_price) || empty($customer_name) || empty($dine_or_takeaway)) {
        echo "Error: Please fill out all required fields.";
        exit();
    }

    // Check if user_id is set in session
    if (!isset($_SESSION['user_id'])) {
        echo "Error: You must be logged in to place an order.";
        exit();
    }

    // Process the order
    if ($order->processOrder($customer_name, $special_instructions, $dine_or_takeaway, $total_price)) {
        $_SESSION['success_message'] = "Order successfully placed!";
        header('Location: ../index.php');
        exit();
    } else {
        echo "Sorry, there was an issue processing your order. Please try again later.";
    }
} else {
    echo "Error: Missing required form data.";
}

// Close the database connection
$conn->close();
?>
