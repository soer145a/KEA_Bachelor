<?php

include_once("components/inputInfoButton.php");

//The sign up form component
function signUpFormComp($bLoginStatus, $nTotalPrice)
{

    $aListItems = array("<li class='infobox__list-item'>8 characters</li>", "<li class='infobox__list-item'>Only numbers</li>");
    $sCvrInfoButtonHtml = inputInfoButtonComp($aListItems,  "Cvr requirements");
    $aListItems = array("<li class='infobox__list-item'>6-30 characters</li>", "<li class='infobox__list-item'>One uppercase character</li>", " <li class='infobox__list-item'>One numeric character</li>", "<li class='infobox__list-item'>One special character</li>");
    $sPasswordInfoButtonHtml = inputInfoButtonComp($aListItems, "Password requirements");
    $aListItems = array("<li class='infobox__list-item'>Start with countrycode (+45)</li>", "<li class='infobox__list-item'>After that, only numbers</li>", "<li class='infobox__list-item'>between 5-15 characters</li>");
    $sPhoneInfoButtonHtml = inputInfoButtonComp($aListItems, "Phone requirements");


    $sSignUpFormHtmlComp =
        "
        <form action='api/payment-handler.php' method='POST' class='account-details'>
            <h2 class='section-header'>Account details</h2>
            <label for='account-details__name class='account-details__label''
                >Company Name</label
            >
            <input
                id='account-details__name'
                type='text'
                name='companyName'
                data-validate='string'
                required
                oninput='inputValidate(); togglePaypalButton($bLoginStatus);'
            />
            <label for='account-details__cvr' class='account-details__label'
                >Company CVR nr. $sCvrInfoButtonHtml       
            </label
            >
            <input
                id='account-details__cvr'
                type='text'
                name='companyCvr'
                data-validate='cvr'
                required
                oninput='inputValidate($bLoginStatus, $nTotalPrice);'
            />
            <div class='account-details__contact'>
                <h4 class='section-subheader contact__header'>Contact Person</h4>
                <div class='contact__wrapper'>
                    <label class='account-details__label' for='contact__firstname'>Firstname</label>
                    <input
                        id='contact__firstname'
                        type='text'
                        name='customerFirstName'
                        data-validate='string'
                        required
                        oninput='inputValidate(); togglePaypalButton($bLoginStatus);'
                    />
                </div>
                <div class='contact__wrapper'>
                    <label class='account-details__label' for='contact__lastname'>Lastname</label>
                    <input
                        id='contact__lastname'
                        type='text'
                        name='customerLastName'
                        data-validate='string'
                        required
                        oninput='inputValidate(); togglePaypalButton($bLoginStatus);'
                    />
                </div>
            </div>
            
            <label class='account-details__label' for='account-details__phone'>Phone Number $sPhoneInfoButtonHtml
            </label>
            <input
                id='account-details__phone'
                type='text'
                name='customerPhone'
                data-validate='phone'
                required
                oninput='inputValidate(); togglePaypalButton($bLoginStatus);'
            />
            <label class='account-details__label' for='account-details__mail'>Email</label>
            <input
                id='account-details__mail'
                type='email'
                name='customerEmail'
                data-validate='email'              
                required
                oninput='inputValidate($bLoginStatus, $nTotalPrice);'
            />
            <label class='account-details__label' for='account-details__password'
                >Password $sPasswordInfoButtonHtml
                </label>
            <input
                id='accountDetails__password'
                type='password'
                name='customerPassword'
                data-validate='password'
                required
                oninput='inputValidate(); togglePaypalButton($bLoginStatus);'
            />
            <label class='account-details__label' for='account-details__confirm-password'
                >Confirm Password</label
            >
            <input
                id='accountDetails__confirmPassword'
                type='password'
                name='customerPasswordConfirm'
                data-validate='confirmPassword'
                required
                oninput='inputValidate(); togglePaypalButton($bLoginStatus);'
            />
            <h2 class='section-header'>
                Shipping/Billing address
            </h2>
            <label class='account-details__label' for='account-details__street-name'
                >Street name</label
            >
            <input
                id='account-details__street-name'
                type='text'
                name='companyStreet'
                data-validate='string'
                required
                oninput='inputValidate(); togglePaypalButton($bLoginStatus);'
            />
            <label class='account-details__label' for='account-details__city'>City</label>
            <input
                id='account-details__city'
                type='text'
                data-validate='string'
                name='companyCity'
                required
                oninput='inputValidate(); togglePaypalButton($bLoginStatus);'
            />
            <label class='account-details__label' for='account-details__zip-code'
                >Zip code</label
            >
            <input
                id='account-details__zip-code'
                type='text'
                data-validate='string'
                name='companyZip'
                required
                oninput='inputValidate(); togglePaypalButton($bLoginStatus);'
            />
            <label class='account-details__label' for='account-details__country'
                >Country</label
            >
            <input
                id='account-details__country'
                type='text'
                data-validate='string'
                name='companyCountry'
                required
                oninput='inputValidate(); togglePaypalButton($bLoginStatus);'
            />            
            <div class='errorMessage'></div>
        </form>
        ";
    //Return html block
    return $sSignUpFormHtmlComp;
}
