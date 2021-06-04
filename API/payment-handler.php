<?php
session_start();
echo "starting the submission";
include("../DB_Connection/connection.php");
include("./create-pdf-receipt.php");
//Including the pdf receipt maker for making the receipt
//Check if the purchase process has started, if not, reject the customer
if(!isset($_SESSION['purchaseProcess'])){
    header("Location: ../index.php");
    exit();
}
$cartProducts = $_SESSION['cartProducts'];
$cartAddOns = $_SESSION['cartAddOns'];
//The embed that the user needs to implement Mirtual on their site
$embed = "<iframe src='http://127.0.0.1/KEA_Bachelor/deploys/product.php?key=INSERT KEY HERE' frameborder='0'></iframe>";

//If the user is not logged in, we create a user in the database for us to link the products to
if (!isset($_SESSION['loginStatus'])) {
    //We use real_escape_string for our variables in order to sanitize them for the database security
    $dbFirstName = $oDbConnection->real_escape_string($_POST['input_first_name']);
    $dbLastName = $oDbConnection->real_escape_string($_POST['input_last_name']);
    $dbEmail = $oDbConnection->real_escape_string($_POST['input_email']);
    $dbPhone = $oDbConnection->real_escape_string($_POST['input_phone']);
    $dbCompanyName = $oDbConnection->real_escape_string($_POST['input_company_name']);
    $dbCVR = $oDbConnection->real_escape_string($_POST['input_company_cvr']);
    //The password is hashed here with the method password_hash
    $hashedPassword = password_hash($_POST['input_password_confirm'], PASSWORD_DEFAULT);
    //A randomly generated confirm code, 32 characters long
    $confirmCode = bin2hex(random_bytes(32));

    $dbCompanyStreet = $oDbConnection->real_escape_string($_POST['input_company_street']);
    $dbCompanyPostcode = $oDbConnection->real_escape_string($_POST['input_company_Postcode']);
    $dbCompanyCity = $oDbConnection->real_escape_string($_POST['input_company_city']);
    $dbCompanyCountry = $oDbConnection->real_escape_string($_POST['input_company_country']);

    //The prepare statement for compartmentalizing the sql variables and securing our sql queries from injections
    $stmt = $oDbConnection->prepare("INSERT INTO customers (customer_id ,customer_first_name, customer_last_name, customer_company_name, customer_email, customer_password, customer_cvr, customer_city, customer_address, customer_country,customer_postcode,customer_phone, customer_confirm_code, customer_confirmed) VALUES ( null,?,?,?,?,?,?,?,?,?,?,?,?,?)");
    $i = 0;
    //Binding the parameters to our variables
    $stmt->bind_param("ssssssssssssi", $dbFirstName, $dbLastName, $dbCompanyName, $dbEmail, $hashedPassword, $dbCVR, $dbCompanyCity, $dbCompanyStreet, $dbCompanyCountry, $dbCompanyPostcode, $dbPhone, $confirmCode, $i);

    $stmt->execute();
    $customerId = $stmt->insert_id; 
    //This is the ID of the user we just created, which is needed for making the bridging tables
    //related to the customer

    //Then we store the data we just created in the session for further accessing down the purchase line
    $_SESSION['customerId'] = $customerId;
    $_SESSION['postData'] = json_encode($_POST);
    $_SESSION['confirmCode'] = $confirmCode;
} else {
    //If the user is already logged in, we instead get the customer information from the database to emulate a newly created user
    $customerId = $_SESSION['customerId'];
    $sql = "SELECT * FROM customers WHERE customer_id = \"$customerId\"";
    $result = $oDbConnection->query($sql);
    $row = $result->fetch_object();
    $postData = array(
        "input_company_name" => $row->customer_company_name,
        "input_company_street" => $row->customer_address,
        "input_company_city" => $row->customer_city,
        "input_company_Postcode" => $row->customer_postcode,
        "input_company_cvr" => $row->customer_cvr,
        "input_email" => $row->customer_email,
        "input_first_name" => $row->customer_first_name,
        "input_last_name" =>  $row->customer_last_name
    );
    $_SESSION['postData'] = json_encode($postData);
}
//Creating the order based on the current time
$currentDate = time();
$stmt_2 = $oDbConnection->prepare("INSERT INTO orders (order_id , customer_id, order_date ) VALUES(null,?,?)");
$stmt_2->bind_param("ii", $customerId, $currentDate);
$stmt_2->execute();
$orderId = $stmt_2->insert_id;
//Setting the order id in the session to use on another page
$_SESSION['orderId'] = $orderId;
//Sending the current time of the order to the receipt maker
createReceipt($currentDate);

