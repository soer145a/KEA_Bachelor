<?php
session_start();

include_once("Components/header.php");
include_once("Components/head.php");
include("DB_Connection/connection.php");
include_once("Components/addOn.php");
$head = headComp();
$header = headerComp('cart');

//productcards being printet starts here
$content = "";
$productCards = "";
$addOnCards = "";
$totalPrice = 0;

if (isset($_SESSION['cartProducts'])) {
    foreach ($_SESSION['cartProducts'] as $product) {
        $totalPrice =  $totalPrice + (float)$product['product_price'];
        $productCards = $productCards . $product['product_name'] . ' €' . $product['product_price'] . '<br>';
    }
} else {
    $productCards = "<strong>No products in cart</strong>";
}
if (isset($_SESSION['cartAddOns'])) {
    foreach ($_SESSION['cartAddOns'] as $addon) {
        $addonTotalprice = (float)$addon['addon_price'] * (float)$addon['addon_amount'];
        $totalPrice =  $totalPrice + $addonTotalprice;
        $addOnCards = $addOnCards . $addon['addon_amount'] . ' x ' . $addon['addon_name'] . '  €' . $addon['addon_price'] . '<br>';
    }
} else {
    $addOnCards = "<strong>No addons in cart</strong>";
}

if (!isset($_SESSION['loginStatus'])) {
    $content = '<div>
    <h1>Sign Up</h1> <a href="login.php">Already a customer? - login here</a>
    <form class="form signUpForm" method="post" action="API/payment-handler.php">
        <label>
            <p>Contact - First Name:</p>
            <input class="form__input" oninput="inputValidate(); printBtnValidate();" data-validate="string" type="text" name="input_first_name" placeholder="John">
        </label>
        <label>
            <p>Contact - Last Name:</p>
            <input class="form__input" oninput="inputValidate(); printBtnValidate();" data-validate="string" type="text" name="input_last_name" placeholder="Doe">
        </label>
        <label>
            <p>Company - Street:</p>
            <input class="form__input" oninput="inputValidate(); printBtnValidate();" data-validate="string" type="text" name="input_company_street" placeholder="John Doe Lane 35A">
        </label>        
        <label>
            <p>Company - City:</p>
            <input class="form__input" oninput="inputValidate(); printBtnValidate();" data-validate="string" type="text" name="input_company_city" placeholder="London">
        </label>
        <label>
            <p>Company - Postcode:</p>
            <input class="form__input" oninput="inputValidate(); printBtnValidate();" data-validate="string" type="text" name="input_company_Postcode" placeholder="SW1W 0NY">
        </label>
        <label>
            <p>Company - country:</p>
            <input class="form__input" oninput="inputValidate(); printBtnValidate();" data-validate="string" type="text" name="input_company_country" placeholder="England">
        </label>
        <label>
            <p>Contact - Email:</p>
            <input class="form__input" oninput="inputValidate(); printBtnValidate();" data-validate="email" type="email" name="input_email" placeholder="example@email.com">
        </label>
        <label>
            <p>Contact - Phone:</p>
            <input class="form__input" oninput="inputValidate(); printBtnValidate();" data-validate="phone" type="text" name="input_phone" placeholder="+4511223344">
        </label>
        <label>
            <p>Company - Name:</p>
            <input class="form__input" oninput="inputValidate(); printBtnValidate();" data-validate="string" type="text" name="input_company_name" placeholder="JohnDoe A/S">
        </label>
        <label>
            <p>Company - CVR:</p>
            <input class="form__input" oninput="inputValidate(); printBtnValidate();" data-validate="cvr" type="text" name="input_company_cvr" placeholder="12345678">
        </label>
        <label>
            <p>Password:</p>
            <input class="form__input" oninput="inputValidate(); printBtnValidate();" data-validate="password" type="password" name="input_password_init" placeholder="MyStr0ng.PW-example">
        </label>
        <label>
            <p>Confirm Password:</p>
            <input class="form__input" oninput="inputValidate(); printBtnValidate();" data-validate="password" type="password" name="input_password_confirm" placeholder="MyStr0ng.PW-example">
        </label>
        <div class="errorMessage"></div>
        <div class="form__btnContainer">
        </div>
    </form>
</div>';
    $loggedIn = 'false';
} else {
    $loggedIn = 'true';
    $content = "<div id='paypal-button-container'></div>";
}

//echo json_encode($_POST);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?= $head ?>
</head>

<body>
    <?= $header ?>
    <div>
        <h1>Cart</h1>
        <p>products</p>
        <?= $productCards ?>
        <p>add-ons</p>
        <?= $addOnCards ?>
        <?= "Total: " . $totalPrice . " Eur" ?>
    </div>
    <?= $content ?>
</body>
<script src="js/app.js"></script>
<script src="https://www.paypal.com/sdk/js?client-id=ASc0sohSJuv9IX6ovw_EQxA0uGoiQO5YxX2U7u9qnfZGwovsZ6Tylr1Arf0XOCAshoqqX8ApS3nkYpGy&currency=EUR&disable-funding=credit,card">
</script>
<script>
    if (<?= $loggedIn ?>) {
        paypal.Buttons({
            style: {
                color: 'blue',
                shape: 'pill',
            },
            createOrder: function(data, actions) {
                return actions.order.create({
                    purchase_units: [{
                        amount: {
                            value: <?= $totalPrice ?>
                        }
                    }]
                });
            },
            onApprove: function(data, actions) {
                return actions.order.capture().then(function(PurchaseDetails) {
                    window.location.assign(window.location.protocol + "/KEA_Bachelor/API/payment-handler.php");
                });
            }
        }).render('#paypal-button-container');
    }

    function printBtnValidate() {
        console.log("Fire");
        btnContainer = document.getElementsByClassName("form__btnContainer")[0];
        if (document.querySelectorAll(".valid").length !== 12) {
            btnContainer.innerHTML = "<p>What would cause you not to fill out all the fields in the form?</p>";
        } else {
            console.log("It does work");
            btnContainer.innerHTML = "<div id='paypal-button-container'></div>";
            paypal.Buttons({
                style: {
                    color: 'blue',
                    shape: 'pill',
                },
                createOrder: function(data, actions) {
                    return actions.order.create({
                        purchase_units: [{
                            amount: {
                                value: <?= $totalPrice ?>
                            }
                        }]
                    });
                },
                onApprove: function(data, actions) {
                    return actions.order.capture().then(function(PurchaseDetails) {
                        document.getElementsByClassName('signUpForm')[0].submit();
                    });
                }
            }).render('#paypal-button-container');
        }
    }
</script>