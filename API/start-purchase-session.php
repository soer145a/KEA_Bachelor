<?php
session_start();
$_POST = json_decode(file_get_contents("php://input"), true); //make json object an assoc array
//Start the purchase product, called from the JS
if (!isset($_POST['confirmString'])) {
    $aResponse = array("purchaseStarted" => false, "error" => "none");
}
$_SESSION['purchaseProcess'] = true;
$aResponse = array("purchaseStarted" => true, "error" => "none");
echo json_encode($aResponse);