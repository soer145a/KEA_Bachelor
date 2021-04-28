<?php
session_start();
if(isset($_SESSION['basketObj'])){
    echo json_encode($_SESSION['basketObj']);
}
$inputFields = 0;
$errorMsg = "";
$errorEmail = "";
$errorCvr = "";
$errorPass = "";
$denySubmitionFlag = false;
foreach ($_POST as $key) {
    if ($key != "") {
        $inputFields++;
    }
}
if ($inputFields != 7) {
    //$errorMsg = "Fuck you fill out the form";
} else {
    //echo "THANKS FOR YOUR DATA FUCK FACE";
    include("DB_Connection/connection.php");
    
    $sEmail = strtolower($conn->real_escape_string($_POST['input_email']));
    
    $sCVR = $conn->real_escape_string($_POST['input_company_cvr']);
    $sPasswordInit = $_POST['input_password_init'];
    $sPasswordConfirm = $_POST['input_password_confirm'];
    $stmt = $conn->prepare("SELECT customer_email FROM customers WHERE customer_email = ?");
    $stmt->bind_param("s", $sEmail);
    $stmt->execute();
    $data = $stmt->get_result();
    $convertedData = $data->fetch_object();
    //echo $convertedData->customer_email;
    if (isset($convertedData->customer_email)) {
        $errorEmail = "<p style='color:red'>Your email was stolen sucker</p>";
        $denySubmitionFlag = true;
    }
    $stmt = $conn->prepare("SELECT customer_cvr FROM customers WHERE customer_cvr = ?");
    $stmt->bind_param("s", $sCVR);
    $stmt->execute();
    $data = $stmt->get_result();
    $convertedData = $data->fetch_object();
    //echo $convertedData->customer_cvr;
    if (isset($convertedData->customer_cvr)) {
        $errorCvr = "<p style='color:red'>Your company was already fo shizzle registered</p>";
        $denySubmitionFlag = true;
    }
    if ($sPasswordInit !== $sPasswordConfirm) {
        $errorPass = "<p style='color:red'> Your big dumb head can't spell for shitz</p>";
        $denySubmitionFlag = true;
    }
    if($denySubmitionFlag){
        echo "You can not submit to database";
    }else{
        echo "you good homie";
        $tempUserData = new stdClass();
        $tempUserData->uEmail = $sEmail;
        $tempUserData->uCvr = $sCVR;
        $tempUserData->uFirstName = $_POST['input_first_name'];
        $tempUserData->uLastName = $_POST['input_last_name'];
        $tempUserData->uCompanyName = $_POST['input_company_name'];
        $tempUserData->uPassword = $sPasswordConfirm;
        $_SESSION['tempUserData'] = $tempUserData;
        header("Location: payment.php");
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/app.css">
    <title>Sign Up</title>
</head>

<body>
    <h1>Sign Up</h1>
    <form class="form" method="post" onsubmit="return inputValidate()">
        <label>
            <p>Contact - First Name:</p>
            <input class="form__input" oninput="inputValidate()" data-validate="string" type="text" name="input_first_name" placeholder="John">
        </label>
        <label>
            <p>Contact - Last Name:</p>
            <input class="form__input" oninput="inputValidate()" data-validate="string" type="text" name="input_last_name" placeholder="Doe">
        </label>
        <label>
            <p>Contact - Email:</p>
            <input class="form__input" oninput="inputValidate()" data-validate="email" type="email" name="input_email" placeholder="example@email.com">
            <?= $errorEmail ?>
        </label>
        <label>
            <p>Company - Name:</p>
            <input class="form__input" oninput="inputValidate()" data-validate="string" type="text" name="input_company_name" placeholder="JohnDoe A/S">
        </label>
        <label>
            <p>Company - CVR:</p>
            <input class="form__input" oninput="inputValidate()" data-validate="cvr" type="text" name="input_company_cvr" placeholder="12345678">
            <?= $errorCvr ?>
        </label>
        <label>
            <p>Password: ( No special characters )</p>
            <input class="form__input" oninput="inputValidate()" data-validate="password" type="password" name="input_password_init" placeholder="MyStr0ng.PW-example">
        </label>
        <label>
            <p>Confirm Password:</p>
            <input class="form__input" oninput="inputValidate()" data-validate="password" type="password" name="input_password_confirm" placeholder="MyStr0ng.PW-example">
            <?= $errorPass ?>
        </label>
        <button class="form__btn" type="submit">Sign up</button>
        <p><?= $errorMsg ?></p>
    </form>
</body>
<script src="js/app.js"></script>
</html>