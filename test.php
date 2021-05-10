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

if (!isset($_SESSION['loginStatus'])) {
    $emailContentConfirm = str_replace("::USERNAME::", $name, $emailContentConfirm);
    $emailContentConfirm = str_replace("::KEY::", $_SESSION['key'], $emailContentConfirm);
}

foreach ($_SESSION['cart'] as $product) {
    $productName = $product['product_name'] . ", " .  $productName;
    $totalPrice =  $totalPrice + (int)$product['product_price'];
}

$emailContentOrder = str_replace("::USERNAME::", $name, $emailContentOrder);
$emailContentOrder = str_replace("::ORDERPRODUCT::", $productName, $emailContentOrder);
$emailContentOrder = str_replace("::ORDERPRICE::", $totalPrice, $emailContentOrder);



/* $emailContent = str_replace("::USERNAME::",$UN,$emailContent);
    */
// Instantiation and passing `true` enables exceptions


//echo $emailContent;
$mailConfirm = new PHPMailer(true);
$mailOrder = new PHPMailer(true);

try {
    //Server settings
    $mailConfirm->SMTPDebug = SMTP::DEBUG_SERVER;                      // Enable verbose debug output
    $mailConfirm->isSMTP();                                            // Send using SMTP
    $mailConfirm->Host       = 'smtp.gmail.com';                       // Set the SMTP server to send through
    $mailConfirm->SMTPAuth   = true;                                   // Enable SMTP authentication
    $mailConfirm->Username   = 'cookbook.kea@gmail.com';              // SMTP username
    $mailConfirm->Password   = 'soer145a';                             // SMTP password
    $mailOrder->SMTPDebug = SMTP::DEBUG_SERVER;                      // Enable verbose debug output
    $mailOrder->isSMTP();                                            // Send using SMTP
    $mailOrder->Host       = 'smtp.gmail.com';                       // Set the SMTP server to send through
    $mailOrder->SMTPAuth   = true;                                   // Enable SMTP authentication
    $mailOrder->Username   = 'cookbook.kea@gmail.com';              // SMTP username
    $mailOrder->Password   = 'soer145a';                             // SMTP password
    //$mailConfirm->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` also accepted
    $mailConfirm->SMTPSecure = 'tls';         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` also accepted
    $mailConfirm->Port       = 587;                                    // TCP port to connect to
    $mailOrder->SMTPSecure = 'tls';         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` also accepted
    $mailOrder->Port       = 587;                                    // TCP port to connect to

    /* $EM = $_GET['email'];
    $key = $_GET['key'];
    $UN = $_GET['displayName']; */
    //Recipients
    $mailConfirm->setFrom('Mirtual@purplescout.com', 'Mirtual');
    $mailConfirm->addAddress("$email", "Søren Rembøll");     // Add a recipient   
    $mailConfirm->addReplyTo('Mirtual@purplescout.com', 'Information');
    $mailOrder->setFrom('Mirtual@purplescout.com', 'Mirtual');
    $mailOrder->addAddress("$email", "Søren Rembøll");     // Add a recipient   
    $mailOrder->addReplyTo('Mirtual@purplescout.com', 'Information');


    // Attachments
    // $mailConfirm->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
    // $mailConfirm->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

    // Content

    if (!isset($_SESSION['loginStatus'])) {
        $mailConfirm->isHTML(true);                                  // Set email format to HTML
        $mailConfirm->Subject = "Mirtual Activation";
        $mailConfirm->Body    = $emailContentConfirm;
        $mailConfirm->send();
    }
    //$mailConfirm->AltBody = 'This is the body in plain text for non-HTML mail clients';
    $mailOrder->isHTML(true);                                  // Set email format to HTML
    $mailOrder->Subject = "Mirtual order";
    $mailOrder->Body    = $emailContentOrder;
    $mailOrder->send();

    if (!isset($_SESSION['loginStatus'])) {
        if ($mailConfirm->send() && $mailOrder->send()) {

            header("Location: ../awaitConfirm.php");
            /* echo "Mailer succes"; */
        } else {
            /* echo "Mailer Error"; */
        }
    } else if ($mailOrder->send()) {

        header("Location: ../awaitConfirm.php");
    } else {
        /* echo "Mailer Error";
        echo "Mailer succes"; */
    }
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mailConfirm->ErrorInfo}";
}
