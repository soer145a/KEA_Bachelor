<?php
session_start();

include_once("../db-connection/connection.php");

$customerId = $_SESSION['customerId'];

if (isset($_POST['customerPassword'])) {
    $sCustomerPassword = $_POST['customerPassword'];
    $sNewPassword = $_POST['customerPasswordConfirm'];
    $sCustomerSelectSql = "SELECT * FROM customers WHERE customer_id = \"$customerId\"";
    $oCustomerResult = $oDbConnection->query($sCustomerSelectSql);
    $oCustomerRow = $oCustomerResult->fetch_object();
    $sOldPassword = $oCustomerRow->customer_password;
    if (password_verify($sCustomerPassword, $sOldPassword)) {
        $sCustomerPasswordHashed = password_hash($sNewPassword, PASSWORD_DEFAULT);
        $sCustomerUpdateSql = "UPDATE `customers` SET `customer_password` = \"$sCustomerPasswordHashed\" WHERE customer_id = \"$customerId\"";
        $oDbConnection->query($sCustomerUpdateSql);
        echo "password updated";
    }
} else if (isset($_POST)) {

    $sColumnToUpdate = key($_POST);
    $sData = reset($_POST);
    $sCustomerUpdateSql = "UPDATE `customers` SET `$sColumnToUpdate` = \"$sData\" WHERE customer_id = \"$customerId\"";
    $oDbConnection->query($sCustomerUpdateSql);
}

header("Location: ../profile.php");
