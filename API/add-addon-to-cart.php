<?php
session_start();
include("../DB_Connection/connection.php");

$_POST = json_decode(file_get_contents("php://input"), true); //make json object an assoc array

if (isset($_POST['addOnId'])) {

    $sAddOnId = $_POST['addOnId'];
    $addOnAmount = $_POST['addOnAmount'];
    $sql = "SELECT * FROM addons WHERE addon_id = \"$sAddOnId\"";
    $result = $conn->query($sql);
    $row = $result->fetch_object();
    $addOnName = $row->addon_name;
    $addOnPrice = $row->addon_price;

    if (isset($_SESSION['cartAddOns'])) {

        $addOnsIdsArray = array_column($_SESSION['cartAddOns'], 'addOnId');
        $count = count($_SESSION['cartAddOns']);

        if (in_array($sAddOnId, $addOnsIdsArray)) {

            for ($i = 0; $i < $count; $i++) {

                if ($_SESSION['cartAddOns'][$i]['addOnId'] == $sAddOnId) {

                    $oldAmount = $_SESSION['cartAddOns'][$i]['addOnAmount'];
                    $newAmount = $addOnAmount + $oldAmount;
                    $_SESSION['cartAddOns'][$i]['addOnAmount'] = $newAmount;
                }
            }
        } else {

            $addOnArray = array(
                'addOnId' => $sAddOnId,
                'addOnName' => $addOnName,
                'addOnPrice' => $addOnPrice,
                'addOnAmount' => $addOnAmount
            );
            $_SESSION['cartAddOns'][$count] = $addOnArray;
        }
    } else {

        $addOnArray = array(
            'addOnId' => $sAddOnId,
            'addOnName' => $addOnName,
            'addOnPrice' => $addOnPrice,
            'addOnAmount' => $addOnAmount
        );
        $_SESSION['cartAddOns'][0] = $addOnArray;
    }
}

$response = array("error" => false);

echo json_encode($response);
