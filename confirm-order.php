<?php
session_start();
include_once("components/head.php");
include_once("components/header.php");
include_once("components/footer.php");
$sHeadHtmlComp = headComp();
$sFooterHtmlComp = footerComp();
//If the order ID is not set, that means that no purchase was made, and we redirect the user back to the index page
if (!isset($_SESSION['orderId'])) {
    header('Location: index.php');
} 

$sProductName = "";
$nTotalPrice = 0;
$sBoughtAddons = "";
//Make the summery for the user to view
if (isset($_SESSION['cartProducts'])) {
    foreach ($_SESSION['cartProducts'] as $aProduct) {

        $sProductName = $aProduct['productName'] . ", " .  $sProductName;
        $nTotalPrice =  $nTotalPrice + $aProduct['productPrice'];
    }
}
if (isset($_SESSION['cartAddOns'])) {
    foreach ($_SESSION['cartAddOns'] as $aAddon) {
        $sAddonName = $aAddon['addOnName'];
        $nAddonTotalprice = $aAddon['addOnPrice'] * $aAddon['addOnAmount'];
        $sBoughtAddons = $sBoughtAddons . $aAddon['addOnAmount'] . " x " . $sAddonName . ", ";
        $nTotalPrice =  $nTotalPrice + $nAddonTotalprice;
    }
}
//Remove the session data
unset($_SESSION['cartProducts']);
unset($_SESSION['cartAddOns']);
unset($_SESSION['customerData']);
unset($_SESSION['customerConfirmCode']);
unset($_SESSION['orderId']);
unset($_SESSION['purchaseProcess']);

$sHeaderHtmlComp = headerComp('');
//Depending on wheter we send the confirm email, we print a seperate message for the user
if (!isset($_SESSION['loginStatus'])) {
    $sUserMessage = "Thank you for your order! <br> We have sent you an email with a link to confirm your email address.";
} else {
    $sUserMessage = "Thank you for your order.";
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
        <section id="orderConfirmation">
            <div class="layout-container orderConfirmation">
                <div class="order-confirmation-summary">
                    <h1 class="section-header"><?= $sUserMessage ?></h1>
                    <p><span class="order-confirmation-summary-text">You have bought:</span> <?= $sProductName ?></p>
                    <p><span class="order-confirmation-summary-text">Addons:</span> <?= $sBoughtAddons ?></p>
                    <p><span class="order-confirmation-summary-text">Price: </span><?= $nTotalPrice ?></p>
                </div>
            </div>
        </section>
    </main>

    <?= $sFooterHtmlComp ?>
</body>

</html>