/**
 * Image Views
 */
DROP VIEW IF EXISTS 'Picture';
DROP VIEW IF EXISTS 'Banner';
DROP VIEW IF EXIsts 'ImageImage';

CREATE VIEW Picture AS
SELECT imageid AS pictureid, imagefile AS picturefile, width AS picturewidth,
    height AS pictureheight, filesize AS picturesize, format AS pictureformat
FROM Image ORDER BY imageid ASC;

CREATE VIEW Banner AS
SELECT imageid AS bannerid, imagefile AS bannerfile, width AS bannerwidth,
    height AS bannerheight, filesize AS bannersize, format AS bannerformat
FROM Image ORDER BY imageid ASC;

CREATE VIEW ImageImage AS
SELECT imageid, imagefile, width AS imagewidth, height AS imageheight,
    filesize AS imagesize, format AS imageformat
FROM Image ORDER BY imageid ASC;

/**
 * User Views
 */
DROP VIEW IF EXISTS 'UserView';
DROP VIEW IF EXISTS 'UserProfile';
DROP VIEW IF EXISTS 'Author';
DROP VIEW IF EXISTS 'Creator';

CREATE VIEW UserView AS
SELECT userid, username
FROM User U
ORDER BY userid ASC;

CREATE VIEW UserProfile AS
SELECT U.userid, U.username, U.email, U.admin, P.*
FROM User U
LEFT JOIN Picture P ON U.imageid = P.pictureid
ORDER BY U.userid ASC;

CREATE VIEW Author AS
SELECT U.userid AS authorid, U.username AS authorname, P.*
FROM User U
LEFT JOIN Picture P ON U.imageid = P.pictureid
ORDER BY U.userid ASC;

CREATE VIEW Creator AS
SELECT U.userid AS creatorid, U.username AS creatorname, P.*
FROM User U
LEFT JOIN Picture P ON U.imageid = P.pictureid
ORDER BY U.userid ASC;

/**
 * Channel Views
 */
DROP VIEW IF EXISTS 'ChannelView';
DROP VIEW IF EXISTS 'ChannelBanner';
DROP VIEW IF EXISTS 'ChannelCreator';
DROP VIEW IF EXISTS 'ChannelAll';

CREATE VIEW ChannelView AS
SELECT C.*, (SELECT count(*) FROM Story S WHERE S.channelid = CA.channelid) stories
FROM Channel C
ORDER BY C.channelid ASC;

CREATE VIEW ChannelBanner AS
SELECT C.channelid, C.channelname, B.*, C.creatorid
FROM Channel C
LEFT JOIN Banner B ON C.imageid = B.bannerid
ORDER BY C.channelid ASC;

CREATE VIEW ChannelCreator AS
SELECT C.channelid, C.channelname, Cr.*
FROM Channel C
NATURAL LEFT JOIN Creator Cr -- on creatorid
ORDER BY C.channelid ASC;

CREATE VIEW ChannelAll AS
SELECT C.channelid, C.channelname, B.*, Cr.*
FROM Channel C
LEFT JOIN Banner B ON C.imageid = B.bannerid
NATURAL LEFT JOIN Creator Cr -- on creatorid
ORDER BY C.channelid ASC;

/**
 * Story Views
 */
DROP VIEW IF EXISTS 'StoryView';
DROP VIEW IF EXISTS 'StoryEntity';
DROP VIEW IF EXISTS 'StoryImage';
DROP VIEW IF EXISTS 'StoryAuthor';
DROP VIEW IF EXISTS 'StoryChannel';
DROP VIEW IF EXISTS 'StoryImageAuthor';
DROP VIEW IF EXISTS 'StoryImageChannel';
DROP VIEW IF EXISTS 'StoryAuthorChannel';
DROP VIEW IF EXISTS 'StoryImageAuthorChannel';
DROP VIEW IF EXISTS 'StoryAll';

CREATE VIEW StoryView AS
SELECT S.entityid AS storyid, S.storyTitle, S.content
FROM Story S
ORDER BY S.entityid ASC;

CREATE VIEW StoryEntity AS
SELECT *, 'story' type, E.upvotes - E.downvotes AS score
FROM Story S
NATURAL JOIN Entity E -- on entityid
ORDER BY S.entityid ASC;

