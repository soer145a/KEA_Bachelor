<?php
session_start();
include("../DB_Connection/connection.php");

$_POST = json_decode(file_get_contents("php://input"), true); //make json object an assoc array


if (isset($_POST['productId'])) {
    $sSubscriptionId = $_POST['sSubscriptionId'];
    $sProductId = $_POST['productId'];
    $sProductSelectSql = "SELECT * FROM products WHERE product_id = \"$sProductId\"";
    $oProductResult = $oDbConnection->query($sProductSelectSql);
    $oProductRow = $oProductResult->fetch_object();
    $sProductName = $oProductRow->product_name;
    $nProductPrice = (float)$oProductRow->product_price;

    $sSubscriptionSql = "SELECT * FROM subscriptions WHERE subscription_id = \"$sSubscriptionId\"";
    $oSubscriptionResult = $oDbConnection->query($sSubscriptionSql);
    $oSubscriptionRow = $oSubscriptionResult->fetch_object();
    $sSubscriptionName = $oSubscriptionRow->subscription_name;
    $nSubscriptionPrice = (float)$oSubscriptionRow->subscription_price;

    if (isset($_SESSION['cartProducts'])) {

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
}
$response = array("error" => false);

echo json_encode($response);
