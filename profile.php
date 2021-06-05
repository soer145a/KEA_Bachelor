<?php
session_start();
include("db-connection/connection.php");
include_once("components/head.php");
include_once("components/header.php");
include_once("components/footer.php");
$sHeadHtmlComp = headComp();
$sHeaderHtmlComp = headerComp('profile');
$sFooterHtmlComp = footerComp();
//Reset the error message variabel
$sErrorMessage = "";
$bShowFlag = false;
//Deny the entry to any customers that is not logged in
if (!isset($_SESSION['loginStatus'])) {
    header('Location: login.php');
} else {
    $customerId = $_SESSION['customerId'];
    //Get the user data for the update inputs
    $sCustomerSelectSql = "SELECT * FROM customers WHERE customer_id = \"$customerId\"";
    $oCustomerResult = $oDbConnection->query($sCustomerSelectSql);
    $oCustomerRow = $oCustomerResult->fetch_object();
    $sCustomerFirstName = $oCustomerRow->customer_first_name;
    $sCustomerLastName = $oCustomerRow->customer_last_name;
    $sCompanyName = $oCustomerRow->customer_company_name;
    $sCustomerEmail = $oCustomerRow->customer_email;
    $sCompanyCvr = $oCustomerRow->customer_cvr;
    $sCustomerCity = $oCustomerRow->customer_city;
    $sCustomerStreet = $oCustomerRow->customer_address;
    $sCustomerCountry = $oCustomerRow->customer_country;
    $sCustomerZip = $oCustomerRow->customer_postcode;
    $sCustomerPhone = $oCustomerRow->customer_phone;

    $_SESSION['customerFirstName'] = $sCustomerFirstName;
    $_SESSION['customerLastName'] = $sCustomerLastName;
}
if (isset($_POST['confirmPassword'])) {

    $sCustomerPassword = $oDbConnection->real_escape_string($_POST['confirmPassword']);
    $sCustomerSelectSql = "SELECT * FROM customers WHERE customer_id = \" $customerId \"";
    $oCustomerResult = $oDbConnection->query($sCustomerSelectSql);
    if ($oCustomerResult->num_rows > 0) {
        $oCustomerRow = $oCustomerResult->fetch_object();
        //echo json_encode($oCustomerRow);
        $sCustomerDbPassword = $oCustomerRow->customer_password;
        if (password_verify($sCustomerPassword, $sCustomerDbPassword)) {
            header("Location: api/delete-user-information.php");
        } else {
            $sErrorMessage = "<p style='color:red'> ERROR - You don' fuckd up kiddo</p>";
            $bShowFlag = true;
        }
    }
}

$sEmbedLink = "";
$sApiKey = "";

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?= $sHeadHtmlComp ?>
</head>

