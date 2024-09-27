<?php
session_start();
include '../db_connect.php';

// Check if the user is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: admin_login.php');
    exit();
}

// Query for recent orders
$recent_orders_query = "
SELECT 
    order_items.*, 
    customer_orders.table_id,  -- Changed from customer_name to table_id
    customer_orders.order_date, 
    customer_orders.total_price, 
    customer_orders.status, 
    menu_items.id AS menu_item_id, 
    menu_items.name AS menu_item_name 
FROM 
    order_items 
INNER JOIN 
    customer_orders ON order_items.order_id = customer_orders.id 
INNER JOIN 
    menu_items ON order_items.menu_item_id = menu_items.id
WHERE 
    customer_orders.status = 'pending';
";


// Query for completed orders
$completed_orders_query = "
SELECT 
    order_items.*, 
    customer_orders.table_id,  -- Changed from customer_name to table_id
    customer_orders.order_date, 
    customer_orders.total_price, 
    customer_orders.status, 
    menu_items.id AS menu_item_id, 
    menu_items.name AS menu_item_name 
FROM 
    order_items 
INNER JOIN 
    customer_orders ON order_items.order_id = customer_orders.id 
INNER JOIN 
    menu_items ON order_items.menu_item_id = menu_items.id
WHERE 
    customer_orders.status = 'completed';
";



// Query for canceled orders
$canceled_orders_query = "
SELECT order_items.*, 
customer_orders.customer_name, 
customer_orders.order_date, 
customer_orders.cancellation_reason, 
customer_orders.status, 
menu_items.id AS menu_item_id, 
menu_items.name AS menu_item_name
FROM order_items 
INNER JOIN 
customer_orders ON order_items.order_id = customer_orders.id 
INNER JOIN 
menu_items ON order_items.menu_item_id = menu_items.id 
WHERE customer_orders.status = 'cancelled';
";


// Execute each query and store the result
$recent_orders_result = $conn->query($recent_orders_query);
$completed_orders_result = $conn->query($completed_orders_query);
$canceled_orders_result = $conn->query($canceled_orders_query);


// Check for SQL errors
if ($conn->error) {
    die('SQL error: ' . $conn->error);
}

// Fetch recent reservations
$sql_recent_reservations = "
SELECT reservations.id, reservations.reservation_time, reservations.created_at, 
       reservations.number_of_people, reservations.number_of_children, reservations.table_id,
       reservations.status
FROM reservations
WHERE reservations.status = 'pending'
ORDER BY reservations.reservation_time DESC
LIMIT 10";

$result_recent_reservations = $conn->query($sql_recent_reservations);

if (!$result_recent_reservations) {
    die("Error executing recent reservations query: " . $conn->error);
}

// Fetch completed reservations
$sql_completed_reservations = "
SELECT reservations.id, reservations.reservation_time, reservations.created_at, 
       reservations.number_of_people, reservations.number_of_children, reservations.table_id,
       reservations.status
FROM reservations
WHERE reservations.status = 'completed'
ORDER BY reservations.reservation_time DESC
LIMIT 10";

$result_completed_reservations = $conn->query($sql_completed_reservations);

if (!$result_completed_reservations) {
    die("Error executing completed reservations query: " . $conn->error);
}

// Fetch canceled reservations
$sql_canceled_reservations = "
SELECT reservations.id, reservations.reservation_time, reservations.created_at, 
       reservations.number_of_people, reservations.number_of_children, reservations.table_id,
       reservations.status
FROM reservations
WHERE reservations.status = 'canceled'
ORDER BY reservations.reservation_time DESC
LIMIT 10";

$result_canceled_reservations = $conn->query($sql_canceled_reservations);

