<?php
session_start();
include("../DB_Connection/connection.php");

$_POST = json_decode(file_get_contents("php://input"), true); //make json object an assoc array

if (isset($_POST['addon_id'])) {

    $addOnId = $_POST['addon_id'];
    $addOnAmount = $_POST['addon_amount'];
    $sql = "SELECT * FROM addons WHERE addon_id = \"$addOnId\"";
    $result = $conn->query($sql);
    $row = $result->fetch_object();
    $addOnName = $row->addon_name;
    $addOnPrice = $row->addon_price;

    if (isset($_SESSION['cartAddOns'])) {

        $addOnsIdsArray = array_column($_SESSION['cartAddOns'], 'addon_id');
        $count = count($_SESSION['cartAddOns']);

        if (in_array($addOnId, $addOnsIdsArray)) {

            for ($i = 0; $i < $count; $i++) {

                if ($_SESSION['cartAddOns'][$i]['addon_id'] == $addOnId) {

                    $oldAmount = $_SESSION['cartAddOns'][$i]['addon_amount'];
                    $newAmount = $addOnAmount + $oldAmount;
                    $_SESSION['cartAddOns'][$i]['addon_amount'] = $newAmount;
                }
            }
        } else {

            $addOnArray = array(
                'addon_id' => $addOnId,
                'addon_name' => $addOnName,
                'addon_price' => $addOnPrice,
                'addon_amount' => $addOnAmount
            );
            $_SESSION['cartAddOns'][$count] = $addOnArray;
        }
    } else {

        $addOnArray = array(
            'addon_id' => $addOnId,
            'addon_name' => $addOnName,
            'addon_price' => $addOnPrice,
            'addon_amount' => $addOnAmount
        );
        $_SESSION['cartAddOns'][0] = $addOnArray;
    }
}

$response = array("error" => false);

echo json_encode($response);
