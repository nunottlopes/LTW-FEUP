let sortby_dropdown = document.querySelector("#sort-dropdown");

document.querySelector("#typesortby").addEventListener('click', () => {
    if(sortby_dropdown.style.display == "block") {
        sortby_dropdown.style.display = "none";
    }
    else {
        sortby_dropdown.style.display = "block";
    }
})

document.querySelector(".triangle_down").addEventListener('click', () => {
    if(sortby_dropdown.style.display == "block") {
        sortby_dropdown.style.display = "none";
    }
    else {
        sortby_dropdown.style.display = "block";
    }
})

// Close the dropdown if the user clicks outside of it
window.onclick = function(event) {
    if (!event.target.matches('.triangle_down') && !event.target.matches('#typesortby') && !event.target.matches('#sort-dropdown')) {
        sortby_dropdown.style.display = "none";
    }
}