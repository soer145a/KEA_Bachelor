<?php
session_start();
if (isset($_SESSION['cartProducts']) || isset($_SESSION['cartAddOns'])) {
    if (count($_SESSION['cartProducts']) <= 0 && count($_SESSION['cartAddOns']) <= 0) {
        $aResponse = array("priceReturned" => false, "priceTotal" => 0, "error" => "no products in cart");
    } else {
        $nTotalPrice = 0;
        if (isset($_SESSION['cartProducts'])) {
            foreach ($_SESSION['cartProducts'] as $aProduct) {
                $nProductPrice = $aProduct['productPrice'];
                $nTotalPrice =  $nTotalPrice + $aProduct['productPrice'];
            }
        }

        //If there are addons in the cart session, add them to the summery
        if (isset($_SESSION['cartAddOns'])) {
            foreach ($_SESSION['cartAddOns'] as $aAddon) {
                $nAddonAmount = $aAddon['addOnAmount'];
                $nAddonPrice = $aAddon['addOnPrice'];
                $nAddonTotalPrice = $nAddonAmount * $nAddonPrice;
                $nTotalPrice =  $nTotalPrice + $nAddonTotalPrice;
            }
        }
        $aResponse = array("priceReturned" => true, "priceTotal" => $nTotalPrice, "error" => "none");
    }
} else {
    $aResponse = array("priceReturned" => false, "priceTotal" => 0, "error" => "no products in cart");
}

echo json_encode($aResponse);
