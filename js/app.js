document.addEventListener("DOMContentLoaded", () => {
  toggleMobileNavigation();
});

function inputValidate() {
  let sInputData;
  let aInputsToValidate = [];

  if (event.type == "submit") {
    eFormToValidate = event.target;
    aInputsToValidate = eFormToValidate.querySelectorAll("[data-validate]");
  } else {
    aInputsToValidate = [event.target];
  }

  for (let i = 0; i < aInputsToValidate.length; i++) {
    let sValidationType = aInputsToValidate[i].getAttribute("data-validate");

    switch (sValidationType) {
      case "phone":
        sInputData = aInputsToValidate[i].value;
        let sPhoneRegEx = /^\+(?:[0-9]●?){6,16}[0-9]$/;

        if (!sPhoneRegEx.test(sInputData)) {
          aInputsToValidate[i].classList.add("invalid");
          aInputsToValidate[i].classList.remove("valid");
        } else {
          aInputsToValidate[i].classList.add("valid");
          aInputsToValidate[i].classList.remove("invalid");
        }
        break;

      case "email":
        sInputData = aInputsToValidate[i].value;
        let sEmailRegEx =
          /^[-!#$%&'*+\/0-9=?A-Z^_a-z`{|}~](\.?[-!#$%&'*+\/0-9=?A-Z^_a-z`{|}~])*@[a-zA-Z0-9](-*\.?[a-zA-Z0-9])*\.[a-zA-Z](-?[a-zA-Z0-9])+$/;

        if (!sEmailRegEx.test(sInputData)) {
          aInputsToValidate[i].classList.remove("valid");
          aInputsToValidate[i].classList.add("invalid");
        } else {
          postData("api/check-db-for-existing-entries.php", {
            whatToCheck: "customer_email",
            data: sInputData,
          }).then((jResponse) => {
            if (!jResponse.dataExists) {
              aInputsToValidate[i].classList.add("valid");
              aInputsToValidate[i].classList.remove("invalid");
            } else {
              aInputsToValidate[i].classList.remove("valid");
              aInputsToValidate[i].classList.add("invalid");
            }
          });
        }
        break;

      case "string":
        
        sInputData = aInputsToValidate[i].value;

        if (sInputData.length < 1) {
          aInputsToValidate[i].classList.add("invalid");
          aInputsToValidate[i].classList.remove("valid");
        } else {
          aInputsToValidate[i].classList.add("valid");
          aInputsToValidate[i].classList.remove("invalid");
        }
        break;

      case "password":
        sInputData = aInputsToValidate[i].value;

        //Must contain 6-30 characters, one uppercase character, one lowercase character, one numeric character and one special character. Eg.: MyStr0ng.PW-example
        let sPasswordRegEx =
          /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[^a-zA-Z0-9])(?!.*\s).{6,30}$/;

        if (aInputsToValidate[i].name !== "customerPasswordConfirm") {
          if (!sPasswordRegEx.test(sInputData)) {
            aInputsToValidate[i].classList.add("invalid");
            aInputsToValidate[i].classList.remove("valid");
          } else {
            aInputsToValidate[i].classList.add("valid");
            aInputsToValidate[i].classList.remove("invalid");
          }
        } else {
          if (
            aInputsToValidate[i].value ===
            document.getElementsByName("customerPassword")[0].value
          ) {
            aInputsToValidate[i].classList.add("valid");
            aInputsToValidate[i].classList.remove("invalid");
          } else {
            aInputsToValidate[i].classList.add("invalid");
            aInputsToValidate[i].classList.remove("valid");
          }
        }
        break;

      case "cvr":
        sInputData = aInputsToValidate[i].value;
        let sCvrRegEx = /^(\d){8}$/;

        if (!sCvrRegEx.test(sInputData)) {
          aInputsToValidate[i].classList.add("invalid");
          aInputsToValidate[i].classList.remove("valid");
        } else {
          postData("api/check-db-for-existing-entries.php", {
            whatToCheck: "customer_company_cvr",
            data: sInputData,
          }).then((jResponse) => {
            
            if (!jResponse.dataExists) {
              aInputsToValidate[i].classList.add("valid");
              aInputsToValidate[i].classList.remove("invalid");
            } else {
              aInputsToValidate[i].classList.add("invalid");
              aInputsToValidate[i].classList.remove("valid");
            }
          });
        }
        break;
    }
  }
  if (event.type == "submit") {
    if (eFormToValidate.querySelectorAll(".invalid").length > 0) {
      return false;
    } else {
      return true;
    }
  }
}

function showDeleteOption() {
  document.querySelector("#deleteModal").classList.remove("hidden");
  document.querySelector("#deleteModal").classList.add("shown");
}
function cancelDeletion() {
  document.querySelector("#deleteModal").classList.add("hidden");
  document.querySelector("#deleteModal").classList.remove("shown");
}
function showDeleteOption2() {
  document.querySelector("#deleteModal").classList.add("hidden");
  document.querySelector("#deleteModal").classList.remove("shown");
  document.querySelector("#deleteModalTotal").classList.remove("hidden");
  document.querySelector("#deleteModalTotal").classList.add("shown");
}
function checkPassword() {
  let CustomerPassword = document.querySelector("#CustomerPassword").value;
  let CustomerPasswordConfirm = document.querySelector(
    "#CustomerPasswordConfirm"
  ).value;

  if (CustomerPassword == CustomerPasswordConfirm) {
    document.querySelector("#deleteButton").removeAttribute("disabled");
  }
}
function removeDeleteModals() {
  document.querySelector("#deleteModalTotal").classList.add("hidden");
  document.querySelector("#deleteModalTotal").classList.remove("shown");
}

