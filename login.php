<?php 
if(isset($_POST['customer_email']) && isset($_POST['customer_password'])){
    echo $_POST['customer_email']." ".$_POST['customer_password'];
    if($_POST['customer_email'] != "" && $_POST['customer_password']!=""){
        
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