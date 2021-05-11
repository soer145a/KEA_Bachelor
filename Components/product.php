<?php


function productComp($productPrice, $productName, $productDescription, $productId, $inCart)
{

    if ($inCart) {
        $productCard = "
        <div class='productCard'>
        <form method='post' action='API/add-product-to-cart.php'>
        <h2>$productName</h2>
        <p>Price: $productPrice</p>
        <p>$productDescription</p>
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
        <input type='submit' readonly name='add_product_to_cart' value='Add to cart'></input>
        <input type='hidden' readonly name='product_id' value='$productId'></input>
        </form>
        </div>
        ";
    }

    return $productCard;
}
