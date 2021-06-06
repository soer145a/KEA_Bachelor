<?php
session_start();
$_POST = json_decode(file_get_contents("php://input"), true); //make json object an assoc array
//Start the purchase product, called from the JS
$_SESSION['purchaseProcess'] = true;
header("Location: ../cart.php");
exit();
