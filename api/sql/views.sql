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
 * Vote Views
 */
DROP VIEW IF EXISTS 'Voting';

CREATE VIEW Voting AS
SELECT U.userid, E.entityid, V.vote,
    CASE S.savedat NOTNULL WHEN 1 THEN 1 ELSE NULL END save
FROM Entity E
JOIN User U
LEFT JOIN Vote V ON V.userid = U.userid AND V.entityid = E.entityid
LEFT JOIN Save S ON S.userid = U.userid AND S.entityid = E.entityid
ORDER BY U.userid ASC, E.entityid ASC;

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
FROM ChannelView C
LEFT JOIN Banner B ON C.imageid = B.bannerid
ORDER BY C.channelid ASC;

CREATE VIEW ChannelCreator AS
SELECT C.channelid, C.channelname, Cr.*
FROM ChannelView C
NATURAL LEFT JOIN Creator Cr -- on creatorid
ORDER BY C.channelid ASC;

CREATE VIEW ChannelAll AS
SELECT C.channelid, C.channelname, B.*, Cr.*
FROM ChannelView C
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
SELECT *, 'story' type, E.upvotes - E.downvotes AS score,
    (SELECT count(*) FROM Tree T WHERE T.ascendantid = SA.entityid) count
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

CREATE VIEW StoryAll AS
SELECT *
FROM StoryEntity SE
NATURAL LEFT JOIN ImageImage I -- on imageid
NATURAL LEFT JOIN Author A -- on authorid
NATURAL JOIN ChannelBanner CB -- on channelid
ORDER BY SE.entityid ASC;

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
 * Story Voted Views
 */
DROP VIEW IF EXISTS 'StoryVotingEntity';
DROP VIEW IF EXISTS 'StoryVotingImage';
DROP VIEW IF EXISTS 'StoryVotingAuthor';
DROP VIEW IF EXISTS 'StoryVotingChannel';
DROP VIEW IF EXISTS 'StoryVotingImageAuthor';
DROP VIEW IF EXISTS 'StoryVotingImageChannel';
DROP VIEW IF EXISTS 'StoryVotingAuthorChannel';
DROP VIEW IF EXISTS 'StoryVotingImageAuthorChannel';
DROP VIEW IF EXISTS 'StoryVotingAll';

CREATE VIEW StoryVotingEntity AS
SELECT S.*, V.userid, V.vote, V.save
FROM StoryEntity S
NATURAL JOIN Voting V -- on entityid
ORDER BY S.entityid ASC;

CREATE VIEW StoryVotingImage AS
SELECT S.*, V.userid, V.vote, V.save
FROM StoryImage S
NATURAL JOIN Voting V -- on entityid
ORDER BY S.entityid ASC;

CREATE VIEW StoryVotingAuthor AS
SELECT S.*, V.userid, V.vote, V.save
FROM StoryAuthor S
NATURAL JOIN Voting V -- on entityid
ORDER BY S.entityid ASC;

CREATE VIEW StoryVotingChannel AS
SELECT S.*, V.userid, V.vote, V.save
FROM StoryChannel S
NATURAL JOIN Voting V -- on entityid
ORDER BY S.entityid ASC;

CREATE VIEW StoryVotingImageAuthor AS
SELECT S.*, V.userid, V.vote, V.save
FROM StoryImageAuthor S
NATURAL JOIN Voting V -- on entityid
ORDER BY S.entityid ASC;

CREATE VIEW StoryVotingImageChannel AS
SELECT S.*, V.userid, V.vote, V.save
FROM StoryImageChannel S
NATURAL JOIN Voting V -- on entityid
ORDER BY S.entityid ASC;

CREATE VIEW StoryVotingAuthorChannel AS
SELECT S.*, V.userid, V.vote, V.save
FROM StoryAuthorChannel S
NATURAL JOIN Voting V -- on entityid
ORDER BY S.entityid ASC;

CREATE VIEW StoryVotingImageAuthorChannel AS
SELECT S.*, V.userid, V.vote, V.save
FROM StoryImageAuthorChannel S
NATURAL JOIN Voting V -- on entityid
ORDER BY S.entityid ASC;

CREATE VIEW StoryVotingAll AS
SELECT S.*, V.userid, V.vote, V.save
FROM StoryAll S
NATURAL JOIN Voting V -- on entityid
ORDER BY S.entityid ASC;

/**
 * Comment Voted Views
 */
DROP VIEW IF EXISTS 'CommentVotingEntity';
DROP VIEW IF EXISTS 'CommentVotingExtra';
DROP VIEW IF EXISTS 'CommentVotingAuthor';
DROP VIEW IF EXISTS 'CommentVotingAll';

