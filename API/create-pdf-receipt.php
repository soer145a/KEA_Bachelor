<?php

require '../CREATEPDF/fpdf.php';
/* if (!isset($_SESSION['customerData'])) {
    header("Location: ../index.php");
} */
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

function createReceipt($iTimeEpoch)
{
    $sOrderId = $_SESSION['orderId'];
    $sCustomerId = $_SESSION['customerId'];
    $oCustomerData = json_decode($_SESSION['customerData']);
    $sCustomerEmail = $oCustomerData->customerEmail;
    $sCompanyName = $oCustomerData->companyName;
    $sCompanyCvr = $oCustomerData->companyCvr;
    $sCompanyStreet = $oCustomerData->companyStreet;
    $sCompanyZip = $oCustomerData->companyZip;
    $sCompanyCity = $oCustomerData->companyCity;
    $sCurrentDate = date('d/m/Y', $iTimeEpoch);
    $nTotalPrice = 0;
    $nAddonTotalprice = 0;

    $oCustomerReceipt = new PDF();
    $oCustomerReceipt->AliasNbPages();
    $oCustomerReceipt->AddPage();
    $oCustomerReceipt->Write(5, "$sCompanyName");
    $oCustomerReceipt->Cell(0, 5, "Customer number: $sCustomerId", 0, 0, 'R');
    $oCustomerReceipt->Ln(8);
    $oCustomerReceipt->Write(5, $sCompanyStreet);
    $oCustomerReceipt->Cell(0, 5, "Order number: $sOrderId", 0, 0, 'R');
    $oCustomerReceipt->Ln(8);
    $oCustomerReceipt->Write(5, "$sCompanyCity $sCompanyZip");
    $oCustomerReceipt->Cell(0, 5, "Date: $sCurrentDate", 0, 0, 'R');
    $oCustomerReceipt->Ln(8);
    $oCustomerReceipt->Write(5, $sCustomerEmail);
    $oCustomerReceipt->Ln(8);
    $oCustomerReceipt->Write(5, "Cvr: $sCompanyCvr");
    $oCustomerReceipt->Ln(30);
    $oCustomerReceipt->SetFont('Arial', 'B', '18');
    $oCustomerReceipt->Cell(0, 5, 'Order details:', 0, 0, 'L');
    $oCustomerReceipt->SetFont('Arial', 'B', '14');
    $oCustomerReceipt->Ln(10);

    if (isset($_SESSION['cartProducts'])) {
        $oCustomerReceipt->Cell(100, 5, 'Products', 0, 0, 'L');
        $oCustomerReceipt->Cell(40, 5, 'Amount', 0, 0, 'L');
        $oCustomerReceipt->Cell(40, 5, 'Price', 0, 0, 'L');
        $oCustomerReceipt->Cell(40, 5, 'Total', 0, 0, 'L');
        $oCustomerReceipt->SetFont('Arial', '', '12');
        $oCustomerReceipt->Ln(10);

        foreach ($_SESSION['cartProducts'] as $aProduct) {

            $sProductName = $aProduct['productName'];
            $nProductPrice = $aProduct['productPrice'];
            $nTotalPrice =  $nTotalPrice + $nProductPrice;

            $oCustomerReceipt->Cell(100, 5, $sProductName, 0, 0, 'L');
            $oCustomerReceipt->Cell(40, 5, 'x 1', 0, 0, 'L');
            $oCustomerReceipt->Cell(40, 5, $nProductPrice, 0, 0, 'L');
            $oCustomerReceipt->Cell(40, 5, $nProductPrice, 0, 0, 'L');
            $oCustomerReceipt->Ln(8);
        }
    }

    if (isset($_SESSION['cartAddOns'])) {
        $oCustomerReceipt->Ln(20);
        $oCustomerReceipt->SetFont('Arial', 'B', '14');
        $oCustomerReceipt->Cell(100, 5, 'Addons', 0, 0, 'L');
        $oCustomerReceipt->Cell(40, 5, 'Amount', 0, 0, 'L');
        $oCustomerReceipt->Cell(40, 5, 'Price', 0, 0, 'L');
        $oCustomerReceipt->Cell(40, 5, 'Total', 0, 0, 'L');
        $oCustomerReceipt->SetFont('Arial', '', '12');
        $oCustomerReceipt->Ln(10);

        foreach ($_SESSION['cartAddOns'] as $aAddon) {

            $sAddonName = $aAddon['addOnName'];
            $nAddonPrice = $aAddon['addOnPrice'];
            $nAddOnAmount = $aAddon['addOnAmount'];
            $nAddonTotalprice = $nAddonPrice * $nAddOnAmount;
            $nTotalPrice =  $nTotalPrice + $nAddonTotalprice;

            $oCustomerReceipt->Cell(100, 5, $sAddonName, 0, 0, 'L');
            $oCustomerReceipt->Cell(40, 5, "x $nAddOnAmount", 0, 0, 'L');
            $oCustomerReceipt->Cell(40, 5, $nAddonPrice, 0, 0, 'L');
            $oCustomerReceipt->Cell(40, 5, $nAddonTotalprice, 0, 0, 'L');
            $oCustomerReceipt->Ln(8);
        }
    }


    $oCustomerReceipt->Ln(10);
    $oCustomerReceipt->SetFont('Arial', 'B', '16');
    $oCustomerReceipt->Cell(20, 5, 'Total:', 0, 0, 'L');
    $oCustomerReceipt->Cell(30, 5, $nTotalPrice, 0, 0, 'L');

    $oCustomerReceipt->Output("../Customer-receipts/$sCustomerId-$sOrderId.pdf", 'F');
}
