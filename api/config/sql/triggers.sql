DROP TRIGGER IF EXISTS InsertStory;
DROP TRIGGER IF EXISTS InsertComment;
DROP TRIGGER IF EXISTS UpdateStory;
DROP TRIGGER IF EXISTS UpdateComment;
DROP TRIGGER IF EXISTS CreateVote;
DROP TRIGGER IF EXISTS UpdateVote;
DROP TRIGGER IF EXISTS DeleteVote;

CREATE TRIGGER InsertStory
AFTER INSERT ON story
FOR EACH ROW
BEGIN
    /**
     * Create an entity entry in the entity table after an insert on this table
     */
    INSERT INTO entity(entity_id)
    VALUES (NULL);

    UPDATE story
    SET entity_id = (SELECT max(entity_id) FROM entity)
    WHERE rowid = NEW.rowid;

    /**
     * Upvote own story
     */
    INSERT INTO vote(entity_id, user_id, kind)
    VALUES ((SELECT max(entity_id) FROM entity), NEW.user_id, '+');
END;

CREATE TRIGGER InsertComment
AFTER INSERT ON comment
FOR EACH ROW
BEGIN
    /**
     * Create an entity entry in the entity table after an insert on this table
     */
    INSERT INTO entity(entity_id)
    VALUES (NULL);

    UPDATE comment
    SET entity_id = (SELECT max(entity_id) FROM entity)
    WHERE rowid = NEW.rowid;

    /**
     * Upvote own comment
     */
    INSERT INTO vote(entity_id, user_id, kind)
    VALUES ((SELECT max(entity_id) FROM entity), NEW.user_id, '+');
END;

/**
 * Update the timestamp on the entity table whenever the story table is updated
 */
CREATE TRIGGER UpdateStory
AFTER UPDATE ON story
FOR EACH ROW
BEGIN
    UPDATE entity
    SET updated_at = CURRENT_TIMESTAMP
    WHERE entity_id = NEW.entity_id;
END;

/**
 * Update the timestamp on the entity table whenever the comment table is updated
 */
CREATE TRIGGER UpdateComment
AFTER UPDATE ON comment
FOR EACH ROW
BEGIN
    UPDATE entity
    SET updated_at = CURRENT_TIMESTAMP
    WHERE entity_id = NEW.entity_id;
END;

/**
 * Update the vote count on the entity table after a new vote
 */
CREATE TRIGGER CreateVote
AFTER INSERT ON vote
FOR EACH ROW
BEGIN
    UPDATE entity
    SET upvotes = CASE
            WHEN New.kind = '+' THEN upvotes + 1 ELSE upvotes
        END,
        downvotes = CASE
            WHEN New.kind = '-' THEN downvotes + 1 ELSE downvotes
        END
    WHERE entity_id = NEW.entity_id;
END;

/**
 * Update the vote count on the entity table on a vote update
 */
CREATE TRIGGER UpdateVote
AFTER UPDATE ON vote
FOR EACH ROW
BEGIN
    UPDATE entity
    SET upvotes = CASE
            WHEN New.kind = '+' AND Old.kind = '-' THEN upvotes + 1
            WHEN New.kind = '-' AND Old.kind = '+' THEN upvotes - 1
            ELSE upvotes
        END,
        downvotes = CASE
            WHEN New.kind = '+' AND Old.kind = '-' THEN downvotes - 1
            WHEN New.kind = '-' AND Old.kind = '+' THEN downvotes + 1
            ELSE downvotes
        END
    WHERE entity_id = NEW.entity_id;
END;

/**
 * Update the vote count on the entity table on a vote deletion
 */
CREATE TRIGGER DeleteVote
AFTER DELETE ON vote
FOR EACH ROW
BEGIN
    UPDATE entity
    SET upvotes = CASE
            WHEN Old.kind = '+' THEN upvotes - 1 ELSE upvotes
        END,
        downvotes = CASE
            WHEN Old.kind = '-' THEN downvotes - 1 ELSE downvotes
        END
    WHERE entity_id = Old.entity_id;
END;
