<?php
session_start();

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


include_once("Components/header.php");
$header = headerComp();
$embedLink = "";
$apiKey = "";

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

    <!-- 
     -->
    <div class="customerInfoContainer">
        <?php
        $profileInfo = "";
        $sql = "SELECT * FROM customer_products LEFT JOIN products ON customer_products.product_id  = products.product_id";
        $results = $conn->query($sql);

        while ($row = $results->fetch_object()) {
            //echo json_encode($row);
            if ($row->subscription_active) {
                $subActive = "subActive";
            } else {
                $subActive = "subInactive";
            }

            $charsToReplace = array("<", ">");
            $replaceWith = array("&lt;", "&gt;");
            $embedLink = str_replace($charsToReplace, $replaceWith, $row->embed_link);
            $apiKey = $row->api_key;

            $dt = new DateTime("@$row->subscription_start");
            $subStart = $dt->format('Y-m-d');
            $dt = new DateTime("@$row->subscription_end");
            $subEnd = $dt->format('Y-m-d');
            $reduceTotalAmount = $row->subscription_end - time();
            //echo $reduceTotalAmount/86400;
            $totalDaysRemaining = round($reduceTotalAmount / 86400);
            $totalDays = round($row->subscription_total_length / 86400);

            $productDesc = $row->product_description;
            $productName = $row->product_name;
            $profileInfoCard = "
                <div class='profileCard $subActive'>
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
                </div>
                </div>
                ";
            $profileInfo = $profileInfo . $profileInfoCard;
        }
        echo $profileInfo;
        ?>
    </div>

    <div>
        <div>
            <p>Firstname: <?= $firstName ?></p>
            <button onclick="editInfo('Firstname: ', '<?= $firstName ?>', 'string', 'customer_first_name')">Edit</button>
        </div>
        <div>
            <p>Lastname: <?= $lastName ?></p>
            <button onclick="editInfo('Lastname: ', '<?= $lastName ?>', 'string', 'customer_last_name')">Edit</button>
        </div>
        <div>
            <p>Street: <?= $customerStreet ?></p>
            <button onclick="editInfo('Street: ', '<?= $customerStreet ?>', 'string', 'customer_address')">Edit</button>
        </div>
        <div>
            <p>City: <?= $customerCity ?></p>
            <button onclick="editInfo('City: ', '<?= $customerCity ?>', 'string', 'customer_city')">Edit</button>
        </div>
        <div>
            <p>Postcode: <?= $customerPostCode ?></p>
            <button onclick="editInfo('Postcode: ', '<?= $customerPostCode ?>', 'string', 'customer_postcode')">Edit</button>
        </div>
        <div>
            <p>Country: <?= $customerCountry ?></p>
            <button onclick="editInfo('Country: ', '<?= $customerCountry ?>', 'string', 'customer_country')">Edit</button>
        </div>
        <div>
            <p>Email: <?= $customerEmail ?></p>
            <button onclick="editInfo('Email: ', '<?= $customerEmail ?>', 'email', 'customer_email')">Edit</button>
        </div>
        <div>
            <p>Phone: <?= $customerPhone ?></p>
            <button onclick="editInfo('Phone: ', '<?= $customerPhone ?>', 'phone', 'customer_phone')">Edit</button>
        </div>
        <div>
            <p>Company name: <?= $customerCompName ?></p>
            <button onclick="editInfo('Company name: ', '<?= $customerCompName ?>', 'string', 'customer_company_name')">Edit</button>
        </div>
        <div>
            <p>Company cvr: <?= $customerCvr ?></p>
            <button onclick="editInfo('Company cvr: ', '<?= $customerCvr ?>', 'cvr', 'customer_cvr')">Edit</button>
        </div>
    </div>

</body>
<script src="//cdnjs.cloudflare.com/ajax/libs/highlight.js/10.7.2/highlight.min.js"></script>
<script>
    hljs.highlightAll();
</script>
<script src="js/app.js"></script>

</html>