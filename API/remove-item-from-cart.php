<?php
session_start();
include("../db-connection/connection.php");

$_POST = json_decode(file_get_contents("php://input"), true); //make json object an assoc array

//When removing an item from the sessions we simply need the id of product and then remove it from the array
if (isset($_POST['itemId'])) {

    $sItemId = $_POST['itemId'];
    $bIsProduct = $_POST['isProduct'];
    if ($bIsProduct) {
        //If the selected item is a product
        foreach ($_SESSION['cartProducts'] as $key => $aProduct) {
            if ($aProduct['productId'] === $sItemId) {

                //removing from array
                unset($_SESSION['cartProducts'][$key]);
            }
        }
    } else {
        foreach ($_SESSION['cartAddOns'] as $key => $aAddon) {
            //If it is an addon
            if ($aAddon['addOnId'] === $sItemId) {

                //remove from array
                unset($_SESSION['cartAddOns'][$key]);
            }
        }
    }
    $aResponse = array("itemRemovedFromCart" => true, "error" => "none");
    //Returning some data for our javascript to use
    echo json_encode($aResponse);
} else {
    header("Location: ../index.php");
}
