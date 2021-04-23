<?php 
if(isset($_POST['customer_email']) && isset($_POST['customer_password'])){
    //echo $_POST['customer_email']." ".$_POST['customer_password'];
    if($_POST['customer_email'] != "" && $_POST['customer_password']!=""){
        echo "Data is there";
        include("DB_Connection/connection.php");
        $password = $_POST['customer_password'];
        $email = $_POST['customer_email'];
        $sql = "SELECT * FROM customers WHERE customer_email = \"$email\" AND customer_password = \"$password\"";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "id: " . $row["customer_id"]. " - Name: " . $row["customer_first_name"]. " " . $row["customer_last_name"]. "<br>";
            }
        } else {
            echo "0 results";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PurpleScout Login</title>
</head>
<body>
<h1>Login</h1>
    <form method="post">
        <label><p>Enter Email:</p>
            <input type="email" name="customer_email">
        </label>
        <label><p>Enter Password:</p>
            <input type="password" name="customer_password">
        </label>
        <br>
        <button type="submit">Login</button>
    </form>
</body>
</html>