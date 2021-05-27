<?php
function headComp()
{
    $head =
        "<meta charset='UTF-8' />
        <meta http-equiv='X-UA-Compatible' content='IE=edge' />
        <meta name='viewport' content='width=device-width, initial-scale=1.0' />
        <title>MainPage</title>
        <link rel='preconnect' href='https://fonts.gstatic.com' />
        <link
            href='https://fonts.googleapis.com/css2?family=Asap+Condensed:wght@400;500&family=Poppins&display=swap'
            rel='stylesheet'
        />
        <link rel='stylesheet' href='css/stylereset.css' />
        <link rel='stylesheet' href='css/app.css' />
        <link rel='stylesheet' href='css/navigation.css' />
        <link rel='stylesheet' href='css/section_intro.css' />
        <link rel='stylesheet' href='css/section_use_case.css' />
        <link rel='stylesheet' href='css/section_technologies.css' />
        <link rel='stylesheet' href='css/section_solutions.css' />
        <link rel='stylesheet' href='css/section_miscelleanous.css' />
        <link rel='stylesheet' href='css/section_contact.css' />
        <link rel='stylesheet' href='css/footer.css' />
        <link rel='stylesheet' href='css/media_queries.css' />
        ";
    return $head;
}
