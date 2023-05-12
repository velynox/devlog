# devlog

A self-hosted release notes and changelog tracker. Built with PHP 8.2 + SQLite.
No framework, no build step, no nonsense.

## Requirements

- PHP 8.2+
- SQLite3 extension enabled (`php -m | grep sqlite`)

## Setup

```bash
git clone ...
cp .env.example .env
php -S localhost:8080 -t public/
```

The SQLite database is created automatically on first run at `data/devlog.sqlite`.

## Usage

- `GET /` — Browse all releases, newest first. Filter by project or tag.
- `GET /release/{id}` — View a single release entry.
- `GET /new` — Form to add a new release.
- `POST /new` — Submit a new release.
- `GET /delete/{id}` — Delete a release (no confirmation, you're an adult).

## Stack

- PHP 8.2 (no framework)
- SQLite via PDO
- TailwindCSS (CDN)
- Catppuccin Mocha color palette
