<?php
class Reservation {
    private $conn;

    public function __construct($dbConnection) {
        $this->conn = $dbConnection;
    }

    public function createReservation($name, $email, $phone, $date, $time, $people, $children, $table_id) {
        // Prepare SQL query
        $stmt = $this->conn->prepare("INSERT INTO reservations (name, email, phone, reservation_date, reservation_time, number_of_people, number_of_children, table_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssiiis", $name, $email, $phone, $date, $time, $people, $children, $table_id);

        return $stmt->execute();
    }
}
?>
