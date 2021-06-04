document.addEventListener("DOMContentLoaded", () => {
  toggleMobileNavigation();
});
function inputValidate() {
  let inputData;
  let inputsToValidate = [];

  if (event.type == "submit") {
    formToValidate = event.target;
    inputsToValidate = formToValidate.querySelectorAll("[data-validate]");
  } else {
    inputsToValidate = [event.target];
  }

  for (let i = 0; i < inputsToValidate.length; i++) {
    let validationType = inputsToValidate[i].getAttribute("data-validate");

    switch (validationType) {
      case "phone":
        inputData = inputsToValidate[i].value;
        let regPhone = /^\+(?:[0-9]●?){6,16}[0-9]$/;

        if (!regPhone.test(inputData)) {
          inputsToValidate[i].classList.add("invalid");
          inputsToValidate[i].classList.remove("valid");
        } else {
          inputsToValidate[i].classList.add("valid");
          inputsToValidate[i].classList.remove("invalid");
        }
        break;

      case "email":
        console.log("checking email");
        document.getElementsByClassName("errorMessage")[0].innerHTML = "";
        inputData = inputsToValidate[i].value;
        let regEmail =
          /^[-!#$%&'*+\/0-9=?A-Z^_a-z`{|}~](\.?[-!#$%&'*+\/0-9=?A-Z^_a-z`{|}~])*@[a-zA-Z0-9](-*\.?[a-zA-Z0-9])*\.[a-zA-Z](-?[a-zA-Z0-9])+$/;

        if (!regEmail.test(inputData)) {
          inputsToValidate[i].classList.remove("valid");
          inputsToValidate[i].classList.add("invalid");
        } else {
          postData("API/check-db-for-existing-entries.php", {
            whatToCheck: "customer_email",
            data: inputData,
          }).then((response) => {
            if (!response.dataExists) {
              inputsToValidate[i].classList.add("valid");
              inputsToValidate[i].classList.remove("invalid");
            } else {
              console.log("setting email class to invalid");
              inputsToValidate[i].classList.remove("valid");
              inputsToValidate[i].classList.add("invalid");

              console.log(inputsToValidate[i].classList);
              document.getElementsByClassName("errorMessage")[0].innerHTML =
                "<strong>This email already exists</strong>";
            }
          });
        }
        break;

      case "string":
        inputData = inputsToValidate[i].value;

        if (inputData.length < 1) {
          inputsToValidate[i].classList.add("invalid");
          inputsToValidate[i].classList.remove("valid");
        } else {
          inputsToValidate[i].classList.add("valid");
          inputsToValidate[i].classList.remove("invalid");
        }
        break;

      case "password":
        inputData = inputsToValidate[i].value;
        document.getElementsByClassName("errorMessage")[0].innerHTML = "";
        //Must contain 6-30 characters, one uppercase character, one lowercase character, one numeric character and one special character. Eg.: MyStr0ng.PW-example
        let regPassword =
          /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[^a-zA-Z0-9])(?!.*\s).{6,30}$/;

        if (inputsToValidate[i].name !== "customerPasswordConfirm") {
          if (!regPassword.test(inputData)) {
            inputsToValidate[i].classList.add("invalid");
            inputsToValidate[i].classList.remove("valid");
            document.getElementsByClassName("errorMessage")[0].innerHTML =
              "<strong>The password has to be strong</strong>";
          } else {
            inputsToValidate[i].classList.add("valid");
            inputsToValidate[i].classList.remove("invalid");
          }
        } else {
          if (
            inputsToValidate[i].value ===
            document.getElementsByName("customerPassword_init")[0].value
          ) {
            inputsToValidate[i].classList.add("valid");
            inputsToValidate[i].classList.remove("invalid");
          } else {
            inputsToValidate[i].classList.add("invalid");
            inputsToValidate[i].classList.remove("valid");
            document.getElementsByClassName("errorMessage")[0].innerHTML =
              "<strong>The passwords don't match</strong>";
          }
        }
        break;

      case "cvr":
        document.getElementsByClassName("errorMessage")[0].innerHTML = "";
        inputData = inputsToValidate[i].value;
        let regCvr = /^(\d){8}$/;

        if (!regCvr.test(inputData)) {
          inputsToValidate[i].classList.add("invalid");
          inputsToValidate[i].classList.remove("valid");
        } else {
          postData("API/check-db-for-existing-entries.php", {
            whatToCheck: "customer_cvr",
            data: inputData,
          }).then((response) => {
            console.log(response);
            if (!response.dataExists) {
              inputsToValidate[i].classList.add("valid");
              inputsToValidate[i].classList.remove("invalid");
            } else {
              inputsToValidate[i].classList.add("invalid");
              inputsToValidate[i].classList.remove("valid");
              document.getElementsByClassName("errorMessage")[0].innerHTML =
                "<strong>This cvr already exists</strong>";
            }
          });
        }
        break;
    }
  }
  if (event.type == "submit") {
    console.log(formToValidate.querySelectorAll(".invalid").length);
    if (formToValidate.querySelectorAll(".invalid").length > 0) {
      document.getElementsByClassName("errorMessage")[0].innerHTML =
        "<strong>A field is not valid</strong>";
      return false;
    } else {
      return true;
    }
  }
}

