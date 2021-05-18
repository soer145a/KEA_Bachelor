<?php
session_start();
$customerConfirmCode = $_GET['confirmCode'];
echo $customerConfirmCode;
include_once("DB_Connection/connection.php");
$sql = "UPDATE customers SET customer_confirmed = 1 WHERE customer_confirm_code = \"$customerConfirmCode\"";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <h1>Thanks for confirming!</h1>
    <p>Your account is now active</p>
    <a href="login.php">To login</a>
    <a href="index.php">To the frontpage</a>
</body>

</html>