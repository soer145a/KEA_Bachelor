<?php
session_start();
$auth = true;
if(!isset($_SESSION['tempUserData'])){
    echo "You need to fill out the user signup form";
    $auth = false;
}
if(!isset($_SESSION['basketObj'])){
    echo "No products in basket";
    $auth = false;
}
if($auth){
    echo "ready to pay";
    header("Location: API/payment-handler.php");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pay here:</title>
</head>
<body>
    
</body>
</html>