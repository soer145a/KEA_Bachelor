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

    let validationType = inputsToValidate[i].getAttribute("data-validate");

    switch (validationType) {
      case "password":
        inputData = inputsToValidate[i].value;
        let regPassword = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[^a-zA-Z0-9])(?!.*\s).{6,30}$/;
        //Must contain 6-30 characters, one uppercase character, one lowercase character, one numeric character and one special character. Eg.: MyStr0ng.PW-example
        if (!regPassword.test(inputData)) {
          inputsToValidate[i].classList.add("invalid");
        }
        break;

      case "cvr":
        inputData = inputsToValidate[i].value;
        let regCvr = /^(\d){8}$/;

        if (!regCvr.test(inputData)) {
          inputsToValidate[i].classList.add("invalid");
        }
        break;

      case "string":
        inputData = inputsToValidate[i].value;

        if (inputData.length < 1) {
          inputsToValidate[i].classList.add("invalid");
        }
        break;

      case "email":
        inputData = inputsToValidate[i].value;
        let regEmail = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;

        if (!regEmail.test(inputData)) {
          inputsToValidate[i].classList.add("invalid");
        }
        break;
    }
  }
  if (event.type == "submit") {
    return formToValidate.querySelectorAll(".invalid").length ? false : true;
  }
}
console.log("x");
async function addToBasket(productNmbr) {
  console.log(productNmbr);
  let connection = await fetch(
    `API/add-product-to-basket.php?productNmbr=${productNmbr}`
  );
  let data = await connection.json();
  console.log(data);
}
