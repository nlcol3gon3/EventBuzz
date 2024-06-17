<?php
session_start();

include("database.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userID = $_POST['userID'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    $userID = mysqli_real_escape_string($conn, $userID);
    $username = mysqli_real_escape_string($conn, $username);
    $email = mysqli_real_escape_string($conn, $email);
    $password = mysqli_real_escape_string($conn, $password);

    $passwordHash = password_hash($password, PASSWORD_BCRYPT);

    
    $insertQuery = "INSERT INTO user (userID, username, email, password) VALUES ('$userID', '$username', '$email', '$password')";

    if (mysqli_query($conn, $insertQuery)) {
        $_SESSION['success_message'] = "You have successfully created an account. You may now log in.";
        header('Location: userRegistration.php');
        exit();
    } else {
        $_SESSION['error_message'] = "Failed to create account: " . mysqli_error($conn);
    }

    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>User Registration</title>
    <style>
        h2 {
            text-align: center;
            margin-top: 110px;
            color: #162938;
        }

        form {
            width: 500px;
            margin: 0 auto;
            margin-top: 50px;
            display: flex;
            flex-direction: column;
            align-items: center;
            background: rgb(255, 253, 253, 0.2);
            border: 2px solid rgba(255,255,255,0.7);
            border-radius: 15px;
            backdrop-filter: blur(10px);
            box-shadow: 0 0 30px #410038;
            padding: 40px;
        }

        label {
            font-size: 1em;
            color: #162938;
            font-weight: 500;
            margin-bottom: 5px;
        }

        input {
            width: 100%;
            height: 35px;
            border: 2px solid #162938;
            border-radius: 6px;
            outline: none;
            font-size: 1em;
            color: #162938;
            font-weight: 400;
            padding: 0 5px;
            margin-bottom: 15px;
        }

        input[type="submit"] {
            background-color: #162938;
            color: white;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #129919;
        }

        .success-message {
            text-align: center;
            color: green;
            font-weight: bold;
            margin: 15px 0;
            display: none;
        }
    </style>
</head>
<body>
    <h2>Create your account</h2>

    <div id="successMessage" class="success-message">
        Account successfully registered. Proceed to <a href="userLogin.php">LOG IN</a>
    </div>

    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label for="userID">Identification Number:</label>
        <input type="text" id="userID" name="userID" required>

        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>

        <input type="submit" name="submit" value="Create account">
    </form>

    <script>
        <?php
        if (isset($_SESSION['success_message'])) {
            echo 'document.getElementById("successMessage").style.display = "block";';
            unset($_SESSION['success_message']);
        }
        ?>
        
        setTimeout(function () {
            var successMessage = document.getElementById("successMessage");
            if (successMessage.style.display === "block") {
                successMessage.style.display = "none";
            }
        }, 10000); 
    </script>
</body>
</html>

