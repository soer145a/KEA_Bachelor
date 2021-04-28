<?php


function headerComp()
{

    if (isset($_SESSION['loginStatus'])) {

        $firstName = $_SESSION['customer_first_name'];
        $lastName = $_SESSION['customer_last_name'];
        $headerLinks = "<p>Hi $firstName $lastName</p> <br>
        <a href='profile.php'>Profile</a>
        <a href='logout.php'>Logout</a>
        <a href='cart.php'>Cart</a>
        <a href='API/empty-cart.php'>Empty cart</a>
        ";
    } else {
        $headerLinks = "<a href='login.php'>login</a>
        <a href='signup.php'>signup</a>
        <a href='cart.php'>Cart</a>
        <a href='empty-cart.php'>Empty cart</a>
        ";
    }
    if (isset($_SESSION['cart'])) {
        $count = count($_SESSION['cart']);
        $cartCount = "<p>You have $count products in your cart</p>";
    } else {
        $cartCount = "<p>You have 0 products in your cart</p>";
    }


    $headerContent = $headerLinks . $cartCount;

    echo $headerContent;
}
