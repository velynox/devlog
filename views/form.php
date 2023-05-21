<?php
/**
 * Shared form view for creating and editing releases.
 *
 * @var array<string, mixed>|null $release  Populated when editing, null when creating.
 * @var array<string, string>     $errors   Validation errors keyed by field name.
 * @var array<string, string>     $old      Previously submitted values (for repopulation).
 * @var string                    $action   Form action URL.
 * @var string                    $heading  Page heading text.
 */

use Devlog\View;
use Devlog\Release;

$release ??= null;
$errors  ??= [];
$old     ??= [];

$val = fn(string $key, string $default = '') =>
    View::e($old[$key] ?? $release[$key] ?? $default);

ob_start();
?>

<div class="mb-8">
    <a href="/" class="flex items-center gap-1.5 text-ctp-overlay0 hover:text-ctp-subtext0 text-xs tracking-widest uppercase transition-colors w-fit mb-6">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
            <line x1="19" y1="12" x2="5" y2="12"></line>
            <polyline points="12 19 5 12 12 5"></polyline>
        </svg>
        back
    </a>
    <h1 class="text-ctp-text font-bold text-xl"><?= View::e($heading) ?></h1>
</div>

<?php if (!empty($errors)): ?>
    <div class="border border-ctp-red/40 bg-ctp-red/5 px-5 py-4 mb-6 text-ctp-red text-sm flex flex-col gap-1">
        <?php foreach ($errors as $err): ?>
            <span><?= View::e($err) ?></span>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<form method="POST" action="<?= View::e($action) ?>" class="flex flex-col gap-5">

    <div class="flex flex-col gap-1.5">
        <label class="text-ctp-overlay0 text-xs tracking-widest uppercase" for="project">Project</label>
        <input
            id="project" name="project" type="text"
            value="<?= $val('project') ?>"
            placeholder="velynox/devlog"
            class="bg-ctp-mantle border border-ctp-surface0 focus:border-ctp-surface1 text-ctp-text text-sm px-4 py-2 focus:outline-none placeholder:text-ctp-overlay0"
            required
        >
    </div>

    <div class="flex gap-4">
        <div class="flex flex-col gap-1.5 flex-1">
            <label class="text-ctp-overlay0 text-xs tracking-widest uppercase" for="version">Version</label>
            <input
                id="version" name="version" type="text"
                value="<?= $val('version') ?>"
                placeholder="1.0.0"
                class="bg-ctp-mantle border border-ctp-surface0 focus:border-ctp-surface1 text-ctp-text text-sm px-4 py-2 focus:outline-none placeholder:text-ctp-overlay0"
                required
            >
        </div>
        <div class="flex flex-col gap-1.5">
            <label class="text-ctp-overlay0 text-xs tracking-widest uppercase" for="tag">Tag</label>
            <select
                id="tag" name="tag"
                class="bg-ctp-mantle border border-ctp-surface0 focus:border-ctp-surface1 text-ctp-text text-sm px-4 py-2 focus:outline-none"
            >
                <?php foreach (Release::TAGS as $t): ?>
                    <option value="<?= View::e($t) ?>" <?= ($val('tag', 'feat') === $t) ? 'selected' : '' ?>>
                        <?= View::e($t) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>

    <div class="flex flex-col gap-1.5">
        <label class="text-ctp-overlay0 text-xs tracking-widest uppercase" for="released_at">Released at</label>
        <input
            id="released_at" name="released_at" type="datetime-local"
            value="<?= $val('released_at', date('Y-m-d\TH:i')) ?>"
            class="bg-ctp-mantle border border-ctp-surface0 focus:border-ctp-surface1 text-ctp-text text-sm px-4 py-2 focus:outline-none"
        >
    </div>

    <div class="flex flex-col gap-1.5">
        <label class="text-ctp-overlay0 text-xs tracking-widest uppercase" for="body">Release notes</label>
        <textarea
            id="body" name="body"
            rows="8"
            placeholder="What changed in this release..."
            class="bg-ctp-mantle border border-ctp-surface0 focus:border-ctp-surface1 text-ctp-text text-sm px-4 py-2 focus:outline-none placeholder:text-ctp-overlay0 resize-y leading-relaxed"
        ><?= $val('body') ?></textarea>
    </div>

    <div class="flex items-center gap-4 pt-2">
        <button type="submit" class="text-sm text-ctp-base bg-ctp-mauve hover:bg-ctp-lavender px-6 py-2 font-bold transition-colors">
            save
        </button>
        <a href="/" class="text-sm text-ctp-overlay0 hover:text-ctp-subtext0 transition-colors">cancel</a>
    </div>

</form>

<?php
$content = ob_get_clean();
require __DIR__ . '/layout.php';
