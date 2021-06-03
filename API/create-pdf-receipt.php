<?php
session_start();
require '../CREATEPDF/fpdf.php';

class PDF extends FPDF
{
    function Header()
    {
        $this->Image('../assets/logo.png', 10, 6);
        $this->SetFont('Arial', 'B', 25);
        $this->Cell(0, 5, 'Receipt', 0, 0, 'R');
        $this->SetFont('Arial', 'B', 12);
        $this->Ln(30);
    }
    function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'Page ' . $this->PageNo() . '/{nb}', 0, 0, 'R');
    }
}

function createReceipt($time)
{
    $orderId = $_SESSION['orderId'];
    $customerId = $_SESSION['customer_id'];
    $userSubmittedData = json_decode($_SESSION['postData']);
    $email = $userSubmittedData->input_email;
    $companyName = $userSubmittedData->input_company_name;
    $cvr = $userSubmittedData->input_company_cvr;
    $street = $userSubmittedData->input_company_street;
    $postCode = $userSubmittedData->input_company_Postcode;
    $city = $userSubmittedData->input_company_city;
    $currentDate = date('d/m/Y', $time);
    $totalPrice = 0;
    $addonTotalprice = 0;

    $receipt = new PDF();
    $receipt->AliasNbPages();
    $receipt->AddPage();
    $receipt->Write(5, "$companyName");
    $receipt->Cell(0, 5, "Customer number: $customerId", 0, 0, 'R');
    $receipt->Ln(8);
    $receipt->Write(5, $street);
    $receipt->Cell(0, 5, "Order number: $orderId", 0, 0, 'R');
    $receipt->Ln(8);
    $receipt->Write(5, "$city $postCode");
    $receipt->Cell(0, 5, "Date: $currentDate", 0, 0, 'R');
    $receipt->Ln(8);
    $receipt->Write(5, $email);
    $receipt->Ln(8);
    $receipt->Write(5, "Cvr: $cvr");
    $receipt->Ln(30);
    $receipt->SetFont('Arial', 'B', '18');
    $receipt->Cell(0, 5, 'Order details:', 0, 0, 'L');
    $receipt->SetFont('Arial', 'B', '14');
    $receipt->Ln(10);
    $receipt->Cell(100, 5, 'Products', 0, 0, 'L');
    $receipt->Cell(40, 5, 'Amount', 0, 0, 'L');
    $receipt->Cell(40, 5, 'Price', 0, 0, 'L');
    $receipt->Cell(40, 5, 'Total', 0, 0, 'L');
    $receipt->SetFont('Arial', '', '12');
    $receipt->Ln(10);

    foreach ($_SESSION['cartProducts'] as $product) {

        $productName = $product['productName'];
        $productPrice = $product['productPrice'];
        $totalPrice =  $totalPrice + (float)$productPrice;

        $receipt->Cell(100, 5, $productName, 0, 0, 'L');
        $receipt->Cell(40, 5, 'x 1', 0, 0, 'L');
        $receipt->Cell(40, 5, $productPrice, 0, 0, 'L');
        $receipt->Cell(40, 5, $productPrice, 0, 0, 'L');
        $receipt->Ln(8);
    }

    $receipt->Ln(20);
    $receipt->SetFont('Arial', 'B', '14');
    $receipt->Cell(100, 5, 'Addons', 0, 0, 'L');
    $receipt->Cell(40, 5, 'Amount', 0, 0, 'L');
    $receipt->Cell(40, 5, 'Price', 0, 0, 'L');
    $receipt->Cell(40, 5, 'Total', 0, 0, 'L');
    $receipt->SetFont('Arial', '', '12');
    $receipt->Ln(10);

    foreach ($_SESSION['cartAddOns'] as $addon) {

        $addonName = $addon['addOnName'];
        $addonPrice = (float)$addon['addOnPrice'];
        $nAddOnAmount = (float)$addon['addOnAmount'];
        $addonTotalprice = $addonPrice * $nAddOnAmount;
        $totalPrice =  $totalPrice + $addonTotalprice;

        $receipt->Cell(100, 5, $addonName, 0, 0, 'L');
        $receipt->Cell(40, 5, "x $nAddOnAmount", 0, 0, 'L');
        $receipt->Cell(40, 5, $addonPrice, 0, 0, 'L');
        $receipt->Cell(40, 5, $addonTotalprice, 0, 0, 'L');
        $receipt->Ln(8);
    }
    $receipt->Ln(10);
    $receipt->SetFont('Arial', 'B', '16');
    $receipt->Cell(20, 5, 'Total:', 0, 0, 'L');
    $receipt->Cell(30, 5, $totalPrice, 0, 0, 'L');

    $receipt->Output("../Customer-receipts/$customerId-$orderId.pdf", 'F');
}
