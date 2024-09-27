<?php
include '../db_connect.php'; // Include your database connection

// Check if the required GET parameters are present
if (isset($_GET['id'], $_GET['table_id'], $_GET['people'])) {
    $reservation_id = $_GET['id'];
    $table_id = $_GET['table_id'];
    $number_of_people = $_GET['people'];

    // Validate the number of people
    if (!is_numeric($number_of_people) || $number_of_people <= 0) {
        echo "Invalid number of people.";
        exit();
    }

    // Update the reservation to liberate the table
    $stmt = $conn->prepare("UPDATE reservations SET status = 'liberated', number_of_people = ? WHERE id = ? AND table_id = ?");
    $stmt->bind_param("iii", $number_of_people, $reservation_id, $table_id);

    if ($stmt->execute()) {
        echo "Table has been liberated successfully!";
        // Optionally, redirect to another page after success
        header('Location: admin.php?success=table_liberated');
        exit();
    } else {
        echo "Failed to liberate the table.";
    }

    $stmt->close();
} else {
    echo "Missing parameters.";
}

// Close the database connection
$conn->close();
?>
