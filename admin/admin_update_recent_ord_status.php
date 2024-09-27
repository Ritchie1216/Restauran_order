<?php
session_start();
include '../db_connect.php';


    // Get the order ID, status, and reason from the query parameters
    $order_id = intval($_GET['id']);
    $status = $_GET['status'];
    $reason = isset($_GET['reason']) ? $_GET['reason'] : '';

    echo $order_id;
    echo "<br>";
    echo $status;
    echo "<br>";
    echo $reason;
    echo "<br>";

    // Validate status
    if ($status != 'cancelled') {
        $_SESSION['error_message'] = 'Invalid status update.';
        header('Location: admin.php');
        exit();
    }else{
        // Update the order status and add the cancellation reason
        $update_sql = "UPDATE customer_orders SET status = ?, cancellation_reason = ? WHERE id = ?";
        $stmt = $conn->prepare($update_sql);
        $stmt->bind_param("ssi", $status, $reason, $order_id);

        if ($stmt->execute()) {
            $_SESSION['success_message'] = 'Order successfully cancelled!';
            header('Location: admin.php#recent-orders');
            exit();
        } else {
            $_SESSION['error_message'] = 'Error canceling order. Please try again.';
            header('Location: admin.php');
            exit();
        }
    }

 // Close the prepared statement and connection
 $stmt->close();
 $conn->close();
?>
