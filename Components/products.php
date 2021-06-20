<?php
//The function that creates the products
function productsComp($oDbConnection)
{
    //Get all the products in the database
    $sProductSelectSql = 'SELECT * FROM products';
    $oProductResult = $oDbConnection->query($sProductSelectSql);
    $sSubscriptionSelectSql = 'SELECT * FROM subscriptions';
    $oSubscriptionResult = $oDbConnection->query($sSubscriptionSelectSql);
    $sProductHtmlComp = "";
    $aSubscriptions = [];
    $nCounter = "0";
    //add the subscription data to an array
    while ($subscriptionRow = $oSubscriptionResult->fetch_object()) {
        array_push($aSubscriptions, $subscriptionRow);
    }

    //For each product create an html block
    while ($oProductRow = $oProductResult->fetch_object()) {
        $subscriptionList = "";
        foreach ($aSubscriptions as $oSubscription) {
            //The list of subscription options on the product html block
            $subscriptionList = $subscriptionList . "<span class='dropdown__list-item' data-subscriptionid='$oSubscription->subscription_id' data-buttonid='$nCounter'>$oSubscription->subscription_name - €$oSubscription->subscription_price/month</span>";
        }
        //For each product create the corresponding html block
        switch ($oProductRow->product_id) {
            case 1:
                $sProductHtmlComp = $sProductHtmlComp . "
                <div class='product'>
                    <span class='
                          background-sphere
                          background-sphere--left
                          background-sphere--top
                          background-sphere--purple
                    '></span>
                    <div class='text-wrapper'>
                        <h3 class='section-subheader'>Solution</h3>
                        <h2 class='section-header'>$oProductRow->product_name</h2>
                        <img class='section-image' src='$oProductRow->product_image_url' alt='$oProductRow->product_name' />
                    </div>
                    <div class='text-container'>
                        <span class='text-container__background-block'></span>
                        <div class='text-wrapper product-description'>
                            <p class='section-paragraph'>
                                Free up space otherwise occupied by changing
                                rooms. With an in-store kiosk your customers
                                can try out clothes without having to strip
                                down first.
                            </p>
                            <p class='section-paragraph'>
                                We will provide the hardware and software
                                needed to get it up and running in your
                                store all at the one-time price of €$oProductRow->product_price.
                            </p>
                        </div>
                    <div class='product-fees'>
                      <h4 class='section-paragraph'>
                          The fee includes the following:
                      </h4>
                      <ul class='product-fee__list'>
                          <li class='product-fee__list-item'>
                              An in-kiosk monitor, with built-in
                              hardware
                          </li>
                          <li class='product-fee__list-item'>
                              Company branding on the software
                          </li>
                          <li class='product-fee__list-item'>
                              Company’s description
                          </li>
                          <li class='product-fee__list-item'>
                              Contact details
                          </li>
                          <li class='product-fee__list-item'>
                              AR view of 3 clothing items
                          </li>
                      </ul>
                    </div>
                    <div class='dropdown-container'>
                        <div class='dropdown'>
                          <span class='dialog-box dialog-box--hidden' data-buttonid='$nCounter'>You have to choose a subscription length.</span>
                          <span class='dropdown__label'>Why do I need a subscription?</span>
                          <span class='dropdown__button' data-buttonid='$nCounter'>Choose a subscription length</span>
                          <div class='
                                      dropdown-list-container
                                      dropdown-list-container--hidden
                                  ' data-listid='$nCounter'>
                                  
                                  <div class='dropdown__list'>
                                  $subscriptionList
                                </div>
                          </div>
                      </div>
                      <button class='button button--yellow' onclick='addProductToCart($oProductRow->product_id, $nCounter)' >Add to Cart</button>
                  </div>
              </div>
          </div>";

                break;
            case 2:
                $sProductHtmlComp = $sProductHtmlComp . "<div id='product-2'></div>
                <div class='product'>
                <span class='
                        background-sphere
                        background-sphere--right
                        background-sphere--top
                        background-sphere--yellow
                        '></span>
                <div class='text-wrapper place-right'>
                    <h3 class='section-subheader'>Solution</h3>
                    <h2 class='section-header'>$oProductRow->product_name</h2>
                    <img class='section-image' src='$oProductRow->product_image_url' alt='$oProductRow->product_name' />
                </div>
                <div class='text-container'>
                    <span class='text-container__background-block'></span>
  
                    <div class='text-wrapper product-description'>
                        <p class='section-paragraph'>
                            Free up space otherwise occupied by changing
                            rooms. With an in-store kiosk your customers
                            can try out clothes without having to strip
                            down first.
                        </p>
                        <p class='section-paragraph'>
                            We will provide the hardware and software
                            needed to get it up and running in your
                            store all at the one-time price of €$oProductRow->product_price.
                        </p>
                    </div>
  
                    <div class='product-fees'>
                        <h4 class='section-paragraph'>
                            The fee includes the following:
                        </h4>
                        <ul class='product-fee__list'>
                            <li class='product-fee__list-item'>
                                An in-kiosk monitor, with built-in
                                hardware
                            </li>
                            <li class='product-fee__list-item'>
                                Company branding on the software
                            </li>
                            <li class='product-fee__list-item'>
                                Company’s description
                            </li>
                            <li class='product-fee__list-item'>
                                Contact details
                            </li>
                            <li class='product-fee__list-item'>
                                AR view of 3 clothing items
                            </li>
                        </ul>
                    </div>
  
                    <div class='dropdown-container'>
                      <div class='dropdown'>
                      <span class='dialog-box dialog-box--hidden' data-buttonid='$nCounter'>You have to choose a subscription length.</span>
                          <span class='dropdown__label'>Why do I need a subscription?</span>
                          <span class='dropdown__button' data-buttonid='$nCounter'>Choose a subscription length</span>
                          <div class='
                                      dropdown-list-container
                                      dropdown-list-container--hidden
                                  ' data-listid='$nCounter'>
                                  
                                  <div class='dropdown__list'>
                                  $subscriptionList
                                </div>
                          </div>
                      </div>
                      <button class='button button--yellow' onclick='addProductToCart($oProductRow->product_id, $nCounter)' >Add to Cart</button>
                  </div>
              </div>
          </div>";
                break;
            case 3:
                $sProductHtmlComp = $sProductHtmlComp . "<div id='product-3'></div>
                <div class='product'>
                <span class='
                            background-sphere
                            background-sphere--left
                            background-sphere--top
                            background-sphere--cyan
                        '></span>
                <div class='text-wrapper'>
                    <h3 class='section-subheader'>Solution</h3>
                    <h2 class='section-header'>$oProductRow->product_name</h2>
                    <img class='section-image' src='$oProductRow->product_image_url' alt='$oProductRow->product_name' />
                </div>
                <div class='text-container'>
                    <span class='text-container__background-block'></span>
  
                    <div class='text-wrapper product-description'>
                        <p class='section-paragraph'>
                            Offer you customers the future in purchase
                            experience. Let your customers virtually try
                            on clothes in the comfort of their home and
                            let your business reach out to a wider
                            consumer base that is restricted by borders.
                        </p>
                        <p class='section-paragraph'>
                            We will provide the hardware and software
                            needed to get it up and running in your
                            store all at the one-time price of €$oProductRow->product_price.
                        </p>
                    </div>
  
                    <div class='product-fees'>
                        <h4 class='section-paragraph'>
                            The fee includes the following:
                        </h4>
                        <ul class='product-fee__list'>
                            <li class='product-fee__list-item'>
                                An in-kiosk monitor, with built-in
                                hardware
                            </li>
                            <li class='product-fee__list-item'>
                                Company branding on the software
                            </li>
                            <li class='product-fee__list-item'>
                                Company’s description
                            </li>
                            <li class='product-fee__list-item'>
                                Contact details
                            </li>
                            <li class='product-fee__list-item'>
                                AR view of 3 clothing items
                            </li>
                        </ul>
                    </div>
  
                    <div class='dropdown-container'>
                      <div class='dropdown'>
                      <span class='dialog-box dialog-box--hidden' data-buttonid='$nCounter'>You have to choose a subscription length.</span>
                          <span class='dropdown__label'>Why do I need a subscription?</span>
                          <span class='dropdown__button' data-buttonid='$nCounter'>Choose a subscription length</span>
                          <div class='
                                      dropdown-list-container
                                      dropdown-list-container--hidden
                                  ' data-listid='$nCounter'>
                                  
                                  <div class='dropdown__list'>
                                  $subscriptionList
                                </div>
                          </div>
                      </div>
                      <button class='button button--yellow' onclick='addProductToCart($oProductRow->product_id, $nCounter)' >Add to Cart</button>
                  </div>
              </div>
          </div>";
        }
        //Update the counter
        $nCounter++;
    }
    //Return the html blocks
    return $sProductHtmlComp;
};
