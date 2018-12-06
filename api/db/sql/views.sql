/**
 * Entity Views
 */
DROP VIEW IF EXISTS 'StoryEntity';
DROP VIEW IF EXISTS 'CommentEntity';
DROP VIEW IF EXISTS 'StoryAll';
DROP VIEW IF EXISTS 'CommentAll';
DROP VIEW IF EXISTS 'ParentEntity';

CREATE VIEW StoryEntity AS
SELECT *, 'story' type FROM Story NATURAL JOIN Entity;

CREATE VIEW CommentEntity AS
SELECT *, 'comment' type FROM Comment NATURAL JOIN Entity;

CREATE VIEW StoryAll AS
SELECT * FROM StoryEntity NATURAL JOIN Author NATURAL JOIN Channel;

CREATE VIEW CommentAll AS
SELECT * FROM CommentEntity NATURAL JOIN Author;

CREATE VIEW ParentEntity as
SELECT entityid as parentid, createdat, updatedat, upvotes, downvotes FROM Entity;

/**
 * Save Views
 */
DROP VIEW IF EXISTS 'SaveStory';
DROP VIEW IF EXISTS 'SaveComment';

CREATE VIEW SaveStory AS
SELECT * FROM Save NATURAL JOIN StoryAll;

CREATE VIEW SaveComment AS
SELECT * FROM Save NATURAL JOIN CommentAll;

/**
 * User Views
 */
DROP VIEW IF EXISTS 'UserNohash';
DROP VIEW IF EXISTS 'Author';
DROP VIEW IF EXISTS 'Creator';

CREATE VIEW UserNohash AS
SELECT userid, username, email FROM User;

CREATE VIEW Author AS
SELECT userid as authorid, username as authorname FROM User;

CREATE VIEW Creator AS
SELECT userid as creatorid, username as creatorname FROM User;
