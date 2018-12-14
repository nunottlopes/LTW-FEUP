var APITEST = true;

function print(N) {
    let json = api.test.json[N];

    let string = '<pre style="font-size:150%">';
    string += JSON.stringify(json, null, 4);
    string += '</pre>';

    document.querySelector('body').innerHTML = string;
}

async function testheader() {
    api.test.clear();

    await api.login("admin", "admin");
}

function testfooter() {
    console.log(api.test.codes);
    console.log(api.test.responses);
    console.log(api.test.json);
}



async function testchannel(all) {
    await testheader();

    console.log("EXPECTED: [Created] 2, [Updated] 2, [OK] 6, [Deleted] 2, [404] 6");
    console.log("EXPECTED: [200] 10, [201] 2, [404] 6");

    // create
    await api.channel.put("creatorid=5", {channelname: "Amadeuses"});
    await api.channel.put("creatorid=4", {channelname: "Nunopedia"});

    // set-banner
    await api.channel.patch("channelid=5", {imageid: 1});
    await api.channel.patch("channelid=6", {imageid: 2});

    // get-id
    await api.channel.get("channelid=2");
    await api.channel.get("channelid=5");

    // get-name
    await api.channel.get("channelname=showerthoughts");
    await api.channel.get("channelname=Amadeuses");

    // get-id
    await api.channel.get("creatorid=2");
    await api.channel.get("creatorid=5");

    // get-valid
    await api.channel.get("valid=Amadeuses");
    await api.channel.get("valid=WtfPedia");

    // delete-id
    await api.channel.delete("channelid=4");

    // delete-name
    await api.channel.delete("channelname=jokes");

    // 404s
    await api.channel.get("channelid=9"); // 404
    await api.channel.get("channelname=roses"); // 404
    await api.channel.patch("channelid=10", {imageid: 7}); // 404
    await api.channel.patch("channelid=1", {imageid: 700}); // 404
    await api.channel.delete("channelid=10"); // 404
    await api.channel.delete("channelname=ayylmao"); // 404

    // delete-all
    if (all) await api.channel.delete("all");

    testfooter();
}

