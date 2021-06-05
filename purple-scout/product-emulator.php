<?php

include_once("../db-connection/connection.php");
//echo $_GET['key'];
$key = $_GET['key'];
$sCustomerProductSelectSql = "SELECT * FROM customer_products WHERE api_key = \"$key\"";
$oCustomerProductResult = $oDbConnection->query($sCustomerProductSelectSql);
$oCustomerProductRow = $oCustomerProductResult->fetch_object();
//echo $row->subscription_active;
if ($oCustomerProductRow->subscription_active) {

    $nSubscriptionTimeLeft = $oCustomerProductRow->subscription_end - time();
    //echo $reduceTotalAmount/86400;
    $nSubscriptionDaysLeft = round($nSubscriptionTimeLeft / 86400);
    //echo $totalDaysRemaining;   
    if ($nSubscriptionDaysLeft <= 0) {
        //echo "LICENSE RAN OUT";
        $sCustomerProductUpdateSql = "UPDATE customer_products SET subscription_active = 0 WHERE api_key = \"$key\"";
        $oDbConnection->query($sCustomerProductUpdateSql);
        $oCustomerProductRow->subscription_active = 0;
        echo "LICENSE EXPIRED";
    } else {
        echo "Here is the product";
    }
} else {
    echo "LICENSE EXPIRED";
}
