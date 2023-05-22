<?php

declare(strict_types=1);

require_once __DIR__ . '/../src/Database.php';
require_once __DIR__ . '/../src/Release.php';
require_once __DIR__ . '/../src/Router.php';
require_once __DIR__ . '/../src/View.php';

use Devlog\Database;
use Devlog\Release;
use Devlog\Router;
use Devlog\View;

// --- Bootstrap ---

$dbPath = __DIR__ . '/../data/devlog.sqlite';
$db     = new Database($dbPath);
$model  = new Release($db);
$router = new Router();
$view   = new View(__DIR__ . '/../views');

// --- Routes ---

/**
 * GET / — Release timeline with optional project/tag filters.
 */
$router->add('GET', '/', function () use ($model, $view): void {
    $filterProject = trim($_GET['project'] ?? '');
    $filterTag     = trim($_GET['tag'] ?? '');

    $view->render('index', [
        'releases'      => $model->all(
            $filterProject !== '' ? $filterProject : null,
            $filterTag     !== '' ? $filterTag     : null,
        ),
        'projects'      => $model->projects(),
        'filterProject' => $filterProject,
        'filterTag'     => $filterTag,
    ]);
});

/**
 * GET /release/{id} — Single release detail view.
 */
$router->add('GET', '/release/{id}', function (array $params) use ($model, $view): void {
    $release = $model->find((int) $params['id']);

    if ($release === null) {
        http_response_code(404);
        echo '404 — release not found.';
        return;
    }

    $view->render('show', ['release' => $release]);
});

/**
 * GET /new — New release form.
 */
$router->add('GET', '/new', function () use ($view): void {
    $view->render('form', [
        'heading' => 'New Release',
        'action'  => '/new',
    ]);
});

/**
 * POST /new — Create a new release.
 */
$router->add('POST', '/new', function () use ($model, $view): void {
    $data   = $_POST;
    $errors = validate($data);

    if (!empty($errors)) {
        $view->render('form', [
            'heading' => 'New Release',
            'action'  => '/new',
            'errors'  => $errors,
            'old'     => $data,
        ]);
        return;
    }

    $id = $model->create([
        'project'     => $data['project'],
        'version'     => $data['version'],
        'tag'         => $data['tag'],
        'body'        => $data['body'] ?? '',
        'released_at' => normalizeDate($data['released_at'] ?? ''),
    ]);

    header('Location: /release/' . $id);
    exit;
});

/**
 * GET /edit/{id} — Edit release form.
 */
$router->add('GET', '/edit/{id}', function (array $params) use ($model, $view): void {
    $release = $model->find((int) $params['id']);

    if ($release === null) {
        http_response_code(404);
        echo '404 — release not found.';
        return;
    }

    // Convert stored datetime to datetime-local format for the input
    $release['released_at'] = date('Y-m-d\TH:i', strtotime($release['released_at']));

    $view->render('form', [
        'heading' => 'Edit Release',
        'action'  => '/edit/' . $release['id'],
        'release' => $release,
    ]);
});

/**
 * POST /edit/{id} — Update an existing release.
 */
$router->add('POST', '/edit/{id}', function (array $params) use ($model, $db, $view): void {
    $id      = (int) $params['id'];
    $release = $model->find($id);

    if ($release === null) {
        http_response_code(404);
        echo '404 — release not found.';
        return;
    }

    $data   = $_POST;
    $errors = validate($data);

    if (!empty($errors)) {
        $data['released_at'] = date('Y-m-d\TH:i', strtotime($data['released_at'] ?? 'now'));
        $view->render('form', [
            'heading' => 'Edit Release',
            'action'  => '/edit/' . $id,
            'errors'  => $errors,
            'old'     => $data,
        ]);
        return;
    }

    $db->execute(
        'UPDATE releases SET project = :project, version = :version, tag = :tag,
         body = :body, released_at = :released_at WHERE id = :id',
        [
            ':project'     => trim($data['project']),
            ':version'     => trim($data['version']),
            ':tag'         => in_array($data['tag'], \Devlog\Release::TAGS, true) ? $data['tag'] : 'feat',
            ':body'        => trim($data['body'] ?? ''),
            ':released_at' => normalizeDate($data['released_at'] ?? ''),
            ':id'          => $id,
        ]
    );

    header('Location: /release/' . $id);
    exit;
});

/**
 * GET /delete/{id} — Delete a release and redirect home.
 */
$router->add('GET', '/delete/{id}', function (array $params) use ($model): void {
    $model->delete((int) $params['id']);
    header('Location: /');
    exit;
});

// --- Dispatch ---

$router->dispatch();

// --- Helpers ---

/**
 * Validate POST data for a release form.
 *
 * @param array<string, mixed> $data
 * @return string[]
 */
function validate(array $data): array
{
    $errors = [];

    if (empty(trim($data['project'] ?? ''))) {
        $errors[] = 'Project name is required.';
    }

    if (empty(trim($data['version'] ?? ''))) {
        $errors[] = 'Version is required.';
    }

    return $errors;
}

/**
 * Normalize a datetime-local input value to a standard SQL datetime string.
 */
function normalizeDate(string $value): string
{
    if ($value === '') {
        return date('Y-m-d H:i:s');
    }

    $ts = strtotime($value);
    return $ts !== false ? date('Y-m-d H:i:s', $ts) : date('Y-m-d H:i:s');
}
