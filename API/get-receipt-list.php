<?php
include_once("../db-connection/connection.php");
$_POST = json_decode(file_get_contents("php://input"), true); //make json object an assoc array
session_start();
if (!isset($_SESSION['loginStatus'])) {
    $aResponse = array("ordersReceived" => false, "error" => "You are not allowed here!");
    header("Location: ../index.php");
}
if (isset($_POST['firstDate']) && isset($_POST['secondDate'])) {
    $nFirstDate = strtotime($oDbConnection->real_escape_string($_POST['firstDate']));
    $nSecondDate = strtotime($oDbConnection->real_escape_string($_POST['secondDate'])) + 86400;
    $sCustomerId = $_SESSION['customerId'];
    $sOrderSelectSql = "SELECT * FROM orders WHERE customer_id = \"$sCustomerId\" AND order_date BETWEEN $nFirstDate AND $nSecondDate";
    $oOrderResult = $oDbConnection->query($sOrderSelectSql);

    $aReceiptsHtmlList = [];
    while ($oOrderRow = $oOrderResult->fetch_object()) {
        $aOrderDate = localtime($oOrderRow->order_date, true);
        $sOrderDateDay = $aOrderDate['tm_mday'];
        $sOrderDateMonth = $aOrderDate['tm_mon'] + 1;
        $sOrderDateYear = $aOrderDate['tm_year'] + 1900;
        $sOrderDateHour = $aOrderDate['tm_hour'];
        if (strlen($sOrderDateHour) == 1) {
            $sOrderDateHour = "0$sOrderDateHour";
        }
        $sOrderDateMin = $aOrderDate['tm_min'];
        if (strlen($sOrderDateMin) == 1) {
            $sOrderDateMin = "0$sOrderDateMin";
        }
        $sOrderDate = "$sOrderDateDay/$sOrderDateMonth/$sOrderDateYear - $sOrderDateHour:$sOrderDateMin";
        $sOrderDateFileName = "$sOrderDateDay-$sOrderDateMonth-$sOrderDateYear";
        array_push($aReceiptsHtmlList, "<li class='receipt-card__list-item'><p class='section-paragraph receipts-card__text'>$sOrderDate</p><a class='button button__small button__download' href='customer-receipts/$sCustomerId-$oOrderRow->order_id.pdf' download='Mirtual Order $oOrderRow->order_id - $sOrderDateFileName'><p>&#8676</p></a></li>");
    }

    $aResponse = array("ordersReceived" => true, "error" => "none", "results" => $oOrderResult->num_rows, "receiptList" => $aReceiptsHtmlList);
} else {
    $aResponse = array("ordersReceived" => false, "error" => "Missing dates");
}
echo json_encode($aResponse);
