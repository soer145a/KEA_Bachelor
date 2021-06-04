<?php
session_start();
$customer_product_id = $_GET['subID'];
include_once("../DB_Connection/connection.php");
//When toggling the autorenew on the customer products, we need to know which entry we are toggling
$sql = "SELECT * FROM `customer_products` WHERE customer_products_id = \"$customer_product_id\"";
$results = $oDbConnection->query($sql);
$row = $results->fetch_object();
//If it is set to true, set it to false and vise-versa
if($row->subscription_autorenew){
    $sql = "UPDATE `customer_products` SET `subscription_autorenew` = 0 WHERE `customer_products_id` = $customer_product_id";
}else{
    $sql = "UPDATE `customer_products` SET `subscription_autorenew` = 1 WHERE `customer_products_id` = $customer_product_id";
}
//Query the sql string depending on which setting it was before
$oDbConnection->query($sql);
