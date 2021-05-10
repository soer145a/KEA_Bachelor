<?php
session_start();
echo "starting the submission";
include("../DB_Connection/connection.php");
//echo json_encode($_POST['input_first_name']);
//$sql = "INSERT INTO customers VALUES (customer_first_name, customer_last_name, customer_company_name, customer_email, customer_password, customer_cvr) VALUES ('John', 'Doe', 'john@example.com')";
//$result = $conn->query();

$product_id = $_SESSION['cart'][0]['product_id'];
$subscription_id = 1;

if (!isset($_SESSION['loginStatus'])) {
    $dbFirstName = $conn->real_escape_string($_POST['input_first_name']);
    //echo $dbFirstName;
    $dbLastName = $conn->real_escape_string($_POST['input_last_name']);
    //echo $dbLastName;
    $dbEmail = $conn->real_escape_string($_POST['input_email']);

    $dbPhone = $conn->real_escape_string($_POST['input_phone']);
    //echo $dbEmail;
    $dbCompanyName = $conn->real_escape_string($_POST['input_company_name']);
    //echo $dbCompanyName;
    $dbCVR = $conn->real_escape_string($_POST['input_company_cvr']);
    //echo $dbCVR;
    //echo $_SESSION['tempUserData']->uPassword;
    $hashedPassword = password_hash($_POST['input_password_confirm'], PASSWORD_DEFAULT);
    //echo $hashedPassword;
    $apiKey = bin2hex(random_bytes(32));
    //echo $apiKey;
    $embed = "<iframe src='https://purplescout.placeholder.dk/key' frameborder='0'></iframe>";

    $dbCompanyStreet = $conn->real_escape_string($_POST['input_company_street']);
    $dbCompanyPostcode = $conn->real_escape_string($_POST['input_company_Postcode']);
    $dbCompanyCity = $conn->real_escape_string($_POST['input_company_city']);
    $dbCompanyCountry = $conn->real_escape_string($_POST['input_company_country']);

    $stmt = $conn->prepare("INSERT INTO customers (customer_id ,customer_first_name, customer_last_name, customer_company_name, api_key, embed_link, customer_email, customer_password, customer_cvr,customer_city,customer_address,customer_country,customer_postcode,customer_phone, customer_confirm_code, customer_confirmed) VALUES ( null,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
    $i = 0;
    $stmt->bind_param("sssssssssssssii", $dbFirstName, $dbLastName, $dbCompanyName, $apiKey, $embed, $dbEmail, $hashedPassword, $dbCVR, $dbCompanyCity, $dbCompanyStreet, $dbCompanyCountry, $dbCompanyPostcode, $dbPhone, $apiKey, $i);

    $stmt->execute();
    $customerId = $stmt->insert_id;
    $_SESSION['postData'] = json_encode($_POST);
} else {
    $customerId = $_SESSION['customer_id'];
    $sql = "SELECT * FROM customers WHERE customer_id = \"$customerId\"";
    $result = $conn->query($sql);
    $row = $result->fetch_object();
    $customerEmail = $row->customer_email;
    $postData = array("input_email" => $customerEmail, "input_first_name" => $_SESSION['customer_first_name'], "input_last_name" =>  $_SESSION['customer_last_name']);
    $_SESSION['postData'] = json_encode($postData);
}

//echo "sucess";

$currentDate = time();
$sql = "SELECT * FROM subscriptions WHERE subscription_id = \"$subscription_id\"";
$result = $conn->query($sql);
$row = $result->fetch_object();
$subLen = $row->subscription_length;
$subEnd = $currentDate + $subLen;
//echo $subEnd;
$subRemaining = $subEnd - $currentDate;
echo $subRemaining;
$subActive = 1;
$subAuto = 0;
$stmt_2 = $conn->prepare("INSERT INTO customer_products (customer_products_id ,customer_id, product_id, subscription_start,subscription_total_length, subscription_end, subscription_remaining, subscription_active, subscription_autorenew) VALUES ( null,?,?,?,?,?,?,?,?)");
$stmt_2->bind_param("iiiiiiii", $customerId, $product_id, $currentDate, $subLen, $subEnd, $subRemaining, $subActive, $subAuto);
$stmt_2->execute();

$_SESSION['key'] = $apiKey;

/* echo json_encode($_POST);
echo json_encode($_SESSION); */

header("Location: ../MAILER/send-email.php");
