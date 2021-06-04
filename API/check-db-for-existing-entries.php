<?php
include_once("../DB_Connection/connection.php");

$_POST = json_decode(file_get_contents("php://input"), true); //make json object an assoc array

if (isset($_POST['data'])) {

    $sWhatTocheck = $_POST['whatToCheck'];
    $sData = $_POST['data'];

    $sCheckSelectSql = "SELECT * FROM customers WHERE $sWhatTocheck = \"$sData\"";
    $oCheckResult = $oDbConnection->query($sCheckSelectSql);

    if ($oCheckResult->num_rows > 0) {
        $aResponse = array("dataExists" => true);
    } else {
        $aResponse = array("dataExists" => false);
    }
} else {
    $aResponse = array("error" => true);
}

echo json_encode($aResponse);
