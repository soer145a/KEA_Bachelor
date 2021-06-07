<?php

// Import PHPMailer classes into the global namespace
// These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'Exception.php';
require 'PHPMailer.php';
require 'SMTP.php';
$_POST = json_decode(file_get_contents("php://input"), true); //make json object an assoc array

if (isset($_POST['customerMessage'])) {
    $potentialCustomerName = $_POST['customerName'];
    $potentialCustomerEmail = $_POST['customerEmail'];
    $potentialCustomerMessage = $_POST['customerMessage'];

    $sContactEmailContent = file_get_contents("contact-email.php");
    $sContactEmailContent = str_replace("::CUSTOMERNAME::", $potentialCustomerName, $sContactEmailContent);
    $sContactEmailContent = str_replace("::CUSTOMEREMAIL::", $potentialCustomerEmail, $sContactEmailContent);
    $sContactEmailContent = str_replace("::CUSTOMERMESSAGE::", $potentialCustomerMessage, $sContactEmailContent);


    $oMail = new PHPMailer(true);

    try {
        //Server settings
        // $oMail->SMTPDebug = SMTP::DEBUG_SERVER;                      // Enable verbose debug output
        $oMail->SMTPDebug = 0;                      // Enable verbose debug output
        $oMail->isSMTP();                                            // Send using SMTP
        $oMail->Host       = 'smtp.gmail.com';                       // Set the SMTP server to send through
        $oMail->SMTPAuth   = true;                                   // Enable SMTP authentication
        $oMail->Username   = 'cookbook.kea@gmail.com';              // SMTP username
        $oMail->Password   = 'soer145a';                             // SMTP password

        //$oMail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;          // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` also accepted
        $oMail->SMTPSecure = 'tls';                                     // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` also accepted
        $oMail->Port       = 587;                                       // TCP port to connect to

        //Recipients
        $oMail->setFrom("Mirtual@purplescout.com", "Mirtual Contact Form");
        $oMail->addAddress("soren.remboll@gmail.com", "Soren Remboll");     // Add a recipient   
        $oMail->addReplyTo("$potentialCustomerEmail", "$potentialCustomerName");

        // Attachments

        // Content       
        $oMail->isHTML(true);                                  // Set email format to HTML
        $oMail->Subject = "New potential customer message";
        $oMail->Body = $sContactEmailContent;
        $oMail->send();

        $aResponse = array("mailSent" => true, "error" => "none");
        //Do a window relocate after the email is sent
        //This is not the optimal usage, since the customer might see a split second of the email sender

    } catch (Exception $e) {

        $aResponse = array("mailSent" => false, "error" => $oMail->ErrorInfo);
    }
} else {
    header('Location: ../index.php');
}
echo json_encode($aResponse);
