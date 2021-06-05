<?php
session_start();
<<<<<<< HEAD

include_once("../db-connection/connection.php");

=======
include_once("../DB_Connection/connection.php");
//Get the customer id from the session
>>>>>>> fed27c1b87e655eccdb612e4482db8f64634d885
$customerId = $_SESSION['customerId'];
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
    }
} else if (isset($_POST)) {
    //for the other data records that needs to be changed
    $sColumnToUpdate = key($_POST);
    $sData = reset($_POST);
    $sCustomerUpdateSql = "UPDATE `customers` SET `$sColumnToUpdate` = \"$sData\" WHERE customer_id = \"$customerId\"";
    $oDbConnection->query($sCustomerUpdateSql);
}
//Send the user back to the profile page
header("Location: ../profile.php");
