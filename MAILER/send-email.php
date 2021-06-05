<?php
session_start();

// Import PHPMailer classes into the global namespace
// These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'Exception.php';
require 'PHPMailer.php';
require 'SMTP.php';

// Load Composer's autoloader
//require 'vendor/autoload.php';
$oCustomerData = json_decode($_SESSION['customerData']);
$sCustomerId = $_SESSION['customerId'];
$sOrderId = $_SESSION['orderId'];

$sCustomerEmail = $oCustomerData->customerEmail;
$sCustomerFirstName = $oCustomerData->customerFirstName;
$sCustomerLastName = $oCustomerData->customerLastName;

//Get the emails
$sOrderEmailContent = file_get_contents("orderEmail.php");
$sConfirmEmailContent = file_get_contents("confirmEmail.php");
$sCustomerName = "$sCustomerFirstName $sCustomerLastName";
$nTotalPrice = 0;
$sProductName = "";
$sAddonName = "";
$sBoughtAddons = "";
//If the user is not logged in, alter the emails
if (!isset($_SESSION['loginStatus'])) {
    $sConfirmEmailContent = str_replace("::USERNAME::", $sCustomerName, $sConfirmEmailContent);
    $sConfirmEmailContent = str_replace("::CONFIRMCODE::", $_SESSION['customerConfirmCode'], $sConfirmEmailContent);
}
//Add the bought products to the email
foreach ($_SESSION['cartProducts'] as $product) {
    $sProductName = $product['productName'] . ", " .  $sProductName;
    $nTotalPrice =  $nTotalPrice + $product['productPrice'];
}
//Add the bought addons to the email
foreach ($_SESSION['cartAddOns'] as $addon) {
    $sAddonName = $addon['addOnName'];
    $nAddonTotalprice = $addon['addOnPrice'] * $addon['addOnAmount'];
    $sBoughtAddons = $sBoughtAddons . $addon['addOnAmount'] . " x " . $sAddonName . ", ";
    $nTotalPrice =  $nTotalPrice + $nAddonTotalprice;
}
//replace the strings in the email
$sOrderEmailContent = str_replace("::USERNAME::", $sCustomerName, $sOrderEmailContent);
$sOrderEmailContent = str_replace("::ORDERPRODUCT::", $sProductName, $sOrderEmailContent);
$sOrderEmailContent = str_replace("::ORDERADDONS::", $sBoughtAddons, $sOrderEmailContent);
$sOrderEmailContent = str_replace("::ORDERPRICE::", $nTotalPrice, $sOrderEmailContent);

$oMail = new PHPMailer(true);

try {
    //Server settings
    $oMail->SMTPDebug = SMTP::DEBUG_SERVER;                      // Enable verbose debug output
    $oMail->isSMTP();                                            // Send using SMTP
    $oMail->Host       = 'smtp.gmail.com';                       // Set the SMTP server to send through
    $oMail->SMTPAuth   = true;                                   // Enable SMTP authentication
    $oMail->Username   = 'cookbook.kea@gmail.com';              // SMTP username
    $oMail->Password   = 'soer145a';                             // SMTP password

    //$oMail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` also accepted
    $oMail->SMTPSecure = 'tls';         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` also accepted
    $oMail->Port       = 587;                                    // TCP port to connect to

    
    //Recipients
    $oMail->setFrom('Mirtual@purplescout.com', 'Mirtual');
    $oMail->addAddress("$sCustomerEmail", "$sCustomerName");     // Add a recipient   
    $oMail->addReplyTo('Mirtual@purplescout.com', 'Information');

    // Attachments

    // Content
    $oMail->addAttachment("../Customer-receipts/$sCustomerId-$sOrderId.pdf");
    $oMail->isHTML(true);                                  // Set email format to HTML
    $oMail->Subject = "Mirtual order";
    $oMail->Body = $sOrderEmailContent;
    $oMail->send();
    //If the user is not logged in, send the customer confirm email
    if (!isset($_SESSION['loginStatus'])) {
        $oMail->clearAttachments();
        $oMail->isHTML(true);                                  // Set email format to HTML
        $oMail->Subject = "Mirtual Activation";
        $oMail->Body    = $sConfirmEmailContent;
        $oMail->send();
    }


    //Do a window relocate after the email is sent
    //This is not the optimal usage, since the customer might see a split second of the email sender
    echo "<script>window.location.assign(window.location.protocol + '/KEA_Bachelor/confirmOrder.php');</script>";
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$oMail->ErrorInfo}";
}