function showDeleteOption() {
  console.log("Ready to delete!");
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
  let pass1 = document.querySelector("#pass1").value;
  let pass2 = document.querySelector("#pass2").value;
  console.log(pass1, pass2);
  if (pass1 == pass2) {
    document.querySelector("#deleteButton").removeAttribute("disabled");
  }
}
function removeDeleteModals() {
  document.querySelector("#deleteModalTotal").classList.add("hidden");
  document.querySelector("#deleteModalTotal").classList.remove("shown");
}

async function postData(url = "", data = {}) {
  const response = await fetch(url, {
    method: "POST",
    mode: "cors",
    cache: "no-cache",
    credentials: "same-origin",
    headers: {
      "Content-Type": "application/json",
    },
    referrerPolicy: "no-referrer",
    body: JSON.stringify(data),
  });

  return response.json();
}

function showUpdateForm() {
  let form = document.querySelector("#updateDataForm");
  form.classList.remove("hidden");
  form.classList.add("shown");
}

async function toggleAutoRenew(subID) {
  console.log(subID);
  fetch(`API/update-autorenewal.php?subID=${subID}`)
    .then((response) => response.text())
    .then((data) => location.reload());
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

function editInfo(value, type, postName) {
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
  eForm.setAttribute("method", "post");
  eForm.setAttribute("onsubmit", "return inputValidate();");
  eForm.setAttribute("action", "API/update-customer-data.php");

  //input element
  let eInput = document.createElement("input");
  eInput.setAttribute("class", "form__input");
  eInput.setAttribute("oninput", "inputValidate()");
  eInput.setAttribute("data-validate", `${type}`);
  eInput.setAttribute("type", "text");
  eInput.setAttribute("name", `${postName}`);
  eInput.setAttribute("value", `${value}`);

  //Submit button
  let eSubmitButton = document.createElement("button");
  eSubmitButton.setAttribute("class", "form__button form__button--submit");
  eSubmitButton.setAttribute("type", "submit");

  //Cancel button
  let eCancelButton = document.createElement("button");
  eCancelButton.setAttribute("class", "form__button form__button--cancel");
  eCancelButton.setAttribute("type", "button");
  eCancelButton.setAttribute(
    "onclick",
    `cancelEdit("${value}", "${type}", "${postName}")`
  );

  //Append button and input inside of form
  eForm.appendChild(eInput);
  eForm.appendChild(eSubmitButton);
  eForm.appendChild(eCancelButton);

  //Append new element inside of parent element
  eParentElement.appendChild(eForm);
}

function cancelEdit(value, type, postName) {
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

function togglePaypalButton(loggedIn, price) {
  console.log(loggedIn, price);
  if (loggedIn) {
    document.querySelector(".order-summary__button").remove();
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
                  value: price,
                },
              },
            ],
          });
        },
        onApprove: function (data, actions) {
          return actions.order.capture().then(function (PurchaseDetails) {
            window.location.assign(
              window.location.protocol + "/KEA_Bachelor/API/payment-handler.php"
            );
          });
        },
      })
      .render("#paypal-button-container");
  } else {
    const ePaypalContainer = document.querySelector("#paypal-button-container");

    if (document.querySelectorAll(".valid").length !== 12) {
      if (document.querySelector(".paypal-buttons") !== null) {
        //Remove paypal button if it's there
        document.querySelector(".paypal-buttons").remove();
        let eButtonPlaceholder = document.createElement("button");
        eButtonPlaceholder.setAttribute(
          "class",
          "order-summary__button button button--purple"
        );
        eButtonPlaceholder.textContent = "Paypal";

        ePaypalContainer.appendChild(eButtonPlaceholder);
      }
    } else {
      console.log("It does work");
      if (document.querySelector(".order-summary__button") !== null) {
        document.querySelector(".order-summary__button").remove();

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
                      value: price,
                    },
                  },
                ],
              });
            },
            onApprove: function (data, actions) {
              return actions.order.capture().then(function (PurchaseDetails) {
                document.querySelector(".account-details").submit();
              });
            },
          })
          .render("#paypal-button-container");
      }
    }
  }
}