async function testcomment(all) {
    await testheader();

    console.log("EXPECTED: [Created] 3, [Updated] 2, [OK] 34, [Deleted] 4, [404] 4");
    console.log("EXPECTED: [200] 40, [201] 3, [404] 4");

    // create
    await api.comment.post("parentid=99&authorid=4", {content: "Comment#101-#1-#7"});
    await api.comment.post("parentid=70&authorid=7", {content: "Comment#102-#1-#4"});
    await api.comment.post("parentid=47&authorid=2", {content: "Comment#103-#1-#3"});

    // edit
    await api.comment.put("commentid=66", {content: "Edited Comment#66-#1-#3"});
    await api.comment.put("commentid=98", {content: "Edited Comment#98-#1-#5"});

    // get-id
    await api.comment.get('commentid=43');
    await api.comment.get('commentid=76');

    // get-parent-author
    await api.comment.get('parentid=28&authorid=5'); // #41, #44
    await api.comment.get('parentid=7&authorid=1&order=top&limit=2&offset=2'); // #40
    await api.comment.get('parentid=7&authorid=1&order=bot&limit=2'); // #40, #36

    // get-parent
    await api.comment.get("parentid=1&limit=2&offset=1&order=best"); // 2
    await api.comment.get("parentid=28&limit=3&offset=2&order=new"); // 2

    // get-author
    await api.comment.get('authorid=3'); // 13
    await api.comment.get('authorid=3&order=top');

    // get-all
    await api.comment.get("all&limit=10&order=top");
    await api.comment.get("all&limit=10&order=bot");
    await api.comment.get("all&limit=10&order=new&offset=2");
    await api.comment.get("all&limit=10&order=old&since=400000000");
    await api.comment.get("all&limit=20&order=best&offset=3");
    await api.comment.get("all&limit=20&order=controversial&since=1000000");
    await api.comment.get("all&limit=25&order=average&offset=60&since=0");
    await api.comment.get("all&limit=20&order=hot&offset=15&since=0");

    // Same but voted (+17)
    // get-id-voted
    await api.comment.get('voterid=6&commentid=43');
    await api.comment.get('voterid=3&commentid=76');

    // get-parent-author-voted
    await api.comment.get('voterid=3&parentid=28&authorid=5'); // #41, #44
    await api.comment.get('voterid=2&parentid=7&authorid=1&order=top&limit=2&offset=2'); // #40
    await api.comment.get('voterid=7&parentid=7&authorid=1&order=bot&limit=2'); // #40, #36

    // get-parent-voted
    await api.comment.get("voterid=1&parentid=1&limit=2&offset=1&order=best"); // 2
    await api.comment.get("voterid=9&parentid=28&limit=3&offset=2&order=new"); // 2

    // get-author-voted
    await api.comment.get('voterid=8&authorid=3'); // 13
    await api.comment.get('voterid=4&authorid=3&order=top');

    // get-all-voted, ordering
    await api.comment.get("voterid=3&all&limit=10&order=top");
    await api.comment.get("voterid=6&all&limit=10&order=bot");
    await api.comment.get("voterid=7&all&limit=10&order=new&offset=2");
    await api.comment.get("voterid=8&all&limit=10&order=old&since=400000000");
    await api.comment.get("voterid=2&all&limit=20&order=best&offset=3");
    await api.comment.get("voterid=1&all&limit=20&order=controversial&since=1000000");
    await api.comment.get("voterid=4&all&limit=25&order=average&offset=60&since=0");
    await api.comment.get("voterid=5&all&limit=20&order=hot&offset=15&since=0");

    await api.comment.get("authorid=10"); // 404
    await api.comment.get("parentid=173"); // 404
    await api.comment.get("authorid=4&parentid=666"); // 404
    await api.comment.get("authorid=0&parentid=43"); // 404

    // delete-id
    await api.comment.delete("commentid=73"); // count = 1 | deleted = 8
    
    // delete-parent-author
    await api.comment.delete("parentid=82&authorid=3"); // count = 2 | deleted = 2
    
    // delete-parent
    await api.comment.delete("parentid=51"); // count = 1
    
    // delete-author
    await api.comment.delete("authorid=8"); // count = 4 | deleted = 7
    
    // delete-all
    if (all) await api.comment.delete("all"); // count = (73 + 3 - 8 - 2 - 1 - 7) = 58

    testfooter();
}

async function testentity() {
    await testheader();

    console.log("EXPECTED: [OK] 5, [404] 2");
    console.log("EXPECTED: [200] 5, [404] 2");

    // get-id
    await api.entity.get("entityid=1");
    await api.entity.get("entityid=17");
    await api.entity.get("entityid=71");
    await api.entity.get("entityid=83");
    await api.entity.get("entityid=150"); // 404
    await api.entity.get("entityid=250"); // 404

    // get-all
    await api.entity.get("all");

    testfooter();
}

async function testlogin() {
    await testheader();

    console.log("EXPECTED: [OK] 8, [Accepted] 4/5, [Forbidden] 6");
    console.log("EXPECTED: [200] 8, [202] 4/5, [403] 6");

    await api.auth(); // 200

    // logout of admin
    await api.logout(); // 202

    await api.auth(); // 200

    // try action requiring authentication
    await api.user.get("self"); // 403

    // try action requiring authentication as X
    await api.vote.get("userid=5"); // 403

    // try action requiring privileged access
    await api.vote.get("all"); // 403

    await api.login("Bruno", "bruno"); // 202

    await api.auth(); // 200

    // try action for user bruno
    await api.user.get("self"); // 200
    await api.vote.get("userid=5"); // 200

    // try action requiring authentication as another user
    await api.vote.get("userid=6"); // 403

    // try action requiring privileged access
    await api.save.get("all"); // 403

    await api.login("admin", "admin"); // 202

    await api.auth(); // 200

    // try privileged action
    await api.save.get("all"); // 200

    await api.logout(); // 202

    await api.user.get("self"); // 403

    await api.auth(); // 200

    testfooter();
}

