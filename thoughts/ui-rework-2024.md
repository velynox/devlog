# UI/UX Rework — September 2024

**Date:** 2024-09-03

## What changed and why

The original UI was functional but rough. Sharp corners everywhere, flat buttons
with no visual weight, no focus states, inconsistent spacing. I'd been building
a component library (ui_shelf) on the side and it felt wrong not to apply those
patterns here.

## Changes by file

### layout.php
- Header is now sticky with `backdrop-blur` — stays visible while scrolling long
  timelines. Lifted from the `sticky_header` component.
- "New release" button is now a filled primary button (mauve bg, dark text) instead
  of a ghost border link. More obvious call-to-action.
- Footer now shows copyright + tech stack, two-column layout from `footer` component.
- Switched from `ctp-*` Tailwind custom classes to raw hex values — more portable,
  no dependency on the custom config being loaded correctly.

### index.php
- Release cards are now rounded (`rounded-lg`) with a hover border lift, matching
  the `cards/repo` pattern.
- Version badge uses the `badges/version` pill style — tag icon, colored border,
  translucent background.
- Tag badges are now rounded pills with per-color inline styles from `badges/tag`.
- Search input has a leading search icon from `forms/search`.
- Selects have a custom chevron and `appearance-none` from `forms/select`.
- Empty state uses the centered icon + text + CTA pattern from `layout/empty_state`.
- Pagination is now numbered with ellipsis, active page highlighted in mauve,
  from `navigation/pagination`.

### show.php
- Back button has a hover slide animation (`group-hover:-translate-x-0.5`) from
  `navigation/back_button`.
- Version heading is now a large badge, not plain text.
- Action buttons use proper `focus:ring-2` states and rounded corners.
- Delete button uses the danger ghost style (red border, red text, red bg on hover).

### form.php
- All inputs have `focus:ring-2 focus:ring-[#cba6f7]` from `forms/input_group`.
- Error block uses the alert component style (icon + colored border + bg tint).
- Selects have custom chevron overlay.
- Submit button is now a proper primary button.

### stats.php
- Stat numbers are now in proper cards with icons, from `cards/stat`.
- Progress bars use the `data/progress` pattern — labeled, colored fill, rounded.
- Tag badges in the breakdown use the same pill style as the timeline.
- Added a labeled divider between the top stats and the breakdown section.

## What I didn't change

- The PHP backend is untouched. This is purely a view layer rework.
- No JS added. The focus ring and hover states are pure CSS/Tailwind.
- No new dependencies.
