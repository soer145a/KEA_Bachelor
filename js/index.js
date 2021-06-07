document.addEventListener("DOMContentLoaded", () => {
    updateSliderButton();
    toggleDropdown();
    itemSelector();
    toggleInfoBox();
    // handleCarouselScroll();
  });
function sendContactForm() {
    if (customerFormEmail.classList.contains("invalid")) {
      showMessage("Please provide a valid email address", true);
    } else {
      let sCustomerName = contactFormName.value;
      let sCustomerEmail = customerFormEmail.value;
      let sCustomerMessage = customerFormMessage.value;
      if (sCustomerName == "" || sCustomerEmail == "" || sCustomerMessage == "") {
        showMessage("Please fill out all the field", true);
      } else {
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
            contactFormName.value = "";
            customerFormEmail.value = "";
            customerFormMessage.value = "";
          }
        });
      }
    }
}
/* function handleCarouselScroll() {
    if (document.querySelector(".slider") !== null) {
      console.log("handleCarouselScroll()");
      let options = {
        root: document.querySelector(".slider"),
        rootMargin: "0px",
        threshold: 1.0,
      };
  
      let target = document.querySelector("#card-2");
  
      let observer = new IntersectionObserver((e) => {
        console.log(e);
        //In this callback find which card is in focus by looking at the position of the middle card
        return;
      }, options);
  
      observer.observe(target);
    }
} */
function addAddOnToCart(sAddOnId) {
    let nAddOnAmount = document.querySelector(
      ".addon-form__input_" + sAddOnId
    ).value;
  
    if (isNaN(parseInt(nAddOnAmount))) {
      //Do user communication here
    } else {
      nAddOnAmount = parseInt(nAddOnAmount);
  
      postData("api/add-addon-to-cart.php", {
        addOnId: sAddOnId,
        addOnAmount: nAddOnAmount,
      }).then((jResponse) => {
        if (jResponse.itemAddedToCart) {
          showMessage("Addon added to cart succesfully", false);
          updateCartCounter(false, nAddOnAmount, true);
        }
      });
    }
  }
  
  function addProductToCart(sProductId, sButtonId) {
    let aSubscriptionItems = document.querySelectorAll(".dropdown__list-item");
    let bSubscriptionChosen = false;
    let sSubscriptionId = undefined;
  
    for (let i = 0; i < aSubscriptionItems.length; i++) {
      if (
        parseInt(aSubscriptionItems[i].dataset.buttonid) === sButtonId &&
        aSubscriptionItems[i].classList.contains("dropdown__list-item--active")
      ) {
        bSubscriptionChosen = true;
        sSubscriptionId = aSubscriptionItems[i].dataset.subscriptionid;
      }
    }
  
    if (!bSubscriptionChosen) {
      let aDialogBoxes = document.querySelectorAll(".dialog-box");
      for (let i = 0; i < aDialogBoxes.length; i++) {
        if (parseInt(aDialogBoxes[i].dataset.buttonid) === sButtonId) {
          aDialogBoxes[i].classList.remove("dialog-box--hidden");
        }
      }
    } else {
      //Add to cart
  
      postData("api/add-product-to-cart.php", {
        productId: sProductId,
        subscriptionId: sSubscriptionId,
      }).then((jResponse) => {
        if (jResponse.itemAddedToCart) {
          showMessage("Product added to cart succesfully", false);
          updateCartCounter(true, 0, true);
        }
      });
    }
}

  
  function updateSliderButton() {
    //This function sets the "active" class to the clicked element
    if (document.querySelector(".js-carousel-button") !== null) {
      let aSliderButtons = document.querySelectorAll(".js-carousel-button");
  
      aSliderButtons.forEach((button) => {
        button.addEventListener("click", () => {
          for (let i = 0; i < aSliderButtons.length; i++) {
            aSliderButtons[i].classList.remove(
              "slider-dots__dot-element--active"
            );
          }
          button.classList.add("slider-dots__dot-element--active");
        });
      });
    }
  }
  
  function toggleDialogBox() {
    let aDialogBoxes = document.querySelectorAll(".dialog-box");
    for (let i = 0; i < aDialogBoxes.length; i++) {
      if (!aDialogBoxes[i].classList.contains("dialog-box--hidden")) {
        aDialogBoxes[i].classList.add("dialog-box--hidden");
      }
    }
  }
  
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
  