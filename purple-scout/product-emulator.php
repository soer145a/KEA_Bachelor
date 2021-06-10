<?php

include_once("../db-connection/connection.php");
//The frame of the purplescout product

//The key that the customer enter in their url
if (!isset($_GET['key'])) {
    exit();
}
$key = $_GET['key'];
$sCustomerProductSelectSql = "SELECT * FROM customer_products WHERE api_key = \"$key\"";
//Get the product subscription data from the database
$oCustomerProductResult = $oDbConnection->query($sCustomerProductSelectSql);
$oCustomerProductRow = $oCustomerProductResult->fetch_object();
if ($oCustomerProductRow->subscription_active) {
    //If the subscription is active
    $nSubscriptionTimeLeft = $oCustomerProductRow->subscription_end - time();
    $nSubscriptionDaysLeft = round($nSubscriptionTimeLeft / 86400);
    if ($nSubscriptionDaysLeft <= 0) {
        //If the amount of days left of the subscription hits zero, we deactive their subscription
        $sCustomerProductUpdateSql = "UPDATE customer_products SET subscription_active = 0 WHERE api_key = \"$key\"";
        $oDbConnection->query($sCustomerProductUpdateSql);
        $oCustomerProductRow->subscription_active = 0;
        //Simple error message returned
        echo "LICENSE EXPIRED";
    } else {
        //The product of PurpleScout goes here, but at the moment we simply display this "correct" message
        echo "Here is the product";
    }
} else {
    //if the product is expired
    echo "LICENSE EXPIRED";
}
