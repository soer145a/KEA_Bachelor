<?php
session_start();
include("../DB_Connection/connection.php");

$_POST = json_decode(file_get_contents("php://input"), true); //make json object an assoc array
//When removing an item from the sessions we simply need the id of product and then remove it from the array
if (isset($_POST['itemId'])) {

    $itemId = $_POST['itemId'];
    $isProduct = $_POST['isProduct'];
    if ($isProduct) {
        //If the selected item is a product
        foreach ($_SESSION['cartProducts'] as $key => $product) {
            if ($product['productId'] === $itemId) {
                $response = array("line 14" => $key);
                //removing from array
                unset($_SESSION['cartProducts'][$key]);
            }
        }
    } else {
        foreach ($_SESSION['cartAddOns'] as $key => $addon) {
            //If it is an addon
            if ($addon['addOnId'] === $itemId) {
                $response = array("line 21" => $key);
                //remove from array
                unset($_SESSION['cartAddOns'][$key]);
            }
        }
    }
}
//Respond back
echo json_encode($response);
