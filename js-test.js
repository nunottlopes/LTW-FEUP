let story = {
    channelid: 3,
    storyTitle: 'Era uma vez...',
    storyType: 'text',
    content: 'Uma Quina aziada e lorem ipsum ipsum'
};

let xhr = new XMLHttpRequest();
xhr.open('POST', 'http://localhost:8080/api/actions/post-story.php', true);
xhr.setRequestHeader('Content-Type', 'application/json');
xhr.send(JSON.stringify(story));
