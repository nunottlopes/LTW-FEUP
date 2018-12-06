var divs = document.querySelectorAll("#account ul>*");

for(let i = 0; i < divs.length; i++){
    divs[i].addEventListener("click", function(){
        divs[i].classList.add("profile_options_selected");
        for(let n = 0; n < divs.length; n++){
            if(n != i){
                divs[n].classList.remove("profile_options_selected");
            }
        }
    });
}