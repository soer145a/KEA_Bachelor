


<?php

function inputInfoButtonComp($aListItems)
{
    $sListItems = "";
    foreach ($aListItems as $sListItem) {
        $sListItems = $sListItems . $sListItem;
    }

    $sInputInfoButtonHtml =
        "<span class='login-form__label-info-outer js-toggle-infobox'>
        <span class='login-form__label-info-inner'>
        </span>
    </span>
    <span class='login-form__label-info-box js-toggle-infobox login-form__label-info-box--hidden'>
        <h5 class='section-subheader label-info-box__header'>The password must concist of:</h5>
        <ul>
            $sListItems
        </ul>
    </span>
        ";
    //Return html block
    return $sInputInfoButtonHtml;
}
