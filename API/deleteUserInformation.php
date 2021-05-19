<?php
session_start();
$customerID = $_SESSION['customer_id'];
include_once("../DB_Connection/connection.php");
$sql = "DELETE FROM customer_addons WHERE customer_id = \"$customerID\"";
$conn->query($sql);
$sql = "DELETE FROM customer_products WHERE customer_id = \"$customerID\"";
$conn->query($sql);
$sql = "SELECT * FROM orders WHERE customer_id = \"$customerID\"";
$results = $conn->query($sql);
while ($row = $results->fetch_assoc()) {
    //echo json_encode($row);
    $orderID = $row['order_id'];
    echo $orderID;
    $sql = "DELETE FROM order_addons WHERE order_id = \"$orderID\"";
    $conn->query($sql);
    $sql = "DELETE FROM order_products WHERE order_id = \"$orderID\"";
    $conn->query($sql);
}
$sql = "DELETE FROM orders WHERE customer_id = \"$customerID\"";
$conn->query($sql);
$sql = "DELETE FROM customers WHERE customer_id = \"$customerID\"";
$conn->query($sql);
header("Location: ../logout.php");
