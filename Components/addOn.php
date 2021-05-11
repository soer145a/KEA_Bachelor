<?php


function addOnComp($addOnPrice, $addOnName, $addOnDescription, $addOnId)
{

    $addOnCard = "
        <div class='addOnCard'>
        <form method='post' action='API/add-addon-to-cart.php'>
        <h2>$addOnName</h2>
        <p>Price: $addOnPrice</p>
        <p>$addOnDescription</p>
        <input type='number' name='amount_of_addon' value='1' min='1'></input>
        <input type='submit' readonly name='add_addon_to_cart' value='Add to cart'></input>
        <input type='hidden' readonly name='addon_id' value='$addOnId'></input>
        </form>
        </div>
        ";

    return $addOnCard;
}
