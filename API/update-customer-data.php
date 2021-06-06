<?php
session_start();
include_once("../db-connection/connection.php");
//Get the customer id from the session
$customerId = $_SESSION['customerId'];

$_POST = json_decode(file_get_contents("php://input"), true); //make json object an assoc array

//A unique check for updating the customer password
if (isset($_POST['customerPassword'])) {
    //Get the 2 passwords from the frontend
    $sCustomerPassword = $_POST['customerPassword'];
    $sNewPassword = $_POST['customerPasswordConfirm'];
    //get the password of the user from the database
    $sCustomerSelectSql = "SELECT * FROM customers WHERE customer_id = \"$customerId\"";
    $oCustomerResult = $oDbConnection->query($sCustomerSelectSql);
    $oCustomerRow = $oCustomerResult->fetch_object();
    $sOldPassword = $oCustomerRow->customer_password;
    //Verify the customers password hash
    if (password_verify($sCustomerPassword, $sOldPassword)) {
        //Create the new hashed password and update the database
        $sCustomerPasswordHashed = password_hash($sNewPassword, PASSWORD_DEFAULT);
        $sCustomerUpdateSql = "UPDATE `customers` SET `customer_password` = \"$sCustomerPasswordHashed\" WHERE customer_id = \"$customerId\"";
        $oDbConnection->query($sCustomerUpdateSql);
        $aResponse = array("customerUpdated" => true, "error" => "None");
    } else {
        $aResponse = array("customerUpdated" => false, "error" => "Wrong password");
    }
} else if (isset($_POST['whatToUpdate'])) {
    //for the other data records that needs to be changed
    $sColumnToUpdate = $_POST['whatToUpdate'];
    $sData = $_POST['data'];
    $sCustomerUpdateSql = "UPDATE `customers` SET `$sColumnToUpdate` = \"$sData\" WHERE customer_id = \"$customerId\"";
    $oDbConnection->query($sCustomerUpdateSql);
    $aResponse = array("customerUpdated" => true, "error" => "None");

    switch ($sColumnToUpdate) {
        case 'customer_first_name':
            $_SESSION['customerFirstName'] = $sData;
            break;
        case 'customer_last_name':
            $_SESSION['customerLastName'] = $sData;
    }
} else {
    $aResponse = array("customerUpdated" => false, "error" => "No field set");
}
echo json_encode($aResponse);
    //Send the user back to the profile page