<?php
session_start();
include("../DB_Connection/connection.php");

$_POST = json_decode(file_get_contents("php://input"), true); //make json object an assoc array


if (isset($_POST['product_id'])) {
    $subID = $_POST['sub'];
    $productId = $_POST['product_id'];
    $productSql = "SELECT * FROM products WHERE product_id = \"$productId\"";
    $productResult = $conn->query($productSql);
    $productRow = $productResult->fetch_object();
    $productName = $productRow->product_name;
    $productPrice = $productRow->product_price;

    $subSql = "SELECT * FROM subscriptions WHERE subscription_id = \"$subID\"";
    $subResult = $conn->query($subSql);
    $subRow = $subResult->fetch_object();
    $subName = $subRow->subscription_name;
    $subPrice = $subRow->subscription_price;
    // $subLength = $subRow->subscription_length;

    if (isset($_SESSION['cartProducts'])) {

        $count = count($_SESSION['cartProducts']);
        $productArray = array(
            'product_id' => $productId,
            'product_name' => $productName,
            'product_price' => $productPrice,
            'subscription_id' => $subID,
            'subscription_name' => $subName,
            'subscription_price' => $subPrice
        );
        $_SESSION['cartProducts'][$count] = $productArray;
    } else {

        $productArray = array(
            'product_id' => $productId,
            'product_name' => $productName,
            'product_price' => $productPrice,
            'subscription_id' => $subID,
            'subscription_name' => $subName,
            'subscription_price' => $subPrice
        );
        $_SESSION['cartProducts'][0] = $productArray;
    }
}
$response = array("error" => false);

echo json_encode($response);
