<?php
session_start();
include("../db-connection/connection.php");
include("./create-pdf-receipt.php");

if (!isset($_SESSION['purchaseProcess'])) {
    header("Location: ../index.php");
    exit();
}



if (!isset($_SESSION['loginStatus'])) {
    //Get the customer data from post and put them into variables
    $sCustomerFirstName = $oDbConnection->real_escape_string($_POST['customerFirstName']);
    $sCustomerLastName = $oDbConnection->real_escape_string($_POST['customerLastName']);
    $sCustomerEmail = $oDbConnection->real_escape_string($_POST['customerEmail']);
    $sCustomerPhone = $oDbConnection->real_escape_string($_POST['customerPhone']);
    $sCompanyName = $oDbConnection->real_escape_string($_POST['companyName']);
    $sCompanyCvr = $oDbConnection->real_escape_string($_POST['companyCvr']);
    //Hashing the password with the password_hash function
    $sCustomerPasswordHashed = password_hash($_POST['customerPasswordConfirm'], PASSWORD_DEFAULT);
    //The confirm code for the customer, 32 characters randomly generated
    $nCustomerConfirmCode = bin2hex(random_bytes(32));
    $sCompanyStreet = $oDbConnection->real_escape_string($_POST['companyStreet']);
    $sCompanyZip = $oDbConnection->real_escape_string($_POST['companyZip']);
    $sCompanyCity = $oDbConnection->real_escape_string($_POST['companyCity']);
    $sCompanyCountry = $oDbConnection->real_escape_string($_POST['companyCountry']);
    //The default setting for wheter a customer has confirmed their account or not
    $nCustomerConfirmed = 0;
    //Querying using the prepare statement for securing against sql injections
    $oCustomerInsertSql = $oDbConnection->prepare("INSERT INTO customers (customer_id ,customer_first_name, customer_last_name, customer_company_name, customer_email, customer_password, customer_company_cvr, customer_city, customer_address, customer_country,customer_postcode,customer_phone, customer_confirm_code, customer_confirmed) VALUES ( null,?,?,?,?,?,?,?,?,?,?,?,?,?)");
    $oCustomerInsertSql->bind_param("ssssssssssssi", $sCustomerFirstName, $sCustomerLastName, $sCompanyName, $sCustomerEmail, $sCustomerPasswordHashed, $sCompanyCvr, $sCompanyCity, $sCompanyStreet, $sCompanyCountry, $sCompanyZip, $sCustomerPhone, $nCustomerConfirmCode, $nCustomerConfirmed);
    $oCustomerInsertSql->execute();
    //Getting the ID of the newly created customer
    $sCustomerId = $oCustomerInsertSql->insert_id;
    //Putting the data in the session
    $_SESSION['customerId'] = $sCustomerId;
    $_SESSION['customerData'] = json_encode($_POST);
    $_SESSION['customerConfirmCode'] = $nCustomerConfirmCode;
} else {
    //If the user is already logged in, get their data from the database
    $sCustomerId = $_SESSION['customerId'];
    $sCustomerSelectSql = "SELECT * FROM customers WHERE customer_id = \"$sCustomerId\"";
    $oCustomerResult = $oDbConnection->query($sCustomerSelectSql);
    $oCustomerRow = $oCustomerResult->fetch_object();
    $aCustomerData = array(
        "companyName" => $oCustomerRow->customer_company_name,
        "companyStreet" => $oCustomerRow->customer_address,
        "companyCity" => $oCustomerRow->customer_city,
        "companyZip" => $oCustomerRow->customer_postcode,
        "companyCvr" => $oCustomerRow->customer_company_cvr,
        "customerEmail" => $oCustomerRow->customer_email,
        "customerFirstName" => $oCustomerRow->customer_first_name,
        "customerLastName" =>  $oCustomerRow->customer_last_name
    );
    //Emulating a newly created user
    $_SESSION['customerData'] = json_encode($aCustomerData);
}
//Creating the order object
$nCurrentDate = time();
$oOrderInsertSql = $oDbConnection->prepare("INSERT INTO orders (order_id , customer_id, order_date ) VALUES(null,?,?)");
$oOrderInsertSql->bind_param("ii", $sCustomerId, $nCurrentDate);
$oOrderInsertSql->execute();
//Getting the order id when data is inserted
$sOrderId = $oOrderInsertSql->insert_id;
$_SESSION['orderId'] = $sOrderId;
//Creating an order with the current time in epoch
createReceipt($nCurrentDate);

