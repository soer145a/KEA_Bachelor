<?php

//signup form post starts here

$_POST;



$sEmail = strtolower($conn->real_escape_string($_POST['input_email']));
$sCVR = $_POST['input_company_cvr'];
$sPasswordInit = $_POST['input_password_init'];
$sPasswordConfirm = $_POST['input_password_confirm'];
$stmt = $conn->prepare("SELECT customer_email FROM customers WHERE customer_email = ?");
$stmt->bind_param("s", $sEmail);
$stmt->execute();
$data = $stmt->get_result();
$convertedData = $data->fetch_object();
//echo $convertedData->customer_email;
if (isset($convertedData->customer_email)) {

    $denySubmitionFlag = true;
}
$stmt = $conn->prepare("SELECT customer_cvr FROM customers WHERE customer_cvr = ?");
$stmt->bind_param("s", $sCVR);
$stmt->execute();
$data = $stmt->get_result();
$convertedData = $data->fetch_object();
//echo $convertedData->customer_cvr;
if (isset($convertedData->customer_cvr)) {

    $denySubmitionFlag = true;
}
if ($sPasswordInit !== $sPasswordConfirm) {

    $denySubmitionFlag = true;
}
if ($denySubmitionFlag) {
    echo "You can not submit to database";
} else {
    echo "you good homie";
    $tempUserData = new stdClass();
    $tempUserData->uEmail = $sEmail;
    $tempUserData->uCvr = $sCVR;
    $tempUserData->uFirstName = $_POST['input_first_name'];
    $tempUserData->uLastName = $_POST['input_last_name'];
    $tempUserData->uCompanyName = $_POST['input_company_name'];
    $tempUserData->uPassword = $sPasswordConfirm;
    $_SESSION['tempUserData'] = $tempUserData;
    header('Location: index.php');
}
