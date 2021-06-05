document.addEventListener("DOMContentLoaded", () => {
  updateSliderButton();
  toggleDropdown();
  itemSelector();
  toggleInfoBox();
});

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

function addAddOnToCart(sAddOnId) {
  let nAddOnAmount = document.querySelector(
    ".addon-form__input_" + sAddOnId
  ).value;

  if (isNaN(parseInt(nAddOnAmount))) {
    //Do user communication here
  } else {
    nAddOnAmount = parseInt(nAddOnAmount);

    postData("API/add-addon-to-cart.php", {
      addOnId: sAddOnId,
      addOnAmount: nAddOnAmount,
    });
    updateCartCounter(false, nAddOnAmount, true);
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

    postData("API/add-product-to-cart.php", {
      productId: sProductId,
      subscriptionId: sSubscriptionId,
    });
    updateCartCounter(true, 0, true);
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

function removeItemFromCart(sItemId, bIsProduct, nAddonAmount, bLoginStatus) {
  updateCartCounter(bIsProduct, nAddonAmount, false);

  event.target.parentElement.parentElement.parentElement.remove();

  if (document.querySelectorAll(".product-row").length == 0) {
    togglePaypalButton(bLoginStatus, 0);
  }

  postData("API/remove-item-from-cart.php", {
    itemId: sItemId,
    isProduct: bIsProduct,
  });
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

// function scrollToItem(itemPosition, numItems, scroller) {
//     scroller.scrollTo({
//         scrollLeft: Math.floor(
//             scroller.scrollWidth * (itemPosition / numItems)
//         ),
//         behavior: "smooth",
//     });
// }

// function getSliderDotElements() {
//     if (document.querySelector(".slider-dots__dot-element") !== null) {
//         let aSliderDotElements = document.querySelectorAll(
//             ".slider-dots__dot-element"
//         );

//         for (let i = 0; i < aSliderDotElements.length; i++) {}
//     }
// }
// function isInViewport(element) {
//     const rect = element.getBoundingClientRect();
//     return (
//         rect.top >= 0 &&
//         rect.left >= 0 &&
//         rect.bottom <=
//             (window.innerHeight || document.documentElement.clientHeight) &&
//         rect.right <=
//             (window.innerWidth || document.documentElement.clientWidth)
//     );
// }