foreach ($cartProducts as $product) {
    //Since each product has its unique entry into the database, we need to loop through them and create them individually
    $apiKey = bin2hex(random_bytes(32));
    $productId = $product['productId'];
    $subscription_id = $product['subscriptionId'];
    $productPrice = $product['productPrice'];
    $sql = "SELECT * FROM subscriptions WHERE subscription_id = \"$subscription_id\"";
    $result = $oDbConnection->query($sql);
    $row = $result->fetch_object();
    $subLen = $row->subscription_length;
    //When we want to calculate when the subscription will run out, we simply add the variables in epoch values
    $subEnd = $currentDate + $subLen;
    //Since prepare statements can't handle strings directly into its parameters, we made these for us to use the bind_param function
    $subActive = 1;
    $subAuto = 1;

    //Adding to the bridging tables for us to ensure we have the all the data linked
    $stmt_3 = $oDbConnection->prepare("INSERT INTO customer_products (customer_products_id ,customer_id, product_id, subscription_start, subscription_total_length, subscription_end, subscription_active, subscription_autorenew, api_key, embed_link) VALUES ( null,?,?,?,?,?,?,?,?,?)");
    $stmt_3->bind_param("iiiiiiiss", $customerId, $productId, $currentDate, $subLen, $subEnd, $subActive, $subAuto, $apiKey, $embed);
    $stmt_3->execute();

    $stmt_4 = $oDbConnection->prepare("INSERT INTO order_products (order_products_id, order_id, product_id, subscription_id, payed_price) VALUES(null,?,?,?,?)");
    $stmt_4->bind_param("iiii", $orderId, $productId, $subscription_id, $productPrice);
    $stmt_4->execute();
    
}

foreach ($cartAddOns as $addOn) {
    //Creating the relevant data to the addons
    $sAddOnId = $addOn['addOnId'];
    $nAddOnAmount = $addOn['addOnAmount'];
    $addOn_price = $addOn['addOnPrice'];
    $payed_price = (float)$addOn_price * (float)$nAddOnAmount;
    $addonExists = false;

    $sql = "SELECT * FROM customer_addons WHERE customer_id = \"$customerId\"";
    $result = $oDbConnection->query($sql);

    while ($row = $result->fetch_object()) {
        //For each addon, we check if it already exists for this user, and if it does, we add the amount to the entry
        if ($row->addon_id == $sAddOnId) {
            
            $currentAmount = $row->addon_amount;
            $newAmount = $currentAmount + $nAddOnAmount;
            $sql = "UPDATE customer_addons SET addon_amount = \"$newAmount\" WHERE customer_addon_id = \"$row->customer_addon_id\"";
            $oDbConnection->query($sql);
            $addonExists = true;
        }
    }
    // if it does not exist we add the entry
    if (!$addonExists) {
        $stmt_6 = $oDbConnection->prepare("INSERT INTO customer_addons (customer_addon_id, customer_id, addon_id, addon_amount) VALUES ( null,?,?,?)");
        $stmt_6->bind_param("iii", $customerId, $sAddOnId, $nAddOnAmount);
        $stmt_6->execute();
        
    }
    //Adding to the briding table for order addons
    $stmt_7 = $oDbConnection->prepare("INSERT INTO order_addons (order_addons_id, order_id, addon_id, payed_price, addon_amount) VALUES(null,?,?,?,?)");
    $stmt_7->bind_param("iiss", $orderId, $sAddOnId, $payed_price, $nAddOnAmount);
    $stmt_7->execute();
    
}

//Redirecting the user out of the payment handler
header("Location: ../MAILER/send-email.php");
