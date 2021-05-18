<?php
session_start();

include_once("../DB_Connection/connection.php");

$customerId = $_SESSION['customer_id'];

if (isset($_POST)) {
    $fieldToUpdate = array_shift(array_keys($_POST));

    $value = array_shift(array_values($_POST));
    $sql = "UPDATE `customers` SET `$fieldToUpdate` = \"$value\" WHERE customer_id = \"$customerId\"";
    $conn->query($sql);
}

header("Location: ../profile.php");
