<?php
// Start the session to manage user authentication
session_start();
// Establish a database connection using MySQLi
$conn = new mysqli("localhost", "root", "", "event_booking");
// Check if an action is sent via POST request
if (isset($_POST['action'])) {
        // Handle user registration
    if ($_POST['action'] == "register") {
                // Sanitize and escape user input to prevent SQL injection
        $username = $conn->real_escape_string($_POST['username']);
        $email = $_POST['email'];
        $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
        $role = $_POST['role'];
                // Insert new user details into the database
        $conn->query("INSERT INTO users (username, email, password, role) VALUES ('$username', '$email','$password', '$role')");
        $message = "Registration successful!";
    }
    // Handle user login
    if ($_POST['action'] == "login") {
        $username = $conn->real_escape_string($_POST['username']);
        $password = $_POST['password'];
        $result = $conn->query("SELECT * FROM users WHERE username='$username'");
        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                $_SESSION['user'] = $user;
                header("Location: index.php");
                exit();
            } else {
                $message = "Incorrect password.";
            }
        } else {
            $message = "User not found.";
        }
    }
    // Handle event creation (Only accessible to admin users)
    if ($_POST['action'] == "add_event" && $_SESSION['user']['role'] == "admin") {
        $name = $conn->real_escape_string($_POST['name']);
        $date = $_POST['date'];
        $location = $conn->real_escape_string($_POST['location']);
        $seats = intval($_POST['seats']);
        $conn->query("INSERT INTO events (name, date, location) VALUES ('$name', '$date', '$location')");
        $event_id = $conn->insert_id;
        for ($i = 1; $i <= $seats; $i++) {
            $seat_number = "S" . str_pad($i, 2, "0", STR_PAD_LEFT);
            $conn->query("INSERT INTO seats (event_id, seat_number, is_booked) VALUES ($event_id, '$seat_number', 0)");
        }
        $message = "Event created with $seats seats.";
    }
}
// Handle user logout
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Event Booking System</title>
    <style>
body {
    font-family: Arial, sans-serif;
    background: #f4f6f8;
    color: #333;
    padding: 20px;
    max-width: 800px;
    margin: 0 auto;
}

h1 {
    text-align: center;
    color: #2c3e50;
}

h2, h3, h4 {
    color: #34495e;
}

form {
    background: #ffffff;
    padding: 20px;
    margin-bottom: 20px;
    border-radius: 8px;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
}

input[type="text"],
input[type="password"],
input[type="date"],
input[type="number"],
select {
    width: 100%;
    padding: 10px;
    margin-bottom: 10px;
    border: 1px solid #bdc3c7;
    border-radius: 5px;
}

button {
    background: #27ae60;
    color: #fff;
    padding: 10px 15px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}

button.cancel {
    background: #e74c3c;
}

button:hover {
    background: #2ecc71;
}

button.cancel:hover {
    background: #c0392b;
}

.event {
    background: #ecf0f1;
    padding: 15px;
    border-radius: 5px;
    margin-bottom: 15px;
}

.event a {
    display: inline-block;
    margin-top: 10px;
    background: #2980b9;
    color: #fff;
    padding: 5px 10px;
    border-radius: 5px;
    text-decoration: none;
}

.event a:hover {
    background: #3498db;
}

.seats {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    margin-bottom: 15px;
}

.seat {
    background: #bdc3c7;
    padding: 10px;
    border-radius: 4px;
    width: 60px;
    text-align: center;
    position: relative;
}

.seat input[type="checkbox"] {
    margin-bottom: 5px;
}

.seat.booked {
    background: #e74c3c;
    color: #fff;
}

p {
    margin-bottom: 15px;
}

a {
    color: #2980b9;
}

a:hover {
    color: #1abc9c;
}
.user-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 15px;
    background: #ffffff;
    border-radius: 8px;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    overflow: hidden;
}

.user-table th,
.user-table td {
    padding: 12px 15px;
    text-align: left;
    border-bottom: 1px solid #ecf0f1;
}

.user-table th {
    background: #34495e;
    color: #fff;
}

.user-table tr:hover {
    background: #f2f2f2;
}

.user-table button {
    padding: 5px 10px;
}

    </style>
</head>

