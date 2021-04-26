<?php
session_start();
$paymentObject = new stdClass();
include_once("../DB_Connection/connection.php");
$productID = $_GET['productNmbr'];
$data = $conn->query("SELECT * FROM products WHERE product_id = $productID");
$data = $data->fetch_object();
echo json_encode($data);
