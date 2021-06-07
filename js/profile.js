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
async function toggleAutoRenew(sCustomerProductId) {
  let autoRenewSpan = document.querySelector(
    `#autoRenewSpan${sCustomerProductId}`
  );
  let autoRenewToggleButton = document.querySelector(
    `#autoRenewToggleButton${sCustomerProductId}`
  );
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
function changeCustomerPassword() {
  let sNewPassword = accountDetails__password;
  let sPasswordConfirm = accountDetails__confirmPassword;
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
