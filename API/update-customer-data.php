<?php
session_start();
include_once("../DB_Connection/connection.php");
echo json_encode($_POST);
echo $_SESSION['customer_id'];
$customerId = $_SESSION['customer_id'];
if($_POST['input_first_name'] != ""){
    $postDataReff = $_POST['input_first_name'];
    $sql = "UPDATE `customers` SET `customer_first_name` = \"$postDataReff\" WHERE `customers`.`customer_id` = \"$customerId\"";
    $conn->query($sql);
    $_SESSION['customer_first_name'] = $postDataReff;
}
if($_POST['input_last_name'] != ""){
    $postDataReff = $_POST['input_last_name'];
    $sql = "UPDATE `customers` SET `customer_last_name` = \"$postDataReff\" WHERE `customers`.`customer_id` = \"$customerId\"";
    $conn->query($sql);
    $_SESSION['customer_last_name'] = $postDataReff;
}
if($_POST['input_company_street'] != ""){
    $postDataReff = $_POST['input_company_street'];
    $sql = "UPDATE `customers` SET `customer_address` = \"$postDataReff\" WHERE `customers`.`customer_id` = \"$customerId\"";
    $conn->query($sql);
}
if($_POST['input_company_city'] != ""){
    $postDataReff = $_POST['input_company_city'];
    $sql = "UPDATE `customers` SET `customer_city` = \"$postDataReff\" WHERE `customers`.`customer_id` = \"$customerId\"";
    $conn->query($sql);
}
if($_POST['input_company_Postcode'] != ""){
    $postDataReff = $_POST['input_company_Postcode'];
    $sql = "UPDATE `customers` SET `customer_postcode` = \"$postDataReff\" WHERE `customers`.`customer_id` = \"$customerId\"";
    $conn->query($sql);
}
if($_POST['input_company_country'] != ""){
    $postDataReff = $_POST['input_company_country'];
    $sql = "UPDATE `customers` SET `customer_country` = \"$postDataReff\" WHERE `customers`.`customer_id` = \"$customerId\"";
    $conn->query($sql);
}
if($_POST['input_email'] != ""){
    $postDataReff = $_POST['input_email'];
    $sql = "UPDATE `customers` SET `customer_email` = \"$postDataReff\" WHERE `customers`.`customer_id` = \"$customerId\"";
    $conn->query($sql);
}
if($_POST['input_phone'] != ""){
    $postDataReff = $_POST['input_phone'];
    $sql = "UPDATE `customers` SET `customer_phone` = \"$postDataReff\" WHERE `customers`.`customer_id` = \"$customerId\"";
    $conn->query($sql);
}
if($_POST['input_company_name'] != ""){
    $postDataReff = $_POST['input_company_name'];
    $sql = "UPDATE `customers` SET `customer_company_name` = \"$postDataReff\" WHERE `customers`.`customer_id` = \"$customerId\"";
    $conn->query($sql);
}
if($_POST['input_company_cvr'] != ""){
    $postDataReff = $_POST['input_company_cvr'];
    $sql = "UPDATE `customers` SET `customer_cvr` = \"$postDataReff\" WHERE `customers`.`customer_id` = \"$customerId\"";
    $conn->query($sql);
}
header("Location: ../profile.php");