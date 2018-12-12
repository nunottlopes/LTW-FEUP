let dropdown_options = document.querySelector("#dropdown_options");


// TODO: get logged in user channels
let channels = ["channel 1", "channel 2", "channel 3", "channel 4"];
channels.forEach(channel => {
    let div = document.createElement('div');
    div.textContent = channel.toUpperCase();
    dropdown_options.appendChild(div);
})
