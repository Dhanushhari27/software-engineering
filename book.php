<?php
session_start();
$conn = new mysqli("localhost", "root", "", "event_booking");

if (isset($_POST['seats']) && isset($_POST['event_id']) && isset($_SESSION['user'])) {
    $user_id = $_SESSION['user']['id'];

    echo "<!DOCTYPE html>";
    echo "<html lang='en'>";
    echo "<head>";
    echo "<meta charset='UTF-8'>";
    echo "<meta name='viewport' content='width=device-width, initial-scale=1.0'>";
    echo "<title>Booking Confirmation</title>";
    // Inline CSS
    echo "<style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f4f4;
            padding: 30px;
            color: #333;
            text-align: center;
        }
        h2 {
            color: #4CAF50;
        }
        ul {
            list-style: none;
            padding: 0;
            margin: 20px 0;
        }
        li {
            background: #fff;
            margin: 5px auto;
            padding: 10px 20px;
            border-radius: 6px;
            max-width: 200px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 15px;
            font-size: 16px;
            transition: background-color 0.3s;
        }
        button:hover {
            background-color: #45a049;
        }
        a {
            display: inline-block;
            margin-top: 15px;
            color: #4CAF50;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
        @media print {
            body {
                background: none;
                color: #000;
            }
            button, a {
                display: none !important;
            }
        }
    </style>";
    echo "</head><body>";

    // Booking logic with user association
    foreach ($_POST['seats'] as $seat_id) {
        $seat_id = intval($seat_id);
        // Only book if seat is not already booked
        $result = $conn->query("SELECT is_booked FROM seats WHERE id=$seat_id");
        $seat = $result->fetch_assoc();
        if ($seat && !$seat['is_booked']) {
            $conn->query("UPDATE seats SET is_booked=1, booked_by=$user_id WHERE id=$seat_id");
        }
    }

    echo "<h2>Booking Confirmed!</h2>";
    echo "<p>Your booked seats:</p><ul>";
    foreach ($_POST['seats'] as $seat_id) {
        $seat = $conn->query("SELECT seat_number FROM seats WHERE id=" . intval($seat_id))->fetch_assoc();
        echo "<li>" . htmlspecialchars($seat['seat_number']) . "</li>";
    }
    echo "</ul>";

    echo "<button id='printBtn'>Print Confirmation</button>";
    echo "<p><a href='index.php'>Back to Dashboard</a></p>";

    echo "<script>
        document.getElementById('printBtn').addEventListener('click', function() {
            setTimeout(function() {
                window.print();
            }, 100);
        });
    </script>";

    echo "</body></html>";
} else {
    echo "No seats selected or user not logged in.";
}
?>
