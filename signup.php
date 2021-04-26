<?php
// session_start();
// $inputFields = 0;
// $errorMsg = "";
// $errorEmail = "";
// $errorCvr = "";
// $errorPass = "";
// foreach ($_POST as $key) {
//     if($key != ""){
//         $inputFields++;
//     }
// }
// if($inputFields != 7){
//     $errorMsg = "Fuck you fill out the form";
// }else{
//     //echo "THANKS FOR YOUR DATA FUCK FACE";
//     include("DB_Connection/connection.php");
//     $sEmail = strtolower($conn->real_escape_string($_POST['input_email']));
//     $sCVR = $conn->real_escape_string($_POST['input_company_cvr']);
//     $sPasswordInit = $conn->real_escape_string($_POST['input_password_init']);
//     //echo $sPasswordInit;
//     $sPasswordConfirm = $conn->real_escape_string($_POST['input_password_confirm']);
//     //echo $sEmail;
//     $sql = "SELECT customer_email FROM customers WHERE customer_email = \"$sEmail\"";
//     $result = $conn->query($sql);
//     $data = $result->fetch_object();
//     if(isset($data->customer_email)){
//         $errorEmail = "<p style='color:red'>Your email was stolen sucker</p>";
//     }
//     $sql = "SELECT customer_cvr FROM customers WHERE customer_cvr = \"$sCVR\"";
//     $result = $conn->query($sql);
//     $data = $result->fetch_object();
//     if(isset($data->customer_cvr)){
//         $errorCvr = "<p style='color:red'>Your company was already fo shizzle registered</p>";
//     }
//     if($sPasswordInit !== $sPasswordConfirm){
//         $errorPass = "<p style='color:red'> Your big dumb head can't spell for shitz</p>";
//     }
// }
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
        <label>
            <p>Contact - First Name:</p>
            <input type="text" name="input_first_name" value="SAM">
        </label>
        <label>
            <p>Contact - Last Name:</p>
            <input type="text" name="input_last_name" value="UEL">
        </label>
        <label>
            <p>Contact - Email:</p>
            <input type="email" name="input_email" value="sam@uel.dk">
            <?= $errorEmail ?>
        </label>
        <label>
            <p>Company - Name:</p>
            <input type="text" name="input_company_name" value="SAMS's bar">
        </label>
        <label>
            <p>Company - CVR: ( Skriv kun talene )</p>
            <input type="text" name="input_company_cvr" value="12399">
            <?= $errorCvr ?>
        </label>
        <label>
            <p>Password: ( No special characters )</p>
            <input type="password" name="input_password_init" value="XXBAJER">
        </label>
        <label>
            <p>Confirm Password:</p>
            <input type="password" name="input_password_confirm" value="XXBAJER">
            <?= $errorPass ?>
        </label>
        <input type="submit" value="Sign up!">
        <p><?= $errorMsg ?></p>
    </form>
</body>

</html>