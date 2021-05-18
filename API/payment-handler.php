<?php
session_start();
echo "starting the submission";
include("../DB_Connection/connection.php");
//echo json_encode($_POST['input_first_name']);
//$sql = "INSERT INTO customers VALUES (customer_first_name, customer_last_name, customer_company_name, customer_email, customer_password, customer_cvr) VALUES ('John', 'Doe', 'john@example.com')";
//$result = $conn->query();

$cartProducts = $_SESSION['cartProducts'];
$cartAddOns = $_SESSION['cartAddOns'];
$embed = "<iframe src='https://purplescout.placeholder.dk/key' frameborder='0'></iframe>";


if (!isset($_SESSION['loginStatus'])) {
    $dbFirstName = $conn->real_escape_string($_POST['input_first_name']);
    //echo $dbFirstName;
    $dbLastName = $conn->real_escape_string($_POST['input_last_name']);
    //echo $dbLastName;
    $dbEmail = $conn->real_escape_string($_POST['input_email']);

    $dbPhone = $conn->real_escape_string($_POST['input_phone']);
    //echo $dbEmail;
    $dbCompanyName = $conn->real_escape_string($_POST['input_company_name']);
    //echo $dbCompanyName;
    $dbCVR = $conn->real_escape_string($_POST['input_company_cvr']);
    //echo $dbCVR;
    //echo $_SESSION['tempUserData']->uPassword;
    $hashedPassword = password_hash($_POST['input_password_confirm'], PASSWORD_DEFAULT);
    //echo $hashedPassword;
    $confirmCode = bin2hex(random_bytes(32));
    //echo $confirmCode;    

    $dbCompanyStreet = $conn->real_escape_string($_POST['input_company_street']);
    $dbCompanyPostcode = $conn->real_escape_string($_POST['input_company_Postcode']);
    $dbCompanyCity = $conn->real_escape_string($_POST['input_company_city']);
    $dbCompanyCountry = $conn->real_escape_string($_POST['input_company_country']);

    $stmt = $conn->prepare("INSERT INTO customers (customer_id ,customer_first_name, customer_last_name, customer_company_name, customer_email, customer_password, customer_cvr, customer_city, customer_address, customer_country,customer_postcode,customer_phone, customer_confirm_code, customer_confirmed) VALUES ( null,?,?,?,?,?,?,?,?,?,?,?,?,?)");
    $i = 0;
    $stmt->bind_param("ssssssssssssi", $dbFirstName, $dbLastName, $dbCompanyName, $dbEmail, $hashedPassword, $dbCVR, $dbCompanyCity, $dbCompanyStreet, $dbCompanyCountry, $dbCompanyPostcode, $dbPhone, $confirmCode, $i);

    $stmt->execute();
    $customerId = $stmt->insert_id;
    $_SESSION['postData'] = json_encode($_POST);
    $_SESSION['confirmCode'] = $confirmCode;
} else {
    $customerId = $_SESSION['customer_id'];
    $sql = "SELECT * FROM customers WHERE customer_id = \"$customerId\"";
    $result = $conn->query($sql);
    $row = $result->fetch_object();
    $customerEmail = $row->customer_email;
    $postData = array("input_email" => $customerEmail, "input_first_name" => $_SESSION['customer_first_name'], "input_last_name" =>  $_SESSION['customer_last_name']);
    $_SESSION['postData'] = json_encode($postData);
}

$currentDate = time();
$stmt_2 = $conn->prepare("INSERT INTO orders (order_id , customer_id, order_date ) VALUES(null,?,?)");
$stmt_2->bind_param("ii", $customerId, $currentDate);
$stmt_2->execute();
$orderId = $stmt_2->insert_id;

//echo "sucess";
foreach ($cartProducts as $product) {
    $apiKey = bin2hex(random_bytes(32));
    $product_id = $product['product_id'];
    $subscription_id = $product['subscription_id'];
    $product_price = $product['product_price'];
    $sql = "SELECT * FROM subscriptions WHERE subscription_id = \"$subscription_id\"";
    $result = $conn->query($sql);
    $row = $result->fetch_object();
    $subLen = $row->subscription_length;
    $subEnd = $currentDate + $subLen;
    //echo $subEnd;
    $subActive = 1;
    $subAuto = 1;
    $stmt_3 = $conn->prepare("INSERT INTO customer_products (customer_products_id ,customer_id, product_id, subscription_start, subscription_total_length, subscription_end, subscription_active, subscription_autorenew, api_key, embed_link) VALUES ( null,?,?,?,?,?,?,?,?,?)");
    $stmt_3->bind_param("iiiiiiiss", $customerId, $product_id, $currentDate, $subLen, $subEnd, $subActive, $subAuto, $apiKey, $embed);
    $stmt_3->execute();
    $licenseID = $stmt_3->insert_id;

    $stmt_4 = $conn->prepare("INSERT INTO order_products (order_products_id, order_id, product_id, subscription_id, payed_price) VALUES(null,?,?,?,?)");
    $stmt_4->bind_param("iiii", $orderId, $product_id, $subscription_id, $product_price);
    $stmt_4->execute();
    $stmt_4->insert_id;
}

foreach ($cartAddOns as $addOn) {
    $addOn_id = $addOn['addon_id'];
    $addOn_amount = $addOn['addon_amount'];
    $addOn_price = $addOn['addon_price'];
    $payed_price = (float)$addOn_price * (float)$addOn_amount;
    $addonExists = false;

    $sql = "SELECT * FROM customer_addons WHERE customer_id = \"$customerId\"";
    $result = $conn->query($sql);

    while ($row = $result->fetch_object()) {
        if ($row->addon_id == $addOn_id) {
            $currentAmount = $row->addon_amount;
            $newAmount = $currentAmount + $addOn_amount;
            $sql = "UPDATE customer_addons SET addon_amount = \"$newAmount\" WHERE customer_addon_id = \"$row->customer_addon_id\"";
            $conn->query($sql);
            $addonExists = true;
        }
    }

    if (!$addonExists) {
        $stmt_6 = $conn->prepare("INSERT INTO customer_addons (customer_addon_id, customer_id, addon_id, addon_amount) VALUES ( null,?,?,?)");
        $stmt_6->bind_param("iii", $customerId, $addOn_id, $addOn_amount);
        $stmt_6->execute();
        $stmt_6->insert_id;
    }

    $stmt_7 = $conn->prepare("INSERT INTO order_addons (order_addons_id, order_id, addon_id, payed_price, addon_amount) VALUES(null,?,?,?,?)");
    $stmt_7->bind_param("iiss", $orderId, $addOn_id, $payed_price, $addOn_amount);
    $stmt_7->execute();
    $stmt_7->insert_id;
}

header("Location: ../MAILER/send-email.php");
