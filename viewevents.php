<?php
require_once 'database.php';
require_once 'functions.php';
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>View Events</title>
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
<body>
    <?php include 'header.html'; ?>

    <div class="container">
        <h2>All Events</h2>
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
                echo '</div>';
            }
            ?>
        </div>
    </div>

    <footer>
        <p>© 2024 Event Management System</p>
    </footer>
</body>
</html>

