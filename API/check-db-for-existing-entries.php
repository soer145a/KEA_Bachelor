<?php
include_once("../DB_Connection/connection.php");

$_POST = json_decode(file_get_contents("php://input"), true); //make json object an assoc array

if (isset($_POST['data'])) {

    $sWhatTocheck = $_POST['whatToCheck'];
    $sData = $_POST['data'];

    $sCheckSelectSql = "SELECT * FROM customers WHERE $sWhatTocheck = \"$sData\"";
    $oCheckResult = $conn->query($sCheckSelectSql);

    if ($oCheckResult->num_rows > 0) {
        $response = array("dataExists" => true);
    } else {
        $response = array("dataExists" => false);
    }
} else {
    $response = array("error" => true);
}

echo json_encode($response);
