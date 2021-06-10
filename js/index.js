document.addEventListener("DOMContentLoaded", () => {
  toggleDropdown();
  itemSelector();
  toggleInfoBox();
  handleCarouselScroll();
});

//Used to send the contact form from the index page
function sendContactForm() {
  let sCustomerName = contactFormName.value;
  let sCustomerEmail = customerFormEmail.value;
  let sCustomerMessage = customerFormMessage.value;

  //check if input fields has been filled out
  if (sCustomerName == "" || sCustomerEmail == "" || sCustomerMessage == "") {
    showMessage("Please fill out all the field", true);
  } else {
    let sEmailRegEx =
      /^[-!#$%&'*+\/0-9=?A-Z^_a-z`{|}~](\.?[-!#$%&'*+\/0-9=?A-Z^_a-z`{|}~])*@[a-zA-Z0-9](-*\.?[a-zA-Z0-9])*\.[a-zA-Z](-?[a-zA-Z0-9])+$/;

    //check that the email is a valid email
    if (!sEmailRegEx.test(sCustomerEmail)) {
      customerFormEmail.classList.add("invalid");
      setTimeout(() => {
        customerFormEmail.classList.remove("invalid");
      }, 5000);
      //lets user know their email was invalid
      showMessage("Please provide a valid email address", true);
    } else {
      document.body.style.cursor = "wait";
      document.querySelector(".contact-form__button").style.backgroundColor =
        "grey";
      customerFormEmail.classList.remove("invalid");
      //post the filled out data to the mailing api
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
          document.body.style.cursor = "default";
          document.querySelector(".contact-form__button").style = "";

          contactFormName.value = "";
          customerFormEmail.value = "";
          customerFormMessage.value = "";
        }
      });
    }
  }
}

function handleCarouselScroll() {
  //Check if there is a element with the class .slider in the DOM
    if (document.querySelector(".slider") !== null) {
      //scrollable container
      let options = {
        root: document.querySelector(".slider"),
        rootMargin: "0px",
        threshold: 1.0,
      };
      
      //Target to observe when it is in viewport
      let target = document.querySelector("#card-3");
      
      //Observer function that will observe scrollable container with a callback function
      let observer = new IntersectionObserver((e) => {
        //In this callback find which card is in focus by looking at the position of the targeted card
        //Get container with scroll indicators
        let eIndicatorContainer = document.querySelector(".scroll-indicators")
        //Check if the target element is in viewport
        if(e[0].isIntersecting) {
          //If true add hidden class
          eIndicatorContainer.classList.add("scroll-indicators--hidden");
        } else {
          //Else remove hidden class
          eIndicatorContainer.classList.remove("scroll-indicators--hidden");
        }
        
        return;
      }, options);
  
      observer.observe(target);
    }
}

//used to add an addon to the cart
function addAddOnToCart(sAddOnId) {
  //get the amount of the addon that the user wanted to ass
  let nAddOnAmount = document.querySelector(
    ".addon-form__input_" + sAddOnId
  ).value;

  //check if it is a number if it isn't let the user know
  if (isNaN(parseInt(nAddOnAmount))) {
    showMessage("Please input a number", false);
  } else {
    nAddOnAmount = parseInt(nAddOnAmount);

    //contact the api to add the addon to the cart in the backend
    postData("api/add-addon-to-cart.php", {
      addOnId: sAddOnId,
      addOnAmount: nAddOnAmount,
    }).then((jResponse) => {
      if (jResponse.itemAddedToCart) {
        showMessage("Addon added to cart succesfully", false);
        //update the cart counter in the frontend
        updateCartCounter(false, nAddOnAmount, true);
      }
    });
  }
}

