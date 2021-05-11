<?php
session_start();
include("../DB_Connection/connection.php");


if (isset($_POST['add_product_to_cart'])) {

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
            'product_price' => $productPrice
        );
        $_SESSION['cartProducts'][$count] = $productArray;
    } else {

        $productArray = array(
            'product_id' => $productId,
            'product_name' => $productName,
            'product_price' => $productPrice
        );
        $_SESSION['cartProducts'][0] = $productArray;
    }
}

header('Location: ../index.php');
