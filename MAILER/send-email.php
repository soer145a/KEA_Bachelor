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
$userSubmittedData = json_decode($_SESSION['postData']);
$email = $userSubmittedData->input_email;
$fName = $userSubmittedData->input_first_name;
$lName = $userSubmittedData->input_last_name;
$emailContentOrder = file_get_contents("orderEmail.php");
$emailContentConfirm = file_get_contents("confirmEmail.php");
$name = "$fName $lName";
$totalPrice = 0;
$productName = "";
$addonName = "";
$boughtAddons = "";

if (!isset($_SESSION['loginStatus'])) {
    $emailContentConfirm = str_replace("::USERNAME::", $name, $emailContentConfirm);
    $emailContentConfirm = str_replace("::KEY::", $_SESSION['confirmCode'], $emailContentConfirm);
}

foreach ($_SESSION['cartProducts'] as $product) {
    $productName = $product['product_name'] . ", " .  $productName;
    $totalPrice =  $totalPrice + (float)$product['product_price'];
}
foreach ($_SESSION['cartAddOns'] as $addon) {
    $addonName = $addon['addon_name'];
    $boughtAddons = $boughtAddons . $addon['addon_amount'] . "x" . $addonName . ", ";
    $totalPrice =  $totalPrice + (float)$product['addon_price'];
}

$emailContentOrder = str_replace("::USERNAME::", $name, $emailContentOrder);
$emailContentOrder = str_replace("::ORDERPRODUCT::", $productName, $emailContentOrder);
$emailContentOrder = str_replace("::ORDERADDONS::", $boughtAddons, $emailContentOrder);
$emailContentOrder = str_replace("::ORDERPRICE::", $totalPrice, $emailContentOrder);



/* $emailContent = str_replace("::USERNAME::",$UN,$emailContent);
    */
// Instantiation and passing `true` enables exceptions


//echo $emailContent;
$mail = new PHPMailer(true);

try {
    //Server settings
    $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      // Enable verbose debug output
    $mail->isSMTP();                                            // Send using SMTP
    $mail->Host       = 'smtp.gmail.com';                       // Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
    $mail->Username   = 'cookbook.kea@gmail.com';              // SMTP username
    $mail->Password   = 'soer145a';                             // SMTP password

    //$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` also accepted
    $mail->SMTPSecure = 'tls';         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` also accepted
    $mail->Port       = 587;                                    // TCP port to connect to


    /* $EM = $_GET['email'];
    $key = $_GET['confirmCode'];
    $UN = $_GET['displayName']; */
    //Recipients
    $mail->setFrom('Mirtual@purplescout.com', 'Mirtual');
    $mail->addAddress("$email", "Søren Rembøll");     // Add a recipient   
    $mail->addReplyTo('Mirtual@purplescout.com', 'Information');

    // Attachments
    // $mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
    // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

    // Content
    //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
    $mail->isHTML(true);                                  // Set email format to HTML
    $mail->Subject = "Mirtual order";
    $mail->Body    = $emailContentOrder;
    $mail->send();

    if (!isset($_SESSION['loginStatus'])) {
        $mail->isHTML(true);                                  // Set email format to HTML
        $mail->Subject = "Mirtual Activation";
        $mail->Body    = $emailContentConfirm;
        $mail->send();
    }

    echo "<script>window.location.assign(window.location.protocol + '/KEA_Bachelor/confirmOrder.php');</script>";
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mailConfirm->ErrorInfo}";
}
