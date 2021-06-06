<?php
session_start();
if(!isset($_GET['customer-product-id'])){
    header("Location: ../index.php");
}
$sCustomerProductId = $_GET['customer-product-id'];
include_once("../db-connection/connection.php");
//When toggling the autorenew on the customer products, we need to know which entry we are toggling
$sCustomerProductSelectSql = "SELECT * FROM `customer_products` WHERE customer_products_id = \"$sCustomerProductId\"";
$oCustomerProductResult = $oDbConnection->query($sCustomerProductSelectSql);
$oCustomerProductRow = $oCustomerProductResult->fetch_object();
//If it is set to true, set it to false and vise-versa
if ($oCustomerProductRow->subscription_autorenew) {
    $sCustomerProductUpdateSql = "UPDATE `customer_products` SET `subscription_autorenew` = 0 WHERE `customer_products_id` = $sCustomerProductId";
} else {
    $sCustomerProductUpdateSql = "UPDATE `customer_products` SET `subscription_autorenew` = 1 WHERE `customer_products_id` = $sCustomerProductId";
}
//Query the sql string depending on which setting it was before
$oDbConnection->query($sCustomerProductUpdateSql);
