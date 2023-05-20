<?php
/** @var array<string, mixed> $release */

use Devlog\View;

ob_start();
$colors = View::tagColor($release['tag']);
?>

<div class="mb-8">
    <a href="/" class="flex items-center gap-1.5 text-ctp-overlay0 hover:text-ctp-subtext0 text-xs tracking-widest uppercase transition-colors w-fit mb-6">
        <!-- arrow-left icon -->
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
            <line x1="19" y1="12" x2="5" y2="12"></line>
            <polyline points="12 19 5 12 12 5"></polyline>
        </svg>
        back
    </a>

    <div class="flex items-center gap-3 flex-wrap mb-2">
        <h1 class="text-ctp-mauve font-bold text-2xl"><?= View::e($release['version']) ?></h1>
        <span class="text-sm border px-2 py-0.5 <?= $colors ?>"><?= View::e($release['tag']) ?></span>
    </div>
    <div class="flex items-center gap-4 text-xs text-ctp-overlay0">
        <span><?= View::e($release['project']) ?></span>
        <span>&mdash;</span>
        <time datetime="<?= View::e($release['released_at']) ?>">
            <?= View::e(date('D, d M Y', strtotime($release['released_at']))) ?>
        </time>
    </div>
</div>

<div class="border border-ctp-surface0 bg-ctp-mantle px-6 py-6 mb-8">
    <?php if ($release['body'] !== ''): ?>
        <div class="text-ctp-text text-sm leading-relaxed whitespace-pre-wrap"><?= View::e($release['body']) ?></div>
    <?php else: ?>
        <p class="text-ctp-overlay0 text-sm italic">No release notes provided.</p>
    <?php endif; ?>
</div>

<div class="flex items-center gap-4">
    <a href="/edit/<?= (int)$release['id'] ?>" class="text-xs text-ctp-subtext0 hover:text-ctp-text border border-ctp-surface0 hover:border-ctp-surface1 px-4 py-1.5 transition-colors flex items-center gap-1.5">
        <!-- edit icon -->
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
        </svg>
        edit
    </a>
    <a href="/delete/<?= (int)$release['id'] ?>"
       onclick="return confirm('Delete this release?')"
       class="text-xs text-ctp-red hover:text-ctp-maroon border border-ctp-red/30 hover:border-ctp-red/60 px-4 py-1.5 transition-colors flex items-center gap-1.5">
        <!-- trash icon -->
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
            <polyline points="3 6 5 6 21 6"></polyline>
            <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"></path>
            <path d="M10 11v6M14 11v6"></path>
            <path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"></path>
        </svg>
        delete
    </a>
</div>

<?php
$content = ob_get_clean();
$title   = $release['project'] . ' ' . $release['version'] . ' — devlog';
require __DIR__ . '/layout.php';