CREATE VIEW StoryImage AS
SELECT *
FROM StoryEntity SE
NATURAL LEFT JOIN ImageImage I -- on imageid
ORDER BY SE.entityid ASC;

CREATE VIEW StoryAuthor AS
SELECT *
FROM StoryEntity SE
NATURAL LEFT JOIN Author A -- on authorid
ORDER BY SE.entityid ASC;

CREATE VIEW StoryChannel AS
SELECT *
FROM StoryEntity SE
NATURAL JOIN ChannelBanner CB -- on channelid
ORDER BY SE.entityid ASC;

CREATE VIEW StoryImageAuthor AS
SELECT *
FROM StoryEntity SE
NATURAL LEFT JOIN ImageImage I -- on imageid
NATURAL LEFT JOIN Author A -- on authorid
ORDER BY SE.entityid ASC;

CREATE VIEW StoryImageChannel AS
SELECT *
FROM StoryEntity SE
NATURAL LEFT JOIN ImageImage I -- on imageid
NATURAL JOIN ChannelBanner CB -- on channelid
ORDER BY SE.entityid ASC;

CREATE VIEW StoryAuthorChannel AS
SELECT *
FROM StoryEntity SE
NATURAL LEFT JOIN Author A -- on authorid
NATURAL JOIN ChannelBanner CB -- on channelid
ORDER BY SE.entityid ASC;

CREATE VIEW StoryImageAuthorChannel AS
SELECT *
FROM StoryEntity SE
NATURAL LEFT JOIN ImageImage I -- on imageid
NATURAL LEFT JOIN Author A -- on authorid
NATURAL JOIN ChannelBanner CB -- on channelid
ORDER BY SE.entityid ASC;

CREATE VIEW StoryAll AS
SELECT *, (SELECT count(*) FROM Tree T WHERE T.ascendantid = SA.entityid) count
FROM StoryImageAuthorChannel SA
ORDER BY SA.entityid ASC;

/**
 * Comment Views
 */
DROP VIEW IF EXISTS 'CommentView';
DROP VIEW IF EXISTS 'CommentEntity';
DROP VIEW IF EXISTS 'CommentExtra';
DROP VIEW IF EXISTS 'CommentAuthor';
DROP VIEW IF EXISTS 'CommentAll';

CREATE VIEW CommentView AS
SELECT C.entityid AS commentid, C.content
FROM Comment C
ORDER BY C.entityid ASC;

CREATE VIEW CommentEntity AS
SELECT *, 'comment' type, E.upvotes - E.downvotes AS score
FROM Comment C
NATURAL JOIN Entity E -- on entityid
ORDER BY C.entityid ASC;

CREATE VIEW CommentExtra AS
SELECT *,
    (SELECT count(*) FROM Tree T WHERE T.descendantid = CE.entityid) AS level,
    (SELECT count(*) FROM Tree T WHERE T.ascendantid = CE.entityid) AS count,
    (SELECT ascendantid FROM Tree T WHERE T.descendantid = CE.entityid
                                    ORDER BY depth DESC LIMIT 1) AS storyid
FROM CommentEntity CE
ORDER BY CE.entityid ASC;

CREATE VIEW CommentAuthor AS
SELECT *
FROM CommentEntity CE
NATURAL LEFT JOIN Author A -- on authorid
ORDER BY CE.entityid ASC;

CREATE VIEW CommentAll AS
SELECT *
FROM CommentExtra CA
NATURAL LEFT JOIN Author A -- on authorid
ORDER BY CA.entityid ASC;

/**
 * Entity Views
 */
DROP VIEW IF EXISTS 'AnyEntity';
DROP VIEW IF EXISTS 'AnyEntityAuthor';
DROP VIEW IF EXISTS 'AnyEntityAll';

CREATE VIEW AnyEntity AS
SELECT entityid, authorid, parentid, content, createdat, updatedat, upvotes, downvotes,
    type, score, channelid, storyTitle, storyType, imageid
