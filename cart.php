<?php
session_start();
include_once("Components/product.php");
include_once("Components/header.php");
include("DB_Connection/connection.php");
$header = headerComp();

//productcards being printet starts here

$productCards = "";
$totalPrice = 0;
if (isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $product) {
        $totalPrice =  $totalPrice + (int)$product['product_price'];
        $productCards = $productCards . productComp($product['product_price'], $product['product_name'], $product['product_id'], true);
    }
} else {
    $productCards = "<strong>Nothing in cart</strong>";
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart</title>
    <link rel="stylesheet" href="css/app.css">
</head>

<body>
    <div><?= $header ?></div>
    <div>
        <h1>Cart</h1>
        <?= $productCards ?>
        <?= "Total: " . $totalPrice . " Eur" ?>
    </div>
    <div>
        <h1>Sign Up</h1>
        <form class="form signUpForm" method="post" onsubmit="return inputValidate()">
            <label>
                <p>Contact - First Name:</p>
                <input class="form__input" oninput="inputValidate(); printBtn();" data-validate="string" type="text" name="input_first_name" placeholder="John">
            </label>
            <label>
                <p>Contact - Last Name:</p>
                <input class="form__input" oninput="inputValidate(); printBtn();" data-validate="string" type="text" name="input_last_name" placeholder="Doe">
            </label>
            <label>
                <p>Contact - Email:</p>
                <input class="form__input" oninput="inputValidate(); printBtn();" data-validate="email" type="email" name="input_email" placeholder="example@email.com">

            </label>
            <label>
                <p>Company - Name:</p>
                <input class="form__input" oninput="inputValidate(); printBtn();" data-validate="string" type="text" name="input_company_name" placeholder="JohnDoe A/S">
            </label>
            <label>
                <p>Company - CVR:</p>
                <input class="form__input" oninput="inputValidate(); printBtn();" data-validate="cvr" type="text" name="input_company_cvr" placeholder="12345678">

            </label>
            <label>
                <p>Password: ( No special characters )</p>
                <input class="form__input" oninput="inputValidate(); printBtn();" data-validate="password" type="password" name="input_password_init" placeholder="MyStr0ng.PW-example">
            </label>
            <label>
                <p>Confirm Password:</p>
                <input class="form__input" oninput="inputValidate(); printBtn();" data-validate="password" type="password" name="input_password_confirm" placeholder="MyStr0ng.PW-example">

            </label>
            <div class="form__btnContainer">

            </div>
            <p><?= $errorMsg ?></p>
        </form>
    </div>
</body>
<script src="js/app.js"></script>
<script src="https://www.paypal.com/sdk/js?client-id=ASc0sohSJuv9IX6ovw_EQxA0uGoiQO5YxX2U7u9qnfZGwovsZ6Tylr1Arf0XOCAshoqqX8ApS3nkYpGy&currency=EUR&disable-funding=credit,card">
</script>
<script>
    function printBtn() {
        btnContainer = document.getElementsByClassName("form__btnContainer")[0];
        if (document.querySelectorAll(".valid").length !== 7) {
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