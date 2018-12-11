/**
 * Entity Views
 */
DROP VIEW IF EXISTS 'ParentEntity';

CREATE VIEW ParentEntity AS
SELECT entityid AS parentid, createdat, updatedat, upvotes, downvotes
FROM Entity E
ORDER BY parentid ASC;

/**
 * Story Views
 */
DROP VIEW IF EXISTS 'StoryEntity';
DROP VIEW IF EXISTS 'StoryAll';
DROP VIEW IF EXISTS 'StorySortTop';
DROP VIEW IF EXISTS 'StorySortBot';
DROP VIEW IF EXISTS 'StorySortBest';
DROP VIEW IF EXISTS 'StorySortWorst';
DROP VIEW IF EXISTS 'StorySortAverage';
DROP VIEW IF EXISTS 'StorySortNew';
DROP VIEW IF EXISTS 'StorySortOld';

CREATE VIEW StoryEntity AS
SELECT *, 'story' type, E.upvotes - E.downvotes AS score
FROM Story S
NATURAL JOIN Entity E
ORDER BY entityid ASC;

CREATE VIEW StoryAll AS
SELECT SE.*, A.authorname, C.channelname
FROM StoryEntity SE
LEFT JOIN Author A ON SE.authorid = A.authorid
LEFT JOIN Channel C ON SE.channelid = C.channelid
ORDER BY entityid ASC;

CREATE VIEW StorySortTop AS
SELECT *
FROM StoryAll
ORDER BY score DESC;

CREATE VIEW StorySortBot AS
SELECT *
FROM StoryAll
ORDER BY score ASC;

CREATE VIEW StorySortHot AS
SELECT *, REDDITHOT(upvotes, downvotes, createdat) AS rating
FROM StoryAll
WHERE upvotes + downvotes > 0
ORDER BY rating DESC;

CREATE VIEW StorySortNew AS
SELECT *
FROM StoryAll
ORDER BY createdat DESC;

CREATE VIEW StorySortOld AS
SELECT *
FROM StoryAll
ORDER BY createdat ASC;

/**
 * Comment Views
 */
DROP VIEW IF EXISTS 'CommentEntity';
DROP VIEW IF EXISTS 'CommentAll';
DROP VIEW IF EXISTS 'CommentSortTop';
DROP VIEW IF EXISTS 'CommentSortBot';
DROP VIEW IF EXISTS 'CommentSortBest';
DROP VIEW IF EXISTS 'CommentSortControversial';
DROP VIEW IF EXISTS 'CommentSortAverage';
DROP VIEW IF EXISTS 'CommentSortNew';
DROP VIEW IF EXISTS 'CommentSortOld';

CREATE VIEW CommentEntity AS
SELECT *, 'comment' type, E.upvotes - E.downvotes AS score
FROM Comment C
NATURAL JOIN Entity E;

CREATE VIEW CommentAll AS
SELECT CE.*, A.authorname
FROM CommentEntity CE
LEFT JOIN Author A ON CE.authorid = A.authorid;

CREATE VIEW CommentSortTop AS
SELECT *
FROM CommentAll
ORDER BY score DESC;

CREATE VIEW CommentSortBot AS
SELECT *
FROM CommentAll
ORDER BY score ASC;

CREATE VIEW CommentSortBest AS
SELECT *, WILSONLOWERBOUND(upvotes, downvotes) AS rating
FROM CommentAll
WHERE upvotes + downvotes > 0
ORDER BY rating DESC;

CREATE VIEW CommentSortControversial AS
SELECT *, (upvotes + downvotes) / MAX(ABS(upvotes - downvotes), 1) AS rating
FROM CommentAll
WHERE upvotes + downvotes > 0
ORDER BY rating DESC;

CREATE VIEW CommentSortAverage AS
SELECT *, (upvotes + 1) / (upvotes + downvotes + 1) AS rating
FROM CommentAll
WHERE upvotes + downvotes > 0
ORDER BY rating DESC;

CREATE VIEW CommentSortNew AS
SELECT *
FROM CommentAll
ORDER BY createdat DESC;

CREATE VIEW CommentSortOld AS
SELECT *
FROM CommentAll
ORDER BY createdat ASC;

/**
 * Save Views
 */
DROP VIEW IF EXISTS 'SaveStory';
DROP VIEW IF EXISTS 'SaveComment';

CREATE VIEW SaveStory AS
SELECT *
FROM Save S
NATURAL JOIN StoryAll SA;

CREATE VIEW SaveComment AS
SELECT *
FROM Save S
NATURAL JOIN CommentAll CA;

/**
 * User Views
 */
DROP VIEW IF EXISTS 'UserNohash';
DROP VIEW IF EXISTS 'Author';
DROP VIEW IF EXISTS 'Creator';

CREATE VIEW UserNohash AS
SELECT userid, username, email
FROM User U
ORDER BY U.userid;

CREATE VIEW Author AS
SELECT userid AS authorid, username AS authorname
FROM User U
ORDER BY U.userid;

CREATE VIEW Creator AS
SELECT userid AS creatorid, username AS creatorname
FROM User U
ORDER BY U.userid;
