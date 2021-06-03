<?php
session_start();
include_once("Components/head.php");
include_once("Components/header.php");
include_once("Components/footer.php");
$head = headComp();
$header = headerComp('profile');
$footer = footerComp();

$errorMess = "";
$showFlag = false;
if (!isset($_SESSION['loginStatus'])) {
    header('Location: login.php');
} else {
    $customerId = $_SESSION['customer_id'];
    include("DB_Connection/connection.php");

    $sql = "SELECT * FROM customers WHERE customer_id = \"$customerId\"";
    $result = $conn->query($sql);
    $row = $result->fetch_object();
    $firstName = $row->customer_first_name;
    $lastName = $row->customer_last_name;
    $customerCompName = $row->customer_company_name;
    $customerEmail = $row->customer_email;
    $customerCvr = $row->customer_cvr;
    $customerCity = $row->customer_city;
    $customerStreet = $row->customer_address;
    $customerCountry = $row->customer_country;
    $customerPostCode = $row->customer_postcode;
    $customerPhone = $row->customer_phone;

    $_SESSION['customer_first_name'] = $firstName;
    $_SESSION['customer_last_name'] = $lastName;
}
if (isset($_POST['confirmPassword'])) {

    $password = $conn->real_escape_string($_POST['confirmPassword']);
    $sql = "SELECT * FROM customers WHERE customer_id = \" $customerId \"";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $row = $result->fetch_object();
        //echo json_encode($row);
        $db_password = $row->customer_password;
        if (password_verify($password, $db_password)) {
            header("Location: API/delete-user-information.php");
        } else {
            $errorMess = "<p style='color:red'> ERROR - You don' fuckd up kiddo</p>";
            $showFlag = true;
        }
    }
}

$embedLink = "";
$apiKey = "";

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?= $head ?>
</head>

