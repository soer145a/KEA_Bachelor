
function togglePaypalButton(bLoginStatus) {
  //The parameters come from the backend via php variables on the cart page
  let ePaypalContainer = document.querySelector("#paypal-button-container");
  let eButtonPlaceholder = document.createElement("button");
  eButtonPlaceholder.setAttribute(
    "class",
    "order-summary__button button button--purple"
  );
  let nPrice = 0;
  eButtonPlaceholder.textContent = "PayPal";
  postData("api/get-cart-total.php", {
      testString: "It worked!"
  }).then((jResponse) => {
    if (jResponse.priceReturned) {
      nPrice = jResponse.priceTotal;
      
    }
    console.log(jResponse);
    console.log(nPrice,typeof nPrice);
  
    
  //If the price is
  if (nPrice > 0) {
    //If the price is set below 0, we do not want to send data to the backend
    if (bLoginStatus) {
      ePaypalContainer.textContent = "";

      paypal
        .Buttons({
          style: {
            color: "blue",
            shape: "rect",
            size: "responsive",
          },
          //Create order with paypal
          createOrder: function (data, actions) {
            return actions.order.create({
              purchase_units: [
                {
                  amount: {
                    value: nPrice,
                  },
                },
              ],
            });
          },
          //When the order is approved and the money is transfered
          onApprove: function (data, actions) {
            return actions.order.capture().then(function () {
              postData("api/start-purchase-session.php", {
                confirmString: true,
              }).then(
                window.location.assign(
                  //The way we redirect with javascript
                  window.location.protocol +
                    "/KEA_Bachelor/api/payment-handler.php"
                )
              );
            });
          },
        })
        .render("#paypal-button-container");
    } else {
      //If the user is not logged in, we validate on the input fields
      
      if (document.querySelectorAll(".valid").length !== 12) {
        
        if (document.querySelector(".paypal-buttons") !== null) {
          
          //Remove paypal button if it's there
          ePaypalContainer.textContent = "";
          ePaypalContainer.appendChild(eButtonPlaceholder);
        }
      } else {
        //If all input fields are valid, we then make the paypal button
        
        
          
          ePaypalContainer.textContent = "";
          paypal
            .Buttons({
              style: {
                color: "blue",
                shape: "rect",
                size: "responsive",
              },
              //creating the order in paypal
              createOrder: function (data, actions) {
                return actions.order.create({
                  purchase_units: [
                    {
                      amount: {
                        value: nPrice,
                      },
                    },
                  ],
                });
              },
              //When the money is transfered successfully
              onApprove: function (data, actions) {
                return actions.order.capture().then(function () {
                  postData("api/start-purchase-session.php", {
                    confirmString: true,
                  }).then(document.querySelector(".account-details").submit());
                });
              },
            })
            .render("#paypal-button-container");
        
      }
    }
  } else {
    //If there are nothing in the cart, remove the paypal button
    
    ePaypalContainer.textContent = "";
    ePaypalContainer.appendChild(eButtonPlaceholder);
  }})
}
function removeItemFromCart(sItemId, bIsProduct, nAddonAmount, bLoginStatus, nPrice) {
  //the function that removes a selected object from the session
  updateCartCounter(bIsProduct, nAddonAmount, false);
  //Removing the element in the dom
  event.target.parentElement.parentElement.parentElement.remove();
  //Making the new paypal button
  if (document.querySelectorAll(".product-row").length == 0) {
    togglePaypalButton(bLoginStatus);
  }
  //Send the request to the api
  postData("api/remove-item-from-cart.php", {
    itemId: sItemId,
    isProduct: bIsProduct,
  });
  let eTotalPriceSpan = document.querySelector("#totalPriceSpan");
  let nNewTotalPrice = parseFloat(eTotalPriceSpan.textContent) - nPrice;
  totalPriceSpan.textContent = nNewTotalPrice;
  togglePaypalButton(bLoginStatus);
}
