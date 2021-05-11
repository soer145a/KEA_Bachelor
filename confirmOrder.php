<?php
session_start();
$productName = "";
$totalPrice = 0;
if (isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $product) {
        $productName = $product['product_name'] . ", " .  $productName;
        $totalPrice =  $totalPrice + (int)$product['product_price'];
    }
}

unset($_SESSION['cart']);
unset($_SESSION['postData']);
unset($_SESSION['key']);

include_once("Components/header.php");
$header = headerComp();

if (!isset($_SESSION['loginStatus'])) {
    $message = "Thanks for your order, we have sent you an email with a link to confirm your email address";
} else {
    $message = "Thanks for your order";
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmation</title>
</head>

<body>
    <div><?= $header ?></div>
    <h1><?= $message ?></h1>
    <p>You have bought: <?= $productName ?></p>
    <p>Price: <?= $totalPrice ?></p>
</body>

</html>