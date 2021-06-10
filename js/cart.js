function togglePaypalButton(bLoginStatus, nPrice) {
  //The parameters come from the backend via php variables on the cart page
  let ePaypalContainer = document.querySelector("#paypal-button-container");
  let eButtonPlaceholder = document.createElement("button");
  eButtonPlaceholder.setAttribute(
    "class",
    "order-summary__button button button--purple"
  );
  eButtonPlaceholder.textContent = "PayPal";
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
      console.log(53);
      if (document.querySelectorAll(".valid").length !== 12) {
        console.log(
          document.querySelectorAll(".valid").length,
          document.querySelectorAll(".valid")
        );
        if (document.querySelector(".paypal-buttons") !== null) {
          console.log(57);
          //Remove paypal button if it's there
          ePaypalContainer.textContent = "";
          ePaypalContainer.appendChild(eButtonPlaceholder);
        }
      } else {
        //If all input fields are valid, we then make the paypal button
        console.log(61);
        if (document.querySelector(".order-summary__button") !== null) {
          console.log(
            document.querySelectorAll(".valid").length,
            document.querySelectorAll(".valid")
          );
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
    }
  } else {
    //If there are nothing in the cart, remove the paypal button
    console.log(102);
    ePaypalContainer.textContent = "";
    ePaypalContainer.appendChild(eButtonPlaceholder);
  }
}
function removeItemFromCart(sItemId, bIsProduct, nAddonAmount, bLoginStatus) {
  //the function that removes a selected object from the session
  updateCartCounter(bIsProduct, nAddonAmount, false);
  //Removing the element in the dom
  event.target.parentElement.parentElement.parentElement.remove();
  //Making the new paypal button
  if (document.querySelectorAll(".product-row").length == 0) {
    togglePaypalButton(bLoginStatus, 0);
  }
  //Send the request to the api
  postData("api/remove-item-from-cart.php", {
    itemId: sItemId,
    isProduct: bIsProduct,
  });
}
