<?php
//$productId, $productName, $imageUrl, $productPrice,

function productsComp($conn)
{

    $productSql = 'SELECT * FROM products';
    $productResult = $conn->query($productSql);
    $subscriptionSql = 'SELECT * FROM subscriptions';
    $subscriptionResult = $conn->query($subscriptionSql);
    $productsContent = "";
    $aSubscription = [];
    $counter = "0";

    while ($subscriptionRow = $subscriptionResult->fetch_object()) {
        array_push($aSubscription, $subscriptionRow);
    }
    // echo json_encode($aSubscription);


    while ($productRow = $productResult->fetch_object()) {
        $subscriptionList = "";
        foreach ($aSubscription as $sub) {
            $subscriptionList = $subscriptionList . "<span class='dropdown__list-item' data-subscriptionid='$sub->subscription_id' data-buttonid='$counter'>$sub->subscription_name - €$sub->subscription_price/month</span>";
        }

        switch ($productRow->product_id) {
            case 1:
                $productsContent = $productsContent . "<div class='product'>
              <span class='
                          background-sphere
                          background-sphere--left
                          background-sphere--top
                          background-sphere--purple
                      '></span>
              <div class='text-wrapper'>
                  <h3 class='section-subheader'>Solution</h3>
                  <h2 class='section-header'>$productRow->product_name</h2>
                  <img class='section-image' src='$productRow->product_image_url' alt='$productRow->product_name' />
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
                          store all at the one-time price of €$productRow->product_price.
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
                          <span class='dialog-box dialog-box--hidden' data-buttonid='$counter'>You have to choose a subscription lenght.</span>
                          <span class='dropdown__label'>Why do I need a subscription?</span>
                          <span class='dropdown__button' data-buttonid='$counter'>Choose a subscription length</span>
                          <div class='
                                      dropdown-list-container
                                      dropdown-list-container--hidden
                                  ' data-listid='$counter'>
                                  
                                  <div class='dropdown__list'>
                                  $subscriptionList
                                </div>
                          </div>
                      </div>
<<<<<<< HEAD
                      <button class='button button--yellow' onclick='addToCart($productRow->product_id, $counter)' >Add to Cart</button>
=======
                      <button class='add-to-cart' onclick='addProductToCart($productRow->product_id, $counter)' >Add to Cart</button>
>>>>>>> 51eb7067c51fb8da6fadcc4e307323b75a6b1c07
                  </div>
              </div>
          </div>";

                break;
            case 2:
                $productsContent = $productsContent . "<div id='product-2'></div>
                <div class='product'>
                <span class='
                        background-sphere
                        background-sphere--right
                        background-sphere--top
                        background-sphere--yellow
                        '></span>
                <div class='text-wrapper place-right'>
                    <h3 class='section-subheader'>Solution</h3>
                    <h2 class='section-header'>$productRow->product_name</h2>
                    <img class='section-image' src='$productRow->product_image_url' alt='$productRow->product_name' />
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
                            store all at the one-time price of €$productRow->product_price.
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
                      <span class='dialog-box dialog-box--hidden' data-buttonid='$counter'>You have to choose a subscription lenght.</span>
                          <span class='dropdown__label'>Why do I need a subscription?</span>
                          <span class='dropdown__button' data-buttonid='$counter'>Choose a subscription length</span>
                          <div class='
                                      dropdown-list-container
                                      dropdown-list-container--hidden
                                  ' data-listid='$counter'>
                                  
                                  <div class='dropdown__list'>
                                  $subscriptionList
                                </div>
                          </div>
                      </div>
<<<<<<< HEAD
                      <button class='button button--yellow' onclick='addToCart($productRow->product_id, $counter)' >Add to Cart</button>
=======
                      <button class='add-to-cart' onclick='addProductToCart($productRow->product_id, $counter)' >Add to Cart</button>
>>>>>>> 51eb7067c51fb8da6fadcc4e307323b75a6b1c07
                  </div>
              </div>
          </div>";
                break;
            case 3:
                $productsContent = $productsContent . "<div id='product-3'></div>
                <div class='product'>
                <span class='
                            background-sphere
                            background-sphere--left
                            background-sphere--top
                            background-sphere--cyan
                        '></span>
                <div class='text-wrapper'>
                    <h3 class='section-subheader'>Solution</h3>
                    <h2 class='section-header'>$productRow->product_name</h2>
                    <img class='section-image' src='$productRow->product_image_url' alt='$productRow->product_name' />
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
                            store all at the one-time price of €$productRow->product_price.
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
                      <span class='dialog-box dialog-box--hidden' data-buttonid='$counter'>You have to choose a subscription lenght.</span>
                          <span class='dropdown__label'>Why do I need a subscription?</span>
                          <span class='dropdown__button' data-buttonid='$counter'>Choose a subscription length</span>
                          <div class='
                                      dropdown-list-container
                                      dropdown-list-container--hidden
                                  ' data-listid='$counter'>
                                  
                                  <div class='dropdown__list'>
                                  $subscriptionList
                                </div>
                          </div>
                      </div>
<<<<<<< HEAD
                      <button class='button button--yellow' onclick='addToCart($productRow->product_id, $counter)' >Add to Cart</button>
=======
                      <button class='add-to-cart' onclick='addProductToCart($productRow->product_id, $counter)' >Add to Cart</button>
>>>>>>> 51eb7067c51fb8da6fadcc4e307323b75a6b1c07
                  </div>
              </div>
          </div>";
        }
        $counter++;
    }
    return $productsContent;
};
