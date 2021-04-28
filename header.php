<?php

session_start();
if (isset($_SESSION['loginStatus'])) {
    $firstName = $_SESSION['customer_first_name'];
    $lastName = $_SESSION['customer_last_name'];
    $welcomeMessage = "Welcome " . $firstName . " " . $lastName;
} else {
    $welcomeMessage = "Welcome stranger";
}

?>


<a href="login.php">login</a>
<a href="signup.php">signup</a>
<a href="profile.php">Profile</a>
<?= $welcomeMessage ?>