<?php
session_start();
$productName = "";
$totalPrice = 0;
$boughtAddons = "";

if (isset($_SESSION['cartProducts'])) {
    foreach ($_SESSION['cartProducts'] as $product) {
        $productName = $product['product_name'] . ", " .  $productName;
        $totalPrice =  $totalPrice + (float)$product['product_price'];
    }
}
if (isset($_SESSION['cartAddOns'])) {
    foreach ($_SESSION['cartAddOns'] as $addon) {
        $addonName = $addon['addon_name'];
        $addonTotalprice = (float)$addon['addon_price'] * (float)$addon['addon_amount'];
        $boughtAddons = $boughtAddons . $addon['addon_amount'] . " x " . $addonName . ", ";
        $totalPrice =  $totalPrice + $addonTotalprice;
    }
}

unset($_SESSION['cartProducts']);
unset($_SESSION['cartAddOns']);
unset($_SESSION['postData']);
unset($_SESSION['confirmCode']);
unset($_SESSION['orderId']);

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
    <p>Addons: <?= $boughtAddons ?></p>
    <p>Price: <?= $totalPrice ?></p>
</body>

</html>