FROM CommentEntity CE
NATURAL LEFT JOIN StoryEntity SE
UNION ALL
SELECT entityid, authorid, parentid, content, createdat, updatedat, upvotes, downvotes,
    type, score, channelid, storyTitle, storyType, imageid
FROM StoryEntity SE
NATURAL LEFT JOIN CommentEntity CE
ORDER BY entityid ASC;

CREATE VIEW AnyEntityAuthor AS
SELECT *
FROM AnyEntity AE
NATURAL LEFT JOIN Author A
ORDER BY entityid ASC;

CREATE VIEW AnyEntityAll AS
SELECT entityid, authorid, parentid, content, createdat, updatedat, upvotes, downvotes,
    type, score, channelid, storyTitle, storyType, level, count, storyid, authorname,
    pictureid, picturefile, picturewidth, pictureheight, picturesize, pictureformat,
    imageid, imagefile, imagewidth, imageheight, imagesize, imageformat, channelname,
    bannerid, bannerfile, bannerwidth, bannerheight, bannersize, bannerformat, creatorid
FROM CommentAll CA
NATURAL LEFT JOIN StoryAll SA
UNION ALL
SELECT entityid, authorid, parentid, content, createdat, updatedat, upvotes, downvotes,
    type, score, channelid, storyTitle, storyType, level, count, storyid, authorname,
    pictureid, picturefile, picturewidth, pictureheight, picturesize, pictureformat,
    imageid, imagefile, imagewidth, imageheight, imagesize, imageformat, channelname,
    bannerid, bannerfile, bannerwidth, bannerheight, bannersize, bannerformat, creatorid
FROM StoryAll SA
NATURAL LEFT JOIN CommentAll CA
ORDER BY entityid ASC;

/**
 * Tree Views
 */
DROP VIEW IF EXISTS 'CommentAncestryTree';
DROP VIEW IF EXISTS 'CommentTree';
DROP VIEW IF EXISTS 'StoryTree';

CREATE VIEW CommentAncestryTree AS
SELECT T.descendantid, CA.*, T.depth
FROM Tree T
JOIN CommentAll CA ON T.ascendantid = CA.entityid
ORDER BY CA.level ASC;

CREATE VIEW CommentTree AS
SELECT T.ascendantid, CA.*, T.depth
FROM Tree T
JOIN CommentAll CA ON T.descendantid = CA.entityid
ORDER BY T.descendantid ASC;

CREATE VIEW StoryTree AS
SELECT T.descendantid AS commentid, SA.*, T.depth
FROM Tree T
JOIN StoryAll SA ON T.ascendantid = SA.entityid
ORDER BY T.descendantid ASC;

/**
 * Save views
 */
DROP VIEW IF EXISTS 'SaveStory';
DROP VIEW IF EXISTS 'SaveComment';
DROP VIEW IF EXISTS 'SaveAscendant';
DROP VIEW IF EXISTS 'SaveAll';
DROP VIEW IF EXISTS 'SaveAllAscendant';
DROP VIEW IF EXISTS 'SaveUser';
DROP VIEW IF EXISTS 'SaveUserStory';
DROP VIEW IF EXISTS 'SaveUserComment';
DROP VIEW IF EXISTS 'SaveUserAscendant';
DROP VIEW IF EXISTS 'SaveUserAll';
DROP VIEW IF EXISTS 'SaveUserAllAscendant';

CREATE VIEW SaveStory AS
SELECT SA.*, S.userid, S.savedat
FROM Save S
NATURAL JOIN StoryAll SA -- on entityid
ORDER BY S.entityid ASC;

CREATE VIEW SaveComment AS
SELECT CA.*, S.userid, S.savedat
FROM Save S
NATURAL JOIN CommentAll CA -- on entityid
ORDER BY S.entityid ASC;

CREATE VIEW SaveAscendant AS
SELECT SA.*, CA.entityid AS commentid, S.userid, S.savedat
FROM Save S
NATURAL JOIN CommentAll CA -- on entityid
JOIN StoryAll SA ON SA.entityid = CA.storyid
ORDER BY S.entityid ASC;

CREATE VIEW SaveAll AS
SELECT AE.*, S.userid, S.savedat
FROM Save S
NATURAL JOIN AnyEntityAll AE
ORDER BY S.entityid ASC;

