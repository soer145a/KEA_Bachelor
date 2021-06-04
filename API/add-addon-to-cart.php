<?php

session_start();
include("../DB_Connection/connection.php");

$_POST = json_decode(file_get_contents("php://input"), true); //make json object an assoc array

if (isset($_POST['addOnId'])) {

    $sAddOnId = $_POST['addOnId'];
    $nAddOnAmount = $_POST['addOnAmount'];
    $sAddOnSelectSql = "SELECT * FROM addons WHERE addon_id = \"$sAddOnId\"";
    $oAddOnResult = $oDbConnection->query($sAddOnSelectSql);
    $oAddOnRow = $oAddOnResult->fetch_object();
    $sAddOnName = $oAddOnRow->addon_name;
    $nAddOnPrice = (float)$oAddOnRow->addon_price;

    if (isset($_SESSION['cartAddOns'])) {

        $aAddOnIdArray = array_column($_SESSION['cartAddOns'], 'addOnId');
        $nAddOnCount = count($_SESSION['cartAddOns']);

        if (in_array($sAddOnId, $aAddOnIdArray)) {

            for ($i = 0; $i < $nAddOnCount; $i++) {

                if ($_SESSION['cartAddOns'][$i]['addOnId'] == $sAddOnId) {

                    $nOldAddonAmount = $_SESSION['cartAddOns'][$i]['addOnAmount'];
                    $nNewAddonAmount = $nAddOnAmount + $nOldAddonAmount;
                    $_SESSION['cartAddOns'][$i]['addOnAmount'] = $nNewAddonAmount;
                }
            }
        } else {
            $aAddOnArray = array(
                'addOnId' => $sAddOnId,
                'addOnName' => $sAddOnName,
                'addOnPrice' => $nAddOnPrice,
                'addOnAmount' => $nAddOnAmount
            );
            $_SESSION['cartAddOns'][$nAddOnCount] = $aAddOnArray;
        }
    } else {

        $aAddOnArray = array(
            'addOnId' => $sAddOnId,
            'addOnName' => $sAddOnName,
            'addOnPrice' => $nAddOnPrice,
            'addOnAmount' => $nAddOnAmount
        );
        $_SESSION['cartAddOns'][0] = $aAddOnArray;
    }
}else{
   header('Location: index.php');
}
$response = array("error" => false);
echo json_encode($response);
