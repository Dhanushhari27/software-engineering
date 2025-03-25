<?php
session_start(); // Start the session to track user login status
$conn = new mysqli("localhost", "root", "", "event_booking"); // Establish a database connection using MySQLi
// Check if a seat number is provided and if a user is logged in
if (isset($_POST['seat_number']) && isset($_SESSION['user'])) {
    $seat_number = $conn->real_escape_string($_POST['seat_number']);
    $user_id = $_SESSION['user']['id'];
    // Query the database to check if the seat exists
    $result = $conn->query("SELECT * FROM seats WHERE seat_number='$seat_number'");
    // If the seat exists in the database
    if ($result->num_rows > 0) {
        $seat = $result->fetch_assoc();
        if ($seat['booked_by'] == $user_id) {         // Check if the logged-in user is the one who booked the seat
            // Authorized to cancel
            $conn->query("UPDATE seats SET is_booked=0, booked_by=NULL WHERE seat_number='$seat_number'");
            echo "Booking cancelled for seat: " . htmlspecialchars($seat_number) . ". <a href='index.php'>Go back</a>";
        } else {
            echo "Error: You can only cancel seats you have booked! <a href='index.php'>Go back</a>";
        }
    } else {
        echo "Error: Seat not found. <a href='index.php'>Go back</a>";
    }
} else {
    echo "No seat number provided or user not logged in. <a href='index.php'>Go back</a>";
}
?>
