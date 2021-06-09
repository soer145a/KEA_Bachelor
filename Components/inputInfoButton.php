


<?php

function inputInfoButtonComp($aListItems)
{
    $sListItems = "";
    foreach ($aListItems as $sListItem) {
        $sListItems = $sListItems . $sListItem;
    }

    $sInputInfoButtonHtml =
        "<span class='infobox-container'>
            <span class='question-mark js-toggle-infobox'>
                <span class='question-mark__inner'></span>
            </span>
            <span class='infobox js-toggle-infobox infobox--hidden'>
                <h5 class='section-subheader'>The password must concist of:</h5>
                <ul>
                    $sListItems
                </ul>
            </span>
        </span>";
    //Return html block
    return $sInputInfoButtonHtml;
}
