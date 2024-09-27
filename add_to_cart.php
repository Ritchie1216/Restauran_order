<?php
session_start();
include '../db_connect.php';
include '../classes/Cart.php';

if (isset($_POST['item_id'])) {
    $item_id = $_POST['item_id'];

    // Fetch item details from the menu
    $stmt = $conn->prepare("SELECT * FROM menu_items WHERE id = ?");
    $stmt->bind_param("i", $item_id);
    $stmt->execute();
    $item = $stmt->get_result()->fetch_assoc();

    // Add to cart
    $cart = new Cart();
    $cart->addItem($item); // You might want to implement quantity handling here

    $_SESSION['success_message'] = "Item added to cart!";
    header('Location: ../menu.php'); // Redirect back to menu page
    exit();
}
?>
