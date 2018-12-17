document.querySelector("#tab_default").click();

function tab_option(event, option) {
    let i, tabcontent, tablinks;

    tabcontent = document.querySelectorAll(".tabcontent");
    for (i = 0; i < tabcontent.length; i++) {
      tabcontent[i].style.display = "none";
    }
  
    tablinks = document.querySelectorAll(".tablinks");
    for (i = 0; i < tablinks.length; i++) {
      tablinks[i].className = tablinks[i].className.replace(" active", "");
    }
      document.querySelector(`#${option}`).style.display = "block";
    event.currentTarget.className += " active";
}