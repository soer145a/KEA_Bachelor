<?php
session_start();
$_POST = json_decode(file_get_contents("php://input"), true); //make json object an assoc array
if (!isset($_POST['loginStatus'])) {
    header("Location: ../index.php");
    exit();
}
//Start the purchase product, called from the JS
$_SESSION['purchaseProcess'] = true;
exit();
