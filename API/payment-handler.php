<?php
session_start();
echo "starting the submission";
include("../db-connection/connection.php");
include("./create-pdf-receipt.php");
//echo json_encode($_POST['customerFirstName']);
//$sql = "INSERT INTO customers VALUES (customer_first_name, customer_last_name, customer_company_name, customer_email, customer_password, customer_cvr) VALUES ('John', 'Doe', 'john@example.com')";
//$result = $oDbConnection->query();

/* if (!isset($_SESSION['purchaseProcess'])) {
    header("Location: ../index.php");
    exit();
} */

$sEmbed = "<iframe src='http://127.0.0.1/KEA_Bachelor/purple-scout/product-emulator.php?key=INSERT KEY HERE' frameborder='0'></iframe>";

if (!isset($_SESSION['loginStatus'])) {
    echo '18';
    $sCustomerFirstName = $oDbConnection->real_escape_string($_POST['customerFirstName']);
    //echo $sCustomerFirstName;
    $sCustomerLastName = $oDbConnection->real_escape_string($_POST['customerLastName']);
    //echo $sCustomerLastName;
    $sCustomerEmail = $oDbConnection->real_escape_string($_POST['customerEmail']);

    $sCustomerPhone = $oDbConnection->real_escape_string($_POST['customerPhone']);
    //echo $sCustomerEmail;
    $sCompanyName = $oDbConnection->real_escape_string($_POST['companyName']);
    //echo $sCompanyName;
    $sCompanyCvr = $oDbConnection->real_escape_string($_POST['companyCvr']);
    //echo $sCompanyCvr;
    //echo $_SESSION['tempUserData']->uPassword;
    $customerPasswordHashed = password_hash($_POST['customerPasswordConfirm'], PASSWORD_DEFAULT);
    //echo $customerPasswordHashed;
    $nCustomerConfirmCode = bin2hex(random_bytes(32));
    //echo $nProfileConfirmCode;    

    $sCompanyStreet = $oDbConnection->real_escape_string($_POST['companyStreet']);
    $sCompanyZip = $oDbConnection->real_escape_string($_POST['companyZip']);
    $sCompanyCity = $oDbConnection->real_escape_string($_POST['companyCity']);
    $sCompanyCountry = $oDbConnection->real_escape_string($_POST['companyCountry']);
    $nCustomerConfirmed = 0;

    $oCustomerInsertSql = $oDbConnection->prepare("INSERT INTO customers (customer_id ,customer_first_name, customer_last_name, customer_company_name, customer_email, customer_password, customer_cvr, customer_city, customer_address, customer_country,customer_postcode,customer_phone, customer_confirm_code, customer_confirmed) VALUES ( null,?,?,?,?,?,?,?,?,?,?,?,?,?)");
    $oCustomerInsertSql->bind_param("ssssssssssssi", $sCustomerFirstName, $sCustomerLastName, $sCompanyName, $sCustomerEmail, $customerPasswordHashed, $sCompanyCvr, $sCompanyCity, $sCompanyStreet, $sCompanyCountry, $sCompanyZip, $sCustomerPhone, $nCustomerConfirmCode, $nCustomerConfirmed);

    $oCustomerInsertSql->execute();
    $sCustomerId = $oCustomerInsertSql->insert_id;
    $_SESSION['customerId'] = $sCustomerId;
    $_SESSION['customerData'] = json_encode($_POST);
    $_SESSION['customerConfirmCode'] = $nCustomerConfirmCode;
} else {
    echo '52';
    $sCustomerId = $_SESSION['customerId'];
    $sCustomerSelectSql = "SELECT * FROM customers WHERE customer_id = \"$sCustomerId\"";
    $oCustomerResult = $oDbConnection->query($sCustomerSelectSql);
    $oCustomerRow = $oCustomerResult->fetch_object();
    $aCustomerData = array(
        "companyName" => $oCustomerRow->customer_company_name,
        "companyStreet" => $oCustomerRow->customer_address,
        "companyCity" => $oCustomerRow->customer_city,
        "companyZip" => $oCustomerRow->customer_postcode,
        "companyCvr" => $oCustomerRow->customer_cvr,
        "customerEmail" => $oCustomerRow->customer_email,
        "customerFirstName" => $oCustomerRow->customer_first_name,
        "customerLastName" =>  $oCustomerRow->customer_last_name
    );
    $_SESSION['customerData'] = json_encode($aCustomerData);
}

$nCurrentDate = time();
$oOrderInsertSql = $oDbConnection->prepare("INSERT INTO orders (order_id , customer_id, order_date ) VALUES(null,?,?)");
$oOrderInsertSql->bind_param("ii", $sCustomerId, $nCurrentDate);
$oOrderInsertSql->execute();
$sOrderId = $oOrderInsertSql->insert_id;
$_SESSION['orderId'] = $sOrderId;
createReceipt($nCurrentDate);

