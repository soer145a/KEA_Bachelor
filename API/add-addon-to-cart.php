<?php
session_start();
include("../DB_Connection/connection.php");

$_POST = json_decode(file_get_contents("php://input"), true); //make json object an assoc array

if (isset($_POST['addOnId'])) {
    //Gather the information for what the addon contains
    $sAddOnId = $_POST['addOnId'];
    $nAddOnAmount = $_POST['addOnAmount'];
    $sAddOnSelectSql = "SELECT * FROM addons WHERE addon_id = \"$sAddOnId\"";
    $oAddOnResult = $oDbConnection->query($sAddOnSelectSql);
    $oAddOnRow = $oAddOnResult->fetch_object();
    $sAddOnName = $oAddOnRow->addon_name;
    $nAddOnPrice = (float)$oAddOnRow->addon_price;

    if (isset($_SESSION['cartAddOns'])) {
        //Check to see if there is already a cart addon object that we can add to
        $aAddOnIdArray = array_column($_SESSION['cartAddOns'], 'addOnId');
        $nAddOnCount = count($_SESSION['cartAddOns']);

        if (in_array($sAddOnId, $aAddOnIdArray)) {

            for ($i = 0; $i < $nAddOnCount; $i++) {

                if ($_SESSION['cartAddOns'][$i]['addOnId'] == $sAddOnId) {
                    //Adding the new addon to the existing cartaddon array
                    $nOldAddonAmount = $_SESSION['cartAddOns'][$i]['addOnAmount'];
                    $nNewAddonAmount = $nAddOnAmount + $nOldAddonAmount;
                    $_SESSION['cartAddOns'][$i]['addOnAmount'] = $nNewAddonAmount;
                }
            }
        } else {
            //If there is not a copy of addon in array, create one, and add it to that
            $aAddOnArray = array(
                'addOnId' => $sAddOnId,
                'addOnName' => $sAddOnName,
                'addOnPrice' => $nAddOnPrice,
                'addOnAmount' => $nAddOnAmount
            );
            $_SESSION['cartAddOns'][$nAddOnCount] = $aAddOnArray;
        }
    } else {
            //If there isn't a cartaddon array, create one, and add it to that
        $aAddOnArray = array(
            'addOnId' => $sAddOnId,
            'addOnName' => $sAddOnName,
            'addOnPrice' => $nAddOnPrice,
            'addOnAmount' => $nAddOnAmount
        );
        $_SESSION['cartAddOns'][0] = $aAddOnArray;
    }
}
//If failed, display why and echo the data/response
$response = array("error" => false);
echo json_encode($response);
