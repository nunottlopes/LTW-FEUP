/**
 * User Views
 */
DROP VIEW IF EXISTS 'UserClean';
DROP VIEW IF EXISTS 'Author';
DROP VIEW IF EXISTS 'Creator';

CREATE VIEW UserClean AS
SELECT userid, username, email, admin
FROM User U
ORDER BY U.userid;

CREATE VIEW Author AS
SELECT userid AS authorid, username AS authorname, admin
FROM User U
ORDER BY U.userid;

CREATE VIEW Creator AS
SELECT userid AS creatorid, username AS creatorname, admin
FROM User U
ORDER BY U.userid;

/**
 * Entity Views
 */
DROP VIEW IF EXISTS 'ParentEntity';
DROP VIEW IF EXISTS 'ChildEntity';
DROP VIEW IF EXISTS 'AscendantEntity';
DROP VIEW IF EXISTS 'DescendantEntity';

CREATE VIEW ParentEntity AS
SELECT entityid AS parentid, authorid, createdat, updatedat, upvotes, downvotes
FROM Entity E
ORDER BY parentid ASC;

CREATE VIEW ChildEntity AS
SELECT entityid AS childid, authorid, createdat, updatedat, upvotes, downvotes
FROM Entity E
ORDER BY childid ASC;

CREATE VIEW AscendantEntity AS
SELECT entityid AS ascendantid, authorid, createdat, updatedat, upvotes, downvotes
FROM Entity E
ORDER BY ascendantid ASC;

CREATE VIEW DescendantEntity AS
SELECT entityid AS descendantid, authorid, createdat, updatedat, upvotes, downvotes
FROM Entity E
ORDER BY descendantid ASC;

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
SELECT SE.*, SE.entityid as storyid, A.authorname, C.channelname,
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
SELECT *, score as rating
FROM StoryAll SA
ORDER BY score DESC;

CREATE VIEW StorySortBot AS
SELECT *, -score as rating
FROM StoryAll SA
ORDER BY score ASC;

CREATE VIEW StorySortNew AS
SELECT *, created as rating
FROM StoryAll SA
ORDER BY createdat DESC;

CREATE VIEW StorySortOld AS
SELECT *, -createdat as rating
FROM StoryAll SA
ORDER BY createdat ASC;

CREATE VIEW StorySortBest AS
SELECT *, WILSONLOWERBOUND(upvotes, downvotes) AS rating
FROM StoryAll SA
ORDER BY rating DESC;

CREATE VIEW StorySortControversial AS
SELECT *, REDDITCONTROVERSIAL(upvotes, downvotes) AS rating
FROM StoryAll SA
ORDER BY rating DESC;

CREATE VIEW StorySortHot AS
SELECT *, REDDITHOT(upvotes, downvotes, createdat) AS rating
FROM StoryAll SA
ORDER BY rating DESC;

CREATE VIEW StorySortAverage AS
SELECT *, (upvotes + 1) / (upvotes + downvotes + 1) AS rating
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
ORDER BY entityid;

CREATE VIEW CommentAll AS
SELECT CE.*, CE.entityid as commentid, A.authorname,
    (SELECT count(*) FROM Tree T WHERE T.ascendantid = CE.entityid) count,
    (SELECT count(*) FROM Tree T WHERE T.descendantid = CE.entityid) level
FROM CommentEntity CE
LEFT JOIN Author A ON CE.authorid = A.authorid
ORDER BY entityid;

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
SELECT *, score as rating
FROM CommentAll CA
ORDER BY score DESC;

CREATE VIEW CommentSortBot AS
SELECT *, -score as rating
FROM CommentAll CA
ORDER BY score ASC;

CREATE VIEW CommentSortNew AS
SELECT *, createdat as rating
FROM CommentAll CA
ORDER BY createdat DESC;

CREATE VIEW CommentSortOld AS
SELECT *, -createdat as rating
FROM CommentAll CA
ORDER BY createdat ASC;

CREATE VIEW CommentSortBest AS
SELECT *, WILSONLOWERBOUND(upvotes, downvotes) AS rating
FROM CommentAll CA
ORDER BY rating DESC;

