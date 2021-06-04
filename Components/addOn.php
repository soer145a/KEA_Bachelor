<?php


function addOnsComp($oDbConnection)
{
    $addOnSql = 'SELECT * FROM addons';
    $addOnResult = $oDbConnection->query($addOnSql);

    while ($addOnRow = $addOnResult->fetch_object()) {

        switch ($addOnRow->addon_id) {
            case 1:
                $addOn1 = "<div class='addon-wrapper'>
                <img class='addon-image' src='./assets/images/3d-model.png' alt='3d model of shirt'>
                <div class='text-wrapper'>
                    <div class='addon-form'>
                        <h3 class='section-subheader'>3D models</h3>
                        <label class='addon-form__label' for='3d-models'>Choose extra models for €$addOnRow->addon_price each:</label>
                        <input class='addon-form__input addon-form__input_$addOnRow->addon_id' type='number' value='1' id='3d-models'>
                        <button onclick='addAddOnToCart($addOnRow->addon_id)' class='addon-form__button button button--yellow'>Add to Cart</button>
                    </div>
                </div>
            </div>";
                break;
            case 2:
                $addOn2 = "<div class='addon-wrapper'>
                <img class='addon-image' src='./assets/images/variations.png' alt='shirt varitions'>
                <div class='text-wrapper'>
                    <div class='addon-form'>
                        <h3 class='section-subheader'>3D Variations</h3>
                        <label class='addon-form__label' for='model-variations'>Choose extra model variations for €$addOnRow->addon_price each:</label>
                        <input class='addon-form__input addon-form__input_$addOnRow->addon_id' type='number' value='1' id='model-variations'>
                        <button onclick='addAddOnToCart($addOnRow->addon_id)' class='addon-form__button button button--yellow'>Add to Cart</button>
                    </div>
                </div>
            </div>";
                break;
        }
    }

    $addOnsComp = $addOn1 . $addOn2;

    return $addOnsComp;
}
