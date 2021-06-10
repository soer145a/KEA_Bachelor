<?php
session_start();
$nTotalPrice = 0;
$bCartCheck = false;
if (isset($_SESSION['cartProducts'])){
    if (count($_SESSION['cartProducts']) > 0){
        
        if (isset($_SESSION['cartProducts'])) {
            $bCartCheck = true;
            foreach ($_SESSION['cartProducts'] as $aProduct) {
                $nProductPrice = $aProduct['productPrice'];
                $nTotalPrice =  $nTotalPrice + $aProduct['productPrice'];
            }
        }
    }
}
if(isset($_SESSION['cartAddOns'])) {
    if(count($_SESSION['cartAddOns']) > 0) {
        //If there are addons in the cart session, add them to the summery
        if (isset($_SESSION['cartAddOns'])) {
            $bCartCheck = true;
            foreach ($_SESSION['cartAddOns'] as $aAddon) {
                $nAddonAmount = $aAddon['addOnAmount'];
                $nAddonPrice = $aAddon['addOnPrice'];
                $nAddonTotalPrice = $nAddonAmount * $nAddonPrice;
                $nTotalPrice =  $nTotalPrice + $nAddonTotalPrice;
            }
        }
    } 
}
if($bCartCheck){
    $aResponse = array("priceReturned" => true, "priceTotal" => $nTotalPrice, "error" => "none");
}else{
    $aResponse = array("priceReturned" => false, "priceTotal" => 0, "error" => "No products!");
}

echo json_encode($aResponse);