async function testsave() {
    await testheader();

    console.log("EXPECTED: [Created] 6, [OK] 18, [Deleted] 4, [404] 2");
    console.log("EXPECTED: [200] 22, [201] 6, [404] 2");

    // get-comment
    await api.save.get("commentid=66"); // 5

    // get-story
    await api.save.get("storyid=3"); // 2

    // get-entity
    await api.save.get("entityid=67"); // 3
    await api.save.get("entityid=67&limit=2"); // 2
    await api.save.get("entityid=67&offset=1&limit=1"); // 1
    await api.save.get("entityid=67&offset=1"); // 2

    // get-user-comments
    await api.save.get("userid=1&comments"); // 3
    await api.save.get("userid=1&comments&limit=1"); // 1
    await api.save.get("userid=1&comments&limit=2&offset=2"); // 1
    await api.save.get("userid=1&comments&offset=1"); // 2

    // get-user-stories
    await api.save.get("userid=2&stories");
    await api.save.get("userid=2&stories&limit=1");
    await api.save.get("userid=2&stories&limit=1&offset=1");
    await api.save.get("userid=2&stories&offset=1");

    // get-user-all
    await api.save.get("userid=2&all"); // 9

    // get-comments
    await api.save.get("comments"); // 21

    // get-stories
    await api.save.get("stories"); // 19

    // get-all
    await api.save.get("all"); // 40

    // put
    await api.save.put("entityid=3&userid=1"); // 0
    await api.save.put("entityid=4&userid=2"); // 0
    await api.save.put("entityid=5&userid=3"); // 1
    await api.save.put("entityid=6&userid=3"); // 1
    await api.save.put("entityid=3&userid=2"); // 0
    await api.save.put("entityid=4&userid=4"); // 1

    await api.save.get("entityid=150&userid=2"); // 404
    await api.save.get("entityid=5&userid=15"); // 404

    // delete-id
    await api.save.delete("entityid=3&userid=1"); // 1
    
    // delete-user
    await api.save.delete("userid=2&all"); // 9
    
    // delete-entity
    await api.save.delete("entityid=4&all"); // 2
    
    // delete-all
    await api.save.delete("all"); // 28

    testfooter();
}

