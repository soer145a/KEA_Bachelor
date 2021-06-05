<?php
//Emties the cart data and resets the purchases
session_start();
unset($_SESSION['cartAddOns']);
unset($_SESSION['cartProducts']);
header('Location: index.php');
