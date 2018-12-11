PRAGMA foreign_keys = OFF;

BEGIN;

.read sql/schema.sql
.read sql/views.sql
.read sql/triggers.sql
.read sql/config.sql

END;

PRAGMA foreign_keys = ON;
