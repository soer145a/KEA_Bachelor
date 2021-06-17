<?php
session_start();
include("db-connection/connection.php");
include_once("components/head.php");
include_once("components/header.php");
include_once("components/footer.php");
include_once("components/inputInfoButton.php");
$sHeadHtmlComp = headComp();
$sHeaderHtmlComp = headerComp('profile');
$sFooterHtmlComp = footerComp();
$aListItems = array("<li class='infobox__list-item'>6-30 characters</li>", "<li class='infobox__list-item'>One uppercase character</li>", " <li class='infobox__list-item'>One numeric character</li>", "<li class='infobox__list-item'>One special character</li>");
$sPasswordInfoButtonHtml = inputInfoButtonComp($aListItems, "Password requirements");
//Reset the error message variabel
$sErrorMessage = "";
if (isset($_SESSION['wrongPassword'])) {
    $sErrorMessage = "<script>showMessage('Wrong Password',true);</script>";
    unset($_SESSION['wrongPassword']);
}
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
    $sCompanyCvr = $oCustomerRow->customer_company_cvr;
    $sCustomerCity = $oCustomerRow->customer_city;
    $sCustomerStreet = $oCustomerRow->customer_address;
    $sCustomerCountry = $oCustomerRow->customer_country;
    $sCustomerZip = $oCustomerRow->customer_postcode;
    $sCustomerPhone = $oCustomerRow->customer_phone;

    $_SESSION['customerFirstName'] = $sCustomerFirstName;
    $_SESSION['customerLastName'] = $sCustomerLastName;
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
                <h1 class="section-header profile__header">Welcome <span id="customerFirstNameHeader"><?= $sCustomerFirstName ?></span> <span id="customerLastNameHeader"><?= $sCustomerLastName ?></span></h1>
                <div class="profile__main">
                    <div id="deleteModal" class="modal--hidden delete-profile modal--delete">
                        <h2 class="section-header">Are you sure you want to delete your data?</h2>
                        <p class="section-paragraph">You are about to delete every data we have regarding your product and your orders. <br>
                            Going foward with this, there will be no recovering this information, and your product and licenses will be removed from your account.</p>
                        <div>
                            <p class="section-paragraph">You will be deleting:</p>
                            <ul>
                                <?php
                                //Creating the addon container and write the delete-user modal blocks
                                $sCustomerAddonHtmlContainer = "<div class='addon-card'>
                                <h4 class='section-header addon-card__header' onclick='toggleDropdownProfile(true)' >Addons:<span class='addon-card__arrow-outer' ><span class='addon-card__arrow-inner' id='addonRotateArrow'></span></span></h4>                                  
                                <div class='addon-info'>
                                <div class='collapsable collapsed' id='collapsableAddonContainer'>
                                ";
                                $sCustomerAddonHtml = "";
                                //We use count(*) to only get the numbers
                                $sCustomerProductSelectSql = "SELECT count(*) FROM `customer_products` WHERE `customer_id` = \"$customerId\"";
                                $oCustomerProductResult = $oDbConnection->query($sCustomerProductSelectSql);
                                $oCustomerProductRow = $oCustomerProductResult->fetch_assoc();
                                $nCustomerProductAmount = $oCustomerProductRow['count(*)'];
                                echo "<li> $nCustomerProductAmount products with active licences</li>";
                                //The addons the customer has access to
                                $sCustomerAddonSelectSql = "SELECT * FROM customer_addons LEFT JOIN addons ON customer_addons.addon_id  = addons.addon_id  WHERE `customer_id` = \"$customerId\"";
                                $oCustomerAddonResults = $oDbConnection->query($sCustomerAddonSelectSql);
                                if ($oCustomerAddonResults->num_rows > 0) {
                                    while ($oCustomerAddonRow = $oCustomerAddonResults->fetch_assoc()) {
                                        $nAddOnAmount = $oCustomerAddonRow['addon_amount'];
                                        $sAddonName = $oCustomerAddonRow['addon_name'];

                                        $sCustomerAddonHtml = $sCustomerAddonHtml . "
                                    <p class='section-paragraph addon-card__text'>$nAddOnAmount x <span class='addon-card__text--bold'>$sAddonName</span></p> ";

                                        echo "<li> $nAddOnAmount $sAddonName's in our database</li>";
                                    }
                                } else {
                                    $sCustomerAddonHtml = $sCustomerAddonHtml . "
                                    <p class='section-paragraph addon-card__text'>No Addons bought</p>";
                                }

                                $sCustomerAddonHtmlContainer = $sCustomerAddonHtmlContainer . $sCustomerAddonHtml . "</div></div></div>";
                                //Get the order amount from the database
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
                    <div id="deleteModalTotal" class=" modal--hiddenmodal modal--delete <?php
                                                                                        if ($bShowFlag) {
                                                                                            echo "modal--shown";
                                                                                        } else {
                                                                                            echo "modal--hidden";
                                                                                        } ?> ">
                        <h2 class="section-header">Enter password</h2>
                        <p class="section-paragraph">By entering your password, your account will be deleted.</p>
                        <form class="modal-form" method="post" action="api/delete-user-information.php">
                            <div class="form-wrapper">
                                <label class="customer-password-form__input-label">Password</label>
                                <input class="customer-password-form__input" type="password" name="customerPassword" id="customerPassword">
                            </div>
                            <div class="button-wrapper">
                                <button type="button" class="delete-profile__button button button--purple" onclick="removeDeleteModals()">Cancel</button>
                                <button id="deleteButton" class="delete-profile__button button button--red">DELETE MY ACOUNT</button>
                            </div>
                        </form>
                    </div>
                    <div class="account-information">
                        <div class="customer-information">
                            <h2 class="section-header">Profile information</h2>
                            <div class="customer-information-container">
                                <h4 class="section-subheader">Company Information</h4>
                                <div class="customer-information-wrapper">
                                    <div class="customer-information__item customer-information__customer_company_name">
                                        <p class="section-paragraph customer-information__item__text"><?= $sCompanyName ?></p>
                                        <span class="customer-information__item__icon-outer" onclick="editInfo('string', 'customer_company_name')">
                                            <span class="customer-information__item__icon-inner "></span>
                                        </span>
                                    </div>
                                    <div class="customer-information__item customer-information__customer_company_cvr">
                                        <p class="section-paragraph customer-information__item__text"><?= $sCompanyCvr ?></p></span>

                                        <span class="customer-information__item__icon-outer" onclick="editInfo('cvr', 'customer_company_cvr')">
                                            <span class="customer-information__item__icon-inner "></span>
                                        </span>
                                    </div>
                                    <div class="customer-information__item customer-information__customer_address">
                                        <p class="section-paragraph customer-information__item__text"><?= $sCustomerStreet ?></p>
                                        <span class="customer-information__item__icon-outer" onclick="editInfo('string', 'customer_address')">
                                            <span class="customer-information__item__icon-inner "></span>
                                        </span>
                                    </div>
                                    <div class="customer-information__item customer-information__customer_postcode">
                                        <p class="section-paragraph customer-information__item__text"><?= $sCustomerZip ?></p>
                                        <span class="customer-information__item__icon-outer" onclick="editInfo('string', 'customer_postcode')">
                                            <span class="customer-information__item__icon-inner "></span>
                                        </span>
                                    </div>
                                    <div class="customer-information__item customer-information__customer_city">
                                        <p class="section-paragraph customer-information__item__text"><?= $sCustomerCity ?></p>
                                        <span class="customer-information__item__icon-outer" onclick="editInfo('string', 'customer_city')">
                                            <span class="customer-information__item__icon-inner "></span>
                                        </span>
                                    </div>
                                    <div class="customer-information__item customer-information__customer_country">
                                        <p class="section-paragraph customer-information__item__text"><?= $sCustomerCountry ?></p>
                                        <span class="customer-information__item__icon-outer" onclick="editInfo('string', 'customer_country')">
                                            <span class="customer-information__item__icon-inner "></span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="customer-information-container">
                                <h4 class="section-subheader">Contact Person</h4>
                                <div class="customer-information-wrapper">
                                    <div class="customer-information__item customer-information__customer_first_name">
                                        <p class="section-paragraph customer-information__item__text"><?= $sCustomerFirstName ?></p>
                                        <span class="customer-information__item__icon-outer" onclick="editInfo('string', 'customer_first_name')">
                                            <span class="customer-information__item__icon-inner "></span>
                                        </span>
                                    </div>
                                    <div class="customer-information__item customer-information__customer_last_name">
                                        <p class="section-paragraph customer-information__item__text"><?= $sCustomerLastName ?></p>
                                        <span class="customer-information__item__icon-outer" onclick="editInfo('string', 'customer_last_name')">
                                            <span class="customer-information__item__icon-inner "></span>
                                        </span>
                                    </div>
                                    <div class="customer-information__item customer-information__customer_email">
                                        <p class="section-paragraph customer-information__item__text"><?= $sCustomerEmail ?></p>
                                        <span class="customer-information__item__icon-outer" onclick="editInfo('email', 'customer_email')">
                                            <span class="customer-information__item__icon-inner "></span>
                                        </span>

                                    </div>
                                    <div class="customer-information__item customer-information__customer_phone">
                                        <p class="section-paragraph customer-information__item__text"><?= $sCustomerPhone ?></p>
                                        <span class="customer-information__item__icon-outer" onclick="editInfo('phone', 'customer_phone')">
                                            <span class="customer-information__item__icon-inner "></span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="customer-information-container">
                                <h4 class="section-subheader">Edit password</h4>
                                <form class="customer-password-form" onsubmit="event.preventDefault();">
                                    <div class="form-wrapper">
                                        <label class="customer-password-form__input-label">New password:
                                            <?= $sPasswordInfoButtonHtml ?>
                                        </label>
                                        <input id="accountDetails__password" class="customer-password-form__input" oninput="inputValidate()" data-validate="password" type="password" name="customerPassword" placeholder="New password">
                                    </div>
                                    <div class="form-wrapper">
                                        <label for="confirmPassword" class="customer-password-form__input-label">Confirm new password:</label>
                                        <input id="accountDetails__confirmPassword" class="customer-password-form__input" oninput="inputValidate()" data-validate="confirmPassword" type="password" name="customerPasswordConfirm" placeholder="Confirm password">
                                    </div>
                                    <div class="form-wrapper">
                                        <label for="oldPassword" class="customer-password-form__input-label">Old password:</label>
                                        <input id="accountDetails__passwordOld" class="customer-password-form__input" type="password" placeholder="Type your old password">
                                    </div>
                                    <button class="button button--yellow customer-password-form__button" type="submit" onclick="changeCustomerPassword()">Change password</button>
                                    <div class="errorMessage"></div>
                                </form>
                            </div>
                            <button class="customer-information__button button button--red" onclick="showDeleteOption()">Delete account</button>
                        </div>
                        <div class="product-overview">
                            <h2 class="section-header">Product overview</h2>
                            <?php
                            $sCustomerProductHtml = "";
                            $sCustomerProductSelectSql = "SELECT * FROM customer_products LEFT JOIN products ON customer_products.product_id  = products.product_id WHERE customer_id = \"$customerId\"";
                            $oCustomerProductResults = $oDbConnection->query($sCustomerProductSelectSql);
                            //For each product the customer has bought, we print a block that cna display it back to the user
                            while ($oCustomerProductRow = $oCustomerProductResults->fetch_object()) {
                                $sCharsToReplace = array("<", ">");
                                $sReplaceCharsWith = array("&lt;", "&gt;");
                                $sEmbedLink = str_replace($sCharsToReplace, $sReplaceCharsWith, $oCustomerProductRow->embed_link);
                                $sApiKey = $oCustomerProductRow->api_key;
                                //Make sure the new values are correct in the database
                                $sCustomerProductId = $oCustomerProductRow->customer_products_id;
                                $oStartDate = new DateTime("@$oCustomerProductRow->subscription_start");
                                $sCustomerProductStartDate = $oStartDate->format('Y-m-d');
                                $oEndDate = new DateTime("@$oCustomerProductRow->subscription_end");
                                $sCustomerProductEndDate = $oEndDate->format('Y-m-d');
                                $nSubscriptionTimeLeft = $oCustomerProductRow->subscription_end - time();
                                $nSubscriptionDaysLeft = round($nSubscriptionTimeLeft / 86400);
                                //If the subscription has ended, turn the product off
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
                                //The html block we print
                                if ($oCustomerProductRow->subscription_active) {
                                    $sCustomerProductHtml = $sCustomerProductHtml . "<div class='product-card'>
                                                                                            <h4 onclick='toggleDropdownProfile($sCustomerProductId)' class='section-header product-card__header'>$sProductName<span class='product-card__arrow-outer' ><span class='product-card__arrow-inner' id='product-card-arrow$sCustomerProductId'></span></span></h4>
                                                                                            <div class='collapsable collapsed' id='collapsable$sCustomerProductId'>
                                                                                            <div class='subscription-info'>
                                                                                                <h5 class='section-subheader product-card__subheader'>Subscription Period:</h5>
                                                                                                <p class='section-paragraph product-card__text'><span class='product-card__text--bold'>FROM: </span>$sCustomerProductStartDate <span class='product-card__text--bold'>TO: </span> $sCustomerProductEndDate</p>
                                                                                                <p class='section-paragraph product-card__text'><span class='product-card__title'>Total days:</span> $nCustomerProductTotalDays</p>
                                                                                                <p class='section-paragraph product-card__text'><span class='product-card__title'>Active days remaining:</span> $nSubscriptionDaysLeft</p>
                                                                                            </div>
                                                                                            <h5 class='section-subheader product-card__subheader'>Embed Link</h5>
                                                                                            <div class='product-card__container'>                                                                                
                                                                                               <pre><code class='html'> $sEmbedLink</code></pre>
                                                                                            </div>
                                                                                            <h5 class='section-subheader product-card__subheader'>API-key</h5>
                                                                                            <div class='product-card__container'>
                                                                                                <pre><code class='html'>$sApiKey</code></pre>
                                                                                            </div>
                                                                                            <h5 class='section-subheader product-card__subheader'>Auto renew:</h5>
                                                                                            <div class='product-card__container'>
                                                                                                <p class='section-paragraph'><span id='autoRenewSpan$sCustomerProductId'>$sAutoRenew</span><span class='product-card__button-outer' type='button' onclick='toggleAutoRenew($sCustomerProductId)'><span id='autoRenewToggleButton$sCustomerProductId' class='product-card__button-inner'>Turn $sButtonToggle</span></span></p>
                                                                                            </div>
                                                                                            </div>
                                                                                    </div>";
                                }
                            }
                            echo $sCustomerAddonHtmlContainer . $sCustomerProductHtml;
                            ?>
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
<script src="js/profile.js"></script>
<?= $sErrorMessage ?>

</html>