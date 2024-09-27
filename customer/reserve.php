<?php
session_start();
include '../db_connect.php'; // Include the database connection file
include '../classes/Reservation.php'; // Include the Reservation class

// Create an instance of the Database class and get the connection
$database = new Database();
$conn = $database->getConnection(); // Make sure this method returns the connection

// Create an instance of the Reservation class with the connection
$reservation = new Reservation($conn);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize and validate input
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $date = trim($_POST['date']);
    $time = trim($_POST['time']);
    $people = intval($_POST['people']);
    $children = intval($_POST['children']); // Optional
    $table_id = intval($_POST['table_id']); // Add this line to handle table_id

    if (!empty($name) && !empty($email) && !empty($phone) && !empty($date) && !empty($time) && $people > 0) {
        // Call the createReservation method
        if ($reservation->createReservation($name, $email, $phone, $date, $time, $people, $children, $table_id)) {
            $_SESSION['success_message'] = 'Reservation successfully made!';
            header('Location: ../index.php');
            exit();
        } else {
            $_SESSION['error_message'] = 'Error making reservation. Please try again.';
            header('Location: ../index.php');
            exit();
        }
    } else {
        $_SESSION['error_message'] = 'Please fill in all required fields correctly.';
        header('Location: ../index.php');
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reserve a Table</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<!-- Reservation Form -->
<section class="reservation-form my-5">
    <div class="container">
        <h2 class="text-center">Reserve a Table</h2>
        <form id="reservationForm" method="post" action="reserve.php">
            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="mb-3">
                <label for="phone" class="form-label">Phone</label>
                <input type="tel" class="form-control" id="phone" name="phone" required>
            </div>
            <div class="mb-3">
                <label for="date" class="form-label">Date</label>
                <input type="date" class="form-control" id="date" name="date" required>
            </div>
            <div class="mb-3">
                <label for="time" class="form-label">Time</label>
                <input type="time" class="form-control" id="time" name="time" required>
            </div>
            <div class="mb-3">
                <label for="people" class="form-label">Number of People</label>
                <input type="number" class="form-control" id="people" name="people" min="1" required>
            </div>
            <div class="mb-3">
                <label for="children" class="form-label">Number of Children (Optional)</label>
                <input type="number" class="form-control" id="children" name="children" min="0">
            </div>
            <div class="mb-3">
                <label for="table_id" class="form-label">Table ID</label>
                <input type="number" class="form-control" id="table_id" name="table_id" required>
            </div>
            
            <button type="submit" class="btn btn-primary" onclick="confirmOrder(event)">Make Reservation</button>
        </form>
    </div>
</section>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script>
  function confirmOrder(event) {
        event.preventDefault(); // Prevent form submission
        const reservationForm = document.getElementById('reservationForm');
        const userResponse = confirm("Do you want to Order Now? Click 'OK' for Order Now or 'Cancel' for Order Later.");
        
        if (userResponse) {
            // If they choose to order now, submit the form
            reservationForm.submit();
        } else {
            // If they choose to order later, set a session variable and redirect to index1.php
            window.location.href = '../index1.php';
        }
    }
</script>
</body>
</html>
