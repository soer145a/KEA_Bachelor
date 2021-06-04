<?php
session_start();

include_once("Components/header.php");
include_once("Components/head.php");
include_once("Components/footer.php");
include("DB_Connection/connection.php");
include_once("Components/addOn.php");
$head = headComp();
$header = headerComp('cart');
$footer = footerComp();

//productcards being printet starts here
$content = "";
$products = "";
$addons = "";
$totalPrice = 0;

if (isset($_SESSION['cartProducts'])) {
    foreach ($_SESSION['cartProducts'] as $product) {
        $productName = $product['productName'];
        $productPrice = $product['productPrice'];
        $productId = $product['productId'];
        $subscriptionName = $product['subscriptionName'];
        $subscriptionPrice = $product['subscriptionPrice'];
        $totalPrice =  $totalPrice + $product['productPrice'];

        $products =  $products . "<div class='product-row'>
                                    <div class='product-item'>
                                        <p class='product-item__name'>$productName</p>
                                        <p class='product-item__quantity'>
                                            <span class='product-item__delete' onclick='removeItemFromCart($productId, true, 0)'></span>
                                            1
                                        </p>
                                        <p class='product-item__price'>$productPrice</p>
                                    </div>

                                    <div class='subscription-row'>
                                        <p class='subscription-row__text'>Subscription: $subscriptionName</p>
                                        <p class='subscription-row__text subscription-row__price'>$subscriptionPrice</p>
                                    </div>
                                </div>";
    }
}


if (isset($_SESSION['cartAddOns'])) {
    foreach ($_SESSION['cartAddOns'] as $addon) {
        $addonId = $addon['addOnId'];
        $addonName = $addon['addOnName'];
        $addonQuantity = $addon['addOnAmount'];
        $addonPrice = $addon['addOnPrice'];
        $addonTotalPrice = (float)$addonQuantity * (float)$addonPrice;
        $totalPrice =  $totalPrice + $addonTotalPrice;
        $addons =  $addons . "<div class='product-row'>
                                    <div class='product-item'>
                                        <p class='product-item__name'>$addonName</p>
                                        <p class='product-item__quantity'>
                                            <span class='product-item__delete' onclick='removeItemFromCart($addonId, false, $addonQuantity)'></span>
                                            $addonQuantity
                                        </p>
                                        <p class='product-item__price'>$addonTotalPrice</p>
                                    </div>
                                </div>";
    }
}

