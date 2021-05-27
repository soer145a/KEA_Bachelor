<?php
session_start();
include("../DB_Connection/connection.php");

$_POST = json_decode(file_get_contents("php://input"), true); //make json object an assoc array


if (isset($_POST['product_id'])) {
    $subID = $_POST['sub'];
    $productId = $_POST['product_id'];
    $sql = "SELECT * FROM products WHERE product_id = \"$productId\"";
    $result = $conn->query($sql);
    $row = $result->fetch_object();
    $productName = $row->product_name;
    $productPrice = $row->product_price;

    if (isset($_SESSION['cartProducts'])) {

        $count = count($_SESSION['cartProducts']);
        $productArray = array(
            'product_id' => $productId,
            'product_name' => $productName,
            'product_price' => $productPrice,
            'subscription_id' => $subID
        );
        $_SESSION['cartProducts'][$count] = $productArray;
    } else {

        $productArray = array(
            'product_id' => $productId,
            'product_name' => $productName,
            'product_price' => $productPrice,
            'subscription_id' => $subID
        );
        $_SESSION['cartProducts'][0] = $productArray;
    }
}
$response = array("error" => false);

echo json_encode($response);
