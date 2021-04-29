<?php
session_start();
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
    $embedLink = $row->embed_link;
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
    <h1>Welcome <?= $firstName, " ", $lastName ?></h1>

    <p>Embed link:</p>
    <pre><code class="html"><?= $embedLink ?></code></pre>


    <p>API Key:</p>
    <pre><code class="html"><?= $apiKey ?></code></pre>

    <div class="customerInfoContainer">

    </div>


</body>
<script src="//cdnjs.cloudflare.com/ajax/libs/highlight.js/10.7.2/highlight.min.js"></script>
<script>
    hljs.highlightAll();
</script>
<script src="js/app.js"></script>

</html>