CREATE VIEW SaveAllAscendant AS
SELECT SA.*, AE.entityid AS commentid, S.userid, S.savedat
FROM Save S
NATURAL JOIN AnyEntityAll AE
LEFT JOIN StoryAll SA ON SA.entityid = AE.storyid
ORDER BY S.entityid ASC;

CREATE VIEW SaveUser AS
SELECT S.entityid, S.savedat, U.*
FROM Save S
NATURAL JOIN UserProfile U -- on userid
ORDER BY S.entityid ASC;

CREATE VIEW SaveUserStory AS
SELECT SS.*, V.vote
FROM SaveStory SS
NATURAL LEFT JOIN Vote V -- on entityid & userid
ORDER BY SS.userid ASC;

CREATE VIEW SaveUserComment AS
SELECT SC.*, V.vote
FROM SaveComment SC
NATURAL LEFT JOIN Vote V -- on entityid & userid
ORDER BY SC.userid ASC;

CREATE VIEW SaveUserAscendant AS
SELECT SA.*, V.vote
FROM SaveAscendant SA
NATURAL LEFT JOIN Vote V -- on entityid of story & userid
ORDER BY SA.userid ASC;

CREATE VIEW SaveUserAll AS
SELECT SE.*, V.vote
FROM SaveAll SE
NATURAL LEFT JOIN Vote V -- on entityid & userid
ORDER BY SE.userid ASC;

CREATE VIEW SaveUserAllAscendant AS
SELECT SAA.*, V.vote
FROM SaveAllAscendant SAA
NATURAL LEFT JOIN Vote V -- on entityid of story & userid
ORDER BY SAA.userid ASC;


/**
 * Vote Views
 */
DROP VIEW IF EXISTS 'UserVote';

CREATE VIEW UserVote AS -- complicated
SELECT U.userid, E.entityid, V.vote
FROM Entity E
JOIN UserClean U
LEFT JOIN Vote V ON V.userid = U.userid AND V.entityid = E.entityid
ORDER BY U.userid ASC, E.entityid ASC;

/**
 * ~~~~~~~~~~~~~~~~~~~~~~~
 * ~~~~~ Table Sorts ~~~~~
 * ~~~~~~~~~~~~~~~~~~~~~~~
 * Sorts for Story, Comment and Tree.
 */

/**
 * Sorted Story Views
 */
DROP VIEW IF EXISTS 'StorySortTop';
DROP VIEW IF EXISTS 'StorySortBot';
DROP VIEW IF EXISTS 'StorySortNew';
DROP VIEW IF EXISTS 'StorySortOld';
DROP VIEW IF EXISTS 'StorySortBest';
DROP VIEW IF EXISTS 'StorySortControversial';
DROP VIEW IF EXISTS 'StorySortHot';
DROP VIEW IF EXISTS 'StorySortAverage';

CREATE VIEW StorySortTop AS
SELECT SA.*, score as rating
FROM StoryAll SA
ORDER BY rating DESC;

CREATE VIEW StorySortBot AS
SELECT SA.*, -score as rating
FROM StoryAll SA
ORDER BY rating DESC;

CREATE VIEW StorySortNew AS
SELECT SA.*, createdat as rating
FROM StoryAll SA
ORDER BY rating DESC;

CREATE VIEW StorySortOld AS
SELECT SA.*, -createdat as rating
FROM StoryAll SA
ORDER BY rating DESC;

CREATE VIEW StorySortBest AS
SELECT SA.*, WILSONLOWERBOUND(upvotes, downvotes) AS rating
FROM StoryAll SA
ORDER BY rating DESC;

CREATE VIEW StorySortControversial AS
SELECT SA.*, REDDITCONTROVERSIAL(upvotes, downvotes) AS rating
FROM StoryAll SA
ORDER BY rating DESC;

CREATE VIEW StorySortHot AS
SELECT SA.*, REDDITHOT(upvotes, downvotes, createdat) AS rating
FROM StoryAll SA
ORDER BY rating DESC;

CREATE VIEW StorySortAverage AS
SELECT SA.*, CAST(upvotes + 1 AS float) / CAST(upvotes + downvotes + 1 AS float) AS rating
FROM StoryAll SA
ORDER BY rating DESC;

