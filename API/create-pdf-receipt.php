<?php
session_start();
require '../CREATEPDF/fpdf.php';


/* $userSubmittedData = json_decode($_SESSION['postData']);

$email = $userSubmittedData->input_email;
$fName = $userSubmittedData->input_first_name;
$lName = $userSubmittedData->input_last_name;

$phone = $userSubmittedData->input_phone;
$companyName = $userSubmittedData->input_company_name;
$cvr = $userSubmittedData->input_company_cvr;
$street = $userSubmittedData->input_company_street;
$postCode = $userSubmittedData->input_company_Postcode;
$city = $userSubmittedData->input_company_city;
$country = $userSubmittedData->input_company_country; */

class PDF extends FPDF
{
    function Header()
    {
        $this->Image('../Assets/logo.png', 10, 6);
        $this->SetFont('Arial', 'B', 25);
        $this->Cell(0, 5, 'Receipt', 0, 0, 'R');
        $this->SetFont('Arial', 'B', 12);
        $this->Ln(30);
        $this->Write(5, 'Daniel Beck');
        $this->Cell(0, 5, 'Customer number: 12', 0, 0, 'R');
        $this->Ln(8);
        $this->Write(5, 'Street');
        $this->Cell(0, 5, 'Order number: 99', 0, 0, 'R');
        $this->Ln(8);
        $this->Write(5, 'Postcode - city');
        $this->Write(5, 'Country');
        $this->Ln(30);
    }
    function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'Page ' . $this->PageNo() . '/{nb}', 0, 0, 'R');
    }
    function content()
    {
        $this->SetFont('Arial', 'B', '18');
        $this->Cell(0, 5, 'Order details:', 0, 0, 'L');
        $this->SetFont('Arial', 'B', '14');
        $this->Ln(10);
        $this->Cell(100, 5, 'Products', 0, 0, 'L');
        $this->Cell(40, 5, 'Amount', 0, 0, 'L');
        $this->Cell(40, 5, 'Price', 0, 0, 'L');
        $this->Cell(40, 5, 'Total', 0, 0, 'L');
        $this->Ln(20);
        $this->Cell(100, 5, 'Addons', 0, 0, 'L');
        $this->Cell(40, 5, 'Amount', 0, 0, 'L');
        $this->Cell(40, 5, 'Price', 0, 0, 'L');
        $this->Cell(40, 5, 'Total', 0, 0, 'L');
        $this->Ln(15);
        $this->SetFont('Arial', 'B', '16');
        $this->Cell(20, 5, 'Total:', 0, 0, 'L');
        $this->Cell(30, 5, 'Price', 0, 0, 'L');
    }
}

$totalPrice = 0;
$productName = "";
$addonName = "";
$boughtAddons = "";

$receipt = new PDF();
$receipt->AliasNbPages();
$receipt->AddPage();
$receipt->content();
$receipt->Output();

// $receipt->Output('../Customer-receipts/customerID.pdf', 'F');

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