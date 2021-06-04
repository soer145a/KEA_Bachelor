<?php

include_once("../DB_Connection/connection.php");
//echo $_GET['key'];
$key = $_GET['key'];
$sql = "SELECT * FROM customer_products WHERE api_key = \"$key\"";
$results = $oDbConnection->query($sql);
$row = $results->fetch_object();
//echo $row->subscription_active;
if($row->subscription_active){
    
    $reduceTotalAmount = $row->subscription_end - time();
    //echo $reduceTotalAmount/86400;
    $totalDaysRemaining = round($reduceTotalAmount / 86400);
    //echo $totalDaysRemaining;   
    if($totalDaysRemaining < 0){
        //echo "LICENSE RAN OUT";
        $sql = "UPDATE customer_products SET subscription_active = 0 WHERE api_key = \"$key\"";
        $oDbConnection->query($sql);
        $row->subscription_active = 0;
        $totalDaysRemaining = 0;
    }    
}
if($row->subscription_active){
    //ADD PURPLESCOUT EMBED HERE FOR THE CLIENT
    echo "HERE IS THE PRODUCT";
}else{
    echo "LICENSE EXPIRED";
}