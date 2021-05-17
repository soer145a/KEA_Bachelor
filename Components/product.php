<?php



function productComp($productPrice, $productName, $productDescription, $productId, $inCart)
{
    
    include("DB_Connection/connection.php");
$sql = "SELECT * FROM subscriptions";
$results = $conn->query($sql);
$subHtml = "";
$firstFlag = true;
while($row = $results->fetch_object()){
    
    //echo json_encode($row);
    $subId = $row->subscription_id;
    $subName = $row->subscription_name;
    if($firstFlag){
        $radioButton = "<label><input type='radio' checked id='$subId' name='sub' value='$subId'>$subName</label>";
    }else{
        $radioButton = "<label><input type='radio' id='$subId' name='sub' value='$subId'>$subName</label>";
    }
    $firstFlag = false;
    
    $subHtml = $subHtml.$radioButton;
}
    if ($inCart) {

        $productCard = "
        <div class='productCard'>
        <form method='post' action='API/add-product-to-cart.php'>
        <h2>$productName</h2>
        <p>Price: $productPrice</p>
        <p>$productDescription</p>
        <div>
            $subHtml
        </div>
        <input type='submit' readonly disabled name='add_product_to_cart' value='Already in cart'></input>
        </form>
        </div>
        ";
    } else {
        $productCard = "
        <div class='productCard'>
        <form method='post' action='API/add-product-to-cart.php'>
        <h2>$productName</h2>
        <p>Price: $productPrice</p>
        <p>$productDescription</p>
        <div>
            $subHtml
        </div>
        <input type='submit' readonly name='add_product_to_cart' value='Add to cart'></input>
        <input type='hidden' readonly name='product_id' value='$productId'></input>
        </form>
        </div>
        ";
    }

    return $productCard;
}
