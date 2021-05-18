<?php
session_start();
$customer_product_id = $_GET['subID'];
include_once("../DB_Connection/connection.php");
$sql = "SELECT * FROM `customer_products` WHERE customer_products_id = \"$customer_product_id\"";
$results = $conn->query($sql);
$row = $results->fetch_object();
//echo json_encode($row);
//echo $_SESSION['customer_id'].__LINE__;
if($row->subscription_autorenew){
    $sql = "UPDATE `customer_products` SET `subscription_autorenew` = 0 WHERE `customer_products_id` = $customer_product_id";
    //echo $sql;
}else{
    $sql = "UPDATE `customer_products` SET `subscription_autorenew` = 1 WHERE `customer_products_id` = $customer_product_id";
    //echo $sql;
}
$conn->query($sql);
