<?php
session_start();
include_once("components/head.php");
include_once("components/header.php");
include_once("components/footer.php");
include_once("components/loginForm.php");
$sLoginFormHtmlComp = loginFormComp();
$sHeadHtmlComp = headComp();
$sHeaderHtmlComp = headerComp('login');
$sFooterHtmlComp = footerComp();
$sErrorMessage = "";

//If the user IS logged in, they should go to the profile page instead
if (isset($_SESSION['loginStatus'])) {
    header('Location: profile.php');
}
//If the user has submitted the email and password, we check for a match in the database
if (isset($_POST['customerEmail']) && isset($_POST['customerPassword'])) {
    //Check to see if the data sent has value
    if ($_POST['customerEmail'] != "" && $_POST['customerPassword'] != "") {

        include("db-connection/connection.php");
        //Sanitize POSTed data to remove SQL query characters
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

                    //If the user is authorized, set session data and send the user to the index page
                    $_SESSION['loginStatus'] = true;
                    $_SESSION['customerId'] = $oCustomerRow->customer_id;
                    $_SESSION['customerFirstName'] = $oCustomerRow->customer_first_name;
                    $_SESSION['customerLastName'] = $oCustomerRow->customer_last_name;
                    header('Location: index.php');
                } else {
                    //Display an error message the user
                    $sErrorMessage = "<script>showMessage('You have not confirmed your account, please check your email', true)</script>";
                }
            } else {
                $sErrorMessage = "<script>showMessage('Wrong password or email', true)</script>";
            }
        } else {
            $sErrorMessage = "<script>showMessage('Wrong password or email', true)</script>";
        }
    } else {
        $sErrorMessage = "<script>showMessage('Please enter your login information', true)</script>";
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
                <?= $sLoginFormHtmlComp ?>
            </div>
        </section>
    </main>

    <?= $sFooterHtmlComp ?>
</body>
<script src="js/app.js"></script>
<?= $sErrorMessage ?>

</html>