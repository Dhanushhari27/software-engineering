<?php
$conn = new mysqli("localhost", "root", "", "event_booking");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
