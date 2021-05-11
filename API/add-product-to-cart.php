<?php
session_start();
include("../DB_Connection/connection.php");


if (isset($_POST['add_to_cart'])) {

    $productId = $_POST['product_id'];
    $sql = "SELECT * FROM products WHERE product_id = \"$productId\"";
    $result = $conn->query($sql);
    $row = $result->fetch_object();
    $productName = $row->product_name;
    $productPrice = $row->product_price;


    if (isset($_SESSION['cart'])) {

        $count = count($_SESSION['cart']);
        $productArray = array(
            'product_id' => $productId,
            'product_name' => $productName,
            'product_price' => $productPrice
        );
        $_SESSION['cart'][$count] = $productArray;
    } else {

        $productArray = array(
            'product_id' => $productId,
            'product_name' => $productName,
            'product_price' => $productPrice
        );
        $_SESSION['cart'][0] = $productArray;
    }
}

header('Location: ../index.php');





while ($row = $result->fetch_object()) {

    if (isset($_SESSION['cart'])) {
        $productIdsArray = array_column($_SESSION['cart'], 'product_id');
        if (in_array($row->product_id, $productIdsArray)) {
            $productCards = $productCards . productComp($row->product_price, $row->product_name, $row->product_description, $row->product_id, true);
        } else {
            $productCards = $productCards . productComp($row->product_price, $row->product_name, $row->product_description, $row->product_id, false);
        }
    } else {
        $productCards = $productCards . productComp($row->product_price, $row->product_name, $row->product_description, $row->product_id, false);
    }
}
