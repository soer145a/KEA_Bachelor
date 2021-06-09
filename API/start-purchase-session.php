<?php
session_start();
$_POST = json_decode(file_get_contents("php://input"), true); //make json object an assoc array
//Start the purchase product, called from the JS
if (!isset($_POST['confirmString'])) {
    header("Location: ../index.php");
}
$_SESSION['purchaseProcess'] = true;