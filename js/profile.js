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

function removeDeleteModals() {
  document.querySelector("#deleteModalTotal").classList.add("hidden");
  document.querySelector("#deleteModalTotal").classList.remove("shown");
}

//Gets called when the user turns their subscription autorenewal on or off
function toggleAutoRenew(sCustomerProductId) {
  let autoRenewSpan = document.querySelector(
    `#autoRenewSpan${sCustomerProductId}`
  );
  let autoRenewToggleButton = document.querySelector(
    `#autoRenewToggleButton${sCustomerProductId}`
  );
  //Contact the database and make sure it updates the relevant columns
  postData("api/update-autorenewal.php", {
    customerProductId: sCustomerProductId,
  }).then((jResponse) => {
    //Check the response for what happend and update the front end to display the same and communicate this to the user
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

//Changes the text displaying customer information to and input field with the same information as its value
// and adds a cancel and save button
function editInfo(sValidateType, sInputName) {
  //54-62 hides existing html
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

//Updates the changes customer information in the database and the frontend
function updateCustomerInfo(sInputName) {
  let eInput = document.getElementsByName(sInputName)[0];
  //check if the customer has provided a valid input
  if (eInput.classList.contains("invalid")) {
  } else {
    //contact database to update changes
    postData("api/update-customer-data.php", {
      data: eInput.value,
      whatToUpdate: sInputName,
    }).then((jResponse) => {
      if (jResponse.customerUpdated) {
        //if succes then update frontend and let user know
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

//let the user update their password
function changeCustomerPassword() {
  let sNewPassword = accountDetails__password;
  let sPasswordConfirm = accountDetails__confirmPassword;
  let sOldPassword = accountDetails__passwordOld;

  //check if password fields have been filled out and let user know if they are missing fields
  if (
    sNewPassword.value == "" ||
    sPasswordConfirm.value == "" ||
    sOldPassword.value == ""
  ) {
    showMessage("Please fill out all fields", true);
  } else {
    //Check if new password meets requirements and let user know if it does not
    if (sNewPassword.classList.contains("invalid")) {
      showMessage("New password does not meet requirements", true);
    } else {
      //check if the two passwords match and let the user know if they don't
      if (sPasswordConfirm.classList.contains("invalid")) {
        showMessage("The passwords do not match", true);
      } else {
        //update the password in the database and communicate result to user
        postData("api/update-customer-data.php", {
          customerPassword: sOldPassword.value,
          newCustomerPassword: sPasswordConfirm.value,
        }).then((jResponse) => {
          if (jResponse.customerUpdated) {
            //Communicate to user and empty passwords fields
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

//Close down the inputform and replace with the html that just displays the user information
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