async function teststory(all) {
    await testheader();

    console.log("EXPECTED: [Created] 7, [Updated] 2, [OK] 34, [Deleted] 5, [Bad] 6, [404] 4");
    console.log("EXPECTED: [200] 41, [201] 7, [400] 6, [404] 4");

    // create
    await api.story.post("channelid=1&authorid=5", {
        storyTitle: 'Story#101',
        storyType: 'text',
        content: "Content #101"
    }); // 201

    await api.story.post("channelid=2&authorid=1", {
        storyTitle: 'Story#102',
        storyType: 'text',
        content: "Content #102"
    }); // 201

    await api.story.post("channelid=4&authorid=5", {
        storyTitle: 'Story#103',
        storyType: 'text',
        content: "Content #103"
    }); // 201

    await api.story.post("channelid=1&authorid=4", {
        storyTitle: 'Story#104',
        storyType: 'image',
        content: 'Content #104',
        imageid: 1
    }); // 201

    await api.story.post("channelid=1&authorid=4", {
        storyTitle: 'Story#105',
        storyType: 'image',
        content: '',
        imageid: 2
    }); // 201

    await api.story.post("channelid=1&authorid=4", {
        storyTitle: 'Story#106',
        storyType: 'title'
    }); // 201

    await api.story.post("channelid=2&authorid=1", {
        storyTitle: 'Story#107',
        storyType: 'title',
        imageid: 3,                // ignored
        content: 'Content Ignored' // ignored
    }); // 201

    await api.story.post("channelid=2&authorid=1", {
        storyTitle: 'Story#108',
        storyType: 'image',
        content: 'Content #108'
    }); // 400

    await api.story.post("channelid=2&authorid=1", {
        storyTitle: 'Story#108',
        storyType: 'image',
        imageid: 6
    }); // 400

    await api.story.post("channelid=2&authorid=1", {
        storyTitle: 'Story#108',
        storyType: 'text',
        imageid: 6
    }); // 400

    await api.story.post("channelid=5&authorid=15", {
        storyTitle: 'Story#108',
        storyType: 'text',
        content: "Content #108"
    }); // 404

    // edit
    await api.story.patch("storyid=4", {
        content: "New story content #1",
        imageid: 8 // ignored
    }); // 200

    await api.story.patch("storyid=5", {
        content: "New story content #2",
        imageid: 2
    }); // 200

    await api.story.patch("storyid=5", {
        content: "New story content #2",
    }); // 400

    await api.story.patch("storyid=2", {
        content: "New story content #3",
    }); // 400

    await api.story.patch("storyid=7", {
        imageid: 7
    }); // 400

    await api.story.patch("storyid=40", {
        content: "New story content #3"
    }); // 404

    // get-id
    await api.story.get("storyid=14");
    await api.story.get("storyid=1");

    // get-channel-author, ordering
    await api.story.get("channelid=2&authorid=1"); // 4

    await api.story.get("channelid=2&authorid=1&limit=2&offset=0&order=top");
    await api.story.get("channelid=2&authorid=1&limit=1&offset=2&order=bot");
    await api.story.get("channelid=2&authorid=1&limit=2&offset=4&order=new");
    await api.story.get("channelid=2&authorid=1&limit=3&offset=3&order=old");
    await api.story.get("channelid=2&authorid=1&limit=4&offset=0&order=best");
    await api.story.get("channelid=2&authorid=1&limit=2&offset=2&order=controversial");
    await api.story.get("channelid=2&authorid=1&limit=1&offset=0&order=average");
    await api.story.get("channelid=2&authorid=1&limit=3&offset=1&order=hot");

    // get-channel
    await api.story.get("channelid=3"); // 7
    await api.story.get("channelid=4"); // 13

    // get-author
    await api.story.get("authorid=7&limit=4"); // 3
    await api.story.get("authorid=3&offset=2"); // 2

    // get-all
    await api.story.get("all&limit=20&order=hot"); // 20
    await api.story.get("all&limit=15&order=controversial");

    // Same but voted (+17)
    // get-id-voted
    await api.story.get("voterid=7&storyid=14");
    await api.story.get("voterid=1&storyid=1");

    // get-channel-author-voted, ordering
    await api.story.get("voterid=2&channelid=2&authorid=1"); // 4

    await api.story.get("voterid=3&channelid=2&authorid=1&limit=2&offset=0&order=top");
    await api.story.get("voterid=4&channelid=2&authorid=1&limit=1&offset=2&order=bot");
    await api.story.get("voterid=5&channelid=2&authorid=1&limit=2&offset=4&order=new");
    await api.story.get("voterid=6&channelid=2&authorid=1&limit=3&offset=3&order=old");
    await api.story.get("voterid=7&channelid=2&authorid=1&limit=4&offset=0&order=best");
    await api.story.get("voterid=8&channelid=2&authorid=1&limit=2&offset=2&order=controversial");
    await api.story.get("voterid=9&channelid=2&authorid=1&limit=1&offset=0&order=average");
    await api.story.get("voterid=1&channelid=2&authorid=1&limit=3&offset=1&order=hot");

    // get-channel-voted
    await api.story.get("voterid=4&channelid=3"); // 7
    await api.story.get("voterid=5&channelid=4"); // 13

    // get-author-voted
    await api.story.get("voterid=6&authorid=7&limit=4"); // 3
    await api.story.get("voterid=2&authorid=3&offset=2"); // 2

    // get-all-voted
    await api.story.get("voterid=3&all&limit=20&order=hot"); // 20
    await api.story.get("voterid=8&all&limit=15&order=controversial");

    await api.story.get("authorid=105"); // 404
    await api.story.get("channelid=10"); // 404

    // delete-id
    await api.story.delete("storyid=1"); // 1
    await api.story.delete("storyid=2"); // 1

    // delete-channel-author
    await api.story.delete("channelid=2&authorid=1"); // 3

    // delete-channel
    await api.story.delete("channelid=3"); // 7

    // delete-author
    await api.story.delete("authorid=3"); // 4

    // delete-all
    if (all) await api.story.delete("all"); // 16

    testfooter();
}

