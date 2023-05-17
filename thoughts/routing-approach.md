# Routing Approach — devlog

**Date:** 2023-05-17

## Decision: No framework router

I wrote a 50-line router instead of pulling in a package. The requirements are:
- Match GET/POST
- Extract named URL segments like `{id}`
- Call a handler closure

That's it. A regex-based pattern matcher covers this completely. The Router class
in `src/Router.php` does exactly this and nothing more.

## Why not Laravel/Slim/etc.

Laravel is my daily driver for real projects. For a tool this small, the overhead
is embarrassing. Slim would be reasonable, but it still requires Composer and adds
a dependency I'd have to maintain. The custom router has zero dependencies and I
understand every line of it.

## Trade-offs accepted

- No middleware pipeline — I don't need one.
- No named routes / URL generation — I'm hardcoding paths in views. Fine for now.
- No regex constraints on segments — `{id}` matches any non-slash string. The
  controller casts to int and the model returns null for invalid IDs. Safe enough.

## Edit route design

I used `GET /edit/{id}` + `POST /edit/{id}` instead of `PATCH` because HTML forms
only support GET and POST. I'm not adding JavaScript fetch calls just to use the
correct HTTP verb on a personal tool.