//echo "sucess";

if (isset($_SESSION['cartProducts'])) {
    foreach ($_SESSION['cartProducts'] as $aProduct) {
        $nApiKey = bin2hex(random_bytes(32));
        $sProductId = $aProduct['productId'];
        $sSubscriptionId = $aProduct['subscriptionId'];
        $nProductPrice = $aProduct['productPrice'];
        $sSubscriptionSelectSql = "SELECT * FROM subscriptions WHERE subscription_id = \"$sSubscriptionId\"";
        $oSubscriptionResult = $oDbConnection->query($sSubscriptionSelectSql);
        $oSubscriptionRow = $oSubscriptionResult->fetch_object();
        $nSubscriptionLength = (float)$oSubscriptionRow->subscription_length;
        $nSubscriptionEnd = $nCurrentDate + $nSubscriptionLength;
        //echo $subEnd;
        $nSubscriptionActive = 1;
        $nSubscriptionRenew = 1;
        $sCustomerProductInsertSql = $oDbConnection->prepare("INSERT INTO customer_products (customer_products_id ,customer_id, product_id, subscription_start, subscription_total_length, subscription_end, subscription_active, subscription_autorenew, api_key, embed_link) VALUES ( null,?,?,?,?,?,?,?,?,?)");
        $sCustomerProductInsertSql->bind_param("iiiiiiiss", $sCustomerId, $sProductId, $nCurrentDate, $nSubscriptionLength, $nSubscriptionEnd, $nSubscriptionActive, $nSubscriptionRenew, $nApiKey, $sEmbed);
        $sCustomerProductInsertSql->execute();
        $sCustomerProductId = $sCustomerProductInsertSql->insert_id;

        $sOrderProductInsertSql = $oDbConnection->prepare("INSERT INTO order_products (order_products_id, order_id, product_id, subscription_id, payed_price) VALUES(null,?,?,?,?)");
        $sOrderProductInsertSql->bind_param("iiii", $sOrderId, $sProductId, $sSubscriptionId, $nProductPrice);
        $sOrderProductInsertSql->execute();
        $sOrderProductInsertSql->insert_id;
    }
}


if (isset($_SESSION['cartAddOns'])) {
    foreach ($_SESSION['cartAddOns'] as $aAddOn) {
        $bAddonExists = false;
        $sAddOnId = $aAddOn['addOnId'];
        $nAddOnAmount = $aAddOn['addOnAmount'];
        $nAddOnPrice = $aAddOn['addOnPrice'];
        $nAddonTotalprice = $nAddOnPrice * $nAddOnAmount;


        $sCustomerAddonSelectSql = "SELECT * FROM customer_addons WHERE customer_id = \"$sCustomerId\"";
        $oCustomerAddonResult = $oDbConnection->query($sCustomerAddonSelectSql);

        while ($oCustomerAddonRow = $oCustomerAddonResult->fetch_object()) {
            if ($oCustomerAddonRow->addon_id == $sAddOnId) {
                $nCustomerAddonAmount = (float)$oCustomerAddonRow->addon_amount;
                $nNewCustomerAddonAmount = $nCustomerAddonAmount + $nAddOnAmount;
                $sCustomerAddonUpdateSql = "UPDATE customer_addons SET addon_amount = \"$nNewCustomerAddonAmount\" WHERE customer_addon_id = \"$oCustomerAddonRow->customer_addon_id\"";
                $oDbConnection->query($sCustomerAddonUpdateSql);
                $bAddonExists = true;
            }
        }

        if (!$bAddonExists) {
            $sCustomerAddonInsertSql = $oDbConnection->prepare("INSERT INTO customer_addons (customer_addon_id, customer_id, addon_id, addon_amount) VALUES ( null,?,?,?)");
            $sCustomerAddonInsertSql->bind_param("iii", $sCustomerId, $sAddOnId, $nAddOnAmount);
            $sCustomerAddonInsertSql->execute();
            $sCustomerAddonInsertSql->insert_id;
        }

        $sOrderAddonInsertSql = $oDbConnection->prepare("INSERT INTO order_addons (order_addons_id, order_id, addon_id, payed_price, addon_amount) VALUES(null,?,?,?,?)");
        $sOrderAddonInsertSql->bind_param("iiss", $sOrderId, $sAddOnId, $nAddonTotalprice, $nAddOnAmount);
        $sOrderAddonInsertSql->execute();
        $sOrderAddonInsertSql->insert_id;
    }
}


header("Location: ../MAILER/send-email.php");
