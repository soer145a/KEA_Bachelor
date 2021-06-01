<?php


function headerComp($activeLink)
{

    $indexActiveClass = '';
    $profileActiveClass = '';
    $cartActiveClass = '';
    $loginActiveClass = '';

    switch ($activeLink) {

        case 'index':
            $indexActiveClass = "navigation-list__item-link--active";
            break;

        case 'profile':
            $profileActiveClass = "navigation-list__item-link--active";
            break;

        case 'cart':
            $cartActiveClass = "navigation-list__item-link--active";
            break;

        case 'login':
            $loginActiveClass = "navigation-list__item-link--active";
            break;
    }

    $cartCount = 0;

    if (isset($_SESSION['loginStatus'])) {
        $loginLink =
            "<a href='logout.php' class='navigation-list__item-link $loginActiveClass'>Logout</a>";
        $profileLink = "<li class='navigation-list__item'><a href='profile.php' class='navigation-list__item-link $profileActiveClass'>Profile</a></li>";
    } else {
        $loginLink =
            "<a href='login.php' class='navigation-list__item-link $loginActiveClass'>Login</a>";
        $profileLink = '';
    }

    if (isset($_SESSION['cartProducts'])) {

        $cartCount = $cartCount + count($_SESSION['cartProducts']);
    }
    if (isset($_SESSION['cartAddOns'])) {

        $cartCount = $cartCount + count($_SESSION['cartAddOns']);
    }

    $headerContent =
        "<header class='container-full-width'>
        <nav class='layout-container navigation-contatiner navigation'>
            <a href='index.php'
                ><img
                    class='navigation__logo'
                    src='./Assets/logo.png'
                    alt='brand logo'
            /></a>
            <div class='navigation--wrapper'>
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
                            <a href='index.php' class='navigation-list__item-link $indexActiveClass'
                                >Home</a
                            >
                        </li>   
                        $profileLink                       
                        <li class='navigation-list__item'>
                            $loginLink
                        </li>
                        <li class='navigation-list__item'>
                            <a href='cart.php' class='navigation-list__item-link $cartActiveClass'
                                >Cart<span class='cart-counter'>$cartCount</span></a
                            >
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>";

    return $headerContent;
}