if (!$result_canceled_reservations) {
    die("Error executing canceled reservations query: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Orders and Reservations</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            display: flex;
            height: 100vh;
        }
        .sidebar {
            width: 250px;
            background-color: #f8f9fa;
            padding-top: 20px;
            position: fixed;
            height: 100%;
        }
        .content {
            margin-left: 250px; /* Adjust based on sidebar width */
            padding: 20px;
            width: 100%;
        }
        .sidebar a {
            font-size: 18px;
            padding: 10px 15px;
            display: block;
            text-decoration: none;
            color: #333;
        }
        .sidebar a:hover {
            background-color: #007bff;
            color: white;
        }
        .header-wrapper {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
        }
        .header-wrapper img {
            height: 150px;
            margin-right: 2px;
        }
        .header-wrapper h1 {
            margin: 0;
        }
    </style>
</head>
<body>
<!-- Sidebar -->
<div class="sidebar">
    <div class="text-center">
        <span class="navbar-brand" style="font-size: 1.5rem; font-weight: ;">
            Admin Panel
        </span>
    </div>
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" href="admin.php">Dashboard</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="manage_categories.php">Menu Categories</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="dashboard.php">Menu</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="tables.php">Tables</a> <!-- New Manage Tables section -->
        </li>
        <li class="nav-item">
            <a class="nav-link" href="qr_code1.php">QR Codes</a> <!-- New QR Code Management section -->
        </li>
        <li class="nav-item">
            <a class="nav-link" href="gallery.php">Gallery</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="grand_total.php">Grand Total</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="chef.php">Chef</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="admin_profile.php">Admin</a>
        </li>
        <li>
            <a class="nav-item" href="../index1.php">View Website</a>
        </li>
        <li class="nav-item">
            <!-- Logout button -->
            <form action="admin_logout.php" method="POST" class="d-inline">
                <button type="submit" class="btn btn-danger w-100">Logout</button>
            </form>
        </li>
    </ul>
</div>



<!-- Main Content -->
<div class="content">
    <div class="header-wrapper">
        <img src="../uploads/logo.jpg" alt="Restaurant Logo">
        <h1>Admin Panel - Manage Orders and Reservations</h1>
    </div>

    <!-- Back Button -->
    <div class="mb-4">
        <a href="admin.php" class="btn btn-secondary">Back to Admin Panel</a>
    </div>

    <!-- Tab Navigation for Orders -->
    <ul class="nav nav-tabs" id="orderTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <a class="nav-link active" id="recent-orders-tab" data-bs-toggle="tab" href="#recent-orders" role="tab" aria-controls="recent-orders" aria-selected="true">Recent Orders</a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link" id="completed-orders-tab" data-bs-toggle="tab" href="#completed-orders" role="tab" aria-controls="completed-orders" aria-selected="false">Completed Orders</a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link" id="canceled-orders-tab" data-bs-toggle="tab" href="#canceled-orders" role="tab" aria-controls="canceled-orders" aria-selected="false">Canceled Orders</a>
        </li>
    </ul>

    <div class="tab-content mt-3" id="orderTabsContent">
        <!-- Recent Orders Tab -->
        <div class="tab-pane fade show active" id="recent-orders" role="tabpanel" aria-labelledby="recent-orders-tab">
            <h2>Recent Orders</h2>
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Table ID</th> <!-- Changed from customer_name to table_id -->
                        <th>Order Date</th>
                        <th>Total Price</th>
                        <th>Items</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                <?php if ($recent_orders_result->num_rows > 0) {
                    while ($order = $recent_orders_result->fetch_assoc()) { ?>
                        <tr>
                            <td><?php echo htmlspecialchars($order['id']); ?></td>
                            <td><?php echo htmlspecialchars($order['table_id']); ?></td> <!-- Changed from customer_name to table_id -->
                            <td><?php echo htmlspecialchars($order['order_date']); ?></td>
                            <td>$<?php echo htmlspecialchars(number_format($order['total_price'], 2)); ?></td>
                            <td><?php echo htmlspecialchars($order['menu_item_name']); ?></td>
                             <td>
                            <!-- Complete Button -->
                            <?php if ($order['status'] === 'pending') { ?>
                                <a href="admin_update_order_status.php?id=<?php echo htmlspecialchars($order['order_id']); ?>&status=completed" class="btn btn-success btn-sm" onclick="return confirm('Mark this order as complete?');">
                                    <i class="fas fa-check"></i> Complete
                                </a>
                                <a href="#" class="btn btn-danger btn-sm" onclick="return promptForCancellation(<?php echo htmlspecialchars($order['order_id']); ?>)">
                                    <i class="fas fa-times"></i> Cancel
                                </a>

                                
                            <?php } ?>
                            <td>
                                <form action="admin_delete_order.php" method="post" style="display:inline;">
                                    <input type="hidden" name="order_id" value="<?php echo htmlspecialchars($order['order_id']); ?>">
                                    <input type="submit" name='delete' class="btn btn-danger btn-sm" value="Delete">
                                </form>
                            </td>
                        </tr>
                    <?php } 
                } else {
                    echo '<tr><td colspan="8">No recent orders found.</td></tr>';
                } ?>
                </tbody>
            </table>
        </div>
        <script>
function promptForCancellation(orderId) {
    var reason = prompt("Please enter a reason for canceling this order:");
    if (reason) {
        // Redirect to the cancellation script with reason as a query parameter
        window.location.href = 'admin_update_recent_ord_status.php?id=' + orderId + '&status=cancelled&reason=' + encodeURIComponent(reason);
    }
    return false; // Prevent default link behavior
}


</script>

        <!-- Completed Orders Tab -->
        <div class="tab-pane fade" id="completed-orders" role="tabpanel" aria-labelledby="completed-orders-tab">
            <h2>Completed Orders</h2>
            <table class="table table-striped table-bordered">
                 <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Table ID</th> <!-- This should match the updated field -->
                    <th>Order Date</th>
                    <th>Total Price</th>
                    <th>Items</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            <?php if ($completed_orders_result->num_rows > 0) {
                while ($order = $completed_orders_result->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo htmlspecialchars($order['id']); ?></td>
                        <td><?php echo htmlspecialchars($order['table_id']); ?></td> <!-- Updated to table_id -->
                        <td><?php echo htmlspecialchars($order['order_date']); ?></td>
                        <td>$<?php echo htmlspecialchars(number_format($order['total_price'], 2)); ?></td> 
                        <td><?php echo htmlspecialchars($order['menu_item_name']); ?></td>
                        <td><?php echo ucfirst(htmlspecialchars($order['status'])); ?></td> 
                    </tr>
                    <?php } 
                } else {
                    echo '<tr><td colspan="7">No completed orders found.</td></tr>';
                } ?>
                </tbody>
            </table>
        </div>

        <!-- Canceled Orders Tab -->
        <div class="tab-pane fade" id="canceled-orders" role="tabpanel" aria-labelledby="canceled-orders-tab">
            <h2>Canceled Orders</h2>
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>Order Date</th>
                        <th>Customer</th>
                        <th>Cancellation Reason</th>
                    </tr>
                </thead>
                <tbody>
                <?php if ($canceled_orders_result->num_rows > 0) {
                    while ($order = $canceled_orders_result->fetch_assoc()) { ?>
                        <tr>
                            <td><?php echo htmlspecialchars($order['order_date']); ?></td>
                            <td><?php echo htmlspecialchars($order['customer_name']); ?></td>
                            <td><?php echo htmlspecialchars($order['cancellation_reason']); ?></td>
                        </tr>
                    <?php } 
                } else {
                    echo '<tr><td colspan="3">No canceled orders found.</td></tr>';
                } ?>
                </tbody>
            </table>
        </div>
    </div>

        <!-- Reservations Section -->
        <div class="mt-5">
            <!-- Tab Navigation for Reservations -->
            <ul class="nav nav-tabs" id="reservationTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <a class="nav-link active" id="recent-reservations-tab" data-bs-toggle="tab" href="#recent-reservations" role="tab" aria-controls="recent-reservations" aria-selected="true">Recent Reservations</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="completed-reservations-tab" data-bs-toggle="tab" href="#completed-reservations" role="tab" aria-controls="completed-reservations" aria-selected="false">Completed Reservations</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="canceled-reservations-tab" data-bs-toggle="tab" href="#canceled-reservations" role="tab" aria-controls="canceled-reservations" aria-selected="false">Canceled Reservations</a>
                </li>
            </ul>


    <div class="tab-content mt-3" id="reservationTabsContent">
    <!-- Recent Reservations Tab -->
    <div class="tab-pane fade show active" id="recent-reservations" role="tabpanel" aria-labelledby="recent-reservations-tab">
        <h2>Recent Reservations</h2>
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>Reservation ID</th>
                    <th>Reservation Time</th>
                    <th>Created At</th>
                    <th>Number of People</th>
                    <th>Number of Children</th>
                    <th>Table ID</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php if ($result_recent_reservations->num_rows > 0) {
                while ($reservation = $result_recent_reservations->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo htmlspecialchars($reservation['id']); ?></td>
                        <td><?php echo htmlspecialchars($reservation['reservation_time']); ?></td>
                        <td><?php echo htmlspecialchars($reservation['created_at']); ?></td>
                        <td><?php echo htmlspecialchars($reservation['number_of_people']); ?></td>
                        <td><?php echo htmlspecialchars($reservation['number_of_children']); ?></td>
                        <td><?php echo htmlspecialchars($reservation['table_id']); ?></td>
                        <td>
                            <!-- Liberate Table Button with Confirmation and Number Input -->
                            <a href="#" class="btn btn-success btn-sm" onclick="liberateTable(<?php echo $reservation['id']; ?>, <?php echo $reservation['table_id']; ?>); return false;">
                                <i class="fas fa-chair"></i> Liberate Table
                            </a>

                            <!-- Cancel Reservation Button with Cancellation Reason Prompt -->
                            <a href="#" class="btn btn-danger btn-sm" onclick="cancelReservation(<?php echo $reservation['id']; ?>)">
                                <i class="fas fa-times-circle"></i> Cancel Reservation
                            </a>

                            <!-- Complete Reservation Button with Confirmation Alert -->
                            <a href="complete_reservation.php?id=<?php echo $reservation['id']; ?>" class="btn btn-primary btn-sm" onclick="return confirm('Mark this reservation as complete?');">
                                <i class="fas fa-check-circle"></i> Complete Reservation
                            </a>
                        </td>
                    </tr>
                <?php } 
            } else {
                echo '<tr><td colspan="7">No recent reservations found.</td></tr>';
            } ?>
            </tbody>
        </table>
    </div>
</div>

<!-- JavaScript to Handle Cancel Reservation with Reason Prompt -->
<script>
    function cancelReservation(reservationId) {
        var reason = prompt('Please provide a reason for cancellation:');
        
        // If the user provides a reason, redirect to the cancellation script
        if (reason != null && reason !== "") {
            window.location.href = 'cancel_reservation.php?id=' + reservationId + '&reason=' + encodeURIComponent(reason);
        } else {
            alert('Cancellation requires a reason.');
        }
    }

    function liberateTable(reservationId, tableId) {
        // Prompt user to input the number of people
        var numberOfPeople = prompt('Enter the number of people to liberate the table:');
        
        // Check if the user provided a valid number
        if (numberOfPeople !== null && numberOfPeople !== "" && !isNaN(numberOfPeople) && numberOfPeople > 0) {
            // Redirect to liberate_table.php with the number of people, reservation ID, and table ID
            window.location.href = 'liberate_table.php?id=' + reservationId + '&table_id=' + tableId + '&people=' + encodeURIComponent(numberOfPeople);
        } else {
            alert('Please provide a valid number.');
        }
    }
</script>

<!-- Include Font Awesome for Icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<!-- Include Bootstrap JS and Dependencies -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

                <!-- Completed Reservations Tab -->
                <div class="tab-pane fade" id="completed-reservations" role="tabpanel" aria-labelledby="completed-reservations-tab">
                    <h2>Completed Reservations</h2>
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Reservation Time</th>
                                <th>Created At</th>
                                <th>Number of People</th>
                                <th>Number of Children</th>
                                <th>Table ID</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php if ($result_completed_reservations->num_rows > 0) {
                            while ($reservation = $result_completed_reservations->fetch_assoc()) { ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($reservation['reservation_time']); ?></td>
                                    <td><?php echo htmlspecialchars($reservation['created_at']); ?></td>
                                    <td><?php echo htmlspecialchars($reservation['number_of_people']); ?></td>
                                    <td><?php echo htmlspecialchars($reservation['number_of_children']); ?></td>
                                    <td><?php echo htmlspecialchars($reservation['table_id']); ?></td>
                                </tr>
                            <?php } 
                        } else {
                            echo '<tr><td colspan="5">No completed reservations found.</td></tr>';
                        } ?>
                        </tbody>
                    </table>
                </div>

                
                <!-- Canceled Reservations Tab -->
                <div class="tab-pane fade" id="canceled-reservations" role="tabpanel" aria-labelledby="canceled-reservations-tab">
                    <h2>Canceled Reservations</h2>
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Reservation Time</th>
                                <th>Created At</th>
                                <th>Number of People</th>
                                <th>Number of Children</th>
                                <th>Table ID</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php if ($result_canceled_reservations->num_rows > 0) {
                            while ($reservation = $result_canceled_reservations->fetch_assoc()) { ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($reservation['reservation_time']); ?></td>
                                    <td><?php echo htmlspecialchars($reservation['created_at']); ?></td>
                                    <td><?php echo htmlspecialchars($reservation['number_of_people']); ?></td>
                                    <td><?php echo htmlspecialchars($reservation['number_of_children']); ?></td>
                                    <td><?php echo htmlspecialchars($reservation['table_id']); ?></td>
                                </tr>
                            <?php } 
                        } else {
                            echo '<tr><td colspan="5">No canceled reservations found.</td></tr>';
                        } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
