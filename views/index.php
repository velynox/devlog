<?php
/** @var \Devlog\Release $releaseModel */
/** @var array<int, array<string, mixed>> $releases */
/** @var string[] $projects */
/** @var string $filterProject */
/** @var string $filterTag */

use Devlog\View;
use Devlog\Release;

ob_start();
?>

<div class="mb-8 flex flex-col gap-1">
    <h1 class="text-ctp-text font-bold text-xl tracking-tight">Release Timeline</h1>
    <p class="text-ctp-subtext0 text-sm">All logged releases, newest first.</p>
</div>

<!-- Filter bar -->
<form method="GET" action="/" class="flex flex-wrap gap-3 mb-10 items-end">
    <div class="flex flex-col gap-1">
        <label class="text-ctp-overlay0 text-xs tracking-widest uppercase">Project</label>
        <select name="project" class="bg-ctp-mantle border border-ctp-surface0 text-ctp-text text-sm px-3 py-1.5 focus:outline-none focus:border-ctp-surface1">
            <option value="">all projects</option>
            <?php foreach ($projects as $p): ?>
                <option value="<?= View::e($p) ?>" <?= $filterProject === $p ? 'selected' : '' ?>>
                    <?= View::e($p) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="flex flex-col gap-1">
        <label class="text-ctp-overlay0 text-xs tracking-widest uppercase">Tag</label>
        <select name="tag" class="bg-ctp-mantle border border-ctp-surface0 text-ctp-text text-sm px-3 py-1.5 focus:outline-none focus:border-ctp-surface1">
            <option value="">all tags</option>
            <?php foreach (Release::TAGS as $t): ?>
                <option value="<?= View::e($t) ?>" <?= $filterTag === $t ? 'selected' : '' ?>>
                    <?= View::e($t) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    <button type="submit" class="text-xs text-ctp-subtext0 hover:text-ctp-text border border-ctp-surface0 hover:border-ctp-surface1 px-4 py-1.5 transition-colors">
        filter
    </button>
    <?php if ($filterProject || $filterTag): ?>
        <a href="/" class="text-xs text-ctp-overlay0 hover:text-ctp-subtext0 py-1.5 transition-colors">clear</a>
    <?php endif; ?>
</form>

<!-- Timeline -->
<?php if (empty($releases)): ?>
    <div class="border border-ctp-surface0 px-6 py-10 text-center text-ctp-overlay0 text-sm">
        No releases found.
        <?php if (!$filterProject && !$filterTag): ?>
            <a href="/new" class="text-ctp-blue hover:underline ml-1">Add the first one.</a>
        <?php endif; ?>
    </div>
<?php else: ?>
    <div class="flex flex-col gap-px">
        <?php foreach ($releases as $release): ?>
            <?php $colors = View::tagColor($release['tag']); ?>
            <div class="group border border-ctp-surface0 hover:border-ctp-surface1 bg-ctp-mantle hover:bg-[#1c1c2c] transition-colors px-5 py-4">
                <div class="flex items-start justify-between gap-4">
                    <div class="flex items-center gap-3 flex-wrap">
                        <!-- version badge -->
                        <span class="text-ctp-mauve font-bold text-sm"><?= View::e($release['version']) ?></span>
                        <!-- tag badge -->
                        <span class="text-xs border px-1.5 py-0.5 <?= $colors ?>">
                            <?= View::e($release['tag']) ?>
                        </span>
                        <!-- project -->
                        <span class="text-ctp-subtext0 text-xs"><?= View::e($release['project']) ?></span>
                    </div>
                    <div class="flex items-center gap-3 shrink-0">
                        <time class="text-ctp-overlay0 text-xs" datetime="<?= View::e($release['released_at']) ?>">
                            <?= View::e(date('Y-m-d', strtotime($release['released_at']))) ?>
                        </time>
                        <a href="/release/<?= (int)$release['id'] ?>" class="text-ctp-overlay0 hover:text-ctp-blue transition-colors" title="View">
                            <!-- arrow-right icon -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <line x1="5" y1="12" x2="19" y2="12"></line>
                                <polyline points="12 5 19 12 12 19"></polyline>
                            </svg>
                        </a>
                    </div>
                </div>
                <?php if ($release['body'] !== ''): ?>
                    <p class="mt-2 text-ctp-subtext0 text-sm leading-relaxed line-clamp-2">
                        <?= View::e(mb_strimwidth($release['body'], 0, 160, '...')) ?>
                    </p>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php
$content = ob_get_clean();
$title   = 'devlog';
require __DIR__ . '/layout.php';
