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
      case "password":
        inputData = inputsToValidate[i].value;
        document.getElementsByClassName("errorMessage")[0].innerHTML = "";
        //Must contain 6-30 characters, one uppercase character, one lowercase character, one numeric character and one special character. Eg.: MyStr0ng.PW-example
        let regPassword =
          /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[^a-zA-Z0-9])(?!.*\s).{6,30}$/;

        if (inputsToValidate[i].name !== "input_password_confirm") {
          if (!regPassword.test(inputData)) {
            inputsToValidate[i].classList.add("invalid");
            inputsToValidate[i].classList.remove("valid");
          } else {
            inputsToValidate[i].classList.add("valid");
            inputsToValidate[i].classList.remove("invalid");
          }
        } else {
          if (
            inputsToValidate[i].value ===
            document.getElementsByName("input_password_init")[0].value
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
            customer_cvr: inputData,
          }).then((response) => {
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
          /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;

        if (!regEmail.test(inputData)) {
          inputsToValidate[i].classList.remove("valid");
          inputsToValidate[i].classList.add("invalid");
        } else {
          postData("API/check-db-for-existing-entries.php", {
            customer_email: inputData,
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

function gatherProductData() {
  let addOnFields = document.querySelectorAll(".addOn");
  let returnObject = [];
  addOnFields.forEach((addon) => {
    let dataField = {
      name: addon.name,
      isChecked: addon.checked,
    };
    returnObject.push(dataField);
  });
  return returnObject;
}

function informationHandler(returnData) {
  //console.log(returnData);
  window.location.href = document.location + "signup.php";
}
async function addProductToCustomer() {
  console.log("Cross-call-function");
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
          .querySelector(".navigation-list-wrapper")
          .classList.toggle("navigation-list-wrapper--hidden");
      });
  }
}

function editInfo(text, value, type, postName) {
  let container = event.target.parentElement;
  container.innerHTML =
    '<form method="post" onsubmit="return inputValidate();" action="API/update-customer-data.php"><input class="form__input" oninput="inputValidate();" data-validate="' +
    type +
    '"type="text" name="' +
    postName +
    '" value="' +
    value +
    '"><button type="submit">Update</button><div class="errorMessage"></div></form><button onclick="cancelEdit(' +
    "'" +
    text +
    "'" +
    ", " +
    "'" +
    value +
    "'" +
    ", " +
    "'" +
    type +
    "'" +
    ", " +
    "'" +
    postName +
    "'" +
    ')">Cancel</button>';
}

function cancelEdit(text, value, type, postName) {
  let container = event.target.parentElement;
  container.innerHTML =
    "<p>" +
    text +
    value +
    '</p><button onclick="editInfo(' +
    "'" +
    text +
    "'" +
    ", " +
    "'" +
    value +
    "'" +
    ", " +
    "'" +
    type +
    "'" +
    ", " +
    "'" +
    postName +
    "'" +
    ')">Edit</button>';
}
