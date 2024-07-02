<?php
session_start();
require_once 'database.php';
require_once 'functions.php';

// Handle form submission for adding an event
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['uploadEvent'])) {
    $eventName = $_POST['eventName'];
    $eventDate = $_POST['eventDate'];
    $venue = $_POST['venue'];
    $performer = $_POST['performer'];
    $ticketPrice = $_POST['ticketPrice'];

    // Handle file upload
    $targetDir = "uploads/";
    $targetFile = $targetDir . basename($_FILES["image"]["name"]);
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
    $check = getimagesize($_FILES["image"]["tmp_name"]);

    if ($check !== false && move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
        $image = $targetFile;
        $sql = "INSERT INTO event (eventName, eventDate, venue, performer, ticketPrice, image) VALUES ('$eventName', '$eventDate', '$venue', '$performer', '$ticketPrice', '$image')";

        if (mysqli_query($conn, $sql)) {
            $_SESSION['success_message'] = "Event uploaded successfully!";
        } else {
            $_SESSION['error_message'] = "Failed to upload event: " . mysqli_error($conn);
        }
    } else {
        $_SESSION['error_message'] = "Failed to upload image.";
    }
}

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
    <title>Events</title>
</head>

<style>
    * {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
  font-family: 'Roboto', sans-serif;
}

body.indexPage {
  display: flex;
  flex-direction: column;
  min-height: 100vh;
  background: url('images/background.jpeg') no-repeat center center;
  background-size: cover;
  background-position: center;
}

main {
  flex: 1 0 auto;
}

header {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  padding: 15px 70px;
  display: flex;
  justify-content: space-between;
  align-items: center;
  z-index: 99;
  background-color: rgba(255, 164, 158, 0.7); /* Bright orange background */
}

.logo {
  font-size: 2em;
  color: white;
  user-select: none;
}

.navigation a {
  position: relative;
  font-size: 1.1em;
  color: #fff;
  text-decoration: none;
  font-weight: 500;
  margin-left: 40px;
}

.navigation a::after {
  content: '';
  position: absolute;
  left: 0;
  bottom: -6px;
  width: 100%;
  height: 3px;
  background: #fff;
  border-radius: 5px;
  transform: scaleX(0);
  transform-origin: right;
  transition: transform .5s;
}

.search-bar {
  display: flex;
  align-items: center;
}

.search-bar input[type="text"] {
  padding: 8px;
  border-radius: 5px;
  border: 1px solid #ffffff;
  margin-right: 10px;
}

.search-bar button {
  padding: 8px;
  border-radius: 1px;
  border: none;
  background-color: #ffffff;
  color: #ff8c00;
  font-weight: bold;
  cursor: pointer;
}

.search-bar button:hover {
  background-color: pink;
}

.container {
  padding: 100px 20px 20px 20px;
}

.events {
  display: flex;
  flex-wrap: wrap;
  justify-content: center;
}

.event {
  width: 300px;
  border: 1px solid #ddd;
  margin: 10px;
  padding: 10px;
  border-radius: 8px;
  background-color: #ffffff; 
  box-shadow: 0 4px 8px rgba(0,0,0,0.1); 
}

.event img {
  width: 100%;
  height: auto;
  border-radius: 8px;
}

.event h3 {
  margin-top: 10px;
  color: #162938;
}

.event p {
  margin: 5px 0;
  color: #555;
}

form {
  margin-top: 20px;
  display: flex;
  flex-direction: column;
  align-items: center; 
}

form label {
  margin: 10px 0 5px;
  color: #162938;
}

form input, form textarea, form button {
  width: 100%; 
  max-width: 500px; 
  padding: 10px;
  margin-bottom: 10px;
  border: 1px solid #ddd;
  border-radius: 4px;
}

form button {
  background-color: #162938;
  color: white;
  cursor: pointer;
}

form button:hover {
  background-color: #129919;
}

.success-message, .error-message {
  text-align: center;
  font-weight: bold;
  margin: 10px 0;
}

.success-message {
  color: green;
}

.error-message {
  color: red;
}

</style>

<body>
    <?php include 'header.html'; ?>

    <div class="container" style="margin-top: 50px;">
        <?php
        if (isset($_SESSION['success_message'])) {
            echo '<p class="success-message">' . $_SESSION['success_message'] . '</p>';
            unset($_SESSION['success_message']);
        }
        if (isset($_SESSION['error_message'])) {
            echo '<p class="error-message">' . $_SESSION['error_message'] . '</p>';
            unset($_SESSION['error_message']);
        }
        ?>

        <h2></h2>
        <div class="events">
            <?php
            $events = getAllEvents();
            while ($event = $events->fetch_assoc()) {
                echo '<div class="event">';
                echo '<img src="' . $event['image'] . '" alt="' . $event['eventName'] . '">';
                echo '<h3>' . $event['eventName'] . '</h3>';
                echo '<p><strong>Date:</strong> ' . $event['eventDate'] . '</p>';
                echo '<p><strong>Venue:</strong> ' . $event['venue'] . '</p>';
                echo '<p><strong>Performer:</strong> ' . $event['performer'] . '</p>';
                echo '<p><strong>Ticket Price:</strong> Ksh. ' . $event['ticketPrice'] . '</p>';
                echo '<form method="post" action="events.php">';
                echo '<input type="hidden" name="eventID" value="' . $event['eventID'] . '">';
                echo '<button type="submit" name="deleteEvent" class="btn">Delete Event</button>';
                echo '</form>';
                echo '</div>';
            }
            ?>
        </div>

        <h2>Upload Event</h2>
        <form method="post" action="events.php" enctype="multipart/form-data">
            <label for="eventName">Event Name:</label>
            <input type="text" id="eventName" name="eventName" required>

            <label for="eventDate">Event Date:</label>
            <input type="date" id="eventDate" name="eventDate" required>

            <label for="venue">Venue:</label>
            <input type="text" id="venue" name="venue" required>

            <label for="performer">Performer:</label>
            <input type="text" id="performer" name="performer" required>

            <label for="ticketPrice">Ticket Price:</label>
            <input type="number" step="100" id="ticketPrice" name="ticketPrice" required>

            <label for="image">Event Poster:</label>
            <input type="file" id="image" name="image" required>

            <button type="submit" name="uploadEvent" class="btn">Upload Event</button>
        </form>
    </div>
</body>
</html>

