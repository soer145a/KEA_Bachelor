<?php
session_start();
$sErrorMessage = "";
include_once("Components/head.php");
include_once("Components/header.php");
include_once("Components/footer.php");
$sHeadHtmlComp = headComp();
$sHeaderHtmlComp = headerComp('login');
$sFooterHtmlComp = footerComp();

if (isset($_SESSION['loginStatus'])) {
    header('Location: profile.php');
}

if (isset($_POST['customerEmail']) && isset($_POST['customerPassword'])) {
    //echo $_POST['customerEmail']." ".$_POST['customer_password'];
    if ($_POST['customerEmail'] != "" && $_POST['customerPassword'] != "") {
        //echo "Data is there";
        include("DB_Connection/connection.php");
        $sCustomerPassword = $oDbConnection->real_escape_string($_POST['customerPassword']);
        $sCustomerEmail = $oDbConnection->real_escape_string($_POST['customerEmail']);
        $sCustomerSelectSql = "SELECT * FROM customers WHERE customer_email = \"$sCustomerEmail\"";
        $oCustomerResult = $oDbConnection->query($sCustomerSelectSql);
        if ($oCustomerResult->num_rows > 0) {
            $oCustomerRow = $oCustomerResult->fetch_object();
            $sCustomerDbPassword = $oCustomerRow->customer_password;
            if (password_verify($sCustomerPassword, $sCustomerDbPassword)) {
                if ($oCustomerRow->customer_confirmed == 1) {
                    $_SESSION['loginStatus'] = true;
                    $_SESSION['customerId'] = $oCustomerRow->customer_id;
                    $_SESSION['customerFirstName'] = $oCustomerRow->customer_first_name;
                    $_SESSION['customerLastName'] = $oCustomerRow->customer_last_name;
                    header('Location: index.php');
                } else {
                    $sErrorMessage = "<p style='color:red'> ERROR - You have not confirmed yo account</p>";
                }
            } else {
                $sErrorMessage = "<p style='color:red'> ERROR - You don' fuckd up kiddo</p>";
            }
        } else {
            $sErrorMessage = "<p style='color:red'> ERROR - You don' fuckd up kiddo</p>";
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?= $sHeadHtmlComp ?>
</head>

<body>
    <?= $sHeaderHtmlComp ?>
    <main>
        <section id="login">
            <div class="layout-container login">

                <h1 class="section-header login-header">Login</h1>
                <form class="login-form" method="post">
                    <label class="login-form__label">Email:</label>

                    <input class="login-form__input" type="email" placeholder="example@email.com" name="customerEmail">

                    <label class="login-form__label">Password:
                        <span class="login-form__label-info-outer js-toggle-infobox">
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

                    <input class="login-form__input" type="password" placeholder="Type in your password" name="customerPassword">

                    <?= $sErrorMessage ?>
                    <br>
                    <button class="login-form__button button button--purple" type="submit">Login</button>
                </form>
            </div>
        </section>
    </main>

    <?= $sFooterHtmlComp ?>
</body>
<script src="js/app.js"></script>
<script src="js/helper.js"></script>

</html>