<?php
session_start();
//indluce the styling blocks
include_once("components/head.php");
include_once("components/header.php");
include_once("components/footer.php");
$sHeadHtmlComp = headComp();
$sHeaderHtmlComp = headerComp('');
$sFooterHtmlComp = footerComp();
//If there is no confirm code, send the user away
if (!isset($_GET['confirmCode'])) {
    header('Location: index.php');
}
$sCustomerConfirmCode = $_GET['confirmCode'];
//Update the customer_confirmed record for where the confirm code is a match
include_once("db-connection/connection.php");
$sCustomerUpdateSql = "UPDATE customers SET customer_confirmed = 1 WHERE customer_confirm_code = \"$sCustomerConfirmCode\"";
$oCustomerResult = $oDbConnection->query($sCustomerUpdateSql);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?= $sHeadHtmlComp ?>
</head>

<body>
    <?= $sHeaderHtmlComp ?>
    <main>
        <section id="email-confirmation">
            <div class="layout-container email-confirmation">
                <h1 class="section-header">We have recieved your confirmation - thank you.
                    <br>
                    Your account is now active.
                </h1>
                <a href="login.php">Go to the login page</a>
                <a href="index.php">Go to the homepage</a>
            </div>
        </section>
    </main>
    <?= $sFooterHtmlComp ?>
</body>

</html>