if (isset($_SESSION['cartProducts'])) {
    //If there are any products in the purchase, loop through them 
    foreach ($_SESSION['cartProducts'] as $aProduct) {
        $nApiKey = bin2hex(random_bytes(32));
        //the iframe embed that gates off customers which license has run out
        $sEmbed = "<iframe src='http://127.0.0.1/KEA_Bachelor/purple-scout/product-emulator.php?key=$nApiKey' frameborder='0'></iframe>";
        $sProductId = $aProduct['productId'];
        $sSubscriptionId = $aProduct['subscriptionId'];
        $nProductPrice = $aProduct['productPrice'];
        //Getting the subscription data
        $sSubscriptionSelectSql = "SELECT * FROM subscriptions WHERE subscription_id = \"$sSubscriptionId\"";
        $oSubscriptionResult = $oDbConnection->query($sSubscriptionSelectSql);
        $oSubscriptionRow = $oSubscriptionResult->fetch_object();
        //creating the customer_product entry in the database
        $nSubscriptionLength = (float)$oSubscriptionRow->subscription_length;
        $nSubscriptionEnd = $nCurrentDate + $nSubscriptionLength;
        //The default setting for active subscription and auto renew
        $nSubscriptionActive = 1;
        $nSubscriptionRenew = 1;
        //Insert the data in the database
        $sCustomerProductInsertSql = $oDbConnection->prepare("INSERT INTO customer_products (customer_products_id ,customer_id, product_id, subscription_start, subscription_total_length, subscription_end, subscription_active, subscription_autorenew, api_key, embed_link) VALUES ( null,?,?,?,?,?,?,?,?,?)");
        $sCustomerProductInsertSql->bind_param("iiiiiiiss", $sCustomerId, $sProductId, $nCurrentDate, $nSubscriptionLength, $nSubscriptionEnd, $nSubscriptionActive, $nSubscriptionRenew, $nApiKey, $sEmbed);
        $sCustomerProductInsertSql->execute();

        //Create the link to the order_products
        $sOrderProductInsertSql = $oDbConnection->prepare("INSERT INTO order_products (order_products_id, order_id, product_id, subscription_id, order_products_payed_price) VALUES(null,?,?,?,?)");
        $sOrderProductInsertSql->bind_param("iiii", $sOrderId, $sProductId, $sSubscriptionId, $nProductPrice);
        $sOrderProductInsertSql->execute();
    }
}

if (isset($_SESSION['cartAddOns'])) {
    //If there are addons in the card, loop through them
    foreach ($_SESSION['cartAddOns'] as $aAddOn) {
        //if the user already has bought the addon before, either add the amount to the existing record
        //or create the new addon link to the customer
        $bAddonExists = false;
        $sAddOnId = $aAddOn['addOnId'];
        $nAddOnAmount = $aAddOn['addOnAmount'];
        $nAddOnPrice = $aAddOn['addOnPrice'];
        $nAddonTotalprice = $nAddOnPrice * $nAddOnAmount;

        //Quering at the database
        $sCustomerAddonSelectSql = "SELECT * FROM customer_addons WHERE customer_id = \"$sCustomerId\"";
        $oCustomerAddonResult = $oDbConnection->query($sCustomerAddonSelectSql);
        //check to see for each entry if already exists
        while ($oCustomerAddonRow = $oCustomerAddonResult->fetch_object()) {
            if ($oCustomerAddonRow->addon_id == $sAddOnId) {
                $nCustomerAddonAmount = (float)$oCustomerAddonRow->addon_amount;
                $nNewCustomerAddonAmount = $nCustomerAddonAmount + $nAddOnAmount;
                //Update the entry
                $sCustomerAddonUpdateSql = "UPDATE customer_addons SET addon_amount = \"$nNewCustomerAddonAmount\" WHERE customer_addon_id = \"$oCustomerAddonRow->customer_addon_id\"";
                $oDbConnection->query($sCustomerAddonUpdateSql);
                $bAddonExists = true;
            }
        }
        //If doesn't already exist create a new entry
        if (!$bAddonExists) {
            $sCustomerAddonInsertSql = $oDbConnection->prepare("INSERT INTO customer_addons (customer_addon_id, customer_id, addon_id, addon_amount) VALUES ( null,?,?,?)");
            $sCustomerAddonInsertSql->bind_param("iii", $sCustomerId, $sAddOnId, $nAddOnAmount);
            $sCustomerAddonInsertSql->execute();
        }
        //Create a link to the order addons table with the addon
        $sOrderAddonInsertSql = $oDbConnection->prepare("INSERT INTO order_addons (order_addons_id, order_id, addon_id, order_addon_payed_price, addon_amount) VALUES(null,?,?,?,?)");
        $sOrderAddonInsertSql->bind_param("iiss", $sOrderId, $sAddOnId, $nAddonTotalprice, $nAddOnAmount);
        $sOrderAddonInsertSql->execute();
    }
}

//Redirect to the send email function
header("Location: ../MAILER/send-order-email.php");
