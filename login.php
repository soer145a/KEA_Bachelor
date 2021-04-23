<?php
session_start();
$errorMess = "";
if(isset($_POST['customer_email']) && isset($_POST['customer_password'])){
    //echo $_POST['customer_email']." ".$_POST['customer_password'];
    if($_POST['customer_email'] != "" && $_POST['customer_password']!=""){
        //echo "Data is there";
        include("DB_Connection/connection.php");
        $password = $conn->real_escape_string($_POST['customer_password']);
        $email = $conn->real_escape_string($_POST['customer_email']);
        $sql = "SELECT * FROM customers WHERE customer_email = \"$email\"";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            $row = $result->fetch_object();
            $db_password = $row->customer_password;
            if(password_verify($password,$db_password)){
                $_SESSION['loginStatus'] = true;
                $_SESSION['customer_id'] = $row->customer_id;
                $_SESSION['customer_first_name'] = $row->customer_first_name;
                $_SESSION['customer_last_name'] = $row->customer_last_name;
                header('Location: index.php');


            }else{
                $errorMess = "<p style='color:red'> ERROR - You don' fuckd up kiddo</p>";
            }
            
        } else {
            $errorMess = "<p style='color:red'> ERROR - You don' fuckd up kiddo</p>";
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
        <?=$errorMess?>
        <br>
        <button type="submit">Login</button>
    </form>
</body>
</html>