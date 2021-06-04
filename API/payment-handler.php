<?php
session_start();
echo "starting the submission";
include("../DB_Connection/connection.php");
include("./create-pdf-receipt.php");
//echo json_encode($_POST['input_first_name']);
//$sql = "INSERT INTO customers VALUES (customer_first_name, customer_last_name, customer_company_name, customer_email, customer_password, customer_cvr) VALUES ('John', 'Doe', 'john@example.com')";
//$result = $oDbConnection->query();
if(!isset($_SESSION['purchaseProcess'])){
    header("Location: ../index.php");
    exit();
}
$cartProducts = $_SESSION['cartProducts'];
$cartAddOns = $_SESSION['cartAddOns'];
$embed = "<iframe src='http://127.0.0.1/KEA_Bachelor/deploys/product.php?key=INSERT KEY HERE' frameborder='0'></iframe>";


if (!isset($_SESSION['loginStatus'])) {
    $dbFirstName = $oDbConnection->real_escape_string($_POST['input_first_name']);
    //echo $dbFirstName;
    $dbLastName = $oDbConnection->real_escape_string($_POST['input_last_name']);
    //echo $dbLastName;
    $dbEmail = $oDbConnection->real_escape_string($_POST['input_email']);

    $dbPhone = $oDbConnection->real_escape_string($_POST['input_phone']);
    //echo $dbEmail;
    $dbCompanyName = $oDbConnection->real_escape_string($_POST['input_company_name']);
    //echo $dbCompanyName;
    $dbCVR = $oDbConnection->real_escape_string($_POST['input_company_cvr']);
    //echo $dbCVR;
    //echo $_SESSION['tempUserData']->uPassword;
    $hashedPassword = password_hash($_POST['input_password_confirm'], PASSWORD_DEFAULT);
    //echo $hashedPassword;
    $confirmCode = bin2hex(random_bytes(32));
    //echo $confirmCode;    

    $dbCompanyStreet = $oDbConnection->real_escape_string($_POST['input_company_street']);
    $dbCompanyPostcode = $oDbConnection->real_escape_string($_POST['input_company_Postcode']);
    $dbCompanyCity = $oDbConnection->real_escape_string($_POST['input_company_city']);
    $dbCompanyCountry = $oDbConnection->real_escape_string($_POST['input_company_country']);

    $stmt = $oDbConnection->prepare("INSERT INTO customers (customer_id ,customer_first_name, customer_last_name, customer_company_name, customer_email, customer_password, customer_cvr, customer_city, customer_address, customer_country,customer_postcode,customer_phone, customer_confirm_code, customer_confirmed) VALUES ( null,?,?,?,?,?,?,?,?,?,?,?,?,?)");
    $i = 0;
    $stmt->bind_param("ssssssssssssi", $dbFirstName, $dbLastName, $dbCompanyName, $dbEmail, $hashedPassword, $dbCVR, $dbCompanyCity, $dbCompanyStreet, $dbCompanyCountry, $dbCompanyPostcode, $dbPhone, $confirmCode, $i);

    $stmt->execute();
    $customerId = $stmt->insert_id;
    $_SESSION['customerId'] = $customerId;
    $_SESSION['postData'] = json_encode($_POST);
    $_SESSION['confirmCode'] = $confirmCode;
} else {
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

$currentDate = time();
$stmt_2 = $oDbConnection->prepare("INSERT INTO orders (order_id , customer_id, order_date ) VALUES(null,?,?)");
$stmt_2->bind_param("ii", $customerId, $currentDate);
$stmt_2->execute();
$orderId = $stmt_2->insert_id;
$_SESSION['orderId'] = $orderId;
createReceipt($currentDate);

//echo "sucess";
foreach ($cartProducts as $product) {
    $apiKey = bin2hex(random_bytes(32));
    $productId = $product['productId'];
    $subscription_id = $product['subscriptionId'];
    $productPrice = $product['productPrice'];
    $sql = "SELECT * FROM subscriptions WHERE subscription_id = \"$subscription_id\"";
    $result = $oDbConnection->query($sql);
    $row = $result->fetch_object();
    $subLen = $row->subscription_length;
    $subEnd = $currentDate + $subLen;
    //echo $subEnd;
    $subActive = 1;
    $subAuto = 1;
    $stmt_3 = $oDbConnection->prepare("INSERT INTO customer_products (customer_products_id ,customer_id, product_id, subscription_start, subscription_total_length, subscription_end, subscription_active, subscription_autorenew, api_key, embed_link) VALUES ( null,?,?,?,?,?,?,?,?,?)");
    $stmt_3->bind_param("iiiiiiiss", $customerId, $productId, $currentDate, $subLen, $subEnd, $subActive, $subAuto, $apiKey, $embed);
    $stmt_3->execute();
    $licenseID = $stmt_3->insert_id;

    $stmt_4 = $oDbConnection->prepare("INSERT INTO order_products (order_products_id, order_id, product_id, subscription_id, payed_price) VALUES(null,?,?,?,?)");
    $stmt_4->bind_param("iiii", $orderId, $productId, $subscription_id, $productPrice);
    $stmt_4->execute();
    $stmt_4->insert_id;
}

foreach ($cartAddOns as $addOn) {
    $sAddOnId = $addOn['addOnId'];
    $nAddOnAmount = $addOn['addOnAmount'];
    $addOn_price = $addOn['addOnPrice'];
    $payed_price = (float)$addOn_price * (float)$nAddOnAmount;
    $addonExists = false;

    $sql = "SELECT * FROM customer_addons WHERE customer_id = \"$customerId\"";
    $result = $oDbConnection->query($sql);

    while ($row = $result->fetch_object()) {
        if ($row->addon_id == $sAddOnId) {
            $currentAmount = $row->addon_amount;
            $newAmount = $currentAmount + $nAddOnAmount;
            $sql = "UPDATE customer_addons SET addon_amount = \"$newAmount\" WHERE customer_addon_id = \"$row->customer_addon_id\"";
            $oDbConnection->query($sql);
            $addonExists = true;
        }
    }

    if (!$addonExists) {
        $stmt_6 = $oDbConnection->prepare("INSERT INTO customer_addons (customer_addon_id, customer_id, addon_id, addon_amount) VALUES ( null,?,?,?)");
        $stmt_6->bind_param("iii", $customerId, $sAddOnId, $nAddOnAmount);
        $stmt_6->execute();
        $stmt_6->insert_id;
    }

    $stmt_7 = $oDbConnection->prepare("INSERT INTO order_addons (order_addons_id, order_id, addon_id, payed_price, addon_amount) VALUES(null,?,?,?,?)");
    $stmt_7->bind_param("iiss", $orderId, $sAddOnId, $payed_price, $nAddOnAmount);
    $stmt_7->execute();
    $stmt_7->insert_id;
}


header("Location: ../MAILER/send-email.php");