CREATE VIEW CommentVotingEntity AS
SELECT C.*, V.userid, V.vote, V.save
FROM CommentEntity C
NATURAL JOIN Voting V -- on entityid
ORDER BY C.entityid ASC;

CREATE VIEW CommentVotingExtra AS
SELECT C.*, V.userid, V.vote, V.save
FROM CommentExtra C
NATURAL JOIN Voting V -- on entityid
ORDER BY C.entityid ASC;

CREATE VIEW CommentVotingAuthor AS
SELECT C.*, V.userid, V.vote, V.save
FROM CommentAuthor C
NATURAL JOIN Voting V -- on entityid
ORDER BY C.entityid ASC;

CREATE VIEW CommentVotingAll AS
SELECT C.*, V.userid, V.vote, V.save
FROM CommentAll C
NATURAL JOIN Voting V -- on entityid
ORDER BY C.entityid ASC;

/**
 * Entity Views
 */
DROP VIEW IF EXISTS 'AnyEntityVoting';
DROP VIEW IF EXISTS 'AnyEntityVotingAuthor';
DROP VIEW IF EXISTS 'AnyEntityVotingAll';

CREATE VIEW AnyEntityVoting AS
SELECT AE.*, V.userid, V.vote, V.save
FROM AnyEntity AE
NATURAL JOIN Voting V
ORDER BY AE.entityid ASC;

CREATE VIEW AnyEntityVotingAuthor AS
SELECT AE.*, V.userid, V.vote, V.save
FROM AnyEntityAuthor AE
NATURAL JOIN Voting V
ORDER BY AE.entityid ASC;

CREATE VIEW AnyEntityVotingAll AS
SELECT AE.*, V.userid, V.vote, V.save
FROM AnyEntityAll AE
NATURAL JOIN Voting V
ORDER BY AE.entityid ASC;

/**
 * Tree Voted Views
 */
DROP VIEW IF EXISTS 'CommentAncestryVotingTree';
DROP VIEW IF EXISTS 'CommentVotingTree';
DROP VIEW IF EXISTS 'StoryVotingTree';

CREATE VIEW CommentAncestryVotingTree AS
SELECT T.*, V.userid, V.vote, V.save
FROM CommentAncestryTree T
NATURAL JOIN Voting V -- on entityid
ORDER BY T.level ASC;

CREATE VIEW CommentVotingTree AS
SELECT T.*, V.userid, V.vote, V.save
FROM CommentTree T
NATURAL JOIN Voting V -- on entityid
ORDER BY T.entityid ASC;

CREATE VIEW StoryVotingTree AS
SELECT T.*, V.userid, V.vote, V.save
FROM StoryTree T
NATURAL JOIN Voting V -- on entityid
ORDER BY T.commentid ASC;

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
 * ~~~~~~~~~~~~~~~~~~~~~~~
 * ~~~~~ Table Sorts ~~~~~
 * ~~~~~~~~~~~~~~~~~~~~~~~
 * THIS WAS REFACTORED IN PHP.
 * THESE VIEWS BELOW ARE NO LONGER USED.
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

/**
 * The verbose and complex query in entity Tree
 * at functions getAllDescendants and getAllDescendantsVoted
 */
-- Testing getAllDescendants on story #1
WITH Choice(ascendantid) AS (
    VALUES (1)
), Best(entityid) AS (
    SELECT entityid
    FROM CommentTree
    WHERE ascendantid IN Choice
    AND depth <= ? AND createdat >= ?
    ORDER BY $sort DESC
    LIMIT ? OFFSET ?
), BestAncestry(entityid) AS (
    SELECT Tree.ascendantid FROM Tree
    WHERE Tree.descendantid IN Best
)
SELECT *, $sort AS rating
FROM CommentTree
WHERE ascendantid IN Choice
AND (entityid IN BestAncestry OR entityid IN Best)
ORDER BY depth ASC, rating DESC, createdat DESC, entityid ASC;

-- Testing getAllDescendantsVoted on story #1
WITH Choice(ascendantid) AS (
    VALUES (1)
), Best(entityid) AS (
    SELECT entityid
    FROM CommentTree CT
    WHERE CT.ascendantid IN Choice
    AND depth <= ? AND createdat >= ?
    ORDER BY $sort DESC
    LIMIT ? OFFSET ?
), BestAncestry(entityid) AS (
    SELECT Tree.ascendantid FROM Tree
    WHERE Tree.descendantid IN Best
)
SELECT *, $sort AS rating
FROM CommentVotingTree
WHERE ascendantid IN Choice AND userid = ?
AND (entityid IN BestAncestry OR entityid IN Best)
ORDER BY depth ASC, rating DESC, createdat DESC, entityid ASC;
