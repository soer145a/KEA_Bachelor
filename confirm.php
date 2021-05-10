<?php
$key = $_GET['key'];
include_once("DB_Connection/connection.php");
$sql = "UPDATE `customers` SET `customer_confirmed` = 1 WHERE `customers`.`customer_confirm_code` = \"$key\"";
$stmt = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirm Account</title>
</head>
<body>
    <h1>Your account has been approoved</h1>
</body>
</html>