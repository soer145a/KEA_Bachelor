document.addEventListener("DOMContentLoaded", () => {
  toggleMobileNavigation();
});

let bMessageBoxShown = false;
let fTimeOutFunction;

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
              showMessage("This email already exists", true);
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
              showMessage("CVR already exists", true);
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
      if (
        accountDetails__password.value !== accountDetails__passwordConfirm.value
      ) {
        showMessage("The passwords do not match", true);
      } else {
        showMessage(
          "One or more fields has not been filled out correctly",
          true
        );
      }

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
  document.querySelector("#deleteModal").classList.remove("shown");0
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
  } else {
    showMessage("Your passwords do not match", true);
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

async function toggleAutoRenew(sCustomerProductId) {
  postData("api/update-autorenewal.php", {
    customerProductId: sCustomerProductId,
  }).then((jResponse) => {
    if (jResponse.renewToggledOn) {
      console.log(jResponse);
      autoRenewSpan.textContent = "On";
      autoRenewToggleButton.textContent = "Turn off";
      showMessage("Auto-renewal has been turned on", false);
    } else {
      console.log(jResponse);
      autoRenewSpan.textContent = "Off";
      autoRenewToggleButton.textContent = "Turn on";
      showMessage("Auto-renewal has been turned off", false);
    }
  });
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

function editInfo(sValidateType, sInputName) {
  let eParentElement = event.target.parentElement;
  let aParentElementChildren = eParentElement.children;
  //hide existing elements
  for (let i = 0; i < aParentElementChildren.length; i++) {
    aParentElementChildren[i].classList.add(
      "customer-information__item--hidden"
    );
  }

  let eProfileInfo = document.getElementsByClassName(
    "customer-information__" + sInputName
  )[0];
  let eProfileInfoPTag = eProfileInfo.querySelector("p").textContent;
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
  eInput.setAttribute("value", `${eProfileInfoPTag}`);

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
  eInput.focus();
}

function updateCustomerInfo(sInputName) {
  let eInput = document.getElementsByName(sInputName)[0];
  if (eInput.classList.contains("invalid")) {
  } else {
    postData("api/update-customer-data.php", {
      data: eInput.value,
      whatToUpdate: sInputName,
    }).then((jResponse) => {
      if (jResponse.customerUpdated) {
        let eProfileInfo = document.getElementsByClassName(
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
        switch (sInputName) {
          case "customer_first_name":
            customerFirstNameHeader.textContent = eInput.value;
            break;
          case "customer_last_name":
            customerLastNameHeader.textContent = eInput.value;
        }
        showMessage("Your information has been updated", false);
      }
    });
  }
}

function sendContactForm() {
  if (customerFormEmail.classList.contains("invalid")) {
    showMessage("Please provide a valid email address", true);
  } else {
    let sCustomerName = contactFormName.value;
    let sCustomerEmail = customerFormEmail.value;
    let sCustomerMessage = customerFormMessage.value;
    if (sCustomerName == "" || sCustomerEmail == "" || sCustomerMessage == "") {
      showMessage("Please fill out all the field", true);
    } else {
      postData("MAILER/send-contact-message-email.php", {
        customerName: sCustomerName,
        customerEmail: sCustomerEmail,
        customerMessage: sCustomerMessage,
      }).then((jResponse) => {
        console.log(jResponse);
        if (!jResponse.mailSent) {
          showMessage("An error occurred", true);
        } else {
          showMessage(
            "Thank you, Your message has been sent to Mirtual",
            false
          );
          contactFormName.value = "";
          customerFormEmail.value = "";
          customerFormMessage.value = "";
        }
      });
    }
  }
}

function changeCustomerPassword() {
  let sNewPassword = accountDetails__password;
  let sPasswordConfirm = accountDetails__passwordConfirm;
  let sOldPassword = accountDetails__passwordOld;
  console.log(361);
  if (
    sNewPassword.value == "" ||
    sPasswordConfirm.value == "" ||
    sOldPassword.value == ""
  ) {
    console.log(367);
    showMessage("Please fill out all fields", true);
  } else {
    console.log(370);
    if (sNewPassword.classList.contains("invalid")) {
      console.log(372);
      showMessage("New password does not meet requirements", true);
    } else {
      console.log(375);
      if (sPasswordConfirm.classList.contains("invalid")) {
        console.log(377);
        showMessage("The passwords do not match", true);
      } else {
        console.log(380);
        postData("api/update-customer-data.php", {
          customerPassword: sOldPassword.value,
          newCustomerPassword: sPasswordConfirm.value,
        }).then((jResponse) => {
          console.log(jResponse);
          if (jResponse.customerUpdated) {
            showMessage("Your password has been updated", false);
            sNewPassword.value = "";
            sPasswordConfirm.value = "";
            sOldPassword.value = "";
          } else {
            showMessage("The password was incorrect", true);
          }
        });
      }
    }
  }
}

function showMessage(sMessage, bIsError) {
  if (bMessageBoxShown) {
    stopTimeOut();
  }
  bMessageBoxShown = true;
  messageBox.classList.remove(...messageBox.classList);
  messageBox.classList.add("message-box");
  messageText.textContent = sMessage;
  if (bIsError) {
    messageBox.classList.add("message-box--red");
  } else {
    messageBox.classList.add("message-box--green");
  }
  fTimeOutFunction = setTimeout(() => {
    messageBox.classList.add("message-box--visually-hidden");
    setTimeout(() => {
      messageBox.classList.remove(...messageBox.classList);
      messageBox.classList = "message-box message-box--hidden";
      messageText.textContent = "";
      bMessageBoxShown = false;
    }, 1100);
  }, 5000);
  console.log(fTimeOutFunction, "hello");
}

function stopTimeOut() {
  clearTimeout(fTimeOutFunction);
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
