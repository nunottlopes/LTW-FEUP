DROP TRIGGER IF EXISTS DeleteEntity;
DROP TRIGGER IF EXISTS InsertComment;
DROP TRIGGER IF EXISTS InsertStory;
DROP TRIGGER IF EXISTS InsertTree;
DROP TRIGGER IF EXISTS UpdateStory;
DROP TRIGGER IF EXISTS UpdateComment;
DROP TRIGGER IF EXISTS DeleteStory;
DROP TRIGGER IF EXISTS DeleteComment;
DROP TRIGGER IF EXISTS InsertVoteBefore;
DROP TRIGGER IF EXISTS InsertVoteAfter;
DROP TRIGGER IF EXISTS UpdateVote;
DROP TRIGGER IF EXISTS DeleteVote;

/**
 * Recursively delete entity's descendants.
 * Delete upwards, so we must use a before trigger.
 */
CREATE TRIGGER DeleteEntity
BEFORE DELETE ON Entity
FOR EACH ROW
BEGIN
    DELETE FROM Entity
    WHERE Entity.entityid IN (
        SELECT Tree.descendantid
        FROM Tree
        WHERE Tree.ascendantid = OLD.entityid
    );
END;

/**
 * Add Entity entry and Tree entry on Comment insert.
 */
CREATE TRIGGER InsertComment
AFTER INSERT ON Comment
FOR EACH ROW
BEGIN
    INSERT INTO Entity(entityid) VALUES (NULL);

    UPDATE Comment
    SET entityid = (SELECT max(entityid) FROM Entity)
    WHERE rowid = NEW.rowid;

    INSERT INTO Vote(entityid, userid, vote)
    VALUES ((SELECT max(entityid) FROM Entity), NEW.authorid, '+');

    INSERT INTO Tree(ascendantid, descendantid, depth)
    VALUES (NEW.parentid, (SELECT max(entityid) FROM Entity), 1);
END;

/**
 * Add Entity entry and Tree entry on Comment insert.
 */
CREATE TRIGGER InsertStory
AFTER INSERT ON Story
FOR EACH ROW
BEGIN
    INSERT INTO Entity(entityid) VALUES (NULL);

    UPDATE Story
    SET entityid = (SELECT max(entityid) FROM Entity)
    WHERE rowid = NEW.rowid;

    INSERT INTO Vote(entityid, userid, vote)
    VALUES ((SELECT max(entityid) FROM Entity), NEW.authorid, '+');
END;

/**
 * Propagate Tree insert upwards
 */
CREATE TRIGGER InsertTree
AFTER INSERT ON Tree
FOR EACH ROW
WHEN NEW.depth = 1
BEGIN
    INSERT INTO Tree(ascendantid, descendantid, depth)
    SELECT Tree.ascendantid, NEW.descendantid, Tree.depth + 1
    FROM Tree
    WHERE Tree.descendantid = NEW.ascendantid;
END;

/**
 * Update the timestamp on the entity table whenever the story table is updated
 */
CREATE TRIGGER UpdateStory
AFTER UPDATE ON Story
FOR EACH ROW
BEGIN
    UPDATE Entity
    SET updatedat = strftime('%s', 'now')
    WHERE entityid = NEW.entityid;
END;

/**
 * Update the timestamp on the entity table whenever the comment table is updated
 */
CREATE TRIGGER UpdateComment
AFTER UPDATE ON Comment
FOR EACH ROW
BEGIN
    UPDATE Entity
    SET updatedat = strftime('%s', 'now')
    WHERE entityid = NEW.entityid;
END;

/**
 * Delete the entity row corresponding to a deleted story
 */
CREATE TRIGGER DeleteStory
AFTER DELETE ON Story
FOR EACH ROW
BEGIN
    DELETE FROM Entity
    WHERE entityid = OLD.entityid;
END;

/**
 * Delete the entity row corresponding to a deleted comment
 */
CREATE TRIGGER DeleteComment
AFTER DELETE ON Comment
FOR EACH ROW
BEGIN
    DELETE FROM Entity
    WHERE entityid = OLD.entityid;
END;

/**
 * Check if a vote already existed prior to an insert, delete it in that case
 * Essentially ON CONFLICT REPLACE but calls the DeleteVote trigger.
 */
CREATE TRIGGER InsertVoteBefore
BEFORE INSERT ON Vote
FOR EACH ROW
BEGIN
    DELETE FROM Vote
    WHERE entityid = NEW.entityid AND userid = NEW.userid;
END;

/**
 * Update the vote count on the entity table after a NEW vote
 */
CREATE TRIGGER InsertVoteAfter
AFTER INSERT ON Vote
FOR EACH ROW
BEGIN
    UPDATE Entity
    SET upvotes = CASE
            WHEN NEW.vote = '+' THEN upvotes + 1 ELSE upvotes
        END,
        downvotes = CASE
            WHEN NEW.vote = '-' THEN downvotes + 1 ELSE downvotes
        END
    WHERE entityid = NEW.entityid;
END;

/**
 * Update the vote count on the entity table on a vote update
 */
CREATE TRIGGER UpdateVote
AFTER UPDATE ON Vote
FOR EACH ROW
WHEN NEW.vote != OLD.vote
BEGIN
    UPDATE Entity
    SET upvotes = CASE
            WHEN NEW.vote = '+' AND OLD.vote = '-' THEN upvotes + 1
            WHEN NEW.vote = '-' AND OLD.vote = '+' THEN upvotes - 1
        END,
        downvotes = CASE
            WHEN NEW.vote = '+' AND OLD.vote = '-' THEN downvotes - 1
            WHEN NEW.vote = '-' AND OLD.vote = '+' THEN downvotes + 1
        END
    WHERE entityid = NEW.entityid;
END;

/**
 * Update the vote count on the entity table on a vote deletion
 */
CREATE TRIGGER DeleteVote
AFTER DELETE ON Vote
FOR EACH ROW
BEGIN
    UPDATE Entity
    SET upvotes = CASE
            WHEN OLD.vote = '+' THEN upvotes - 1 ELSE upvotes
        END,
        downvotes = CASE
            WHEN OLD.vote = '-' THEN downvotes - 1 ELSE downvotes
        END
    WHERE entityid = OLD.entityid;
END;
