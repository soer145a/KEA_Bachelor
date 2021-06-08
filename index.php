<?php
session_start();
//Includes for styling purposes
include_once("db-connection/connection.php");
include_once("components/addOn.php");
include_once("components/head.php");
include_once("components/header.php");
include_once("components/products.php");
include_once("components/footer.php");
$sHeadHtmlComp = headComp();
$sHeaderHtmlComp = headerComp('index');
$sProductHtmlComp = productsComp($oDbConnection);
$sFooterHtmlComp = footerComp();
$sAddOnHtmlComp = addOnsComp($oDbConnection);


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?= $sHeadHtmlComp; ?>
</head>

<body>
    <?= $sHeaderHtmlComp ?>
    <main class="container-full-width">
        <section id="intro">
            <div class="layout-container intro">
                <h1 class="intro-heading">
                    Elevate your customers experience with Mirtual - the
                    virtual mirror.
                </h1>
                <div class="video-container">
                    <div class="explainer-video">
                        <iframe width="560" height="315" src="https://www.youtube-nocookie.com/embed/cey_vcyR1EQ?rel=0&modestbranding=1&autohide=1&showinfo=0" title="YouTube video player" frameborder="0" allowfullscreen></iframe>
                        <!-- <div class="explainer-video__thumbnail" alt=" explainer video">
                            <span class="play-button-circle">
                                <span class="play-button-triangle"></span>
                            </span>
                        </div> -->
                    </div>
                </div>
                <span class="background-sphere"></span>
            </div>
        </section>
        <section id="use-case">
            <div class="layout-container use-case">
                <span class="background-sphere"></span>
                <div class="text-wrapper">
                    <h3 class="section-subheader">Use case</h3>
                    <h2 class="section-header">Live Preview</h2>
                    <p class="section-paragraph">
                        Mirtual is a virtual mirror app that allows you to
                        try on clothes using augmented reality. Mirtual uses
                        a camera for it to function and allows customers to
                        virtually try on clothes without a changing room or
                        in the comfort of their home.
                    </p>
                    <p class="section-paragraph">
                        Mirtual can help boutiques selling clothes to reach
                        out to a wider consumer base, without being
                        restricted to their home country.
                    </p>
                    <p class="section-paragraph">
                        Each solution is branded individually and includes
                        an AR view of the uploaded styles, and provides a
                        follow-through for the purchase.
                    </p>
                </div>
                <div class="image-container">
                    <div class="image-wrapper">
                        <img src="./assets/images/use-case.jpg" alt="live preview of clothes in front of in-store screen" />
                    </div>
                    <span class="background-block"></span>
                </div>
            </div>
        </section>

        <section id="technologies">
            <div class="layout-container technologies">
                <span class="background-block"></span>
                <div class="text-container">
                    <div class="text-wrapper">
                        <h3 class="section-subheader">Technologies</h3>
                        <h2 class="section-header">Choose a product</h2>
                        <p class="section-paragraph">
                            Lorem ipsum dolor sit amet, consectetur
                            adipiscing elit, sed do eiusmod tempor
                            incididunt ut labore et dolore magna aliqua.
                            Fusce id velit ut tortor pretium. Pharetra magna
                            ac placerat vestibulum lectus.
                        </p>
                    </div>
                </div>
                <div class="slider-container">
                    <div class="slider">
                        <div id="card-1" class="card">
                            <img class="card__image" src="./assets/images/product-1.jpg" alt="" />
                            <span class="card__overlay"></span>
                            <h4 class="card__title">In-Store Kiosk</h4>
                            <button class="card__button">
                                <a class="card__link" href="#product-1">Learn more</a>
                            </button>
                        </div>
                        <div id="card-2" class="card">
                            <img class="card__image" src="./assets/images/product-2.jpg" alt="" />
                            <span class="card__overlay"></span>
                            <h4 class="card__title">Mobile Devices</h4>
                            <button class="card__button">
                                <a class="card__link" href="#product-2">Learn more</a>
                            </button>
                        </div>
                        <div id="card-3" class="card">
                            <img class="card__image" src="./assets/images/product-3.png" alt="" />
                            <span class="card__overlay"></span>
                            <h4 class="card__title">Webcam</h4>
                            <button class="card__button">
                                <a class="card__link" href="#product-3">Learn more</a>
                            </button>
                        </div>
                    </div>
                    <div class="slider-dots">
                        <a href="#card-1" class="
                                    slider-dots__dot-element
                                    slider-dots__dot-element--active
                                    js-carousel-button
                                "></a>
                        <a href="#card-2" class="
                                    slider-dots__dot-element
                                    js-carousel-button
                                "></a>
                        <a href="#card-3" class="
                                    slider-dots__dot-element
                                    js-carousel-button
                                "></a>
                    </div>
                </div>
            </div>
        </section>

        <section id="solutions">
            <div id="product-1" class="product-link"></div>
            <div class="layout-container solutions">
                <?= $sProductHtmlComp ?>
            </div>
        </section>

        <section id="miscellaneous">
            <div class="layout-container miscellaneous">
                <div class="text-container">
                    <div class="text-wrapper">
                        <h3 class="section-subheader">Miscellaneous</h3>
                        <h2 class="section-header">Add-ons</h2>
                        <p class="section-paragraph">
                            Lorem ipsum dolor sit amet, consectetur
                            adipiscing elit, sed do eiusmod tempor
                            incididunt ut labore et dolore magna aliqua.
                            Fusce id velit ut tortor pretium. Pharetra magna
                            ac placerat vestibulum lectus.
                        </p>
                    </div>

                </div>
                <div class='addon-container'>

                    <?= $sAddOnHtmlComp ?>
                </div>

            </div>
        </section>
        <section id="contact">
            <div class="layout-container contact">
                <div class="section-container">
                    <h2 class="section-header">Contact Us</h2>
                    <p class="section-paragraph">
                        Got a question? We’d love to hear from you. Send us
                        a message and we’ll resond as soon as possible.
                    </p>
                    <div class="form-container">
                        <form class="contact-form" onsubmit="event.preventDefault();">
                            <label class="contact-form__label" for="contact-form__name">Name</label>
                            <input class="contact-form__input" type="text" id="contactFormName" name="potentialCustomerName" />
                            <label class="contact-form__label" for="contact-form__email">Email</label>
                            <input class="contact-form__input" type="text" id="customerFormEmail" name="potentialCustomerEmail" />
                            <label class="contact-form__label" for="contact-form__message">Message</label>
                            <textarea class="contact-form__textarea" id="customerFormMessage" name="potentialCustomerMessage"></textarea>
                            <button type="submit" class="contact-form__button" onclick="sendContactForm();">
                                Send Message
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </main>
    <?= $sFooterHtmlComp ?>
</body>
<script src="js/app.js"></script>
<script src="js/index.js"></script>

</html>