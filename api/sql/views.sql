/**
 * Entity Views
 */
DROP VIEW IF EXISTS 'StoryEntity';
DROP VIEW IF EXISTS 'CommentEntity';
DROP VIEW IF EXISTS 'StoryAll';
DROP VIEW IF EXISTS 'CommentAll';
DROP VIEW IF EXISTS 'ParentEntity';

CREATE VIEW StoryEntity AS
SELECT *, 'story' type
FROM Story S
NATURAL JOIN Entity E;

CREATE VIEW CommentEntity AS
SELECT *, 'comment' type
FROM Comment C
NATURAL JOIN Entity E;

CREATE VIEW StoryAll AS
SELECT SE.*, A.authorname, C.channelname
FROM StoryEntity SE
LEFT JOIN Author A ON SE.authorid = A.authorid
LEFT JOIN Channel C ON SE.channelid = C.channelid;

CREATE VIEW CommentAll AS
SELECT CE.*, A.authorname
FROM CommentEntity CE
LEFT JOIN Author A ON CE.authorid = A.authorid;

CREATE VIEW ParentEntity as
SELECT entityid as parentid, createdat, updatedat, upvotes, downvotes
FROM Entity E;

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
SELECT userid as authorid, username as authorname
FROM User U
ORDER BY U.userid;

CREATE VIEW Creator AS
SELECT userid as creatorid, username as creatorname
FROM User U
ORDER BY U.userid;
