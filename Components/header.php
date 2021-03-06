<?php


function headerComp($sActiveLink)
{

    $sIndexActiveClass = '';
    $sProfileActiveClass = '';
    $sCartActiveClass = '';
    $sLoginActiveClass = '';
    //When creating the header component, we need to have an active link for which side of website the user is on
    switch ($sActiveLink) {

        case 'index':
            $sIndexActiveClass = "navigation-list__item-link--active";
            break;

        case 'profile':
            $sProfileActiveClass = "navigation-list__item-link--active";
            break;

        case 'cart':
            $sCartActiveClass = "navigation-list__item-link--active";
            break;

        case 'login':
            $sLoginActiveClass = "navigation-list__item-link--active";
            break;
    }

    $nCartCount = 0;
    //Add the correct navigation links if the user is logged in
    if (isset($_SESSION['loginStatus'])) {
        $sLoginLinkHtml =
            "<a href='logout.php' class='navigation-list__item-link $sLoginActiveClass'>Logout</a>";
        $sProfileLinkHtml = "<li class='navigation-list__item'><a href='profile.php' class='navigation-list__item-link $sProfileActiveClass'>Profile</a></li>";
    } else {
        $sLoginLinkHtml =
            "<a href='login.php' class='navigation-list__item-link $sLoginActiveClass'>Login</a>";
        $sProfileLinkHtml = '';
    }

    if (isset($_SESSION['cartProducts'])) {
        //Update the little red blob on the cart information
        $nCartCount = $nCartCount + count($_SESSION['cartProducts']);
    }
    if (isset($_SESSION['cartAddOns'])) {
        foreach ($_SESSION['cartAddOns'] as $aAddon) {
            $nCartCount = $nCartCount + $aAddon['addOnAmount'];
        }
    }
    //The actual html block
    $sHeaderHtmlComp =
        "<header class='container-full-width'>
        <nav class='layout-container navigation-contatiner navigation'>
            <a href='index.php'
                ><img
                    class='navigation__logo'
                    src='./assets/logo.png'
                    alt='brand logo'
            /></a>
            <div class='navigation-wrapper'>
                <a href='cart.php' class='navigation-list__item-link $sCartActiveClass cart-mobile'>
                    Cart<span class='cart-counter'>$nCartCount</span>
                </a>
                <div class='navigation-hamburger js-toggleNavigation'>
                    <span class='navigation-hamburger__line'></span>
                    <span class='navigation-hamburger__line'></span>
                    <span class='navigation-hamburger__line'></span>
                </div>
                <div
                    class='
                        navigation-list-wrapper
                        navigation-list-wrapper--hidden
                    '
                >
                    <ul class='navigation-list'>
                        <li class='navigation-list__item'>
                            <a href='index.php' class='navigation-list__item-link $sIndexActiveClass'
                                >Home</a
                            >
                        </li>   
                        $sProfileLinkHtml                       
                        <li class='navigation-list__item'>
                            $sLoginLinkHtml
                        </li>
                        <li class='navigation-list__item'>
                            <a href='cart.php' class='navigation-list__item-link $sCartActiveClass cart-desktop'
                                >Cart<span class='cart-counter'>$nCartCount</span></a
                            >
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        <div class='message-box message-box--hidden' id='messageBox'>
            <p class='message-box__text' id='messageText'></p>
        </div>
    </header>";
    //Return the newly assembled block of html
    return $sHeaderHtmlComp;
}