CREATE VIEW CommentSortControversial AS
SELECT *, REDDITCONTROVERSIAL(upvotes, downvotes) AS rating
FROM CommentAll CA
ORDER BY rating DESC;

CREATE VIEW CommentSortHot AS
SELECT *, REDDITHOT(upvotes, downvotes, createdat) AS rating
FROM CommentAll CA
ORDER BY rating DESC;

CREATE VIEW CommentSortAverage AS
SELECT *, (upvotes + 1) / (upvotes + downvotes + 1) AS rating
FROM CommentAll CA
ORDER BY rating DESC;

/**
 * Tree Views
 */
DROP VIEW IF EXISTS 'EntityAscendantTree';
DROP VIEW IF EXISTS 'EntityDescendantTree';
DROP VIEW IF EXISTS 'CommentAncestryTree';
DROP VIEW IF EXISTS 'CommentTree';
DROP VIEW IF EXISTS 'StoryTree';

CREATE VIEW EntityAscendantTree AS
SELECT T.descendantid, E.*, T.depth
FROM Tree T
JOIN Entity E ON T.ascendantid = E.entityid
ORDER BY T.ascendantid ASC;

CREATE VIEW EntityDescendantTree AS
SELECT T.ascendantid, E.*, T.depth
FROM Tree T
JOIN Entity E ON T.descendantid = E.entityid
ORDER BY T.descendantid ASC;

CREATE VIEW CommentAncestryTree AS
SELECT T.descendantid, CA.*, T.depth
FROM Tree T
JOIN CommentAll CA ON T.ascendantid = CA.entityid
ORDER BY T.ascendantid ASC;

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
SELECT *, score as rating
FROM CommentTree CT
ORDER BY score DESC;

CREATE VIEW CommentTreeSortBot AS
SELECT *, -score as rating
FROM CommentTree CT
ORDER BY score ASC;

CREATE VIEW CommentTreeSortNew AS
SELECT *, createdat as rating
FROM CommentTree CT
ORDER BY createdat DESC;

CREATE VIEW CommentTreeSortOld AS
SELECT *, -createdat as rating
FROM CommentTree CT
ORDER BY createdat ASC;

CREATE VIEW CommentTreeSortBest AS
SELECT *, WILSONLOWERBOUND(upvotes, downvotes) AS rating
FROM CommentTree CT
ORDER BY rating DESC;

CREATE VIEW CommentTreeSortControversial AS
SELECT *, REDDITCONTROVERSIAL(upvotes, downvotes) AS rating
FROM CommentTree CT
ORDER BY rating DESC;

CREATE VIEW CommentTreeSortHot AS
SELECT *, REDDITHOT(upvotes, downvotes, createdat) AS rating
FROM CommentTree CT
ORDER BY rating DESC;

CREATE VIEW CommentTreeSortAverage AS
SELECT *, (upvotes + 1) / (upvotes + downvotes + 1) AS rating
FROM CommentTree CT
ORDER BY rating DESC;

/*
WITH Choice(ascendantid) AS (
    VALUES (1)
),   Best(entityid) AS (
    SELECT entityid
    FROM CommentTreeSortTop CT
    WHERE CT.ascendantid IN Choice
    AND depth <= 5
    LIMIT 1 OFFSET 2
),   BestAncestry(entityid) AS (
    SELECT Tree.ascendantid FROM Tree
    WHERE Tree.descendantid IN Best
)
SELECT *
FROM CommentTreeSortTop
WHERE ascendantid IN Choice
AND (entityid IN BestAncestry OR entityid IN Best)
ORDER BY depth ASC, rating DESC;

WITH Choice(ascendantid) AS (
    VALUES (1))
SELECT *
FROM CommentTreeSortTop
WHERE entityid IN (
    WITH Best(entityid) AS (
        SELECT entityid
        FROM CommentTreeSortTop CT
        WHERE CT.ascendantid IN Choice
        LIMIT 1 OFFSET 0)
    SELECT Tree.ascendantid FROM Tree
    WHERE Tree.descendantid IN BEST
    UNION
    SELECT entityid FROM Best
) AND ascendantid IN Choice
ORDER BY depth;
*/
