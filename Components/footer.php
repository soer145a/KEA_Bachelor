<?php

//The function that creates the footer on each page
function footerComp()
{   //The footer that is returned to where it was requested from
    $sFooterHtmlComp =
        "<footer class='container-full-width'>
            <div class='layout-container footer'>
                <div class='address address-container'>
                    <h3 class='address__header'>Mirtual</h3>
                    <address class='section-paragraph address__information'>
                        Njalsgade 21G, 3rd fl.
                        <br />
                        2300 København S
                        <br />
                        Denmark
                        <br />
                        <a class='address__telephone' href='tel:+4526744609'
                            >+45 2674 4609</a
                        >
                        <br />
                        <a
                            class='address__email'
                            href='mail:mailto:contact@mirtual.dk'
                            >contact@mirtual.dk</a
                        >
                    </address>
                </div>
                <div class='about about-container'>
                    <h3 class='address__header'>About</h3>
                    <p class='section-paragraph'>
                        We help companies and marketing teams use Digital
                        Solutions, Virtual and Augmented Reality (VR/AR) to
                        create memorable interactive experiences with customers
                        and clients. Our unique approach enables us to
                        communicate complex messages combined into intuitive,
                        interactive, and personal stories that can be
                        experienced in 3D space.
                    </p>
                </div>
            </div>
        </footer>";
    //Return the html block
    return $sFooterHtmlComp;
}