if (!isset($_SESSION['loginStatus'])) {
    $loggedIn = 'false';
    $content = "
        <form action='API/payment-handler.php' method='POST' class='account-details'>
            <h2 class='section-header'>Account details</h2>
            <label for='account-details__name'
                >Company Name</label
            >
            <input
                id='account-details__name'
                type='text'
                name='companyName'
                data-validate='string'
                required
                oninput='inputValidate(); togglePaypalButton($loggedIn, $totalPrice);'
            />
            <label for='account-details__cvr'
                >Company CVR nr.</label
            >
            <input
                id='account-details__cvr'
                type='text'
                name='companyCvr'
                data-validate='cvr'
                required
                oninput='inputValidate(); togglePaypalButton($loggedIn, $totalPrice);'
            />
            <div class='account-details__contact'>
                <h4 class='section-subheader contact__header'>Contact Person</h4>
                <div class='contact__wrapper'>
                    <label for='contact__firstname'>Firstname</label>
                    <input
                        id='contact__firstname'
                        type='text'
                        name='customerFirstName'
                        data-validate='string'
                        required
                        oninput='inputValidate(); togglePaypalButton($loggedIn, $totalPrice);'
                    />
                </div>
                <div class='contact__wrapper'>
                    <label for='contact__lastname'>Lastname</label>
                    <input
                        id='contact__lastname'
                        type='text'
                        name='customerLastName'
                        data-validate='string'
                        required
                        oninput='inputValidate(); togglePaypalButton($loggedIn, $totalPrice);'
                    />
                </div>
            </div>
            
            <label for='account-details__mail'>Phone Number</label>
            <input
                id='account-details__mail'
                type='text'
                name='customerPhone'
                data-validate='phone'
                required
                oninput='inputValidate(); togglePaypalButton($loggedIn, $totalPrice);'
            />
            <label for='account-details__mail'>Email</label>
            <input
                id='account-details__mail'
                type='email'
                name='customerEmail'
                data-validate='email'
                required
                oninput='inputValidate(); togglePaypalButton($loggedIn, $totalPrice);'
            />
            <label for='account-details__password'
                >Password</label
            >
            <input
                id='account-details__password'
                type='password'
                name='customerPassword_init'
                data-validate='password'
                required
                oninput='inputValidate(); togglePaypalButton($loggedIn, $totalPrice);'
            />
            <label for='account-details__confirm-password'
                >Confirm Password</label
            >
            <input
                id='account-details__confirm-password'
                type='password'
                name='customerPasswordConfirm'
                data-validate='password'
                required
                oninput='inputValidate(); togglePaypalButton($loggedIn, $totalPrice);'
            />
            <h2 class='section-header'>
                Shipping/Billing address
            </h2>
            <label for='account-details__street-name'
                >Street name</label
            >
            <input
                id='account-details__street-name'
                type='text'
                name='companyStreet'
                data-validate='string'
                required
                oninput='inputValidate(); togglePaypalButton($loggedIn, $totalPrice);'
            />
            <label for='account-details__city'>City</label>
            <input
                id='account-details__city'
                type='text'
                data-validate='string'
                name='companyCity'
                required
                oninput='inputValidate(); togglePaypalButton($loggedIn, $totalPrice);'
            />
            <label for='account-details__zip-code'
                >Zip code</label
            >
            <input
                id='account-details__zip-code'
                type='text'
                data-validate='string'
                name='companyZip'
                required
                oninput='inputValidate(); togglePaypalButton($loggedIn, $totalPrice);'
            />
            <label for='account-details__zip-code'
                >Country</label
            >
            <input
                id='account-details__zip-code'
                type='text'
                data-validate='string'
                name='companyCountry'
                required
                oninput='inputValidate(); togglePaypalButton($loggedIn, $totalPrice);'
            />
            <div class='errorMessage'></div>
        </form>";
} else {
    $loggedIn = 'true';
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?= $head ?>
</head>

<body>
    <?= $header ?>
    <main id="cart" class="container-full-width">
        <section class="layout-container cart">
            <div class="checkout-container">
                <?= $content ?>
                <div class="order-summary-container">
                    <h2 class="section-header">Order summary</h2>
                    <div class="order-summary">
                        <div class="order-summary__header">
                            <div class="header-container">
                                <h5 class="header-item__product">Product</h5>
                                <h5 class="header-item__quantity">Quantity</h5>
                                <h5 class="header-item__price">Price</h5>
                            </div>
                        </div>
                        <div class="order-summary__body">
                            <div class="product-container">
                                <?= $products ?>
                                <?= $addons ?>
                                <div class="empty-cart">
                                    <p class="empty-cart__text">
                                        You don't have any products in
                                        the cart
                                    </p>
                                </div>
                            </div>

                        </div>
                        <div id='paypal-button-container' class="paypal-button-container">
                            <button class="order-summary__button button button--purple">
                                Accept and Purchase
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </section>

    </main>
    <?= $footer ?>
</body>
<script src="https://www.paypal.com/sdk/js?client-id=ASc0sohSJuv9IX6ovw_EQxA0uGoiQO5YxX2U7u9qnfZGwovsZ6Tylr1Arf0XOCAshoqqX8ApS3nkYpGy&currency=EUR&disable-funding=credit,card">
</script>
<script src="js/app.js"></script>
<script src="js/helper.js"></script>

<script>
    togglePaypalButton(<?= $loggedIn ?>, <?= $totalPrice ?>);
</script>