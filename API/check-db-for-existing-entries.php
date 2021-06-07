<?php
include_once("../db-connection/connection.php");

$_POST = json_decode(file_get_contents("php://input"), true); //make json object an assoc array
//Check to see if the correct data has been sent to the api, if it is not sent correctly, usually means someone is trying
//to enter from the browser and block it.

if (!isset($_POST['whatToCheck']) || !isset($_POST['data'])) {
    header('Location: ../index.php');
} else {

    //The 2 variables to check for in the database
    $sWhatTocheck = $_POST['whatToCheck'];
    $sData = $_POST['data'];
    //SQL querie with the 2 variables
    $sCheckSelectSql = "SELECT * FROM customers WHERE $sWhatTocheck = \"$sData\"";
    $oCheckResult = $oDbConnection->query($sCheckSelectSql);
    //If the database finds no match the number of rows will be 0
    if ($oCheckResult->num_rows > 0) {
        $aResponse = array("dataExists" => true, "error" => "none");
    } else {
        $aResponse = array("dataExists" => false, "error" => "none");
    }
}
//The response is send based on whether the database found a match or not and is used on the frontend
echo json_encode($aResponse);