<body>
    <?= $sHeaderHtmlComp ?>
    <main>
        <section id="profile">
            <div class="layout-container profile">
                <h1 class="section-header profile__header">Welcome <?= $sCustomerFirstName, " ", $sCustomerLastName ?></h1>
                <div class="profile__main">
                    <div id="deleteModal" class="hidden delete-profile modal--delete">
                        <h2 class="section-header">Are you sure you want to delete your data?</h2>
                        <p class="section-paragraph">You are about to delete every data we have regarding your product and your orders. <br>
                            Going foward with this, there will be no recovering this information, and your product and licenses will be removed from your account.</p>
                        <div>
                            <p class="section-paragraph">You will be deleting:</p>
                            <ul>
                                <?php

                                $sCustomerProductSelectSql = "SELECT count(*) FROM `customer_products` WHERE `customer_id` = \"$customerId\"";
                                $oCustomerProductResult = $oDbConnection->query($sCustomerProductSelectSql);
                                $oCustomerProductRow = $oCustomerProductResult->fetch_assoc();
                                $nCustomerProductAmount = $oCustomerProductRow['count(*)'];
                                echo "<li> $nCustomerProductAmount products with active licences</li>";
                                $sCustomerAddonSelectSql = "SELECT * FROM customer_addons LEFT JOIN addons ON customer_addons.addon_id  = addons.addon_id  WHERE `customer_id` = \"$customerId\"";
                                $oCustomerAddonResults = $oDbConnection->query($sCustomerAddonSelectSql);
                                while ($oCustomerAddonRow = $oCustomerAddonResults->fetch_assoc()) {
                                    $nAddOnAmount = $oCustomerAddonRow['addon_amount'];
                                    $sAddonName = $oCustomerAddonRow['addon_name'];
                                    echo "<li> $nAddOnAmount $sAddonName's in our database</li>";
                                }

                                $sOrderSelectSql = "SELECT count(*) FROM `orders` WHERE `customer_id` = \"$customerId\"";
                                $oOrderResults = $oDbConnection->query($sOrderSelectSql);
                                $oOrderRow = $oOrderResults->fetch_assoc();
                                $nOrderAmount = $oOrderRow['count(*)'];
                                echo "<li> $nOrderAmount orders in our database</li>";
                                ?>
                            </ul>

                        </div>
                        <div class="delete-profile__button-container">
                            <button class="delete-profile__button button button--purple" onclick="cancelDeletion()">Cancel</button>
                            <button class="delete-profile__button button button--red" onclick="showDeleteOption2()">I Understand</button>
                        </div>
                    </div>
                    <div id="deleteModalTotal" class="modal modal--delete <?php if ($bShowFlag) {
                                                                                echo "shown";
                                                                            } else {
                                                                                echo "hidden";
                                                                            } ?> ">
                        <h2 class="section-header">Enter password</h2>
                        <p class="section-paragraph">By entering your password, your account will be deleted.</p>
                        <form class="modal-form" method="post">
                            <div class="form-wrapper">
                                <label class="customer-password-form__input-label">Password</label>
                                <input class="customer-password-form__input" type="password" name="password" oninput="checkPassword()" id="CustomerPassword">
                            </div>
                            <div class="form-wrapper">
                                <label class="customer-password-form__input-label">Confirm Password</label>
                                <input class="customer-password-form__input" type="password" name="confirmPassword" oninput="checkPassword()" id="CustomerPasswordConfirm">
                            </div>
                            <?= "<input type='hidden' name='userID' value='$customerId'>" ?>
                            <?= $sErrorMessage ?>
                            <div class="button-wrapper">
                                <button type="button" class="delete-profile__button button button--purple" onclick="removeDeleteModals()">Cancel</button>
                                <button disabled id="deleteButton" class="delete-profile__button button button--red">DELETE MY ACOUNT</button>
                            </div>
                        </form>
                    </div>
                    <div class="customerInfoContainer">
                        <?php
                        $sCustomerProductHtml = "";
                        $sCustomerProductSelectSql = "SELECT * FROM customer_products LEFT JOIN products ON customer_products.product_id  = products.product_id";
                        $oCustomerProductResults = $oDbConnection->query($sCustomerProductSelectSql);

                        while ($oCustomerProductRow = $oCustomerProductResults->fetch_object()) {
                            //echo json_encode($oCustomerProductRow);

                            $sCharsToReplace = array("<", ">");
                            $sReplaceCharsWith = array("&lt;", "&gt;");
                            $sEmbedLink = str_replace($sCharsToReplace, $sReplaceCharsWith, $oCustomerProductRow->embed_link);
                            $sApiKey = $oCustomerProductRow->api_key;
                            $sCustomerProductId = $oCustomerProductRow->customer_products_id;
                            $oStartDate = new DateTime("@$oCustomerProductRow->subscription_start");
                            $sCustomerProductStartDate = $oStartDate->format('Y-m-d');
                            $oEndDate = new DateTime("@$oCustomerProductRow->subscription_end");
                            $sCustomerProductEndDate = $oEndDate->format('Y-m-d');
                            $nSubscriptionTimeLeft = $oCustomerProductRow->subscription_end - time();
                            //echo $nSubscriptionTimeLeft/86400;
                            $nSubscriptionDaysLeft = round($nSubscriptionTimeLeft / 86400);

                            if ($nSubscriptionDaysLeft <= 0) {
                                $sCustomerProductUpdateSql = "UPDATE customer_products SET subscription_active = 0 WHERE customer_products_id = \"$sCustomerProductId\"";
                                $oDbConnection->query($sCustomerProductUpdateSql);
                                $oCustomerProductRow->subscription_active = 0;
                                $nSubscriptionDaysLeft = 0;
                            }

                            $nCustomerProductTotalDays = round($oCustomerProductRow->subscription_total_length / 86400);

                            $sCustomerProductId = $oCustomerProductRow->customer_products_id;

                            if ($oCustomerProductRow->subscription_autorenew) {
                                $sAutoRenew = "On";
                                $sButtonToggle = "Off";
                            } else {
                                $sAutoRenew = "Off";
                                $sButtonToggle = "On";
                            }


                            $sProductName = $oCustomerProductRow->product_name;

                            if ($oCustomerProductRow->subscription_active) {
                                $sCustomerProductHtml = $sCustomerProductHtml . "<div class='profileCard'>
                                                        <h1>$sProductName</h1>                                                        
                                                        <div class='subInfo'>
                                                            <p>FROM: $sCustomerProductStartDate || TO: $sCustomerProductEndDate</p>
                                                            <p>Total days: $nCustomerProductTotalDays</p>
                                                            <p>$nSubscriptionDaysLeft days left</p>
                                                        </div>
                                                        <p>Embed link:</p>
                                                        <pre><code class='html'> $sEmbedLink</code></pre>
                                                        <p>api Key:</p>
                                                        <pre><code class='html'>$sApiKey</code></pre>
                                                        <p>Auto renew subscription: <span><b>$sAutoRenew</b></span></p>
                                                        <button onclick='toggleAutoRenew($sCustomerProductId)'>Switch Autorenew $sButtonToggle</button>
                                                    </div>";
                            }
                        }
                        echo $sCustomerProductHtml;
                        ?>
                    </div>
                    <div class="account-information">
                        <div class="customer-information">
                            <div class="customer-information-container">
                                <h4 class="section-subheader">Company Information</h4>
                                <div class="customer-information-wrapper">
                                    <div class="customer-information__item customer-information__company-name">
                                        <p class="section-paragraph customer-information__item__text"><?= $sCompanyName ?></p>
                                        <span class="customer-information__item__icon-outer" onclick="editInfo('<?= $sCompanyName ?>', 'string', 'customer_company_name')">
                                            <span class="customer-information__item__icon-inner "></span>
                                        </span>
                                    </div>
                                    <div class="customer-information__item customer-information__cvr">
                                        <p class="section-paragraph customer-information__item__text">CVR: <?= $sCompanyCvr ?></p>
                                        <span class="customer-information__item__icon-outer" onclick="editInfo( '<?= $sCompanyCvr ?>', 'cvr', 'customer_cvr')">
                                            <span class="customer-information__item__icon-inner "></span>
                                        </span>
                                    </div>
                                    <div class="customer-information__item customer-information__streetname">
                                        <p class="section-paragraph customer-information__item__text"><?= $sCustomerStreet ?></p>
                                        <span class="customer-information__item__icon-outer" onclick="editInfo( '<?= $sCustomerStreet ?>', 'string', 'customer_address')">
                                            <span class="customer-information__item__icon-inner "></span>
                                        </span>
                                    </div>
                                    <div class="customer-information__item customer-information__zipcode">
                                        <p class="section-paragraph customer-information__item__text"><?= $sCustomerZip ?></p>
                                        <span class="customer-information__item__icon-outer" onclick="editInfo( '<?= $sCustomerZip ?>', 'string', 'customer_postcode')">
                                            <span class="customer-information__item__icon-inner "></span>
                                        </span>
                                    </div>
                                    <div class="customer-information__item customer-information__city">
                                        <p class="section-paragraph customer-information__item__text"><?= $sCustomerCity ?></p>
                                        <span class="customer-information__item__icon-outer" onclick="editInfo('<?= $sCustomerCity ?>', 'string', 'customer_city')">
                                            <span class="customer-information__item__icon-inner "></span>
                                        </span>
                                    </div>
                                    <div class="customer-information__item customer-information__country">
                                        <p class="section-paragraph customer-information__item__text"><?= $sCustomerCountry ?></p>
                                        <span class="customer-information__item__icon-outer" onclick="editInfo('<?= $sCustomerCountry ?>', 'string', 'customer_country')">
                                            <span class="customer-information__item__icon-inner "></span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="customer-information-container">
                                <h4 class="section-subheader">Contact Person</h4>
                                <div class="customer-information-wrapper">
                                    <div class="customer-information__item customer-information__firstname">
                                        <p class="section-paragraph customer-information__item__text"><?= $sCustomerFirstName ?></p>
                                        <span class="customer-information__item__icon-outer" onclick="editInfo('<?= $sCustomerFirstName ?>', 'string', 'customer_first_name')">
                                            <span class="customer-information__item__icon-inner "></span>
                                        </span>
                                    </div>
                                    <div class="customer-information__item customer-information__lastname">
                                        <p class="section-paragraph customer-information__item__text"><?= $sCustomerLastName ?></p>
                                        <span class="customer-information__item__icon-outer" onclick="editInfo('<?= $sCustomerLastName ?>', 'string', 'customer_last_name')">
                                            <span class="customer-information__item__icon-inner "></span>
                                        </span>
                                    </div>
                                    <div class="customer-information__item customer-information__email">
                                        <p class="section-paragraph customer-information__item__text"><?= $sCustomerEmail ?></p>
                                        <span class="customer-information__item__icon-outer" onclick="editInfo('<?= $sCustomerEmail ?>', 'email', 'customer_email')">
                                            <span class="customer-information__item__icon-inner "></span>
                                        </span>

                                    </div>
                                    <div class="customer-information__item customer-information__phone">
                                        <p class="section-paragraph customer-information__item__text"><?= $sCustomerPhone ?></p>
                                        <span class="customer-information__item__icon-outer" onclick="editInfo('<?= $sCustomerPhone ?>', 'phone', 'customer_phone')">
                                            <span class="customer-information__item__icon-inner "></span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="customer-information-container">
                                <h4 class="section-subheader">Edit password</h4>
                                <form class="customer-password-form" method="post" onsubmit="return inputValidate();" action="api/update-customer-data.php">
                                    <div class="form-wrapper">
                                        <label class="customer-password-form__input-label">New password: <span class="login-form__label-info-outer js-toggle-infobox">
                                                <span class="login-form__label-info-inner">
                                                </span>
                                            </span>
                                            <span class="login-form__label-info-box js-toggle-infobox login-form__label-info-box--hidden">
                                                <h5 class="section-subheader label-info-box__header">The password must concist of:</h5>
                                                <ul>
                                                    <li>6-30 characters</li>
                                                    <li>One uppercase character</li>
                                                    <li>One numeric character</li>
                                                    <li>One special character.</li>
                                                </ul>
                                            </span>
                                        </label>
                                        <input id="newPassword" class="customer-password-form__input" oninput="inputValidate()" data-validate="password" type="password" name="customerPassword" placeholder="New password">
                                    </div>
                                    <div class="form-wrapper">
                                        <label for="confirmPassword" class="customer-password-form__input-label">Confirm new password:</label>
                                        <input id="confirmPassword" class="customer-password-form__input" oninput="inputValidate()" data-validate="password" type="password" name="customerPasswordConfirm" placeholder="Confirm password">
                                    </div>
                                    <div class="form-wrapper">
                                        <label for="oldPassword" class="customer-password-form__input-label">Old password:</label>
                                        <input id="oldPassword" class="customer-password-form__input" data-validate="password" type="password" name="customerPassword" placeholder="Type your old password">
                                    </div>
                                    <button class="button button--yellow customer-password-form__button" type="submit">Change password</button>
                                    <!-- <div class="errorMessage"></div> -->
                                </form>
                            </div>
                            <button class="customer-information__button button button--red" onclick="showDeleteOption()">Delete account</button>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <?= $sFooterHtmlComp ?>
</body>
<script src="//cdnjs.cloudflare.com/ajax/libs/highlight.js/10.7.2/highlight.min.js"></script>
<script>
    hljs.highlightAll();
</script>
<script src="js/app.js"></script>
<script src="js/helper.js"></script>

</html>