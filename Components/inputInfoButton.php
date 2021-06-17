


<?php

function inputInfoButtonComp($aListItems, $sTitleText)
{
    $sListItems = "";
    foreach ($aListItems as $sListItem) {
        $sListItems = $sListItems . $sListItem;
    }

    $sInputInfoButtonHtml =
        "<span class='infobox-container js-toggle-infobox'>
            <span class='question-mark'>
                <span class='question-mark__inner'></span>
            </span>
            <span class='infobox infobox--hidden'>
                <h5 class='section-subheader infobox__header'>$sTitleText:</h5>
                <ul class='infobox__list'>
                    $sListItems
                </ul>
            </span>
        </span>";
    //Return html block
    return $sInputInfoButtonHtml;
}
