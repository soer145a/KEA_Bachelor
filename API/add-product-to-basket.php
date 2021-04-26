<?php
$_POST = json_decode(file_get_contents("php://input"), true);
session_start();
$paymentObject = new stdClass();
include_once("../DB_Connection/connection.php");
//$productID = $_GET['productNmbr'];
echo json_encode($_POST);
$data = $conn->query("SELECT * FROM products WHERE product_id = '1'");
$data = $data->fetch_object();
$returnObj = new stdClass();
$returnObj->DB = $data;
$returnObj->incData = json_encode($_POST);
//echo json_encode($returnObj);