<body>
    <?= $header ?>
    <main>
        <section id="profile">
            <div class="layout-container profile">
                <h1 class="section-header profile__header">Welcome <?= $firstName, " ", $lastName ?></h1>
                <div class="profile__main">
                    <div id="deleteModal" class="hidden modal--delete">
                        <h2 class="section-header">Are you sure you want to delete your data?</h2>
                        <p class="section-paragraph">You are about to delete every data we have regarding your product and your orders. <br>
                            Going foward with this, there will be no recovering this information, and your product and licenses will be removed from your account.</p>
                        <div id="customerInfo">
                            <p class="section-paragraph">You will be deleting:</p>
                            <ul>
                                <?php
                                include("DB_Connection/connection.php");
                                $sql = "SELECT count(*) FROM `customer_products` WHERE `customer_id` = \"$customerId\"";
                                $results = $conn->query($sql);
                                $row = $results->fetch_assoc();
                                $amount = $row['count(*)'];
                                echo "<li> $amount products with active licences</li>";
                                $sql = "SELECT * FROM customer_addons LEFT JOIN addons ON customer_addons.addon_id  = addons.addon_id  WHERE `customer_id` = \"$customerId\"";
                                $results = $conn->query($sql);
                                while ($row = $results->fetch_assoc()) {
                                    $amount = $row['addon_amount'];
                                    $name = $row['addon_name'];
                                    echo "<li> $amount $name's in our database</li>";
                                }

                                $sql = "SELECT count(*) FROM `orders` WHERE `customer_id` = \"$customerId\"";
                                $results = $conn->query($sql);
                                $row = $results->fetch_assoc();
                                $amount = $row['count(*)'];
                                echo "<li> $amount orders in our database</li>";
                                ?>
                            </ul>

                        </div>
                        <button class="button button--purple" onclick="cancelDeletion()">Cancel</button>
                        <button class="button button--red" onclick="showDeleteOption2()">I Understand</button>
                    </div>
                    <div id="deleteModalTotal" class="<?php if ($showFlag) {
                                                            echo "shown";
                                                        } else {
                                                            echo "hidden";
                                                        } ?> ">
                        <h1>Enter password</h1>
                        <p>By entering your password, your account will be deleted.</p>
                        <form method="post">
                            <label>
                                <p>Password:</p>
                                <input type="password" name="password" oninput="checkPassword()" id="pass1">
                            </label>
                            <label>
                                <p>Confirm Password:</p>
                                <input type="password" name="confirmPassword" oninput="checkPassword()" id="pass2">
                            </label>

                            <?= "<input type='hidden' name='userID' value='$customerId'>" ?>
                            <?= $errorMess ?>
                            <button disabled id="deleteButton">DELETE MY ACOUNT</button>
                        </form>
                        <button onclick="removeDeleteModals()">Cancel</button>

                    </div>
                    <div class="customerInfoContainer">
                        <?php
                        $profileInfo = "";
                        $sql = "SELECT * FROM customer_products LEFT JOIN products ON customer_products.product_id  = products.product_id";
                        $results = $conn->query($sql);

                        while ($row = $results->fetch_object()) {
                            //echo json_encode($row);

                            $charsToReplace = array("<", ">");
                            $replaceWith = array("&lt;", "&gt;");
                            $embedLink = str_replace($charsToReplace, $replaceWith, $row->embed_link);
                            $apiKey = $row->api_key;
                            $rowKey = $row->customer_products_id;
                            $dt = new DateTime("@$row->subscription_start");
                            $subStart = $dt->format('Y-m-d');
                            $dt = new DateTime("@$row->subscription_end");
                            $subEnd = $dt->format('Y-m-d');
                            $reduceTotalAmount = $row->subscription_end - time();
                            //echo $reduceTotalAmount/86400;
                            $totalDaysRemaining = round($reduceTotalAmount / 86400);

                            if ($totalDaysRemaining < 0) {
                                $sql = "UPDATE customer_products SET subscription_active = 0 WHERE customer_products_id = \"$rowKey\"";
                                $conn->query($sql);
                                $row->subscription_active = 0;
                                $totalDaysRemaining = 0;
                            }

                            $totalDays = round($row->subscription_total_length / 86400);

                            $subID = $row->customer_products_id;

                            if ($row->subscription_autorenew) {
                                $autoRenew = "On";
                                $buttonToggle = "Off";
                            } else {
                                $autoRenew = "Off";
                                $buttonToggle = "On";
                            }

                            $productDesc = $row->product_description;
                            $productName = $row->product_name;

                            if ($row->subscription_active) {
                                $profileInfoCard = "<div class='profileCard'>
                                                        <h1>$productName</h1>
                                                        <p>$productDesc</p>
                                                        <div class='subInfo'>
                                                            <p>FROM: $subStart || TO: $subEnd</p>
                                                            <p>Total days: $totalDays</p>
                                                            <p>$totalDaysRemaining days left</p>
                                                        </div>
                                                        <p>Embed link:</p>
                                                        <pre><code class='html'> $embedLink</code></pre>
                                                        <p>API Key:</p>
                                                        <pre><code class='html'>$apiKey</code></pre>
                                                        <p>Auto renew subscription: <span><b>$autoRenew</b></span></p>
                                                        <button onclick='toggleAutoRenew($subID)'>Switch Autorenew $buttonToggle</button>
                                                    </div>";
                                $profileInfo = $profileInfo . $profileInfoCard;
                            }
                        }
                        echo $profileInfo;
                        ?>
                    </div>
                    <div class="account-information">
                        <div class="customer-information">
                            <div class="customer-information-container">
                                <h4 class="section-subheader">Company Information</h4>
                                <div class="customer-information-wrapper">
                                    <div class="customer-information__item customer-information__company-name">
                                        <p class="section-paragraph customer-information__item__text"><?= $customerCompName ?></p>
                                        <span class="customer-information__item__icon-outer" onclick="editInfo('<?= $customerCompName ?>', 'string', 'customer_company_name')">
                                            <span class="customer-information__item__icon-inner "></span>
                                        </span>
                                    </div>
                                    <div class="customer-information__item customer-information__cvr">
                                        <p class="section-paragraph customer-information__item__text">CVR: <?= $customerCvr ?></p>
                                        <span class="customer-information__item__icon-outer" onclick="editInfo( '<?= $customerCvr ?>', 'cvr', 'customer_cvr')">
                                            <span class="customer-information__item__icon-inner "></span>
                                        </span>
                                    </div>
                                    <div class="customer-information__item customer-information__streetname">
                                        <p class="section-paragraph customer-information__item__text"><?= $customerStreet ?></p>
                                        <span class="customer-information__item__icon-outer" onclick="editInfo( '<?= $customerStreet ?>', 'string', 'customer_address')">
                                            <span class="customer-information__item__icon-inner "></span>
                                        </span>
                                    </div>
                                    <div class="customer-information__item customer-information__zipcode">
                                        <p class="section-paragraph customer-information__item__text"><?= $customerPostCode ?></p>
                                        <span class="customer-information__item__icon-outer" onclick="editInfo( '<?= $customerPostCode ?>', 'string', 'customer_postcode')">
                                            <span class="customer-information__item__icon-inner "></span>
                                        </span>
                                    </div>
                                    <div class="customer-information__item customer-information__city">
                                        <p class="section-paragraph customer-information__item__text"><?= $customerCity ?></p>
                                        <span class="customer-information__item__icon-outer" onclick="editInfo('<?= $customerCity ?>', 'string', 'customer_city')">
                                            <span class="customer-information__item__icon-inner "></span>
                                        </span>
                                    </div>
                                    <div class="customer-information__item customer-information__country">
                                        <p class="section-paragraph customer-information__item__text"><?= $customerCountry ?></p>
                                        <span class="customer-information__item__icon-outer" onclick="editInfo('<?= $customerCountry ?>', 'string', 'customer_country')">
                                            <span class="customer-information__item__icon-inner "></span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="customer-information-container">
                                <h4 class="section-subheader">Contact Person</h4>
                                <div class="customer-information-wrapper">
                                    <div class="customer-information__item customer-information__firstname">
                                        <p class="section-paragraph customer-information__item__text"><?= $firstName ?></p>
                                        <span class="customer-information__item__icon-outer" onclick="editInfo('Firstname: ', '<?= $firstName ?>', 'string', 'customer_first_name')">
                                            <span class="customer-information__item__icon-inner "></span>
                                        </span>
                                    </div>
                                    <div class="customer-information__item customer-information__lastname">
                                        <p class="section-paragraph customer-information__item__text"><?= $lastName ?></p>
                                        <span class="customer-information__item__icon-outer" onclick="editInfo('Firstname: ', '<?= $lastName ?>', 'string', 'customer_first_name')">
                                            <span class="customer-information__item__icon-inner "></span>
                                        </span>
                                    </div>
                                    <div class="customer-information__item customer-information__email">
                                        <p class="section-paragraph customer-information__item__text"><?= $customerEmail ?></p>
                                        <span class="customer-information__item__icon-outer" onclick="editInfo('Firstname: ', '<?= $customerEmail ?>', 'string', 'customer_first_name')">
                                            <span class="customer-information__item__icon-inner "></span>
                                        </span>

                                    </div>
                                    <div class="customer-information__item customer-information__phone">
                                        <p class="section-paragraph customer-information__item__text"><?= $customerPhone ?></p>
                                        <span class="customer-information__item__icon-outer" onclick="editInfo('Firstname: ', '<?= $customerPhone ?>', 'string', 'customer_first_name')">
                                            <span class="customer-information__item__icon-inner "></span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="customer-information-container">
                                <h4 class="section-subheader">Edit password</h4>
                                <form class="customer-password-form" method="post" onsubmit="return inputValidate();" action="API/update-customer-data.php">
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
                                        <input id="newPassword" class="customer-password-form__input" oninput="inputValidate()" data-validate="password" type="password" name="input_password_init" placeholder="New password">
                                    </div>
                                    <div class="form-wrapper">
                                        <label for="confirmPassword" class="customer-password-form__input-label">Confirm new password:</label>
                                        <input id="confirmPassword" class="customer-password-form__input" oninput="inputValidate()" data-validate="password" type="password" name="input_password_confirm" placeholder="Confirm password">
                                    </div>
                                    <div class="form-wrapper">
                                        <label for="oldPassword" class="customer-password-form__input-label">Old password:</label>
                                        <input id="oldPassword" class="customer-password-form__input" oninput="inputValidate()" data-validate="password" type="password" name="customer_password" placeholder="Type your old password">
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


    <?= $footer ?>
</body>
<script src="//cdnjs.cloudflare.com/ajax/libs/highlight.js/10.7.2/highlight.min.js"></script>
<script>
    hljs.highlightAll();
</script>
<script src="js/app.js"></script>
<script src="js/helper.js"></script>

</html>