async function testtree() {
    await testheader();

    console.log("EXPECTED: [OK] 24, [404] 4");
    console.log("EXPECTED: [200] 24, [404] 4");

    // get-tree
    await api.tree.get("ascendantid=1&maxdepth=10&limit=60");
    // mega tree on story 1.
    
    await api.tree.get("ascendantid=1&maxdepth=10&order=top&limit=1");
    // #28 -- #42
    
    await api.tree.get("ascendantid=1&maxdepth=10&order=top&limit=2");
    // #28 -- #42
    //    ^-- #41 -- #66 

    await api.tree.get("ascendantid=1&maxdepth=10&order=top&limit=3");
    // #28 -- #42
    //    ^-- #41 -- #66 -- #81

    await api.tree.get("ascendantid=1&maxdepth=10&order=top&limit=4");
    // #28 -- #42
    //    ^-- #41 -- #66 -- #81
    //           ^-- #67 -- #85

    await api.tree.get("ascendantid=1&maxdepth=10&order=top&limit=5");
    // #28 -- #42
    //    ^-- #41 -- #66 -- #81
    //                  ^-- #82 -- #94
    //           ^-- #67 -- #85
    
    await api.tree.get("ascendantid=1&maxdepth=10&order=top&limit=3&offset=2");
    // #28 -- #41 -- #66 -- #81
    //                   -- #82 -- #94
    //            -- #67 -- #85

    await api.tree.get("ascendantid=1&maxdepth=10&order=top&limit=1&offset=4");
    // #28 -- #41 -- #66 -- #82 -- #94

    await api.tree.get("ascendantid=100"); // []
    await api.tree.get("ascendantid=81");
    // #90
    // #91 -- #99
    //    ^-- #100
    
    // get-ancestry
    await api.tree.get("voterid=1&descendantid=100"); // story=#1, comments=#28,#41,#66,#81,#91,#100
    await api.tree.get("voterid=1&descendantid=97"); // story=#1, comments=#30,#50,#73,#88,#97

    // Same but voted (+14)
    // get-tree
    await api.tree.get("voterid=1&ascendantid=1&maxdepth=10&limit=60");
    
    await api.tree.get("voterid=1&ascendantid=1&maxdepth=10&order=top&limit=1");
    
    await api.tree.get("voterid=1&ascendantid=1&maxdepth=10&order=top&limit=2");

    await api.tree.get("voterid=1&ascendantid=1&maxdepth=10&order=top&limit=3");

    await api.tree.get("voterid=1&ascendantid=1&maxdepth=10&order=top&limit=4");

    await api.tree.get("voterid=1&ascendantid=1&maxdepth=10&order=top&limit=5");
    
    await api.tree.get("voterid=1&ascendantid=1&maxdepth=10&order=top&limit=3&offset=2");

    await api.tree.get("voterid=1&ascendantid=1&maxdepth=10&order=top&limit=1&offset=4");

    await api.tree.get("voterid=1&ascendantid=100"); // []
    await api.tree.get("voterid=1&ascendantid=81");
    
    // get-ancestry
    await api.tree.get("voterid=1&descendantid=100"); // story=#1, comments=#28,#41,#66,#81,#91,#100
    await api.tree.get("voterid=1&descendantid=97"); // story=#1, comments=#30,#50,#73,#88,#97

    await api.tree.get("ascendantid=700"); // 404
    await api.tree.get("descendantid=20"); // 404
    await api.tree.get("descendantid=300"); // 404

    testfooter();
}

