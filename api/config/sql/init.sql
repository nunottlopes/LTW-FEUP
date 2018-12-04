PRAGMA foreign_keys = OFF;

BEGIN;

.read config/sql/schema.sql
.read config/sql/triggers.sql
.read config/sql/config.sql

END;

PRAGMA foreign_keys = ON;
