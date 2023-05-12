# Project Setup — devlog

**Date:** 2023-05-12
**Decision:** What to build.

I wanted something I'd actually use. URL shorteners are boring. Status dashboards
are fine but shallow. I landed on a self-hosted **dev changelog tracker** — a place
to log releases, write notes per version, and browse a clean timeline.

## Why this project

- I keep forgetting what I changed between versions of my own tools.
- GitHub releases are overkill for personal/small projects.
- I want something that runs on a cheap VPS with just PHP + SQLite. No Docker, no
  Node, no build step.

## Stack decisions

- **PHP 8.2** — OOP, clean classes, no framework. The project is small enough that
  Laravel would be embarrassing overhead.
- **SQLite via PDO** — No MySQL server needed. One file, portable, fast enough for
  this use case.
- **TailwindCSS via CDN** — No build step. This is a personal tool, not a product.
  If it grows, I'll add Vite.
- **No JS framework** — Plain JS for the one interactive bit (tag filtering). Alpine
  would be fine but it's not needed.

## File structure plan

```
devlog/
├── public/
│   └── index.php        # Entry point, handles routing
├── src/
│   ├── Database.php     # PDO wrapper + migrations
│   ├── Release.php      # Release model
│   └── Router.php       # Minimal router
├── thoughts/            # This directory
├── .gitignore
└── README.md
```

## What a "release" looks like

- `id` — autoincrement
- `project` — string, the project name (e.g. "velynox/devlog")
- `version` — semver string (e.g. "1.2.0")
- `tag` — optional label: "feat", "fix", "chore", "breaking"
- `body` — markdown text, the actual notes
- `released_at` — datetime

The UI shows a reverse-chronological timeline. Each entry is a card with the
version badge, tag, project name, and the body rendered as plain text (no markdown
parser dependency — just nl2br for now).
