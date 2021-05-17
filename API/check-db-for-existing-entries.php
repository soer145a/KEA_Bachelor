<?php
include_once("../DB_Connection/connection.php");

$_POST = json_decode(file_get_contents("php://input"), true); //make json object an assoc array

if (isset($_POST)) {

    $type = key($_POST);
    $value = $conn->real_escape_string(reset($_POST));

    $sql = "SELECT * FROM customers WHERE $type = \"$value\"";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $response = array("dataExists" => true, "sql" => $sql, "num-rows" => $result->num_rows);
    } else {
        $response = array("dataExists" => false, "sql" => $sql, "num-rows" => $result->num_rows);
    }
} else {
    $response = array("error" => true, "sql" => $sql, "num-rows" => $result->num_rows);
}

echo json_encode($response);
