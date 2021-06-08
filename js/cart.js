function togglePaypalButton(bLoginStatus, nPrice) {
  let ePaypalContainer = document.querySelector("#paypal-button-container");
  let eButtonPlaceholder = document.createElement("button");
  eButtonPlaceholder.setAttribute(
    "class",
    "order-summary__button button button--purple"
  );
  eButtonPlaceholder.textContent = "PayPal";
  if (nPrice > 0) {
    if (bLoginStatus) {
      ePaypalContainer.textContent = "";

      paypal
        .Buttons({
          style: {
            color: "blue",
            shape: "rect",
            size: "responsive",
          },
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
          onApprove: function (data, actions) {
            return actions.order.capture().then(function () {
              postData("api/start-purchase-session.php", {
                confirmString: true,
              }).then(
                window.location.assign(
                  window.location.protocol +
                    "/KEA_Bachelor/api/payment-handler.php"
                )
              );
            });
          },
        })
        .render("#paypal-button-container");
    } else {
      if (document.querySelectorAll(".valid").length !== 12) {
        if (document.querySelector(".paypal-buttons") !== null) {
          //Remove paypal button if it's there
          ePaypalContainer.textContent = "";
          ePaypalContainer.appendChild(eButtonPlaceholder);
        }
      } else {
        if (document.querySelector(".order-summary__button") !== null) {
          ePaypalContainer.textContent = "";
          paypal
            .Buttons({
              style: {
                color: "blue",
                shape: "rect",
                size: "responsive",
              },
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
    ePaypalContainer.textContent = "";
    ePaypalContainer.appendChild(eButtonPlaceholder);
  }
}
function removeItemFromCart(sItemId, bIsProduct, nAddonAmount, bLoginStatus) {
  updateCartCounter(bIsProduct, nAddonAmount, false);

  event.target.parentElement.parentElement.parentElement.remove();

  if (document.querySelectorAll(".product-row").length == 0) {
    togglePaypalButton(bLoginStatus, 0);
  }

  postData("api/remove-item-from-cart.php", {
    itemId: sItemId,
    isProduct: bIsProduct,
  });
}
