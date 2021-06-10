<?php

//The sign up form component
function loginFormComp()
{
    $sLoginFormHtmlComp = "<form class='login-form' method='post'>
                    <label class='login-form__label'>Email:</label>
                    <input class='login-form__input' type='email' placeholder='example@email.com' name='customerEmail'>
                    <label class='login-form__label'>Password:  
                    </label>
                    <input class='login-form__input' type='password' placeholder='Type in your password' name='customerPassword'>
                    <br>
                    <button class='login-form__button button button--purple' type='submit'>Login</button>
                </form>";

    //Return html block
    return $sLoginFormHtmlComp;
}
