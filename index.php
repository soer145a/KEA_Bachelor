<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MainPage</title>
    <link rel="stylesheet" href="css/app.css">
</head>

<body>
    <h1>Initial Page</h1>
    <?php
    session_start();
    if (isset($_SESSION['loginStatus'])) {
        $firstName = $_SESSION['customer_first_name'];
        $lastName = $_SESSION['customer_last_name'];
        echo "Hi $firstName $lastName";
    } else {
        echo "fuck you";
    }
    ?>
    <a href="login.php">login</a>
    <a href="signup.php">signup</a>
</body>
<script src="js/app.js"></script>

</html>