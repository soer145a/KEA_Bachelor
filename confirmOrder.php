<?php
session_start();
include_once("Components/head.php");
include_once("Components/header.php");
include_once("Components/footer.php");
$head = headComp();
$footer = footerComp();

if (!isset($_SESSION['orderId'])) {
    header('Location: index.php');
}

$productName = "";
$totalPrice = 0;
$boughtAddons = "";

if (isset($_SESSION['cartProducts'])) {
    foreach ($_SESSION['cartProducts'] as $product) {

        $productName = $product['productName'] . ", " .  $productName;
        $totalPrice =  $totalPrice + (float)$product['productPrice'];
    }
}
if (isset($_SESSION['cartAddOns'])) {
    foreach ($_SESSION['cartAddOns'] as $addon) {
        $addonName = $addon['addOnName'];
        $addonTotalprice = (float)$addon['addOnPrice'] * (float)$addon['addOnAmount'];
        $boughtAddons = $boughtAddons . $addon['addOnAmount'] . " x " . $addonName . ", ";
        $totalPrice =  $totalPrice + $addonTotalprice;
    }
}

unset($_SESSION['cartProducts']);
unset($_SESSION['cartAddOns']);
unset($_SESSION['customerData']);
unset($_SESSION['confirmCode']);
unset($_SESSION['orderId']);

$header = headerComp('');

if (!isset($_SESSION['loginStatus'])) {
    $message = "Thank you for your order! <br> We have sent you an email with a link to confirm your email address.";
} else {
    $message = "Thank you for your order.";
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
                    <h1 class="section-header"><?= $message ?></h1>
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