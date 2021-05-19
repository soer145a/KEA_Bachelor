<?php
session_start();
require '../CREATEPDF/fpdf.php';

$userSubmittedData = json_decode($_SESSION['postData']);

$email = $userSubmittedData->input_email;
$fName = $userSubmittedData->input_first_name;
$lName = $userSubmittedData->input_last_name;

$phone = $userSubmittedData->input_phone;
$companyName = $userSubmittedData->input_company_name;
$cvr = $userSubmittedData->input_company_cvr;
$street = $userSubmittedData->input_company_street;
$postCode = $userSubmittedData->input_company_Postcode;
$city = $userSubmittedData->input_company_city;
$country = $userSubmittedData->input_company_country;

$totalPrice = 0;
$productName = "";
$addonName = "";
$boughtAddons = "";

$receipt = new FPDF();
$receipt->AddPage();
$receipt->Image('../Assets/logo.png', 10, 6);
$receipt->SetFont("Arial", "B", 20);
$receipt->Text(190, 10, "Mirtual");
$receipt->Output();





/* if (!isset($_SESSION['loginStatus'])) {
    $emailContentConfirm = str_replace("::USERNAME::", $name, $emailContentConfirm);
    $emailContentConfirm = str_replace("::confirmCode::", $_SESSION['confirmCode'], $emailContentConfirm);
}

foreach ($_SESSION['cartProducts'] as $product) {
    $productName = $product['product_name'] . ", " .  $productName;
    $totalPrice =  $totalPrice + (float)$product['product_price'];
}
foreach ($_SESSION['cartAddOns'] as $addon) {
    $addonName = $addon['addon_name'];
    $addonTotalprice = (float)$addon['addon_price'] * (float)$addon['addon_amount'];
    $boughtAddons = $boughtAddons . $addon['addon_amount'] . " x " . $addonName . ", ";
    $totalPrice =  $totalPrice + $addonTotalprice;
} */