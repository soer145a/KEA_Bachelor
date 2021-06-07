<?php
//The sign up form component
function signUpFormComp($bLoginStatus, $nTotalPrice)
{
    $sSignUpFormHtmlComp =
        "
        <form action='api/payment-handler.php' method='POST' class='account-details'>
            <h2 class='section-header'>Account details</h2>
            <label for='account-details__name'
                >Company Name</label
            >
            <input
                id='account-details__name'
                type='text'
                name='companyName'
                data-validate='string'
                required
                oninput='inputValidate(); togglePaypalButton($bLoginStatus, $nTotalPrice);'
            />
            <label for='account-details__cvr'
                >Company CVR nr.                
            </label
            >
            <input
                id='account-details__cvr'
                type='text'
                name='companyCvr'
                data-validate='cvr'
                required
                oninput='inputValidate(); togglePaypalButton($bLoginStatus, $nTotalPrice);'
            />
            <div class='account-details__contact'>
                <h4 class='section-subheader contact__header'>Contact Person</h4>
                <div class='contact__wrapper'>
                    <label for='contact__firstname'>Firstname</label>
                    <input
                        id='contact__firstname'
                        type='text'
                        name='customerFirstName'
                        data-validate='string'
                        required
                        oninput='inputValidate(); togglePaypalButton($bLoginStatus, $nTotalPrice);'
                    />
                </div>
                <div class='contact__wrapper'>
                    <label for='contact__lastname'>Lastname</label>
                    <input
                        id='contact__lastname'
                        type='text'
                        name='customerLastName'
                        data-validate='string'
                        required
                        oninput='inputValidate(); togglePaypalButton($bLoginStatus, $nTotalPrice);'
                    />
                </div>
            </div>
            
            <label for='account-details__mail'>Phone Number</label>
            <input
                id='account-details__mail'
                type='text'
                name='customerPhone'
                data-validate='phone'
                required
                oninput='inputValidate(); togglePaypalButton($bLoginStatus, $nTotalPrice);'
            />
            <label for='account-details__mail'>Email</label>
            <input
                id='account-details__mail'
                type='email'
                name='customerEmail'
                data-validate='email'
                required
                oninput='inputValidate(); togglePaypalButton($bLoginStatus, $nTotalPrice);'
            />
            <label for='account-details__password'
                >Password</label
            >
            <input
                id='accountDetails__password'
                type='password'
                name='customerPassword'
                data-validate='password'
                required
                oninput='inputValidate(); togglePaypalButton($bLoginStatus, $nTotalPrice);'
            />
            <label for='account-details__confirm-password'
                >Confirm Password</label
            >
            <input
                id='accountDetails__confirmPassword'
                type='password'
                name='customerPasswordConfirm'
                data-validate='confirmPassword'
                required
                oninput='inputValidate(); togglePaypalButton($bLoginStatus, $nTotalPrice);'
            />
            <h2 class='section-header'>
                Shipping/Billing address
            </h2>
            <label for='account-details__street-name'
                >Street name</label
            >
            <input
                id='account-details__street-name'
                type='text'
                name='companyStreet'
                data-validate='string'
                required
                oninput='inputValidate(); togglePaypalButton($bLoginStatus, $nTotalPrice);'
            />
            <label for='account-details__city'>City</label>
            <input
                id='account-details__city'
                type='text'
                data-validate='string'
                name='companyCity'
                required
                oninput='inputValidate(); togglePaypalButton($bLoginStatus, $nTotalPrice);'
            />
            <label for='account-details__zip-code'
                >Zip code</label
            >
            <input
                id='account-details__zip-code'
                type='text'
                data-validate='string'
                name='companyZip'
                required
                oninput='inputValidate(); togglePaypalButton($bLoginStatus, $nTotalPrice);'
            />
            <label for='account-details__zip-code'
                >Country</label
            >
            <input
                id='account-details__zip-code'
                type='text'
                data-validate='string'
                name='companyCountry'
                required
                oninput='inputValidate(); togglePaypalButton($bLoginStatus, $nTotalPrice);'
            />            
            <div class='errorMessage'></div>
        </form>
        ";
    //Return html block
    return $sSignUpFormHtmlComp;
}
