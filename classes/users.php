<?php

include '../db_connect.php'; // Include your database connection


class User {
    private $conn;

    public function __construct($dbConnection) {
        $this->conn = $dbConnection;
    }

    public function login($email, $password) {
        // Prepare the SQL statement to prevent SQL injection
        $stmt = $this->conn->prepare("SELECT id, name, password FROM users WHERE email = ?");
        if (!$stmt) {
            return false; // Return false if preparing the statement fails
        }

        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        // Check if the user exists
        if ($stmt->num_rows == 0) {
            return false; // User not found
        }

        // Bind the result variables
        $stmt->bind_result($id, $name, $hashedPassword);
        $stmt->fetch();

        // Verify the password using password_verify
        if (password_verify($password, $hashedPassword)) {
            // Password is correct; return user data
            return ['id' => $id, 'name' => $name];
        } else {
            return false; // Password is incorrect
        }
    }
}
?>