//used to add a product to the cart
function addProductToCart(sProductId, sButtonId) {
  let aSubscriptionItems = document.querySelectorAll(".dropdown__list-item");
  let bSubscriptionChosen = false;
  let sSubscriptionId = undefined;

  //check which subscription the user has chosen
  for (let i = 0; i < aSubscriptionItems.length; i++) {
    if (
      parseInt(aSubscriptionItems[i].dataset.buttonid) === sButtonId &&
      aSubscriptionItems[i].classList.contains("dropdown__list-item--active")
    ) {
      bSubscriptionChosen = true;
      sSubscriptionId = aSubscriptionItems[i].dataset.subscriptionid;
    }
  }
  //Let the user know that they need to choose a subscription if they have not
  if (!bSubscriptionChosen) {
    let aDialogBoxes = document.querySelectorAll(".dialog-box");
    for (let i = 0; i < aDialogBoxes.length; i++) {
      if (parseInt(aDialogBoxes[i].dataset.buttonid) === sButtonId) {
        //unhides dialogbox with message to choose a subscription
        aDialogBoxes[i].classList.remove("dialog-box--hidden");
      }
    }
  } else {
    //contact the api to add the product to the cart in the backend and let the user know

    postData("api/add-product-to-cart.php", {
      productId: sProductId,
      subscriptionId: sSubscriptionId,
    }).then((jResponse) => {
      if (jResponse.itemAddedToCart) {
        showMessage("Product added to cart succesfully", false);
        //update the frontend cart counter
        updateCartCounter(true, 0, true);
      }
    });
  }
}

//hides any open dialogbox. Gets called when the user chooses a subscription
function toggleDialogBox() {
  let aDialogBoxes = document.querySelectorAll(".dialog-box");
  for (let i = 0; i < aDialogBoxes.length; i++) {
    if (!aDialogBoxes[i].classList.contains("dialog-box--hidden")) {
      aDialogBoxes[i].classList.add("dialog-box--hidden");
    }
  }
}

//opens and closes the subscription dropdown
function toggleDropdown() {
  if (document.querySelector(".dropdown") !== null) {
    let aDropdownButtonElements =
      document.querySelectorAll(".dropdown__button");
    let aDropdownListElements = document.querySelectorAll(
      ".dropdown-list-container"
    );
    aDropdownButtonElements.forEach((eButton) => {
      eButton.addEventListener("click", () => {
        //Reset all dialog boxes

        aDropdownListElements.forEach((eList) => {
          if (eButton.dataset.buttonid === eList.dataset.listid) {
            eList.classList.toggle("dropdown-list-container--hidden");
          }
        });
      });
    });
  }
}

//listens for clicks on any subscription
function itemSelector() {
  let aDropdownItems = "";
  if (document.querySelector(".dropdown__list-item") !== null) {
    aDropdownItems = document.querySelectorAll(".dropdown__list-item");
    for (let i = 0; i < aDropdownItems.length; i++) {
      //remove active class from all items

      aDropdownItems[i].addEventListener("click", (e) => {
        let aChildrenNodes = Array.from(
          aDropdownItems[i].parentElement.children
        );
        aChildrenNodes.forEach((eChild) => {
          //remove class
          eChild.classList.remove("dropdown__list-item--active");
        });
        toggleDialogBox();
        clickedItem(aDropdownItems[i]);
      });
    }
  }
}
//Toggles styling for chosen subscription element
function clickedItem(eDropDownItem) {
  let aDropdownButtons = document.querySelectorAll(".dropdown__button");
  aDropdownButtons.forEach((eButton) => {
    if (eButton.dataset.buttonid === eDropDownItem.dataset.buttonid) {
      if (
        eButton.dataset.subscriptionid === undefined ||
        eButton.dataset.subscriptionid !== eDropDownItem.dataset.subscriptionid
      ) {
        //toogle active class
        eDropDownItem.classList.add("dropdown__list-item--active");
        //update text on dropdown button
        eButton.textContent = eDropDownItem.textContent;
        //Create or update dataset attribute
        eButton.setAttribute(
          "data-subscriptionid",
          eDropDownItem.dataset.subscriptionid
        );
      } else if (
        eButton.dataset.subscriptionid === eDropDownItem.dataset.subscriptionid
      ) {
        //Reset button and remove product id dataset
        eButton.removeAttribute("data-subscriptionid");
        //remove active class from the clicked product item.
        eDropDownItem.classList.remove("dropdown__list-item--active");
        //Update text on dropdown button
        eButton.textContent = "Choose a subscription length";
      }
    }
  });
}
