<?php
// admin_update_order_status.php

include '../db_connect.php'; // Make sure you have the database connection script


    $order_id = $_GET['id'];
    $status = $_GET['status'];

    // Update the order status in the customer_orders table
    $stmt = $conn->prepare("UPDATE customer_orders SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $status, $order_id);

    if ($stmt->execute()) {
        // Redirect back to the admin page after update
        header('Location: admin.php');
        exit();
    } else {
        echo "Error updating order status: " . $conn->error;
      
    }

    $stmt->close();
    $conn->close();
?>
