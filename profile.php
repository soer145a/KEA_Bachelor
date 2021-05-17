<?php
session_start();

include_once("Components/header.php");
$header = headerComp();

if (!isset($_SESSION['loginStatus'])) {
    header('Location: login.php');
} else {
    $firstName = $_SESSION['customer_first_name'];
    $lastName = $_SESSION['customer_last_name'];
    $customerId = $_SESSION['customer_id'];
    include("DB_Connection/connection.php");

    $sql = "SELECT * FROM customers WHERE customer_id = \"$customerId\"";
    $result = $conn->query($sql);
    $row = $result->fetch_object();
    $charsToReplace = array("<", ">");
    $replaceWith = array("&lt;", "&gt;");
    $embedLink = str_replace($charsToReplace, $replaceWith, $row->embed_link);
    $apiKey = $row->api_key;
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/app.css">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/highlight.js/10.7.2/styles/default.min.css">
    <title>Profile page</title>
</head>

<body>
    <div><?= $header ?></div>
    <h1>Welcome <?= $firstName, " ", $lastName ?></h1>

    <p>Embed link:</p>
    <pre><code class="html"><?= $embedLink ?></code></pre>

    <p>API Key:</p>
    <pre><code class="html"><?= $apiKey ?></code></pre>
    <div class="customerInfoContainer">
    </div>
    <p>Alter the company data</p>
    <button onclick="showUpdateForm()">Click here to update</button>
    <form method="post"action="API/update-customer-data.php" class="hidden" id="updateDataForm">
    <label>
            <p>Contact - First Name:</p>
            <input class="form__input" oninput="inputValidateProfile();" data-validate="string" type="text" name="input_first_name" placeholder="John">
        </label>
        <label>
            <p>Contact - Last Name:</p>
            <input class="form__input" oninput="inputValidateProfile();" data-validate="string" type="text" name="input_last_name" placeholder="Doe">
        </label>
        <label>
            <p>Company - Street:</p>
            <input class="form__input" oninput="inputValidateProfile();" data-validate="string" type="text" name="input_company_street" placeholder="John Doe Lane 35A">
        </label>        
        <label>
            <p>Company - City:</p>
            <input class="form__input" oninput="inputValidateProfile()" data-validate="string" type="text" name="input_company_city" placeholder="London">
        </label>
        <label>
            <p>Company - Postcode:</p>
            <input class="form__input" oninput="inputValidateProfile();" data-validate="string" type="text" name="input_company_Postcode" placeholder="SW1W 0NY">
        </label>
        <label>
            <p>Company - country:</p>
            <input class="form__input" oninput="inputValidateProfile();" data-validate="string" type="text" name="input_company_country" placeholder="England">
        </label>
        <label>
            <p>Contact - Email:</p>
            <input class="form__input" oninput="inputValidateProfile();" data-validate="email" type="email" name="input_email" placeholder="example@email.com">
        </label>
        <label>
            <p>Contact - Phone:</p>
            <input class="form__input" oninput="inputValidateProfile();" data-validate="phone" type="text" name="input_phone" placeholder="+4511223344">
        </label>
        <label>
            <p>Company - Name:</p>
            <input class="form__input" oninput="inputValidateProfile();" data-validate="string" type="text" name="input_company_name" placeholder="JohnDoe A/S">
        </label>
        <label>
            <p>Company - CVR:</p>
            <input class="form__input" oninput="inputValidateProfile();" data-validate="cvr" type="text" name="input_company_cvr" placeholder="12345678">
        </label>
        <div class="errorMessage"></div>
        <div class="form__btnContainer">
            <button>UPDATE YOUR DATA</button>
        </div>
    </form>

</body>
<script src="//cdnjs.cloudflare.com/ajax/libs/highlight.js/10.7.2/highlight.min.js"></script>
<script>
    hljs.highlightAll();
</script>
<script src="js/app.js"></script>

</html>