PRAGMA foreign_keys = OFF;

BEGIN;

.read db/sql/schema.sql
.read db/sql/triggers.sql
.read db/sql/views.sql
.read db/sql/config.sql

END;

PRAGMA foreign_keys = ON;
