<?php
/** @var array<int, array<string, mixed>> $releases */
/** @var string[] $projects */
/** @var string $filterProject */
/** @var string $filterTag */
/** @var string $filterSearch */
/** @var \Devlog\Paginator $paginator */

use Devlog\View;
use Devlog\Release;

ob_start();
?>

<div class="mb-8 flex flex-col gap-1">
    <h1 class="text-ctp-text font-bold text-xl tracking-tight">Release Timeline</h1>
    <p class="text-ctp-subtext0 text-sm">
        <?= $paginator->total ?> release<?= $paginator->total !== 1 ? 's' : '' ?> logged.
    </p>
</div>

<!-- Filter bar -->
<form method="GET" action="/" class="flex flex-wrap gap-3 mb-10 items-end">
    <div class="flex flex-col gap-1.5 flex-1 min-w-[160px]">
        <label class="text-ctp-overlay0 text-xs tracking-widest uppercase" for="q">Search</label>
        <input
            id="q" name="q" type="text"
            value="<?= View::e($filterSearch) ?>"
            placeholder="project, version, notes..."
            class="bg-ctp-mantle border border-ctp-surface0 focus:border-ctp-surface1 text-ctp-text text-sm px-3 py-1.5 focus:outline-none placeholder:text-ctp-overlay0"
        >
    </div>
    <div class="flex flex-col gap-1.5">
        <label class="text-ctp-overlay0 text-xs tracking-widest uppercase" for="project">Project</label>
        <select id="project" name="project" class="bg-ctp-mantle border border-ctp-surface0 text-ctp-text text-sm px-3 py-1.5 focus:outline-none focus:border-ctp-surface1">
            <option value="">all</option>
            <?php foreach ($projects as $p): ?>
                <option value="<?= View::e($p) ?>" <?= $filterProject === $p ? 'selected' : '' ?>>
                    <?= View::e($p) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="flex flex-col gap-1.5">
        <label class="text-ctp-overlay0 text-xs tracking-widest uppercase" for="tag">Tag</label>
        <select id="tag" name="tag" class="bg-ctp-mantle border border-ctp-surface0 text-ctp-text text-sm px-3 py-1.5 focus:outline-none focus:border-ctp-surface1">
            <option value="">all</option>
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
    <?php if ($filterProject || $filterTag || $filterSearch): ?>
        <a href="/" class="text-xs text-ctp-overlay0 hover:text-ctp-subtext0 py-1.5 transition-colors">clear</a>
    <?php endif; ?>
</form>

<!-- Timeline -->
<?php if (empty($releases)): ?>
    <div class="border border-ctp-surface0 px-6 py-10 text-center text-ctp-overlay0 text-sm">
        No releases found.
        <?php if (!$filterProject && !$filterTag && !$filterSearch): ?>
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
                        <span class="text-ctp-mauve font-bold text-sm"><?= View::e($release['version']) ?></span>
                        <span class="text-xs border px-1.5 py-0.5 <?= $colors ?>"><?= View::e($release['tag']) ?></span>
                        <span class="text-ctp-subtext0 text-xs"><?= View::e($release['project']) ?></span>
                    </div>
                    <div class="flex items-center gap-3 shrink-0">
                        <time class="text-ctp-overlay0 text-xs" datetime="<?= View::e($release['released_at']) ?>">
                            <?= View::e(date('Y-m-d', strtotime($release['released_at']))) ?>
                        </time>
                        <a href="/release/<?= (int)$release['id'] ?>" class="text-ctp-overlay0 hover:text-ctp-blue transition-colors" title="View">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <line x1="5" y1="12" x2="19" y2="12"></line>
                                <polyline points="12 5 19 12 12 19"></polyline>
                            </svg>
                        </a>
                    </div>
                </div>
                <?php if ($release['body'] !== ''): ?>
                    <p class="mt-2 text-ctp-subtext0 text-sm leading-relaxed">
                        <?= View::e(mb_strimwidth($release['body'], 0, 160, '...')) ?>
                    </p>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Pagination -->
    <?php if ($paginator->totalPages > 1): ?>
        <?php
        $qp = array_filter([
            'q'       => $filterSearch,
            'project' => $filterProject,
            'tag'     => $filterTag,
        ]);
        ?>
        <div class="flex items-center justify-between mt-8 text-xs text-ctp-overlay0">
            <span>
                page <?= $paginator->currentPage ?> of <?= $paginator->totalPages ?>
            </span>
            <div class="flex gap-2">
                <?php if ($paginator->hasPrev()): ?>
                    <a href="<?= View::e($paginator->pageUrl($paginator->currentPage - 1, $qp)) ?>"
                       class="border border-ctp-surface0 hover:border-ctp-surface1 hover:text-ctp-subtext0 px-3 py-1.5 transition-colors flex items-center gap-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <polyline points="15 18 9 12 15 6"></polyline>
                        </svg>
                        prev
                    </a>
                <?php endif; ?>
                <?php if ($paginator->hasNext()): ?>
                    <a href="<?= View::e($paginator->pageUrl($paginator->currentPage + 1, $qp)) ?>"
                       class="border border-ctp-surface0 hover:border-ctp-surface1 hover:text-ctp-subtext0 px-3 py-1.5 transition-colors flex items-center gap-1">
                        next
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <polyline points="9 18 15 12 9 6"></polyline>
                        </svg>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>
<?php endif; ?>

<?php
$content = ob_get_clean();
$title   = 'devlog';
require __DIR__ . '/layout.php';
