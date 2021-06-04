<?php
session_start();
include_once("Components/head.php");
include_once("Components/header.php");
include_once("Components/footer.php");
$head = headComp();
$header = headerComp('');
$footer = footerComp();
if (!isset($_GET['confirmCode'])) {
    header('Location: index.php');
}
$sCustomerConfirmCode = $_GET['confirmCode'];

include_once("DB_Connection/connection.php");
$sql = "UPDATE customers SET customer_confirmed = 1 WHERE customer_confirm_code = \"$sCustomerConfirmCode\"";
$result = $oDbConnection->query($sql);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?= $head ?>
</head>

<body>
    <?= $header ?>
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
    <?= $footer ?>
</body>

</html>