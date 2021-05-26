document.addEventListener("DOMContentLoaded", () => {
    updateSliderButton();
    toggleDropdown();
    dropdownSelector();
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

function toggleDropdown() {
    if (document.querySelector(".dropdown") !== null) {
        let aDropdownBtnElements =
            document.querySelectorAll(".dropdown__button");
        let aDropdownlistElements = document.querySelectorAll(
            ".dropdown-list-container"
        );
        aDropdownBtnElements.forEach((button) => {
            console.log(button);
            button.addEventListener("click", () => {
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

function dropdownSelector() {
    let aDropdownItems = "";
    if (document.querySelector(".dropdown__list-item") !== null) {
        aDropdownItems = document.querySelectorAll(".dropdown__list-item");
        for (let i = 0; i < aDropdownItems.length; i++) {
            //remove active class from all items

            aDropdownItems[i].addEventListener("click", () => {
                for (let i = 0; i < aDropdownItems.length; i++) {
                    aDropdownItems[i].classList.remove(
                        "dropdown__list-item--active"
                    );
                }

                clickedItem(aDropdownItems[i]);
            });
        }
    }
}

function clickedItem(item) {
    let aDropdownButtons = document.querySelectorAll(".dropdown__button");
    aDropdownButtons.forEach((button) => {
        if (button.dataset.buttonid === item.dataset.buttonid) {
            if (button.dataset.productid === item.dataset.productid) {
                //Reset button and remove product id dataset
                button.removeAttribute("data-productid");
                //remove active class from the clicked product item.
                item.classList.remove("dropdown__list-item--active");
                //Update text on dropdown button
                button.textContent = "Choose a subscription length";
            } else if (
                button.dataset.productid === undefined ||
                button.dataset.productid !== item.dataset.productid
            ) {
                //toogle active class
                item.classList.add("dropdown__list-item--active");
                //update text on dropdown button
                button.textContent = item.textContent;
                //Create or update dataset attribute
                button.setAttribute("data-productid", item.dataset.productid);
            }
        }
    });
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
