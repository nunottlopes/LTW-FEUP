/**
 * User Views
 */
DROP VIEW IF EXISTS 'UserClean';
DROP VIEW IF EXISTS 'Author';
DROP VIEW IF EXISTS 'Creator';

CREATE VIEW UserClean AS
SELECT userid, username, email, admin
FROM User U
ORDER BY userid ASC;

CREATE VIEW Author AS
SELECT userid AS authorid, username AS authorname
FROM User U
ORDER BY authorid ASC;

CREATE VIEW Creator AS
SELECT userid AS creatorid, username AS creatorname
FROM User U
ORDER BY creatorid ASC;

/**
 * Channel Views
 */
DROP VIEW IF EXISTS 'ChannelCreator';

CREATE VIEW ChannelCreator AS
SELECT Ch.channelid, Ch.channelname, Cr.creatorid, Cr.creatorname
FROM Channel Ch
NATURAL JOIN Creator Cr
ORDER BY channelid ASC;

/**
 * Story Views
 */
DROP VIEW IF EXISTS 'StoryEntity';
DROP VIEW IF EXISTS 'StoryAll';

CREATE VIEW StoryEntity AS
SELECT *, 'story' type, E.upvotes - E.downvotes AS score
FROM Story S
NATURAL JOIN Entity E
ORDER BY entityid ASC;

CREATE VIEW StoryAll AS
SELECT SE.*, A.authorname, C.channelname,
       (SELECT count(*) FROM Tree T WHERE T.ascendantid = SE.entityid) count
FROM StoryEntity SE
LEFT JOIN Author A ON SE.authorid = A.authorid
LEFT JOIN Channel C ON SE.channelid = C.channelid
ORDER BY entityid ASC;

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
 * Comment Views
 */
DROP VIEW IF EXISTS 'CommentEntity';
DROP VIEW IF EXISTS 'CommentAll';

CREATE VIEW CommentEntity AS
SELECT *, 'comment' type, E.upvotes - E.downvotes AS score
FROM Comment C
NATURAL JOIN Entity E
ORDER BY entityid ASC;

CREATE VIEW CommentAll AS
SELECT CE.*, A.authorname,
      (SELECT count(*) FROM Tree T WHERE T.ascendantid = CE.entityid) count,
      (SELECT count(*) FROM Tree T WHERE T.descendantid = CE.entityid) level,
      (SELECT ascendantid FROM Tree T WHERE T.descendantid = CE.entityid
       ORDER BY depth DESC LIMIT 1) storyid
FROM CommentEntity CE
LEFT JOIN Author A ON CE.authorid = A.authorid
ORDER BY entityid ASC;

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
SELECT T.descendantid commentid, SA.*, T.depth
FROM Tree T
JOIN StoryAll SA ON T.ascendantid = SA.entityid
ORDER BY commentid ASC;

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
 * Save views
 */
DROP VIEW IF EXISTS 'SaveStory';
DROP VIEW IF EXISTS 'SaveComment';
DROP VIEW IF EXISTS 'SaveUser';
DROP VIEW IF EXISTS 'SaveUserStory';
DROP VIEW IF EXISTS 'SaveUserComment';

CREATE VIEW SaveStory AS
SELECT SA.*, S.userid, S.savedat
FROM Save S
JOIN StoryAll SA ON S.entityid = SA.entityid
ORDER BY entityid ASC;

CREATE VIEW SaveComment AS
SELECT CA.*, S.userid, S.savedat
FROM Save S
JOIN CommentAll CA ON S.entityid = CA.entityid
ORDER BY entityid ASC;

CREATE VIEW SaveUser AS
SELECT S.entityid, U.*, S.savedat
FROM Save S
JOIN User U ON S.userid = U.userid
ORDER BY entityid ASC;

CREATE VIEW SaveUserStory AS
SELECT SS.*, coalesce(V.vote, '') vote
FROM SaveStory SS
NATURAL LEFT JOIN Vote V
ORDER BY userid ASC;

CREATE VIEW SaveUserComment AS
SELECT SC.*, coalesce(V.vote, '') vote
FROM SaveComment SC
NATURAL LEFT JOIN Vote V
ORDER BY userid ASC;

/**
 * Vote Views
 */
DROP VIEW IF EXISTS 'UserVote';

CREATE VIEW UserVote AS
SELECT U.userid, E.entityid, coalesce(V.vote, "") vote
FROM Entity E
JOIN UserClean U
LEFT JOIN Vote V ON V.userid = U.userid AND V.entityid = E.entityid
ORDER BY U.userid ASC, E.entityid;


SELECT ST.*, coalesce(V.vote, '') vote
FROM CommentSortTop ST JOIN UserVote V ON V.entityid = ST.entityid
WHERE parentid = 1 AND V.userid = ?
ORDER BY rating DESC;
