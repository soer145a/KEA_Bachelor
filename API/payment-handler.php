<?php
session_start();
//echo "starting the payment";
include("../DB_Connection/connection.php");

//$sql = "INSERT INTO customers VALUES (customer_first_name, customer_last_name, customer_company_name, customer_email, customer_password, customer_cvr) VALUES ('John', 'Doe', 'john@example.com')";
//$result = $conn->query();
foreach ($_SESSION['tempUserData'] as $key) {
    echo "$key <br>";
}

/* $stmt = $conn->prepare("INSERT INTO customers VALUES (customer_first_name, customer_last_name, customer_company_name, customer_email, customer_password, customer_cvr) VALUES (?,?,?,?,?,?)");
$stmt->bind_param("s", $sEmail);
$stmt->execute();
$data = $stmt->get_result();
$convertedData = $data->fetch_object(); */