<body>
    <h1>Event Booking System</h1>

    <?php if (!isset($_SESSION['user'])) : ?>
        <!-- Registration & Login -->
        <h2>Register</h2>
        <?php if (isset($message)) echo "<p style='color:red;'>$message</p>"; ?>
        <form method="post">
            <input type="hidden" name="action" value="register">
            <input type="text" name="username" placeholder="Username" required><br>
            <input type="text" name="email" placeholder="Email" required><br>
            <input type="password" name="password" placeholder="Password" required><br>
            <select name="role" required>
                <option value="user">User</option>
            </select><br>
            <button type="submit">Register</button>
        </form>

        <h2>Login</h2>
        <form method="post">
            <input type="hidden" name="action" value="login">
            <input type="text" name="username" placeholder="Username" required><br>
            <input type="password" name="password" placeholder="Password" required><br>
            <button type="submit">Login</button>
        </form>

    <?php else : ?>

        <p>Welcome, <?php echo $_SESSION['user']['username']; ?> | <a href="?logout=1">Logout</a></p>

        <!-- ADMIN PANEL -->
        <?php if ($_SESSION['user']['role'] == 'admin') : ?>
            <h2>Admin Panel - Add Event</h2>
            <form method="post">
                <input type="hidden" name="action" value="add_event">
                <input type="text" name="name" placeholder="Event Name" required><br>
                <input type="date" name="date" required><br>
                <input type="text" name="location" placeholder="Location" required><br>
                <input type="number" name="seats" placeholder="Number of Seats" required><br>
                <button type="submit">Create Event</button>
            </form>

            <h3>Current Events</h3>
            <?php
            $events = $conn->query("SELECT * FROM events");
            while ($ev = $events->fetch_assoc()) {
                $event_id = $ev['id'];
                // Count available seats for this event
                $seat_count = $conn->query("SELECT COUNT(*) as available FROM seats WHERE event_id=$event_id AND is_booked=0")->fetch_assoc();
                echo "<p>{$ev['name']} - {$ev['date']} at {$ev['location']} | Available Seats: {$seat_count['available']}</p>";
            }
            ?>
            <h2>User Management</h2>
<?php
if (isset($_POST['delete_user'])) {
    $user_id = (int) $_POST['user_id'];
    $conn->query("DELETE FROM users WHERE id=$user_id");
    echo "<p>User ID {$user_id} deleted.</p>";
}
?>

<table class="user-table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Username</th>
            <th>Email</th>
            <th>Role</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $users = $conn->query("SELECT id, username, email, role FROM users");
        while ($user = $users->fetch_assoc()) {
            echo "<tr>";
            echo "<td>{$user['id']}</td>";
            echo "<td>{$user['username']}</td>";
            echo "<td>{$user['email']}</td>";
            echo "<td>{$user['role']}</td>";
            echo "<td>
                    <form method='post' style='display:inline;'>
                        <input type='hidden' name='user_id' value='{$user['id']}'>
                        <button type='submit' name='delete_user' class='cancel' onclick=\"return confirm('Are you sure you want to delete this user?')\">Delete</button>
                    </form>
                  </td>";
            echo "</tr>";
        }
        ?>
    </tbody>
</table>


        <!-- USER DASHBOARD -->
        <?php else : ?>
            <h2>User Dashboard - Available Events</h2>
            <?php
            $events = $conn->query("SELECT * FROM events");
            while ($event = $events->fetch_assoc()) :
            ?>
                <div class="event">
                    <h2><?php echo $event['name']; ?></h2>
                    <p>Date: <?php echo $event['date']; ?> | Location: <?php echo $event['location']; ?></p>
                    <a href="index.php?event_id=<?php echo $event['id']; ?>">View Seats</a>
                </div>
            <?php endwhile; ?>

            <?php if (isset($_GET['event_id'])) :
                $event_id = intval($_GET['event_id']);
                $event = $conn->query("SELECT * FROM events WHERE id=$event_id")->fetch_assoc();
                $seats = $conn->query("SELECT * FROM seats WHERE event_id=$event_id");
            ?>
                <h3>Seats for <?php echo $event['name']; ?></h3>
                <form action="book.php" method="post" target="_blank">
                    <input type="hidden" name="event_id" value="<?php echo $event_id; ?>">
                    <div class="seats">
                        <?php while ($seat = $seats->fetch_assoc()) : ?>
                            <div class="seat <?php echo $seat['is_booked'] ? 'booked' : ''; ?>">
                                <input type="checkbox" name="seats[]" value="<?php echo $seat['id']; ?>" <?php echo $seat['is_booked'] ? 'disabled' : ''; ?>>
                                <?php echo $seat['seat_number']; ?>
                            </div>
                        <?php endwhile; ?>
                    </div>
                    <button type="submit">Book Selected Seats</button>
                </form>

                <h4>Cancel a Seat</h4>
                <form action="cancel.php" method="post">
                    <input type="hidden" name="event_id" value="<?php echo $event_id; ?>">
                    <input type="text" name="seat_number" placeholder="Enter Seat Number (e.g., S01)" required>
                    <button type="submit" class="cancel">Cancel Booking</button>
                </form>
            <?php endif; ?>
        <?php endif; ?>

    <?php endif; ?>

</body>
</html>
