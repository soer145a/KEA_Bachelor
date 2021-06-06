<?php
session_start();
$sErrorMessage = "";
include_once("components/head.php");
include_once("components/header.php");
include_once("components/footer.php");
$sHeadHtmlComp = headComp();
$sHeaderHtmlComp = headerComp('login');
$sFooterHtmlComp = footerComp();
//If the user IS logged in, they should go to the profile page instead
if (isset($_SESSION['loginStatus'])) {
    header('Location: profile.php');
}
//If the user has submitted the email and password, we check for a match in the database
if (isset($_POST['customerEmail']) && isset($_POST['customerPassword'])) {
    //Check to see if the data sent has value
    if ($_POST['customerEmail'] != "" && $_POST['customerPassword'] != "") {

        include("db-connection/connection.php");
        $sCustomerPassword = $oDbConnection->real_escape_string($_POST['customerPassword']);
        $sCustomerEmail = $oDbConnection->real_escape_string($_POST['customerEmail']);
        //Get the customer data from the database
        $sCustomerSelectSql = "SELECT * FROM customers WHERE customer_email = \"$sCustomerEmail\"";
        $oCustomerResult = $oDbConnection->query($sCustomerSelectSql);
        if ($oCustomerResult->num_rows > 0) {
            $oCustomerRow = $oCustomerResult->fetch_object();
            $sCustomerDbPassword = $oCustomerRow->customer_password;
            //Verify that the password submitted is matching the one in the database
            if (password_verify($sCustomerPassword, $sCustomerDbPassword)) {
                if ($oCustomerRow->customer_confirmed == 1) {
                    //If the customer has not confirmed their account through the email, decline the login here
                    $_SESSION['loginStatus'] = true;
                    $_SESSION['customerId'] = $oCustomerRow->customer_id;
                    $_SESSION['customerFirstName'] = $oCustomerRow->customer_first_name;
                    $_SESSION['customerLastName'] = $oCustomerRow->customer_last_name;
                    header('Location: index.php');
                    //Display an error message the user
                } else {
                    $sErrorMessage = "<p style='color:red'> ERROR - You have not confirmed your account, check your email</p>";
                }
            } else {
                $sErrorMessage = "<p style='color:red'> ERROR - Wrong password or email</p>";
            }
        } else {
            $sErrorMessage = "<p style='color:red'> ERROR - Wrong password or email</p>";
        }
    }
}else{
    $sErrorMessage = "<p style='color:red'> ERROR - Wrong password or email</p>";
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