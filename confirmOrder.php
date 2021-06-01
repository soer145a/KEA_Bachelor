<?php
session_start();
include_once("Components/head.php");
include_once("Components/header.php");
include_once("Components/footer.php");
$head = headComp();
$header = headerComp('');
$footer = footerComp();

if (!isset($_SESSION['orderId'])) {
    header('Location: index.php');
}

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
    <?= $head ?>
</head>

<body>
    <?= $header ?>
    <main>
        <section id="orderConfirmation">
            <div class="layout-container orderConfirmation">
                <div class="order-confirmation-summary">
                    <h1><?= $message ?></h1>
                    <p>You have bought: <?= $productName ?></p>
                    <p>Addons: <?= $boughtAddons ?></p>
                    <p>Price: <?= $totalPrice ?></p>
                </div>
            </div>
        </section>
    </main>

    <?= $footer ?>
</body>

</html>