DROP TRIGGER IF EXISTS InsertStory;
DROP TRIGGER IF EXISTS InsertComment;
DROP TRIGGER IF EXISTS UpdateStory;
DROP TRIGGER IF EXISTS UpdateComment;
DROP TRIGGER IF EXISTS DeleteStory;
DROP TRIGGER IF EXISTS DeleteComment;
DROP TRIGGER IF EXISTS InsertVoteBefore;
DROP TRIGGER IF EXISTS InsertVoteAfter;
DROP TRIGGER IF EXISTS UpdateVote;
DROP TRIGGER IF EXISTS DeleteVote;

CREATE TRIGGER InsertStory
AFTER INSERT ON Story
FOR EACH ROW
BEGIN
    /**
     * Create an entity entry in the entity table after an insert on this table
     */
    INSERT INTO Entity(entityid)
    VALUES (NULL);

    UPDATE Story
    SET entityid = (SELECT max(entityid) FROM Entity)
    WHERE rowid = NEW.rowid;

    /**
     * Upvote own story
     */
    INSERT INTO Vote(entityid, userid, vote)
    VALUES ((SELECT max(entityid) FROM Entity), NEW.authorid, '+');
END;

CREATE TRIGGER InsertComment
AFTER INSERT ON Comment
FOR EACH ROW
BEGIN
    /**
     * Create an entity entry in the entity table after an insert on this table
     */
    INSERT INTO Entity(entityid)
    VALUES (NULL);

    UPDATE Comment
    SET entityid = (SELECT max(entityid) FROM Entity)
    WHERE rowid = NEW.rowid;

    /**
     * Upvote own comment
     */
    INSERT INTO Vote(entityid, userid, vote)
    VALUES ((SELECT max(entityid) FROM Entity), NEW.authorid, '+');
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
