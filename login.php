<?php
session_start();
$errorMess = "";
include_once("Components/head.php");
include_once("Components/header.php");
include_once("Components/footer.php");
$head = headComp();
$header = headerComp('login');
$footer = footerComp();

if (isset($_SESSION['loginStatus'])) {
    header('Location: profile.php');
}

if (isset($_POST['customer_email']) && isset($_POST['customer_password'])) {
    //echo $_POST['customer_email']." ".$_POST['customer_password'];
    if ($_POST['customer_email'] != "" && $_POST['customer_password'] != "") {
        //echo "Data is there";
        include("DB_Connection/connection.php");
        $password = $oDbConnection->real_escape_string($_POST['customer_password']);
        $email = $oDbConnection->real_escape_string($_POST['customer_email']);
        $sql = "SELECT * FROM customers WHERE customer_email = \"$email\"";
        $result = $oDbConnection->query($sql);
        if ($result->num_rows > 0) {
            $row = $result->fetch_object();
            $db_password = $row->customer_password;
            if (password_verify($password, $db_password)) {
                if ($row->customer_confirmed == 1) {
                    $_SESSION['loginStatus'] = true;
                    $_SESSION['customerId'] = $row->customer_id;
                    $_SESSION['customer_first_name'] = $row->customer_first_name;
                    $_SESSION['customer_last_name'] = $row->customer_last_name;
                    header('Location: index.php');
                } else {
                    $errorMess = "<p style='color:red'> ERROR - You have not confirmed yo account</p>";
                }
            } else {
                $errorMess = "<p style='color:red'> ERROR - You don' fuckd up kiddo</p>";
            }
        } else {
            $errorMess = "<p style='color:red'> ERROR - You don' fuckd up kiddo</p>";
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?= $head ?>
</head>

<body>
    <?= $header ?>
    <main>
        <section id="login">
            <div class="layout-container login">

                <h1 class="section-header login-header">Login</h1>
                <form class="login-form" method="post">
                    <label class="login-form__label">Email:</label>

                    <input class="login-form__input" type="email" placeholder="example@email.com" name="customer_email">

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

                    <input class="login-form__input" type="password" placeholder="Type in your password" name="customer_password">

                    <?= $errorMess ?>
                    <br>
                    <button class="login-form__button button button--purple" type="submit">Login</button>
                </form>
            </div>
        </section>
    </main>

    <?= $footer ?>
</body>
<script src="js/app.js"></script>
<script src="js/helper.js"></script>

</html>