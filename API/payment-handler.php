<?php
session_start();
//echo "starting the payment";
include("../DB_Connection/connection.php");

$sql = "INSERT INTO customers VALUES (customer_first_name, customer_last_name, customer_company_name, customer_email, customer_password, customer_cvr) VALUES ('John', 'Doe', 'john@example.com')";
$result = $conn->query();
