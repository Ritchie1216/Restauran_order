<?php
session_start();
include '../db_connect.php'; // Include the database connection
include '../classes/Cart.php'; // Include the Cart class

// Create an instance of the Cart class
$cart = new Cart($conn);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $menu_id = $_POST['menu_id'];
    $quantity = $_POST['quantity'];

    // Add item to the cart
    if ($cart->addToCart($menu_id, $quantity)) {
        header("Location: view_cart.php"); // Redirect to view cart page
        exit(); // Ensure no further code is executed
    } else {
        echo "<div class='alert alert-danger' role='alert'>Error adding item to cart.</div>";
    }
}
?>
