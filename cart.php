<?php
session_start();
include_once("Components/product.php");
include_once("Components/header.php");
$header = headerComp();

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
    <h1>Cart</h1>
    <div id="paypal-button-container"></div>
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