document.addEventListener("DOMContentLoaded", () => {
  toggleMobileNavigation();
});
let bMessageBoxShown = false;
let fTimeOutFunction;
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

function updateCartCounter(bIsProduct, nAddonAmount, bIncrement) {
  let eCartCounter = document.querySelector(".cart-counter");

  let counter = parseInt(eCartCounter.textContent);

  if (bIsProduct) {
    if (!bIncrement) {
      //decrement counter
      eCartCounter.textContent = counter - 1;
    } else {
      //increment counter
      eCartCounter.textContent = counter + 1;
    }
  } else {
    if (!bIncrement) {
      //decrement counter
      eCartCounter.textContent = counter - nAddonAmount;
    } else {
      //increment counter
      eCartCounter.textContent = counter + nAddonAmount;
    }
  }
}
function toggleInfoBox() {
  if (document.querySelector(".js-toggle-infobox") !== null) {
    aToggleElements = document.querySelectorAll(".js-toggle-infobox");
    aToggleElements.forEach((eToggleElement) => {
      eToggleElement.addEventListener("click", () => {
        const eInfobox = document.querySelector(".login-form__label-info-box");
        eInfobox.classList.toggle("login-form__label-info-box--hidden");
      });
    });
  }
}
/* APP JS GENERAL FUNCTIONS */