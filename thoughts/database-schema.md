# Database Schema — devlog

**Date:** 2023-05-14

## Why SQLite

I considered MySQL because it's what I use for everything else. But this is a
single-user personal tool. SQLite means zero setup, the database is a single file
I can back up with `cp`, and PHP's PDO handles it natively. No daemon, no config.

If this ever needs multi-user or concurrent writes, I'll migrate. That's a bridge
I'll cross when I get there.

## Schema

```sql
CREATE TABLE releases (
    id          INTEGER PRIMARY KEY AUTOINCREMENT,
    project     TEXT    NOT NULL,
    version     TEXT    NOT NULL,
    tag         TEXT    NOT NULL DEFAULT 'feat',
    body        TEXT    NOT NULL DEFAULT '',
    released_at TEXT    NOT NULL DEFAULT (datetime('now'))
);
```

### Field notes

- `project` — free-form string. I use `owner/repo` style (e.g. `velynox/devlog`)
  but nothing enforces that. Keeping it flexible.
- `version` — also free-form. Semver is the convention but I'm not parsing it.
  Sorting is by `released_at`, not by version number.
- `tag` — constrained in PHP to the TAGS constant. SQLite doesn't have enums,
  so the constraint lives in the application layer. Good enough.
- `body` — plain text. I considered storing Markdown and rendering it, but that
  requires a parser dependency. `nl2br` + `htmlspecialchars` is sufficient for now.
  If I want rendered Markdown later, I'll add `league/commonmark` via Composer.
- `released_at` — stored as TEXT in ISO 8601 format. SQLite's datetime functions
  work fine with this. Sorting by this column is correct lexicographically.

## Migration strategy

The `migrate()` method in `Database.php` uses `CREATE TABLE IF NOT EXISTS`.
This is fine for a single-table schema. If I add tables later, I'll add a
`schema_version` table and run numbered migrations.
