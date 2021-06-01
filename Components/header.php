<?php


function headerComp()
{
    $cartCount = 0;
    if (isset($_SESSION['loginStatus'])) {
        $loginLink =
            "<a href='logout.php' class='navigation-list__item-link'>Logout</a>";
    } else {
        $loginLink =
            "<a href='login.php' class='navigation-list__item-link'>Login</a>";
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
                            <a href='index.php' class='navigation-list__item-link'
                                >Home</a
                            >
                        </li>
                        <li class='navigation-list__item'>
                            <a
                                href='#use-case'
                                class='navigation-list__item-link'
                                >Use Cases</a
                            >
                        </li>
                        <li class='navigation-list__item'>
                            <a
                                href='#technologies'
                                class='navigation-list__item-link'
                                >Technologies</a
                            >
                        </li>
                        <li class='navigation-list__item'>
                            <a
                                href='#solutions'
                                class='navigation-list__item-link'
                                >Solutions</a
                            >
                        </li>
                        <li class='navigation-list__item'>
                            <a href='#' class='navigation-list__item-link'
                                >Profile</a
                            >
                        </li>
                        <li class='navigation-list__item'>
                            <a href='cart.php' class='navigation-list__item-link'
                                >Cart<span class='cart-counter'>$cartCount</span></a
                            >
                        </li>
                        <li class='navigation-list__item'>
                            $loginLink
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>";

    return $headerContent;
}
