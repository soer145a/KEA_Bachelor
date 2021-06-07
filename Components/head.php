<?php
//The header component
function headComp()
{
    $sHeadHtmlComp =
        "<meta charset='UTF-8' />
        <meta http-equiv='X-UA-Compatible' content='IE=edge' />
        <meta name='viewport' content='width=device-width, initial-scale=1.0' />
        <title>MainPage</title>
        <link rel='preconnect' href='https://fonts.gstatic.com' />
        <link rel='icon' type='image/png' href='assets/favicon.png'/>
        <link rel='stylesheet' href='//cdn.jsdelivr.net/gh/highlightjs/cdn-release@11.0.1/build/styles/default.min.css'>
        <link
            href='https://fonts.googleapis.com/css2?family=Asap+Condensed:wght@400;500&family=Poppins&display=swap'
            rel='stylesheet'
        />
        <link rel='stylesheet' href='css/stylereset.css' />
        <link rel='stylesheet' href='css/app.css' />
        <link rel='stylesheet' href='css/navigation.css' />
        <link rel='stylesheet' href='css/section-intro.css' />
        <link rel='stylesheet' href='css/section-use-case.css' />
        <link rel='stylesheet' href='css/section-technologies.css' />
        <link rel='stylesheet' href='css/section-solutions.css' />
        <link rel='stylesheet' href='css/section-miscelleanous.css' />
        <link rel='stylesheet' href='css/section-contact.css' />
        <link rel='stylesheet' href='css/section-cart.css' />
        <link rel='stylesheet' href='css/section-login.css' />
        <link rel='stylesheet' href='css/section-profile.css' />
        <link rel='stylesheet' href='css/section-order-confirmation.css' />
        <link rel='stylesheet' href='css/footer.css' />
        <link rel='stylesheet' href='css/media-queries.css' />
        ";
    //Return html block
    return $sHeadHtmlComp;
}
