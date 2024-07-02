<?php
session_start();
require_once 'database.php';
require_once 'functions.php';

// Check if organizer is logged in
if (!isset($_SESSION['organizerID'])) {
    header('Location: organizerLogin.php');
    exit();
}

// Fetch organizer details
$organizerID = $_SESSION['organizerID'];
$sql = "SELECT * FROM eventorganizer WHERE organizerID = $organizerID";
$result = mysqli_query($conn, $sql);
$organizer = mysqli_fetch_assoc($result);

// Handle event deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['deleteEvent'])) {
    $eventID = $_POST['eventID'];
    $sql = "DELETE FROM event WHERE eventID = $eventID";
    
    if (mysqli_query($conn, $sql)) {
        $_SESSION['success_message'] = "Event deleted successfully!";
    } else {
        $_SESSION['error_message'] = "Failed to delete event: " . mysqli_error($conn);
    } 
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Organizer Home Page</title>
    <style>
        .container {
            margin-top: 70px;
            padding: 20px;
        }
        .events {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }
        .event {
            border: 1px solid #ccc;
            padding: 20px;
            border-radius: 10px;
            background-color: #fff;
            width: calc(33% - 20px);
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            text-align: center;
        }
        .event img {
            max-width: 100%;
            height: auto;
            border-radius: 10px;
        }
        .user-profile {
            position: fixed;
            top: 70px; 
            right: 20px;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 10px;
            background-color: rgba(255, 255, 255, 0.9);
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            text-align: left;
        }
        .user-profile h3 {
            margin-bottom: 10px;
        }
        .user-profile p {
            margin: 5px 0;
        }
        .add-event-button {
            display: block;
            margin: 20px 0;
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            text-align: center;
            text-decoration: none;
            border-radius: 5px;
        }
        footer {
            text-align: center;
            padding: 10px;
            background-color: rgba(255, 164, 158, 0.7);
            position: fixed;
            width: 100%;
            bottom: 0;
        }
    </style>
</head>
<body class="organizerHomePage">
    <?php include 'header.html'; ?>

    <div class="container">
        <h2>All Events</h2>
        <a href="editevents.php" class="add-event-button">Add Event</a>
        <div class="events">
            <?php
            if (isset($_SESSION['success_message'])) {
                echo '<p class="success-message">' . $_SESSION['success_message'] . '</p>';
                unset($_SESSION['success_message']);
            }
            if (isset($_SESSION['error_message'])) {
                echo '<p class="error-message">' . $_SESSION['error_message'] . '</p>';
                unset($_SESSION['error_message']);
            }

            $events = getAllEvents();
            while ($event = $events->fetch_assoc()) {
                echo '<div class="event">';
                echo '<img src="' . $event['image'] . '" alt="' . $event['eventName'] . '">';
                echo '<h3>' . $event['eventName'] . '</h3>';
                echo '<p><strong>Date:</strong> ' . $event['eventDate'] . '</p>';
                echo '<p><strong>Venue:</strong> ' . $event['venue'] . '</p>';
                echo '<p><strong>Performer:</strong> ' . $event['performer'] . '</p>';
                echo '<p><strong>Ticket Price:</strong> Ksh. ' . $event['ticketPrice'] . '</p>';
                echo '<form method="post" action="">';
                echo '<input type="hidden" name="eventID" value="' . $event['eventID'] . '">';
                echo '<button type="submit" name="deleteEvent" class="btn">Delete Event</button>';
                echo '</form>';
                echo '</div>';
            }
            ?>
        </div>
    </div>

    <footer>
        <p>© 2024</p>
    </footer>

    <div class="user-profile">
        <h3>Organizer Profile</h3>
        <p><strong>Organization:</strong> <?php echo $organizer['organizerName']; ?></p>
        <p><strong>Email:</strong> <?php echo $organizer['email']; ?></p>
    </div>
</body>
</html>
