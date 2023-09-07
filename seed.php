<?php

/**
 * Seed script — populates the database with sample releases for development.
 * Run from the project root: php seed.php
 */

declare(strict_types=1);

require_once __DIR__ . '/src/Database.php';
require_once __DIR__ . '/src/Release.php';

use Devlog\Database;
use Devlog\Release;

$db    = new Database(__DIR__ . '/data/devlog.sqlite');
$model = new Release($db);

$entries = [
    ['velynox/devlog',   '0.1.0', 'feat',     '2023-05-22 10:00:00', 'Initial release. Basic timeline view, SQLite storage, create/edit/delete releases.'],
    ['velynox/devlog',   '0.2.0', 'feat',     '2023-06-01 14:30:00', "Added search and pagination.\nFilter bar now supports full-text search across project, version, and notes."],
    ['velynox/devlog',   '0.2.1', 'fix',      '2023-06-03 09:15:00', 'Fixed date normalization bug when editing releases with empty released_at field.'],
    ['velynox/site',     '1.0.0', 'feat',     '2023-04-10 11:00:00', 'Personal site launch. Static HTML, Tailwind, Catppuccin Mocha.'],
    ['velynox/site',     '1.1.0', 'feat',     '2023-05-05 16:45:00', 'Added projects page and RSS feed.'],
    ['velynox/site',     '1.1.1', 'fix',      '2023-05-06 10:20:00', 'Fixed broken og:image meta tag on project pages.'],
    ['velynox/site',     '1.2.0', 'chore',    '2023-07-14 13:00:00', 'Migrated from plain CSS to TailwindCSS v3. Removed ~400 lines of hand-written styles.'],
    ['velynox/toolbox',  '0.1.0', 'feat',     '2023-03-01 09:00:00', 'First commit. Collection of personal PHP utility scripts.'],
    ['velynox/toolbox',  '0.2.0', 'feat',     '2023-03-20 15:30:00', 'Added CSV-to-JSON converter and basic HTTP client wrapper.'],
    ['velynox/toolbox',  '0.3.0', 'security', '2023-08-02 11:45:00', 'Patched path traversal vulnerability in file reader utility. Input is now sanitized with realpath() before any file operation.'],
    ['velynox/toolbox',  '0.3.1', 'fix',      '2023-08-03 08:30:00', 'Fixed regression in HTTP client — timeout was not being applied correctly after 0.3.0 refactor.'],
    ['velynox/devlog',   '0.3.0', 'feat',     '2023-08-15 14:00:00', "Added stats page with release counts by tag and project.\nProgress bars show relative distribution at a glance."],
    ['velynox/devlog',   '0.3.1', 'docs',     '2023-08-16 10:10:00', 'Updated README with setup instructions and route reference.'],
    ['velynox/site',     '1.3.0', 'breaking', '2023-09-01 12:00:00', "Dropped IE11 support. Removed all -ms- prefixes and polyfills.\nThis cuts ~8KB from the CSS bundle."],
    ['velynox/toolbox',  '1.0.0', 'feat',     '2023-10-10 10:00:00', "Stable release. Cleaned up public API, added PHPDoc to all public methods.\nAll utilities now follow consistent error handling conventions."],
];

foreach ($entries as [$project, $version, $tag, $date, $body]) {
    $model->create([
        'project'     => $project,
        'version'     => $version,
        'tag'         => $tag,
        'body'        => $body,
        'released_at' => $date,
    ]);
    echo "  + {$project} {$version} [{$tag}]\n";
}

echo "\nSeeded " . count($entries) . " releases.\n";
