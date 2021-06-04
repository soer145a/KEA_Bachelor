<?php
session_start();
include_once("../DB_Connection/connection.php");
//Check if the customer id is in the session
if(!isset($_SESSION['customerId'])){
    header("Location: ../index.php");
    exit();
}
//When deleting from a relational database, have to do it in the correct order
$sCustomerId = $_SESSION['customerId'];
$sCustomerAddonDeleteSql = "DELETE FROM customer_addons WHERE customer_id = \"$sCustomerId\"";
//First we delete the bridging table records
$oDbConnection->query($sCustomerAddonDeleteSql);
$sCustomerProductDeleteSql = "DELETE FROM customer_products WHERE customer_id = \"$sCustomerId\"";
$oDbConnection->query($sCustomerProductDeleteSql);
//Then we need the order of the customer that we are deleting
$sOrderSelectSql = "SELECT * FROM orders WHERE customer_id = \"$sCustomerId\"";
$oOrderResults = $oDbConnection->query($sOrderSelectSql);

while ($oOrderRow = $oOrderResults->fetch_object()) {
    
    $sOrderId = $row->order_id;
    //Then we delete the relevant records from the other order tables to ensure that no data is left from that order
    $sOrderAddonDeleteSql = "DELETE FROM order_addons WHERE order_id = \"$sOrderId\"";
    $oDbConnection->query($sOrderAddonDeleteSql);
    $sOrderProductDeleteSql = "DELETE FROM order_products WHERE order_id = \"$sOrderId\"";
    $oDbConnection->query($sOrderProductDeleteSql);
}
//Lastly we delete the order and the customer in our database, afterwards we have no data left on the customer.
$sOrderDeleteSql = "DELETE FROM orders WHERE customer_id = \"$sCustomerId\"";
$oDbConnection->query($sOrderDeleteSql);
$sCustomerDeleteSql = "DELETE FROM customers WHERE customer_id = \"$sCustomerId\"";
$oDbConnection->query($sCustomerDeleteSql);
//When all the data is deleted, we then log the user out of our system
header("Location: ../logout.php");
