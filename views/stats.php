<?php
/** @var array<string, mixed> $stats */

use Devlog\View;

ob_start();
?>

<!-- Section header from layout/section_header -->
<div class="flex items-start justify-between mb-10">
    <div class="flex flex-col gap-0.5">
        <span class="text-[#cba6f7] text-xs font-bold tracking-widest uppercase">Overview</span>
        <h1 class="text-[#cdd6f4] font-bold text-xl tracking-tight">Stats</h1>
        <p class="text-[#585b70] text-xs">Aggregate view of all logged releases.</p>
    </div>
    <a href="/"
       class="inline-flex items-center gap-1 text-xs text-[#89b4fa] font-bold
              hover:text-[#cba6f7] transition-colors duration-150">
        timeline
        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
        </svg>
    </a>
</div>

<!-- Stat cards — from cards/stat component -->
<div class="grid grid-cols-2 gap-3 mb-10">
    <div class="rounded-lg border border-[#313244] bg-[#181825] p-4 flex flex-col gap-2">
        <div class="flex items-center justify-between">
            <span class="text-[#585b70] text-xs tracking-widest uppercase">Total releases</span>
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-[#cba6f7]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 009.568 3z"/>
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6z"/>
            </svg>
        </div>
        <p class="text-[#cdd6f4] font-bold text-3xl"><?= (int) $stats['total'] ?></p>
        <p class="text-[#a6adc8] text-xs">across all projects</p>
    </div>

    <div class="rounded-lg border border-[#313244] bg-[#181825] p-4 flex flex-col gap-2">
        <div class="flex items-center justify-between">
            <span class="text-[#585b70] text-xs tracking-widest uppercase">Projects</span>
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-[#89b4fa]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12.75V12A2.25 2.25 0 014.5 9.75h15A2.25 2.25 0 0121.75 12v.75m-8.69-6.44l-2.12-2.12a1.5 1.5 0 00-1.061-.44H4.5A2.25 2.25 0 002.25 6v12a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18V9a2.25 2.25 0 00-2.25-2.25h-5.379a1.5 1.5 0 01-1.06-.44z"/>
            </svg>
        </div>
        <p class="text-[#cdd6f4] font-bold text-3xl"><?= count($stats['by_project']) ?></p>
        <p class="text-[#a6adc8] text-xs">tracked</p>
    </div>

    <?php if ($stats['oldest']): ?>
    <div class="rounded-lg border border-[#313244] bg-[#181825] p-4 flex flex-col gap-2">
        <div class="flex items-center justify-between">
            <span class="text-[#585b70] text-xs tracking-widest uppercase">First release</span>
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-[#a6e3a1]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/>
            </svg>
        </div>
        <p class="text-[#cdd6f4] font-bold text-base"><?= View::e(date('d M Y', strtotime($stats['oldest']))) ?></p>
        <p class="text-[#a6adc8] text-xs">earliest entry</p>
    </div>
    <?php endif; ?>

    <?php if ($stats['latest']): ?>
    <div class="rounded-lg border border-[#313244] bg-[#181825] p-4 flex flex-col gap-2">
        <div class="flex items-center justify-between">
            <span class="text-[#585b70] text-xs tracking-widest uppercase">Latest release</span>
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-[#f9e2af]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <p class="text-[#cdd6f4] font-bold text-base"><?= View::e(date('d M Y', strtotime($stats['latest']))) ?></p>
        <p class="text-[#a6adc8] text-xs">most recent</p>
    </div>
    <?php endif; ?>
</div>

<!-- Divider from data/divider component -->
<div class="flex items-center gap-3 mb-8">
    <hr class="flex-1 border-[#313244]">
    <span class="text-[#585b70] text-xs tracking-widest uppercase shrink-0">breakdown</span>
    <hr class="flex-1 border-[#313244]">
</div>

<!-- By tag — progress bars from data/progress component -->
<div class="mb-10">
    <h2 class="text-[#a6adc8] text-xs font-bold tracking-widest uppercase mb-4">Releases by tag</h2>
    <div class="flex flex-col gap-3">
        <?php foreach ($stats['by_tag'] as $row):
            $pct = $stats['total'] > 0 ? round(($row['n'] / $stats['total']) * 100) : 0;
            [$textColor] = View::tagColors($row['tag']);
        ?>
            <div class="flex flex-col gap-1.5">
                <div class="flex justify-between items-center">
                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-bold border"
                          style="color:<?= $textColor ?>;border-color:<?= $textColor ?>33;background-color:<?= $textColor ?>18">
                        <?= View::e($row['tag']) ?>
                    </span>
                    <span class="text-xs font-bold text-[#cdd6f4]"><?= (int) $row['n'] ?></span>
                </div>
                <div class="w-full h-1.5 rounded-full bg-[#313244] overflow-hidden">
                    <div class="h-full rounded-full transition-all duration-500"
                         style="width:<?= $pct ?>%;background-color:<?= $textColor ?>"></div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- By project -->
<div>
    <h2 class="text-[#a6adc8] text-xs font-bold tracking-widest uppercase mb-4">Releases by project</h2>
    <?php if (empty($stats['by_project'])): ?>
        <!-- Empty state from layout/empty_state -->
        <div class="flex flex-col items-center justify-center gap-3 py-12 text-center rounded-lg border border-[#313244]">
            <p class="text-[#585b70] text-sm">No data yet.</p>
            <a href="/new" class="text-xs text-[#89b4fa] hover:text-[#cba6f7] transition-colors duration-150 font-bold">
                Add a release
            </a>
        </div>
    <?php else: ?>
        <div class="flex flex-col gap-px">
            <?php foreach ($stats['by_project'] as $row): ?>
                <div class="rounded-lg border border-[#313244] bg-[#181825] px-4 py-3
                            hover:border-[#45475a] transition-colors duration-150
                            flex items-center justify-between">
                    <a href="/?project=<?= urlencode($row['project']) ?>"
                       class="text-[#89b4fa] hover:text-[#cba6f7] text-sm transition-colors duration-150">
                        <?= View::e($row['project']) ?>
                    </a>
                    <span class="text-[#585b70] text-xs">
                        <?= (int) $row['n'] ?> release<?= $row['n'] != 1 ? 's' : '' ?>
                    </span>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php
$content = ob_get_clean();
$title   = 'stats — devlog';
require __DIR__ . '/layout.php';
