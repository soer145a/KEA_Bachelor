<?php
session_start();
include_once("DB_Connection/connection.php");
include_once("Components/product.php");
include_once("Components/header.php");
$header = headerComp();

$sql = "SELECT * FROM products";
$result = $conn->query($sql);
$productCards = "";

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

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MainPage</title>
    <link rel="stylesheet" href="css/app.css">
</head>

<body>
    <div><?= $header ?></div>
    <h1>Initial Page</h1>
    <div id="buyOptions">
        <?= $productCards ?>
    </div>
    <div id="addOns">
        <label>
            <p>New 3D model of dress/design/style:</p>
            <input type="checkbox" class="addOn" name="new3DModels">
        </label>
        <label>
            <p>Adding specific accessories (jewellery, shoes, veils, etc.):</p>
            <input type="checkbox" class="addOn" name="additionalAccesories">
        </label>
        <label>
            <p>Price subject to requirements:</p>
            <input type="checkbox" class="addOn" name="priceAltering">
        </label>
        <label>
            <p>Guidance tool for taking measurements:</p>
            <input type="checkbox" class="addOn" name="meassurementTool">
        </label>
    </div>
</body>
<script src="js/app.js"></script>