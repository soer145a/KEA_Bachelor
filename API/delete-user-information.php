<?php
session_start();
include_once("../DB_Connection/connection.php");
if(!isset($_SESSION['customerId'])){
    header("Location: ../index.php");
    exit();
}
$sCustomerId = $_SESSION['customerId'];
$sCustomerAddonDeleteSql = "DELETE FROM customer_addons WHERE customer_id = \"$sCustomerId\"";
$oDbConnection->query($sCustomerAddonDeleteSql);
$sCustomerProductDeleteSql = "DELETE FROM customer_products WHERE customer_id = \"$sCustomerId\"";
$oDbConnection->query($sCustomerProductDeleteSql);
$sOrderSelectSql = "SELECT * FROM orders WHERE customer_id = \"$sCustomerId\"";
$oOrderResults = $oDbConnection->query($sOrderSelectSql);

while ($oOrderRow = $oOrderResults->fetch_object()) {
    //echo json_encode($row);
    $sOrderId = $row->order_id;

    $sOrderAddonDeleteSql = "DELETE FROM order_addons WHERE order_id = \"$sOrderId\"";
    $oDbConnection->query($sOrderAddonDeleteSql);
    $sOrderProductDeleteSql = "DELETE FROM order_products WHERE order_id = \"$sOrderId\"";
    $oDbConnection->query($sOrderProductDeleteSql);
}
$sOrderDeleteSql = "DELETE FROM orders WHERE customer_id = \"$sCustomerId\"";
$oDbConnection->query($sOrderDeleteSql);
$sCustomerDeleteSql = "DELETE FROM customers WHERE customer_id = \"$sCustomerId\"";
$oDbConnection->query($sCustomerDeleteSql);
header("Location: ../logout.php");
