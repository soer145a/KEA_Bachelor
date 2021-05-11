<?php
session_start();
include_once("DB_Connection/connection.php");
include_once("Components/product.php");
include_once("Components/addOn.php");
include_once("Components/head.php");
include_once("Components/header.php");
$head = headComp();
$header = headerComp();

$productSql = "SELECT * FROM products";
$productResult = $conn->query($productSql);
$productCards = "";

while ($productRow = $productResult->fetch_object()) {

    if (isset($_SESSION['cartProducts'])) {
        $productIdsArray = array_column($_SESSION['cartProducts'], 'product_id');
        if (in_array($productRow->product_id, $productIdsArray)) {
            $productCards = $productCards . productComp($productRow->product_price, $productRow->product_name, $productRow->product_description, $productRow->product_id, true);
        } else {
            $productCards = $productCards . productComp($productRow->product_price, $productRow->product_name, $productRow->product_description, $productRow->product_id, false);
        }
    } else {
        $productCards = $productCards . productComp($productRow->product_price, $productRow->product_name, $productRow->product_description, $productRow->product_id, false);
    }
}

$addOnSql = "SELECT * FROM addons";
$addOnResult = $conn->query($addOnSql);
$addOnCards = "";

while ($addOnRow = $addOnResult->fetch_object()) {
    $addOnCards = $addOnCards . addOnComp($addOnRow->addon_price, $addOnRow->addon_name, $addOnRow->addon_description, $addOnRow->addon_id);
}

?>


<!DOCTYPE html>
<html lang="en">

<!-- <head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MainPage</title>
    <link rel="stylesheet" href="css/app.css">
</head> -->

<head>
    <?= $head; ?>
</head>

<body>
    <div><?= $header ?></div>
    <h1>Initial Page</h1>
    <div id="products">
        <p>products</p>
        <?= $productCards ?>
    </div>
    <div id="products">
        <p>add ons</p>
        <?= $addOnCards ?>
    </div>

</body>
<script src="js/app.js"></script>