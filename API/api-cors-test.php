<?php
$servername = "localhost";
$username = "root";
$password = "";
$db = "purplescout";
// Create connection
$conn = new mysqli($servername, $username, $password);
$conn->query("INSERT * INTO customers WHERE 'name = 'John'");
// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
echo "Connected successfully";
