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
    inputsToValidate[i].classList.remove("invalid");
    inputsToValidate[i].classList.remove("valid");

    let validationType = inputsToValidate[i].getAttribute("data-validate");

    switch (validationType) {
      case "password":
        inputData = inputsToValidate[i].value;

        //Must contain 6-30 characters, one uppercase character, one lowercase character, one numeric character and one special character. Eg.: MyStr0ng.PW-example
        let regPassword = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[^a-zA-Z0-9])(?!.*\s).{6,30}$/;

        if (inputsToValidate[i].name !== "input_password_confirm") {
          if (!regPassword.test(inputData)) {
            inputsToValidate[i].classList.add("invalid");
          } else {
            inputsToValidate[i].classList.add("valid");
          }
        } else {
          if (
            inputsToValidate[i].value ===
            document.getElementsByName("input_password_init")[0].value
          ) {
            inputsToValidate[i].classList.add("valid");
          } else {
            inputsToValidate[i].classList.add("invalid");
          }
        }
        break;

      case "cvr":
        inputData = inputsToValidate[i].value;
        let regCvr = /^(\d){8}$/;

        if (!regCvr.test(inputData)) {
          inputsToValidate[i].classList.add("invalid");
        } else {
          let cvrInputValue = { cvr: inputsToValidate[i].value };
          const response = postData(
            "API/check-db-for-existing-entries.php",
            cvrInputValue
          );

          inputsToValidate[i].classList.add("valid");
        }
        break;

      case "string":
        inputData = inputsToValidate[i].value;

        if (inputData.length < 1) {
          inputsToValidate[i].classList.add("invalid");
        } else {
          inputsToValidate[i].classList.add("valid");
        }
        break;

      case "email":
        inputData = inputsToValidate[i].value;
        let regEmail = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;

        if (!regEmail.test(inputData)) {
          inputsToValidate[i].classList.add("invalid");
        } else {
          inputsToValidate[i].classList.add("valid");
        }
        break;
    }
  }
  if (event.type == "submit") {
    return formToValidate.querySelectorAll(".invalid").length ? false : true;
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
    name: "body",
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
