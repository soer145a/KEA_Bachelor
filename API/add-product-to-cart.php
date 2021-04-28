<?php
session_start();

if (isset($_POST['add_to_cart'])) {

    if (isset($_SESSION['cart'])) {

        $count = count($_SESSION['cart']);
        $productArray = array(
            'product_id' => $_POST['product_id'],
            'product_name' => $_POST['product_name'],
            'product_price' => $_POST['product_price']
        );
        $_SESSION['cart'][$count] = $productArray;
    } else {

        $productArray = array(
            'product_id' => $_POST['product_id'],
            'product_name' => $_POST['product_name'],
            'product_price' => $_POST['product_price']
        );
        $_SESSION['cart'][0] = $productArray;
    }
}

header('Location: ../index.php');
