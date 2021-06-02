<?php
session_start();
include("../DB_Connection/connection.php");

$_POST = json_decode(file_get_contents("php://input"), true); //make json object an assoc array

if (isset($_POST['itemId'])) {
    $itemId = $_POST['itemId'];
    $isProduct = $_POST['isProduct'];
    if ($isProduct) {

        foreach ($_SESSION['cartProducts'] as $key => $product) {
            if ($product['product_id'] === $itemId) {
                $response = array("line 14" => $key);
                unset($_SESSION['cartProducts'][$key]);
            }
        }
    } else {
        foreach ($_SESSION['cartAddOns'] as $key => $addon) {
            if ($addon['addon_id'] === $itemId) {
                $response = array("line 21" => $key);
                unset($_SESSION['cartAddOns'][$key]);
            }
        }
    }
};

echo json_encode($response);
