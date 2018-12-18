DROP VIEW IF EXISTS 'ImageOfStory';
DROP VIEW IF EXISTS 'ImageOfComment';

CREATE VIEW ImageOfStory AS
SELECT imageid AS storyimageid, imagefile AS storyimagefile,
    width AS storyimagewidth, height AS storyimageheight,
    filesize AS storyimagesize, format AS storyimageformat
FROM Image ORDER BY imageid ASC;

CREATE VIEW ImageOfComment AS
SELECT imageid AS commentimageid, imagefile AS commentimagefile,
    width AS commentimagewidth, height AS commentimageheight,
    filesize AS commentimagesize, format AS commentimageformat
FROM Image ORDER BY imageid ASC;



DROP VIEW IF EXISTS 'AuthorOfStory';
DROP VIEW IF EXISTS 'AuthorOfComment';
DROP VIEW IF EXISTS 'AuthorProfileOfStory';
DROP VIEW IF EXISTS 'AuthorProfileOfComment';

CREATE VIEW AuthorOfStory AS
SELECT U.userid AS storyauthor, U.username AS storyauthorname, 
FROM User U
ORDER BY A.authorid ASC;

CREATE VIEW AuthorOfComment AS
SELECT U.userid AS commentauthor, U.username AS commentauthorname
FROM User U
ORDER BY U.userid ASC;

CREATE VIEW AuthorProfileOfStory AS
SELECT U.userid AS storyauthor, U.username AS storyauthorname, P.*
FROM User U
LEFT JOIN Picture P ON U.imageid = P.pictureid
ORDER BY U.userid ASC;

CREATE VIEW AuthorProfileOfComment AS
SELECT U.userid AS commentauthor, U.username as commentauthorname, P.*
FROM User U
LEFT JOIN Picture P ON U.imageid = P.pictureid
ORDER BY U.userid ASC;



/**
 * StoryComment cross tree
 */
DROP VIEW IF EXISTS 'StoryComment';

CREATE VIEW StoryComment AS
SELECT CA.entityid AS CA.commentid, SA.entityid AS storyid,
    CA.authorid AS commentauthorid, CA.content AS commentcontent,
    CA.createdat AS commentcreatedat, CA.updatedat AS commentupdatedat,
    CA.upvotes AS commentupvotes, CA.downvotes AS commentdownvotes,
     SA.authorid AS storyAuthorid, SA.channelid, SA.storyTitle, SA.storyType,
    SA.content AS storyContent, SA.imageid AS storyImageid, SA.createdat AS storyCreatedat,
    SA.updatedat AS storyUpdatedat, SA.upvotes AS storyUpvotes, SA.downvotes AS storyDownvotes,
    SA.
FROM CommentAll CA
JOIN StoryAll SA ON SA.entityid = CA.storyid
