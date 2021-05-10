<?php


function productComp($productPrice, $productName, $productDescription, $productId, $inCart)
{

    if ($inCart) {
        $productCard = "
        <div class='buyCard'>
        <form method='post' action='API/add-product-to-cart.php'>
        <h2>$productName</h2>
        <p>Price: $productPrice</p>
        <p>$productDescription</p>
        <button type='submit' disabled name='add_to_cart'>Already in cart</button>
        <input type='hidden' name='product_id' value='$productId'></input>
        <input type='hidden' name='product_price' value='$productPrice'></input>
        <input type='hidden' name='product_name' value='$productName'></input>
        </form>
        </div>
        ";
    } else {
        $productCard = "
        <div class='buyCard'>
        <form method='post' action='API/add-product-to-cart.php'>
        <h2>$productName</h2>
        <p>Price: $productPrice</p>
        <p>$productDescription</p>
        <button type='submit' name='add_to_cart'>Add to cart</button>
        <input type='hidden' name='product_id' value='$productId'></input>
        <input type='hidden' name='product_price' value='$productPrice'></input>
        <input type='hidden' name='product_name' value='$productName'></input>
        </form>
        </div>
        ";
    }

    echo $productCard;
}
