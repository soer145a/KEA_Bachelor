<?php
session_start();
//includes for styling
include_once("components/header.php");
include_once("components/head.php");
include_once("components/footer.php");
include("db-connection/connection.php");
include_once("components/addOn.php");
include_once("components/signUpForm.php");
$sHeadHtmlComp = headComp();
$sHeaderHtmlComp = headerComp('cart');
$sFooterHtmlComp = footerComp();

//productcards being printet starts here
$sSignUpFormHtml = "";
$sProductHtml = "";
$sAddonHtml = "";
$nTotalPrice = 0;

//Check to see if the user is logged in
if (!isset($_SESSION['loginStatus'])) {
    $bLoginStatus = 'false';
} else {
    $bLoginStatus = 'true';
}
//If there are card products in the session, add them to the cart summery
if (isset($_SESSION['cartProducts'])) {
    foreach ($_SESSION['cartProducts'] as $aProduct) {
        $sProductName = $aProduct['productName'];
        $nProductPrice = $aProduct['productPrice'];
        $sProductId = $aProduct['productId'];
        $sSubscriptionName = $aProduct['subscriptionName'];
        $nSubscriptionPrice = $aProduct['subscriptionPrice'];
        $nTotalPrice =  $nTotalPrice + $aProduct['productPrice'];
        //The html block for print
        $sProductHtml =  $sProductHtml . "<div class='product-row'>
                                    <div class='product-items'>
                                        <p class='product-item product-item__name'>$sProductName</p>
                                        <p class='product-item product-item__quantity'>
                                            <span class='product-item product-item__delete' onclick='removeItemFromCart($sProductId, true, 0, $bLoginStatus)'></span>
                                            1
                                        </p>
                                        <p class='product-item product-item__price'>$nProductPrice</p>
                                    </div>

                                    <div class='subscription-row'>
                                        <p class='subscription-row__text'>Subscription: $sSubscriptionName</p>
                                        <p class='subscription-row__text subscription-row__price'>$nSubscriptionPrice</p>
                                    </div>
                                </div>";
    }
}

//If there are addons in the cart session, add them to the summery
if (isset($_SESSION['cartAddOns'])) {
    foreach ($_SESSION['cartAddOns'] as $aAddon) {
        $sAddonId = $aAddon['addOnId'];
        $sAddonName = $aAddon['addOnName'];
        $nAddonAmount = $aAddon['addOnAmount'];
        $nAddonPrice = $aAddon['addOnPrice'];
        $nAddonTotalPrice = $nAddonAmount * $nAddonPrice;
        $nTotalPrice =  $nTotalPrice + $nAddonTotalPrice;
        //The printed HTML
        $sAddonHtml =  $sAddonHtml . "<div class='product-row'>
                                    <div class='product-items'>
                                        <p class='product-item product-item__name'>$sAddonName</p>
                                        <p class='product-item product-item__quantity'>
                                            <span class='product-item__delete' onclick='removeItemFromCart($sAddonId, false, $nAddonAmount, $bLoginStatus)'></span>
                                            $nAddonAmount
                                        </p>
                                        <p class='product-item product-item__price'>$nAddonTotalPrice</p>
                                    </div>
                                </div>";
    }
}
//If the user is not logged in, we print the form for the user to fill out
if ($bLoginStatus != 'true') {
    $sSignUpFormHtml = signUpFormComp($bLoginStatus, $nTotalPrice);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?= $sHeadHtmlComp ?>
</head>

<body>
    <?= $sHeaderHtmlComp ?>
    <main id="cart" class="container-full-width">
        <section class="layout-container cart">
            <div class="checkout-container">
                <?= $sSignUpFormHtml ?>
                <div class="order-summary-container">
                    <h2 class="section-header">Order summary</h2>
                    <div class="order-summary">
                        <div class="order-summary__header">
                            <div class="header-container">
                                <h5 class="header-item header-item__product">Product</h5>
                                <h5 class="header-item header-item__quantity">Quantity</h5>
                                <h5 class="header-item header-item__price">Price</h5>
                            </div>
                        </div>
                        <div class="order-summary__body">
                            <div class="product-container">
                                <?= $sProductHtml ?>
                                <?= $sAddonHtml ?>
                                <div class="empty-cart">
                                    <p class="empty-cart__text">
                                        You don't have any products in
                                        the cart
                                    </p>
                                </div>
                            </div>

                        </div>
                        <div id='paypal-button-container' class="paypal-button-container">
                            <button id="paypalInactive" class="button order-summary__button " title="You need to fill out the form.">
                                PayPal
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </section>

    </main>
    <?= $sFooterHtmlComp ?>
</body>
<script src="https://www.paypal.com/sdk/js?client-id=ASc0sohSJuv9IX6ovw_EQxA0uGoiQO5YxX2U7u9qnfZGwovsZ6Tylr1Arf0XOCAshoqqX8ApS3nkYpGy&currency=EUR&disable-funding=credit,card">
</script>
<script src="js/app.js"></script>
<script src="js/cart.js"></script>

<script>
    togglePaypalButton(<?= $bLoginStatus ?>, <?= $nTotalPrice ?>);
</script>