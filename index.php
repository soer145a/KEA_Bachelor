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
    <?php
    session_start();
    if (isset($_SESSION['loginStatus'])) {
        $firstName = $_SESSION['customer_first_name'];
        $lastName = $_SESSION['customer_last_name'];
        echo "Hi $firstName $lastName";
    } else {
        echo "fuck you";
    }
    ?>
    <a href="login.php">login</a>
    <a href="signup.php">signup</a>
    <div id="buyOptions">
        <div class="buyCard">
            <h2>Buy Option1</h2>
            <p>sample text</p>
            <button onclick="addToBasket(1)">Buy me</button>
        </div>
        <div class="buyCard">
            <h2>Buy Option2</h2>
            <p>sample text</p>
            <button onclick="addToBasket(2)">Buy me</button>
        </div>
        <div class="buyCard">
            <h2>Buy Option3</h2>
            <p>sample text</p>
            <button onclick="addToBasket(3)">Buy me</button>
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

</html>