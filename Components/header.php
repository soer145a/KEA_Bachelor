<?php


function headerComp()
{
    $cartCount = 0;
    if (isset($_SESSION['loginStatus'])) {

        $firstName = $_SESSION['customer_first_name'];
        $lastName = $_SESSION['customer_last_name'];
        $headerLinks = "
        <p>Hi $firstName $lastName</p>
        <br>
        <a href='index.php'>Front page</a>
        <a href='profile.php'>Profile</a>
        <a href='logout.php'>Logout</a>
        <a href='cart.php'>Cart</a>
        <a href='API/empty-cart.php'>Empty cart</a>
        ";
    } else {
        $headerLinks =
            "
        <a href='index.php'>Front page</a>
        <a href='login.php'>login</a>
        <a href='cart.php'>Cart</a>
        <a href='API/empty-cart.php'>Empty cart</a>
        ";
    }
    if (isset($_SESSION['cartProducts'])) {

        $cartCount = $cartCount + count($_SESSION['cartProducts']);
    }
    if (isset($_SESSION['cartAddOns'])) {

        $cartCount = $cartCount + count($_SESSION['cartAddOns']);
    }

    $headerContent = $headerLinks . "<p>You have $cartCount products in your cart</p>";

    return $headerContent;
}