/**
 * Sorted Comment Views
 */
DROP VIEW IF EXISTS 'CommentSortTop';
DROP VIEW IF EXISTS 'CommentSortBot';
DROP VIEW IF EXISTS 'CommentSortNew';
DROP VIEW IF EXISTS 'CommentSortOld';
DROP VIEW IF EXISTS 'CommentSortBest';
DROP VIEW IF EXISTS 'CommentSortControversial';
DROP VIEW IF EXISTS 'CommentSortHot';
DROP VIEW IF EXISTS 'CommentSortAverage';

CREATE VIEW CommentSortTop AS
SELECT CA.*, score as rating
FROM CommentAll CA
ORDER BY rating DESC;

CREATE VIEW CommentSortBot AS
SELECT CA.*, -score as rating
FROM CommentAll CA
ORDER BY rating DESC;

CREATE VIEW CommentSortNew AS
SELECT CA.*, createdat as rating
FROM CommentAll CA
ORDER BY rating DESC;

CREATE VIEW CommentSortOld AS
SELECT CA.*, -createdat as rating
FROM CommentAll CA
ORDER BY rating DESC;

CREATE VIEW CommentSortBest AS
SELECT CA.*, WILSONLOWERBOUND(upvotes, downvotes) AS rating
FROM CommentAll CA
ORDER BY rating DESC;

CREATE VIEW CommentSortControversial AS
SELECT CA.*, REDDITCONTROVERSIAL(upvotes, downvotes) AS rating
FROM CommentAll CA
ORDER BY rating DESC;

CREATE VIEW CommentSortHot AS
SELECT CA.*, REDDITHOT(upvotes, downvotes, createdat) AS rating
FROM CommentAll CA
ORDER BY rating DESC;

CREATE VIEW CommentSortAverage AS
SELECT CA.*, CAST(upvotes + 1 AS float) / CAST(upvotes + downvotes + 1 AS float) AS rating
FROM CommentAll CA
ORDER BY rating DESC;
/**
 * Sorted CommentTree Views
 */
DROP VIEW IF EXISTS 'CommentTreeSortTop';
DROP VIEW IF EXISTS 'CommentTreeSortBot';
DROP VIEW IF EXISTS 'CommentTreeSortNew';
DROP VIEW IF EXISTS 'CommentTreeSortOld';
DROP VIEW IF EXISTS 'CommentTreeSortBest';
DROP VIEW IF EXISTS 'CommentTreeSortControversial';
DROP VIEW IF EXISTS 'CommentTreeSortHot';
DROP VIEW IF EXISTS 'CommentTreeSortAverage';

CREATE VIEW CommentTreeSortTop AS
SELECT CT.*, score as rating
FROM CommentTree CT
ORDER BY rating DESC;

CREATE VIEW CommentTreeSortBot AS
SELECT CT.*, -score as rating
FROM CommentTree CT
ORDER BY rating DESC;

CREATE VIEW CommentTreeSortNew AS
SELECT CT.*, createdat as rating
FROM CommentTree CT
ORDER BY rating DESC;

CREATE VIEW CommentTreeSortOld AS
SELECT CT.*, -createdat as rating
FROM CommentTree CT
ORDER BY rating DESC;

CREATE VIEW CommentTreeSortBest AS
SELECT CT.*, WILSONLOWERBOUND(upvotes, downvotes) AS rating
FROM CommentTree CT
ORDER BY rating DESC;

CREATE VIEW CommentTreeSortControversial AS
SELECT CT.*, REDDITCONTROVERSIAL(upvotes, downvotes) AS rating
FROM CommentTree CT
ORDER BY rating DESC;

CREATE VIEW CommentTreeSortHot AS
SELECT CT.*, REDDITHOT(upvotes, downvotes, createdat) AS rating
FROM CommentTree CT
ORDER BY rating DESC;

CREATE VIEW CommentTreeSortAverage AS
SELECT CT.*, CAST(upvotes + 1 AS float) / CAST(upvotes + downvotes + 1 AS float) AS rating
FROM CommentTree CT
ORDER BY rating DESC;
