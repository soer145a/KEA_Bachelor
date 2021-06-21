s<?php
session_start();
include_once("../db-connection/connection.php");
$_POST = json_decode(file_get_contents("php://input"), true); //make json object an assoc array

if (!isset($_POST['customerProductId'])) {
    header("Location: ../index.php");
} else {

    $sCustomerProductId = $_POST['customerProductId'];

    //When toggling the autorenew on the customer products, we need to know which entry we are toggling
    $sCustomerProductSelectSql = "SELECT * FROM `customer_products` WHERE customer_products_id = \"$sCustomerProductId\"";
    $oCustomerProductResult = $oDbConnection->query($sCustomerProductSelectSql);
    $oCustomerProductRow = $oCustomerProductResult->fetch_object();
    //If it is set to true, set it to false and vise-versa
    if ($oCustomerProductRow->subscription_autorenew) {
        $sCustomerProductUpdateSql = "UPDATE `customer_products` SET `subscription_autorenew` = 0 WHERE `customer_products_id` = $sCustomerProductId";
        $aResponse = array("renewToggledOff" => true, "error" => "none");
    } else {
        $sCustomerProductUpdateSql = "UPDATE `customer_products` SET `subscription_autorenew` = 1 WHERE `customer_products_id` = $sCustomerProductId";
        $aResponse = array("renewToggledOn" => true, "error" => "none");
    }
    //Query the sql string depending on which setting it was before
    $oDbConnection->query($sCustomerProductUpdateSql);
}
echo json_encode($aResponse);
