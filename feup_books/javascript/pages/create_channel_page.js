if(auth == null)
    window.location.replace("index.php");

let form_img = document.querySelector('#new_channel');
form_img.addEventListener('submit', event =>{
    event.preventDefault();
    let name = form_img.querySelector('input[name="channel_name"]').value;
    let img = form_img.querySelector('input[name="upload-file"]').files[0];
       
    if(name == "" && img == undefined) alert("Please fill the form.");
    else if(name == "") alert("Please add a channel name.");
    else if(img == undefined) alert("Please upload a banner image");
    else {
        let channelid;
        api.channel.put({creatorid: auth.userid}, {channelname: name}, [201, 400])
        .then(r => {
            if(r.ok) {
                return r.json();                
            }
            else {
                alert("Channel name already exists / Invalid name");
                throw "Invalid name";
            }
        })
        .then(r => {
            channelid = r.data.channelid;
            console.log(channelid);

            const formData = new FormData(event.target);
            api.fetch('upload', '', {
                method: 'POST',
                body: formData,
                contentType: false,
                processData: false,
            }).then(r => r.json())
            .then( r => api.channel.patch({channelid: channelid}, {imageid: r.id}, [200]))    
            .then( () => window.location.replace('channel.php?id=' + channelid))               
        });
    }
});