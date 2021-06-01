document.addEventListener("DOMContentLoaded", () => {
    updateSliderButton();
    toggleDropdown();
    itemSelector();
    removeItemFromCart();
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
        let aDropdownBtnElements =
            document.querySelectorAll(".dropdown__button");
        let aDropdownlistElements = document.querySelectorAll(
            ".dropdown-list-container"
        );
        aDropdownBtnElements.forEach((button) => {
            button.addEventListener("click", () => {
                //Reset all dialog boxes

                aDropdownlistElements.forEach((list) => {
                    if (button.dataset.buttonid === list.dataset.listid) {
                        list.classList.toggle(
                            "dropdown-list-container--hidden"
                        );
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
                aChildrenNodes.forEach((child) => {
                    //remove class
                    child.classList.remove("dropdown__list-item--active");
                });
                toggleDialogBox();
                clickedItem(aDropdownItems[i]);
            });
        }
    }
}

function clickedItem(item) {
    let aDropdownButtons = document.querySelectorAll(".dropdown__button");
    aDropdownButtons.forEach((button) => {
        if (button.dataset.buttonid === item.dataset.buttonid) {
            if (
                button.dataset.subscriptionid === undefined ||
                button.dataset.subscriptionid !== item.dataset.subscriptionid
            ) {
                //toogle active class
                item.classList.add("dropdown__list-item--active");
                //update text on dropdown button
                button.textContent = item.textContent;
                //Create or update dataset attribute
                button.setAttribute(
                    "data-subscriptionid",
                    item.dataset.subscriptionid
                );
            } else if (
                button.dataset.subscriptionid === item.dataset.subscriptionid
            ) {
                //Reset button and remove product id dataset
                button.removeAttribute("data-subscriptionid");
                //remove active class from the clicked product item.
                item.classList.remove("dropdown__list-item--active");
                //Update text on dropdown button
                button.textContent = "Choose a subscription length";
            }
        }
    });
}

function addAddOnToCart(addOnId) {
    let addOnAmount = document.querySelector(
        ".addon-form__input_" + addOnId
    ).value;

    postData("API/add-addon-to-cart.php", {
        addon_id: addOnId,
        addon_amount: addOnAmount,
    });
    updateCartCounter();
}

function addProductToCart(productId, buttonId) {
    let aSubscriptionItems = document.querySelectorAll(".dropdown__list-item");
    let subChosen = false;
    let chosenSubscription = undefined;

    for (let i = 0; i < aSubscriptionItems.length; i++) {
        if (
            parseInt(aSubscriptionItems[i].dataset.buttonid) === buttonId &&
            aSubscriptionItems[i].classList.contains(
                "dropdown__list-item--active"
            )
        ) {
            subChosen = true;
            chosenSubscription = aSubscriptionItems[i].dataset.subscriptionid;
        }
    }

    if (!subChosen) {
        let aDialogBoxes = document.querySelectorAll(".dialog-box");
        for (let i = 0; i < aDialogBoxes.length; i++) {
            if (parseInt(aDialogBoxes[i].dataset.buttonid) === buttonId) {
                aDialogBoxes[i].classList.remove("dialog-box--hidden");
            }
        }
    } else {
        //Add to cart
        postData("API/add-product-to-cart.php", {
            product_id: productId,
            sub: chosenSubscription,
        });
        updateCartCounter();
    }
}

function updateCartCounter() {
    let eCartCounter = document.querySelector(".cart-counter");

    let counter = parseInt(eCartCounter.textContent);

    eCartCounter.textContent = counter + 1;
}

function removeItemFromCart() {
    if (document.querySelector(".js-remove-item") !== null) {
        let aCartItems = document.querySelectorAll(".js-remove-item");

        for (let i = 0; i < aCartItems.length; i++) {
            aCartItems[i].addEventListener("click", () => {
                console.log(
                    "I was clicked",
                    aCartItems[i].parentElement.parentElement.remove()
                );
            });
        }
    }
}

function toggleInfoBox() {
    console.log("toggleInfoBox()");
    if (document.querySelector(".js-toggle-infobox") !== null) {
        aToggleElements = document.querySelectorAll(".js-toggle-infobox");
        aToggleElements.forEach((element) => {
            element.addEventListener("click", () => {
                const eInfobox = document.querySelector(
                    ".login-form__label-info-box"
                );
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
