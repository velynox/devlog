# Search & Pagination — devlog

**Date:** 2023-06-01

## Problem

The timeline was loading every row on every request. Fine for 20 entries, annoying
for 200. Also, there was no way to find a specific release without scrolling.

## Search implementation

SQLite's `LIKE` operator is good enough here. I'm not indexing a blog — just
filtering a personal changelog. The query looks like:

```sql
WHERE (project LIKE :search OR version LIKE :search OR body LIKE :search)
```

No FTS5, no trigram index. If the table ever gets large enough to need it, I'll
add a virtual FTS5 table. That's a future problem.

## Pagination

Offset-based. Simple, predictable, no cursor complexity needed. The `Paginator`
class takes total count + per-page + current page and hands back offset, page
count, and URL helpers. It doesn't touch the database — that's the model's job.

15 per page feels right. Enough context, not overwhelming.

## What I didn't do

- No AJAX/infinite scroll. A full page reload is fine. This is a personal tool,
  not a product demo.
- No URL-based page state beyond `?page=N`. The filter params are preserved in
  pagination links via `pageUrl()`.
