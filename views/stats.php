<?php
/** @var array<string, mixed> $stats */

use Devlog\View;

ob_start();
?>

<div class="mb-10 flex flex-col gap-1">
    <h1 class="text-ctp-text font-bold text-xl tracking-tight">Stats</h1>
    <p class="text-ctp-subtext0 text-sm">Aggregate view of all logged releases.</p>
</div>

<!-- Top-level numbers -->
<div class="grid grid-cols-2 gap-px mb-10">
    <div class="bg-ctp-mantle border border-ctp-surface0 px-5 py-5">
        <div class="text-ctp-overlay0 text-xs tracking-widest uppercase mb-2">Total releases</div>
        <div class="text-ctp-mauve font-bold text-3xl"><?= (int) $stats['total'] ?></div>
    </div>
    <div class="bg-ctp-mantle border border-ctp-surface0 px-5 py-5">
        <div class="text-ctp-overlay0 text-xs tracking-widest uppercase mb-2">Projects tracked</div>
        <div class="text-ctp-mauve font-bold text-3xl"><?= count($stats['by_project']) ?></div>
    </div>
    <?php if ($stats['oldest']): ?>
    <div class="bg-ctp-mantle border border-ctp-surface0 px-5 py-5">
        <div class="text-ctp-overlay0 text-xs tracking-widest uppercase mb-2">First release</div>
        <div class="text-ctp-text text-sm font-bold"><?= View::e(date('d M Y', strtotime($stats['oldest']))) ?></div>
    </div>
    <?php endif; ?>
    <?php if ($stats['latest']): ?>
    <div class="bg-ctp-mantle border border-ctp-surface0 px-5 py-5">
        <div class="text-ctp-overlay0 text-xs tracking-widest uppercase mb-2">Latest release</div>
        <div class="text-ctp-text text-sm font-bold"><?= View::e(date('d M Y', strtotime($stats['latest']))) ?></div>
    </div>
    <?php endif; ?>
</div>

<!-- By tag -->
<div class="mb-10">
    <h2 class="text-ctp-subtext0 text-xs tracking-widest uppercase mb-4">Releases by tag</h2>
    <div class="flex flex-col gap-px">
        <?php foreach ($stats['by_tag'] as $row):
            $pct = $stats['total'] > 0 ? round(($row['n'] / $stats['total']) * 100) : 0;
            $colors = \Devlog\View::tagColor($row['tag']);
        ?>
            <div class="bg-ctp-mantle border border-ctp-surface0 px-5 py-3 flex items-center gap-4">
                <span class="text-xs border px-1.5 py-0.5 w-20 text-center <?= $colors ?>"><?= View::e($row['tag']) ?></span>
                <div class="flex-1 bg-ctp-surface0 h-1.5">
                    <div class="bg-ctp-mauve h-1.5" style="width: <?= $pct ?>%"></div>
                </div>
                <span class="text-ctp-subtext0 text-xs w-8 text-right"><?= (int) $row['n'] ?></span>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- By project -->
<div>
    <h2 class="text-ctp-subtext0 text-xs tracking-widest uppercase mb-4">Releases by project</h2>
    <div class="flex flex-col gap-px">
        <?php foreach ($stats['by_project'] as $row): ?>
            <div class="bg-ctp-mantle border border-ctp-surface0 px-5 py-3 flex items-center justify-between">
                <a href="/?project=<?= urlencode($row['project']) ?>" class="text-ctp-blue hover:text-ctp-lavender text-sm transition-colors">
                    <?= View::e($row['project']) ?>
                </a>
                <span class="text-ctp-subtext0 text-xs"><?= (int) $row['n'] ?> release<?= $row['n'] != 1 ? 's' : '' ?></span>
            </div>
        <?php endforeach; ?>
        <?php if (empty($stats['by_project'])): ?>
            <div class="border border-ctp-surface0 px-5 py-8 text-center text-ctp-overlay0 text-sm">
                No data yet. <a href="/new" class="text-ctp-blue hover:underline">Add a release.</a>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php
$content = ob_get_clean();
$title   = 'stats — devlog';
require __DIR__ . '/layout.php';
