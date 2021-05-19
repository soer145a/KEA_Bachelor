<?php
session_start();

include_once("../DB_Connection/connection.php");

$customerId = $_SESSION['customer_id'];

if (isset($_POST['customer_password'])) {
    $password = $_POST['customer_password'];
    $newPassword = $_POST['input_password_confirm'];
    $sql = "SELECT * FROM customers WHERE customer_id = \"$customerId\"";
    $result = $conn->query($sql);
    $row = $result->fetch_object();
    $db_password = $row->customer_password;
    if (password_verify($password, $db_password)) {
        $value = password_hash($newPassword, PASSWORD_DEFAULT);
        $sql = "UPDATE `customers` SET `customer_password` = \"$value\" WHERE customer_id = \"$customerId\"";
        $conn->query($sql);
        echo "password updated";
    }
} else if (isset($_POST)) {

    $fieldToUpdate = key($_POST);
    $value = reset($_POST);
    $sql = "UPDATE `customers` SET `$fieldToUpdate` = \"$value\" WHERE customer_id = \"$customerId\"";
    $conn->query($sql);
}

header("Location: ../profile.php");
