function showDeleteOption() {
  //Functionality for showing the delete modal
  document.querySelector("#deleteModal").classList.remove("modal--hidden");
  document.querySelector("#deleteModal").classList.add("modal--shown");
}

function cancelDeletion() {
  //Remove the delete modal
  document.querySelector("#deleteModal").classList.add("modal--hidden");
  document.querySelector("#deleteModal").classList.remove("modal--shown");
}

function showDeleteOption2() {
  //show the next step when deleting your user
  document.querySelector("#deleteModal").classList.add("modal--hidden");
  document.querySelector("#deleteModal").classList.remove("modal--shown");
  document.querySelector("#deleteModalTotal").classList.remove("modal--hidden");
  document.querySelector("#deleteModalTotal").classList.add("modal--shown");
}

function removeDeleteModals() {
  //Cancel the deletion process
  document.querySelector("#deleteModalTotal").classList.add("modal--hidden");
  document.querySelector("#deleteModalTotal").classList.remove("modal--shown");
}
async function toggleAutoRenew(sCustomerProductId) {
  //This function sends the request to the api when to toggle the auto renew on their product
  //Selecting the correct object
  let autoRenewSpan = document.querySelector(
    `#autoRenewSpan${sCustomerProductId}`
  );
  let autoRenewToggleButton = document.querySelector(
    `#autoRenewToggleButton${sCustomerProductId}`
  );
  //Send request
  postData("api/update-autorenewal.php", {
    customerProductId: sCustomerProductId,
  }).then((jResponse) => {
    //The response from the api
    if (jResponse.renewToggledOn) {
      //Check to see what it was toggled to
      // if on
      autoRenewSpan.textContent = "On";
      autoRenewToggleButton.textContent = "Turn off";
      showMessage("Auto-renewal has been turned on", false);
    } else {
      //If off
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

  //Create new dom elements
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

  //Buttons container
  let eButtonContainer = document.createElement("div");
  eButtonContainer.setAttribute("class", "form__button-container");

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

  //Append buttons inside of button container
  eButtonContainer.appendChild(eSubmitButton);
  eButtonContainer.appendChild(eCancelButton);
  //Append button container and input inside of form
  eForm.appendChild(eInput);
  eForm.appendChild(eButtonContainer);

  //Append new element inside of parent element
  eParentElement.appendChild(eForm);
  eInput.focus();
}

//Updates the changes customer information in the database and the frontend
function updateCustomerInfo(sInputName) {
  let eInput = document.getElementsByName(sInputName)[0];
  //check if the customer has provided a valid input
  if (eInput.classList.contains("invalid")) {
    showMessage("Info invalid", true);
  } else {
    //Send the request to the api
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
        //change the header on the profile page based on if one of the names were the changed data record
        switch (sInputName) {
          case "customer_first_name":
            customerFirstNameHeader.textContent = eInput.value;
            break;
          case "customer_last_name":
            customerLastNameHeader.textContent = eInput.value;
        }
        //display message
        showMessage("Your information has been updated", false);
      }
    });
  }
}

//let the user update their password
function changeCustomerPassword() {
  //Gather the data from the input fields
  let sNewPassword = accountDetails__password;
  let sPasswordConfirm = accountDetails__confirmPassword;
  let sOldPassword = accountDetails__passwordOld;
  //If empty, stop the process
  if (
    sNewPassword.value == "" ||
    sPasswordConfirm.value == "" ||
    sOldPassword.value == ""
  ) {
    //Error on the empty fields
    showMessage("Please fill out all fields", true);
  } else {
    if (sNewPassword.classList.contains("invalid")) {
      //The password is invalid case
      showMessage("New password does not meet requirements", true);
    } else {
      if (sPasswordConfirm.classList.contains("invalid")) {
        //If the second input is invalid
        showMessage("The passwords do not match", true);
      } else {
        //Everything is okay and send request
        postData("api/update-customer-data.php", {
          customerPassword: sOldPassword.value,
          newCustomerPassword: sPasswordConfirm.value,
        }).then((jResponse) => {
          //Based on response
          if (jResponse.customerUpdated) {
            //success
            showMessage("Your password has been updated", false);
            sNewPassword.value = "";
            sPasswordConfirm.value = "";
            sOldPassword.value = "";
          } else {
            //Error
            showMessage("The password was incorrect", true);
          }
        });
      }
    }
  }
}

//Close down the inputform and replace with the html that just displays the user information
function cancelEdit() {
  let eRootElement = event.target.parentElement.parentElement.parentElement;

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
function toggleDropdownProfile(sSelector, sCustomerProductId) {
  switch (sSelector) {
    case "addon":
      collapsableAddonContainer.classList.toggle("collapsed");
      addonRotateArrow.classList.toggle("rotated");
      break;
    case "product":
      let eCollapsableBlock = document.querySelector(
        `#collapsable${sCustomerProductId}`
      );
      eCollapsableBlock.classList.toggle("collapsed");
      let eRotatingArrowBlock = document.querySelector(
        `#product-card-arrow${sCustomerProductId}`
      );
      eRotatingArrowBlock.classList.toggle("rotated");
      break;
    case "receipt":
      receiptsCollapsable.classList.toggle("collapsed");
      receiptsRotateArrow.classList.toggle("rotated");
      break;
  }
}
function getReceiptFileNames() {
  receiptList.textContent = "";
  console.log(eInputFirstDate.value, eInputSecondDate.value);
  if (eInputFirstDate.value == "" || eInputSecondDate.value == "") {
    showMessage("Please fill out both dates", true);
  } else {
    postData("api/get-receipt-list.php", {
      firstDate: eInputFirstDate.value,
      secondDate: eInputSecondDate.value,
    }).then((jResponse) => {
      console.log(jResponse);
      if (jResponse.ordersReceived) {
        if (jResponse.results == 0) {
          showMessage("No receipts found for chosen dates", true);
        } else {
          jResponse.receiptList.forEach((eListItem) => {
            let eListItemParsed = stringToHTML(eListItem.html);
            
            document.querySelector("#receiptList").appendChild(eListItemParsed);

            let sOrderDate = new Date(parseInt(eListItem.orderDate * 1000));
            
            let iNewMinute = sOrderDate.getMinutes();
            let sNewMinute = iNewMinute.toString();
            console.log(sNewMinute.length);
            if (sNewMinute.length == 1) {
               sNewMinute = "0"+sNewMinute;
            }
            sOrderDate =
              sOrderDate.getDate() + "/" +
              sOrderDate.getMonth() +"/" +
              sOrderDate.getFullYear() +
              " - " + sOrderDate.getHours() + ":" + sNewMinute;
              

             

            document.querySelector(
              `#receiptData${eListItem.orderDate}`
            ).textContent = sOrderDate;
          });
          showMessage(`${jResponse.results} receipt(s) found`, false);
        }
      } else {
        showMessage(jResponse.error, true);
      }
    });
  }
}
function stringToHTML(sHtmlString) {
  let oParser = new DOMParser();
  let eDocument = oParser.parseFromString(sHtmlString, "text/html");
  return eDocument.body;
}
