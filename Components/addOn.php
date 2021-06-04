<?php


function addOnsComp($oDbConnection)
{
    $sAddOnHtmlComp = "";
    $sAddOnSelectSql = 'SELECT * FROM addons';
    $oAddOnResult = $oDbConnection->query($sAddOnSelectSql);

    while ($oAddOnRow = $oAddOnResult->fetch_object()) {

        switch ($oAddOnRow->addon_id) {
            case 1:
                $sAddOnHtmlComp = $sAddOnHtmlComp . "<div class='addon-wrapper'>
                <img class='addon-image' src='./assets/images/3d-model.png' alt='3d model of shirt'>
                <div class='text-wrapper'>
                    <div class='addon-form'>
                        <h3 class='section-subheader'>3D models</h3>
                        <label class='addon-form__label' for='3d-models'>Choose extra models for €$oAddOnRow->addon_price each:</label>
                        <input class='addon-form__input addon-form__input_$oAddOnRow->addon_id' type='number' value='1' id='3d-models'>
                        <button onclick='addAddOnToCart($oAddOnRow->addon_id)' class='addon-form__button button button--yellow'>Add to Cart</button>
                    </div>
                </div>
            </div>";
                break;
            case 2:
                $sAddOnHtmlComp = $sAddOnHtmlComp . "<div class='addon-wrapper'>
                <img class='addon-image' src='./assets/images/variations.png' alt='shirt varitions'>
                <div class='text-wrapper'>
                    <div class='addon-form'>
                        <h3 class='section-subheader'>3D Variations</h3>
                        <label class='addon-form__label' for='model-variations'>Choose extra model variations for €$oAddOnRow->addon_price each:</label>
                        <input class='addon-form__input addon-form__input_$oAddOnRow->addon_id' type='number' value='1' id='model-variations'>
                        <button onclick='addAddOnToCart($oAddOnRow->addon_id)' class='addon-form__button button button--yellow'>Add to Cart</button>
                    </div>
                </div>
            </div>";
                break;
        }
    }

    return $sAddOnHtmlComp;
}
