function createPostButton(){
    if(auth != null) window.location.replace("create_post.php");
    else openLogIn();
}

function createChannelButton(){
    if(auth != null) window.location.replace("create_channel.php");
    else openLogIn();
}

api.channel.get({all: 1}, [200])
.then(response => response.json())
.then(json => addChannelsToHeader(json.data));

function addChannelsToHeader(data) {
    let all_channels = document.querySelector('.header-dropdown .dropdown_options');
    for(let channel of data) {
        let channelname = channel.channelname;
        let channelid = channel.channelid;
        let div = document.createElement('div');
        div.textContent = channelname.toUpperCase();
        div.addEventListener('click', () => {
            window.location.replace(`channel.php?id=${channelid}`);
        });

        all_channels.appendChild(div);  
    }
}

let channels = document.querySelector(".header-dropdown .dropdown_options");

document.querySelector(".header-dropdown .dropdown_selection").addEventListener('click', () => {
    channelToggle();
})

document.querySelector(".header-dropdown .triangle_down").addEventListener('click', () => {
    channelToggle();
})

function channelToggle() {
    if(channels.style.display == "block") {
        channels.style.display = "none";
    }
    else {
        channels.style.display = "block";
    }
}