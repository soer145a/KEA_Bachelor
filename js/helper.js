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
                    if (button.dataset.btnid === list.dataset.listid) {
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
            aDropdownItems[i].addEventListener("click", () => {
                clickedItem(aDropdownItems[i]);
            });
        }
    }
}

function clickedItem(item) {
    if (localStorage.length < 0) {
        console.log(item);
    }
    localStorage.setItem("chosenItem", item.dataset.value);
    let selectedElement = item;
    let dropdownButton = document.querySelector(".dropdown__button");
    dropdownButton.textContent = selectedElement.textContent;
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
