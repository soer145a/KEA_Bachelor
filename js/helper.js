document.addEventListener("DOMContentLoaded", () => {
    updateSliderButton();
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
