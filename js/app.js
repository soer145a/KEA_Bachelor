//Execute functions on page load
document.addEventListener("DOMContentLoaded", () => {
  toggleMobileNavigation();
  toggleInfoBox();
});

//Variables needed to be global for the timer function used for customer communication
let bMessageBoxShown = false;
let fTimeOutFunction;

//Function used for communicating with the customers on the page
function showMessage(sMessage, bIsError) {
  //checks if a message is currently being shown
  if (bMessageBoxShown) {
    //If message is shown, stop previously set timer to start process from fresh
    stopTimeOut();
  }
  bMessageBoxShown = true;

  //reset messageBox classes
  messageBox.classList.remove(...messageBox.classList);
  messageBox.classList.add("message-box");
  messageText.textContent = sMessage;

  //if bIsError is set to true the message banner turns red to indicate an error or green to indicate a successful action
  if (bIsError) {
    messageBox.classList.add("message-box--red");
  } else {
    messageBox.classList.add("message-box--green");
  }
  //start timer to show message for 5 sec
  fTimeOutFunction = setTimeout(() => {
    messageBox.classList.add("message-box--visually-hidden");
    setTimeout(() => {
      messageBox.classList.remove(...messageBox.classList);
      messageBox.classList = "message-box message-box--hidden";
      messageText.textContent = "";
      bMessageBoxShown = false;
    }, 1100);
  }, 5000);
}
//Function used to stop a prevously set timer
function stopTimeOut() {
  clearTimeout(fTimeOutFunction);
}

//function used to validate inputs
function inputValidate() {
  let sInputData;
  let aInputsToValidate = [];

  // if trigger is of the type submit, create an array of all input fields
  if (event.type == "submit") {
    eFormToValidate = event.target;
    aInputsToValidate = eFormToValidate.querySelectorAll("[data-validate]");
    //else put just the trigger input field into the array
  } else {
    aInputsToValidate = [event.target];
  }

  //Repeat below code for each input in the array
  for (let i = 0; i < aInputsToValidate.length; i++) {
    let sValidationType = aInputsToValidate[i].getAttribute("data-validate");

    //check the input data-validate attribute for what string it contains and execute code corresponding to the string
    switch (sValidationType) {
      case "phone":
        sInputData = aInputsToValidate[i].value;
        let sPhoneRegEx = /^\+(?:[0-9]●?){6,16}[0-9]$/;

        //check if phonenumber is formatted correctly and set the correct class to let the customer visually know if it is correct or not
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

        //check if email is formatted correctly and set the correct class to let the customer visually know if it is correct or not
        if (!sEmailRegEx.test(sInputData)) {
          aInputsToValidate[i].classList.remove("valid");
          aInputsToValidate[i].classList.add("invalid");
        } else {
          //Contact api to check if email address already exists in database
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
        if (!sPasswordRegEx.test(sInputData)) {
          aInputsToValidate[i].classList.add("invalid");
          aInputsToValidate[i].classList.remove("valid");
        } else {
          aInputsToValidate[i].classList.add("valid");
          aInputsToValidate[i].classList.remove("invalid");
          if (
            aInputsToValidate[i].value !== accountDetails__confirmPassword.value
          ) {
            accountDetails__confirmPassword.classList.add("invalid");
            accountDetails__confirmPassword.classList.remove("valid");
          } else {
            accountDetails__confirmPassword.classList.add("valid");
            accountDetails__confirmPassword.classList.remove("invalid");
          }
        }
        break;
      case "confirmPassword":
        if (aInputsToValidate[i].value !== accountDetails__password.value) {
          aInputsToValidate[i].classList.add("invalid");
          aInputsToValidate[i].classList.remove("valid");
        } else {
          aInputsToValidate[i].classList.add("valid");
          aInputsToValidate[i].classList.remove("invalid");
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
        accountDetails__password.value !== accountDetails__confirmPassword.value
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
          .querySelector(".navigation-list-wrapper")
          .classList.toggle("navigation-list-wrapper--hidden");
        document.body.classList.toggle("no-scroll");
        /* document
          .querySelector(".js-toggleNavigation")
          .addEventListener("click", () => {
            //Toggle hide class on navigation
            document
            
          }); */
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
      eToggleElement.addEventListener("click", (eToggleElement) => {
        console.log(eToggleElement.target.children);
        /* const eInfobox = eToggleElement.target.querySelector(
          ".login-form__label-info-box"
        );
        eInfobox.classList.toggle("login-form__label-info-box--hidden"); */
      });
    });
  }
}
/* APP JS GENERAL FUNCTIONS */
