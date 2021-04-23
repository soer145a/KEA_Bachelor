<?php 
session_start();
//if(isset());


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
</head>
<body>
    <h1>Sign Up</h1>
    <form method="post">
        <label><p>Contact - First Name:</p>
            <input type="text" name="input_first_name">
        </label>
        <label><p>Contact - Last Name:</p>
            <input type="text" name="input_last_name">
        </label>
        <label><p>Contact - Email:</p>
            <input type="email" name="input_email">
        </label>
        <label><p>Company - Name:</p>
            <input type="text" name="input_company_name">
        </label>
        <label><p>Company - CVR:</p>
            <input type="text" name="input_company_cvr">
        </label>
        <label><p>Password:</p>
            <input type="password" name="input_password_init">
        </label>
        <label><p>Confirm Password:</p>
            <input type="password" name="input_password_confirm">
        </label>
        <input type="submit" value="Sign up!">
    </form>
</body>
</html>