async function postData(sUrl = "", jData = {}) {
  const response = await fetch(sUrl, {
    method: "POST",
    mode: "cors",
    cache: "no-cache",
    credentials: "same-origin",
    headers: {
      "Content-Type": "application/json",
    },
    referrerPolicy: "no-referrer",
    body: JSON.stringify(jData),
  });

  return response.json();
}

function showUpdateForm() {
  let eCustomerUpdateform = document.querySelector("#updateDataForm");
  eCustomerUpdateform.classList.remove("hidden");
  eCustomerUpdateform.classList.add("shown");
}

async function toggleAutoRenew(sCustomerProductId) {
  fetch(
    `api/update-autorenewal.php?customer-product-id=${sCustomerProductId}`
  ).then((data) => location.reload());
}

// Top Navigation -- Hamburger

function toggleMobileNavigation() {
  if (document.querySelector(".js-toggleNavigation") !== null) {
    document
      .querySelector(".js-toggleNavigation")
      .addEventListener("click", () => {
        document
          .querySelector(".js-toggleNavigation")
          .addEventListener("click", () => {
            //Toggle hide class on navigation
            document
              .querySelector(".navigation-list-wrapper")
              .classList.toggle("navigation-list-wrapper--hidden");
            document.body.classList.toggle("no-scroll");
          });
      });
  }
}

function editInfo(sInputValue, sValidateType, sInputName) {
  let eParentElement = event.target.parentElement;
  let aParentElementChildren = eParentElement.children;
  //hide existing elements
  for (let i = 0; i < aParentElementChildren.length; i++) {
    aParentElementChildren[i].classList.add(
      "customer-information__item--hidden"
    );
  }
  //Create new dom element

  //form element
  let eForm = document.createElement("form");
  eForm.setAttribute("class", "customer-information-form");
  eForm.setAttribute("onsubmit", `event.preventDefault();`);

  //input element
  let eInput = document.createElement("input");
  eInput.setAttribute("class", "form__input");
  eInput.setAttribute("oninput", "inputValidate()");
  eInput.setAttribute("data-validate", `${sValidateType}`);
  eInput.setAttribute("type", "text");
  eInput.setAttribute("name", `${sInputName}`);
  eInput.setAttribute("value", `${sInputValue}`);

  //Submit button
  let eSubmitButton = document.createElement("button");
  eSubmitButton.setAttribute("class", "form__button form__button--submit");
  eSubmitButton.setAttribute("type", "submit");
  eSubmitButton.setAttribute("onclick", `updateCustomerInfo("${sInputName}")`);
  //Cancel button
  let eCancelButton = document.createElement("button");
  eCancelButton.setAttribute("class", "form__button form__button--cancel");
  eCancelButton.setAttribute("type", "button");
  eCancelButton.setAttribute("onclick", `cancelEdit()`);

  //Append button and input inside of form
  eForm.appendChild(eInput);
  eForm.appendChild(eSubmitButton);
  eForm.appendChild(eCancelButton);

  //Append new element inside of parent element
  eParentElement.appendChild(eForm);
}

function updateCustomerInfo(sInputName) {
  let eInput = document.getElementsByName(sInputName)[0];
  if (eInput.classList.contains("invalid")) {
    //make error
  } else {
    postData("api/update-customer-data.php", {
      data: eInput.value,
      whatToUpdate: sInputName,
    }).then((jResponse) => {
      if (jResponse.customerUpdated) {
        eProfileInfo = document.getElementsByClassName(
          "customer-information__" + sInputName
        )[0];

        const eForm = eProfileInfo.querySelector("form");
        //remove form from DOM
        eForm.remove();
        //Find all elements with hidden class inside of root element
        let aHiddenElements = eProfileInfo.querySelectorAll(
          ".customer-information__item--hidden"
        );
        //remove hidden class from elements
        for (let i = 0; i < aHiddenElements.length; i++) {
          aHiddenElements[i].classList.remove(
            "customer-information__item--hidden"
          );
        }
        let eProfileInfoPTag = eProfileInfo.querySelector("p");
        eProfileInfoPTag.textContent = eInput.value;
      }
    });
  }
}

function cancelEdit() {
  let eRootElement = event.target.parentElement.parentElement;

  //Find form element to remove/delete
  const eForm = eRootElement.querySelector("form");

  //remove form from DOM
  eForm.remove();
  //Find all elements with hidden class inside of root element
  let aHiddenElements = eRootElement.querySelectorAll(
    ".customer-information__item--hidden"
  );
  //remove hidden class from elements
  for (let i = 0; i < aHiddenElements.length; i++) {
    aHiddenElements[i].classList.remove("customer-information__item--hidden");
  }
}

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
              window.location.assign(
                window.location.protocol +
                  "/KEA_Bachelor/api/payment-handler.php"
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
                    confirmString: true
                  }).then(document.querySelector(".account-details").submit());                });
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
