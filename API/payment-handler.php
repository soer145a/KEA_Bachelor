<?php
session_start();
echo "starting the submission";
include("../DB_Connection/connection.php");
echo json_encode($_POST['input_first_name']);
//$sql = "INSERT INTO customers VALUES (customer_first_name, customer_last_name, customer_company_name, customer_email, customer_password, customer_cvr) VALUES ('John', 'Doe', 'john@example.com')";
//$result = $conn->query();
foreach ($_SESSION['cart'] as $key) {
    echo json_encode($key)."<br>";
}
$dbFirstName = $conn->real_escape_string($_POST['input_first_name']);
//echo $dbFirstName;
$dbLastName = $conn->real_escape_string($_POST['input_last_name']);
//echo $dbLastName;
$dbEmail = $conn->real_escape_string($_POST['input_email']);
//echo $dbEmail;
$dbCompanyName = $conn->real_escape_string($_POST['input_company_name']);
//echo $dbCompanyName;
$dbCVR = $conn->real_escape_string($_POST['input_company_name']);
//echo $dbCVR;
//echo $_SESSION['tempUserData']->uPassword;
$hashedPassword = password_hash($_POST['input_password_confirm'],PASSWORD_DEFAULT);
//echo $hashedPassword;
$apiKey = bin2hex(random_bytes(32));
//echo $apiKey;
$embed = "<iframe src='https://purplescout.placeholder.dk/key' frameborder='0'></iframe>";

$stmt = $conn->prepare("INSERT INTO customers (customer_id ,customer_first_name, customer_last_name, customer_company_name,api_key,embed_link, customer_email, customer_password, customer_cvr) VALUES ( null,?,?,?,?,?,?,?,?)");
$stmt->bind_param("ssssssss", $dbFirstName, $dbLastName, $dbCompanyName,$apiKey,$embed, $dbEmail, $hashedPassword, $dbCVR);
$stmt->execute();

/* $stmt = $conn->prepare("INSERT INTO invoice (invoice_id, product_id, customer_id, invoice_date, license_id ,invoice_modifier) VALUES ( null,?,?,?,?,?)");
$stmt->bind_param("sssss", );
$stmt->execute(); */