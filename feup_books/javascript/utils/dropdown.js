let dropdown = document.querySelector("#dropdown_options");
let selection = document.querySelector("#dropdown_selection");

selection.addEventListener('click', () => {
    if(dropdown.style.display == "block") {
        dropdown.style.display = "none";
    }
    else {
        dropdown.style.display = "block";
    }
})

document.querySelector(".triangle_down").addEventListener('click', () => {
    if(dropdown.style.display == "block") {
        dropdown.style.display = "none";
    }
    else {
        dropdown.style.display = "block";
    }
})

function bindDropdownOptions() {
    let options = Array.from(dropdown.children);
    options.forEach(element => {
        element.addEventListener('click', () => {
            selection.setAttribute('selectionid', element.getAttribute('id'));
            selection.textContent = element.textContent;
        })
    });
}

// Close the dropdown if the user clicks outside of it
window.onclick = function(event) {
    if (!event.target.matches('.triangle_down') &&
        !event.target.matches('#dropdown_selection') &&
        !event.target.matches('#dropdown_options')) {
        dropdown.style.display = "none";
    }
}

bindDropdownOptions();