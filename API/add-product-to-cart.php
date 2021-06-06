<?php
session_start();
include("../db-connection/connection.php");

$_POST = json_decode(file_get_contents("php://input"), true); //make json object an assoc array from post data

if (isset($_POST['productId'])) {
    //If the product id is sent correctly, gather the data from database and create the variables
    $sSubscriptionId = $_POST['subscriptionId'];
    $sProductId = $_POST['productId'];
    $sProductSelectSql = "SELECT * FROM products WHERE product_id = \"$sProductId\"";
    $oProductResult = $oDbConnection->query($sProductSelectSql);
    $oProductRow = $oProductResult->fetch_object();
    $sProductName = $oProductRow->product_name;
    $nProductPrice = (float)$oProductRow->product_price;

    //Get the subscription data from the database
    $sSubscriptionSql = "SELECT * FROM subscriptions WHERE subscription_id = \"$sSubscriptionId\"";
    $oSubscriptionResult = $oDbConnection->query($sSubscriptionSql);
    $oSubscriptionRow = $oSubscriptionResult->fetch_object();
    $sSubscriptionName = $oSubscriptionRow->subscription_name;
    $nSubscriptionPrice = (float)$oSubscriptionRow->subscription_price;

    //Check to see if there is already a card products object in the session
    if (isset($_SESSION['cartProducts'])) {
        //If there is, add the new product to the array 
        $nProductCount = count($_SESSION['cartProducts']);
        $aProductArray = array(
            'productId' => $sProductId,
            'productName' => $sProductName,
            'productPrice' => $nProductPrice,
            'subscriptionId' => $sSubscriptionId,
            'subscriptionName' => $sSubscriptionName,
            'subscriptionPrice' => $nSubscriptionPrice
        );
        $_SESSION['cartProducts'][$nProductCount] = $aProductArray;
    } else {
        //If there is not a product array in session, create it, and add the product to it.
        $aProductArray = array(
            'productId' => $sProductId,
            'productName' => $sProductName,
            'productPrice' => $nProductPrice,
            'subscriptionId' => $sSubscriptionId,
            'subscriptionName' => $sSubscriptionName,
            'subscriptionPrice' => $nSubscriptionPrice
        );
        $_SESSION['cartProducts'][0] = $aProductArray;
    }
    $aResponse = array("itemAddedToCart" => true, "error" => "none");
//Returning some data for our javascript to use
    echo json_encode($aResponse);
}else{
    header("Location: ../index.php");
}

