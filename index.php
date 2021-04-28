<?php
include_once("DB_Connection/connection.php");
include_once("header.php");


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
    <h1>Initial Page</h1>
    <div id="buyOptions">
        <div class="buyCard">
            <h2>Buy Option1</h2>
            <p>sample text</p>
            <!-- <button onclick="addToBasket(1)">Buy me</button> -->
            <div id="paypal-button-container"></div>
        </div>
        <div class="buyCard">
            <h2>Buy Option2</h2>
            <p>sample text</p>
            <!--   <button onclick="addToBasket(2)">Buy me</button> -->
            <div id="paypal-button-container"></div>
        </div>
        <div class="buyCard">
            <h2>Buy Option3</h2>
            <p>sample text</p>
            <!--  <button onclick="addToBasket(3)">Buy me</button> -->
            <div id="paypal-button-container"></div>
        </div>
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
<script src="https://www.paypal.com/sdk/js?client-id=ASc0sohSJuv9IX6ovw_EQxA0uGoiQO5YxX2U7u9qnfZGwovsZ6Tylr1Arf0XOCAshoqqX8ApS3nkYpGy&currency=EUR">
</script>
<script>
    paypal.Buttons({
        style: {
            color: 'blue',
            shape: 'pill'
        },
        createOrder: function(data, actions) {
            return actions.order.create({
                purchase_units: [{
                    amount: {
                        value: '100'
                    }
                }]
            });
        },
        onApprove: function(data, actions) {
            return actions.order.capture().then(function(details) {
                alert('Transaction completed by ' + details.payer.name.given_name);
            });
        }
    }).render('#paypal-button-container');
</script>