async function testuser() {
    await testheader();

    console.log("EXPECTED: [Created] 2, [OK] 12, [Deleted] 4, [Invalid] 3, [404] 6");
    console.log("EXPECTED: [200] 16, [201] 2, [400] 3, [404] 6");

    // create
    await api.user.post({username: "Rui", email: "rui@gmail.com", password: "123456"});
    await api.user.post({username: "Madonna", email: "mad@gmail.com", password: "7akdqw0"});

    // get-id
    await api.user.get("userid=1");
    await api.user.get("userid=5");

    // get-username
    await api.user.get("username=Rui");
    await api.user.get("username=Madonna");

    // get-email
    await api.user.get("email=bruno@gmail.com");
    await api.user.get("email=admin@feupnews.com");

    // get-all
    await api.user.get("all");

    // get-self
    await api.user.get("self");

    // valid-username
    await api.user.get("valid-username=Valid");
    await api.user.get("valid-username=12Invalid");

    // valid-email
    await api.user.get("valid-email=AbcValid@gmail.com");
    await api.user.get("valid-email=Invalid");

    // delete-id
    await api.user.delete("userid=7");

    // delete-username
    await api.user.delete("username=Rui");
    await api.user.delete("username=Madonna");

    // delete-email
    await api.user.delete("email=bruno@gmail.com");

    await api.user.get("userid=70"); // 404
    await api.user.get("username=qwefdvwer"); // 404
    await api.user.get("email=yoyo"); // 404

    // Invalid username 400
    await api.user.post({username: "RR", email: "rrr@gmail.com", password: "qweg2g324y"});
    
    // Invalid email 400
    await api.user.post({username: "TiagoRui", email: "tiagmail.com", password: "123456"});
    
    // Invalid password 400
    await api.user.post({username: "TiagoRui", email: "tiago@hotmail.com", password: "12345"});

    await api.user.delete("userid=70"); // 404
    await api.user.delete({username: "Abcabcabcdoesnotexist"}); // 404
    await api.user.delete("email=invalidsoinexistent"); // 404

    testfooter();
}

async function testvote() {
    testheader();

    console.log("EXPECTED: [Created] 6, [OK] 7, [Deleted] 5");
    console.log("EXPECTED: [200] 12, [201] 6");

    // put
    await api.vote.upvote("entityid=24&userid=2"); // 1
    await api.vote.upvote("entityid=33&userid=3"); // 1
    await api.vote.upvote("entityid=49&userid=4"); // 1
    await api.vote.downvote("entityid=78&userid=3"); // 0
    await api.vote.downvote("entityid=93&userid=4"); // 1
    await api.vote.upvote("entityid=50&userid=6"); // 0

    // get-id
    await api.vote.get("entityid=2&userid=2");
    await api.vote.get("entityid=11&userid=7");

    // get-entity
    await api.vote.get("entityid=11");
    await api.vote.get("entityid=3");

    // get-user
    await api.vote.get("userid=2");
    await api.vote.get("userid=5");

    // get-all
    await api.vote.get("all");

    // delete-id
    await api.vote.delete("entityid=20&userid=7"); // 1
    await api.vote.delete("entityid=4&userid=2"); // 0

    // delete-entity
    await api.vote.delete("entityid=1"); // 3

    // delete-user
    await api.vote.delete("userid=4"); // 17 + 12 - 1 = 28

    // delete-all
    await api.vote.delete("all"); // the rest lol

    testfooter();
}

async function testimage() {
    testheader();

    console.log("EXPECTED: [OK] 4, [Deleted] 3");
    console.log("EXPECTED: [200] 7");

    await api.image.get("imageid=1");
    await api.image.get("imageid=2");
    await api.image.get("imageid=3");
    await api.image.get("all");

    await api.image.delete("imageid=1");
    await api.image.delete("imageid=2");
    await api.image.delete("imageid=3");

    testfooter();
}
