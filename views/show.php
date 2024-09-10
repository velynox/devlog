<?php
/** @var array<string, mixed> $release */

use Devlog\View;

ob_start();
[$textColor, $accentColor] = View::tagColors($release['tag']);
?>

<!-- Back button — from navigation/back_button component -->
<a href="/"
   class="inline-flex items-center gap-1.5 text-sm text-[#a6adc8]
          hover:text-[#cdd6f4] transition-colors duration-100 group w-fit mb-8">
    <svg xmlns="http://www.w3.org/2000/svg"
         class="w-4 h-4 transition-transform duration-150 group-hover:-translate-x-0.5"
         fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
    </svg>
    back to timeline
</a>

<!-- Header -->
<div class="mb-6">
    <div class="flex items-center gap-3 flex-wrap mb-2">
        <h1 class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-md border
                   border-[#cba6f7]/30 bg-[#cba6f7]/8 text-[#cba6f7] font-bold text-xl tracking-tight">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 009.568 3z"/>
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6z"/>
            </svg>
            <?= View::e($release['version']) ?>
        </h1>
        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full
                     text-xs font-bold tracking-wide border"
              style="color:<?= $textColor ?>;border-color:<?= $accentColor ?>33;background-color:<?= $accentColor ?>18">
            <?= View::e($release['tag']) ?>
        </span>
    </div>
    <div class="flex items-center gap-3 text-xs text-[#585b70]">
        <span><?= View::e($release['project']) ?></span>
        <span class="w-px h-3 bg-[#313244]"></span>
        <time datetime="<?= View::e($release['released_at']) ?>">
            <?= View::e(date('D, d M Y', strtotime($release['released_at']))) ?>
        </time>
    </div>
</div>

<!-- Release notes card -->
<div class="rounded-lg border border-[#313244] bg-[#181825] px-6 py-6 mb-8">
    <?php if ($release['body'] !== ''): ?>
        <div class="text-[#cdd6f4] text-sm leading-relaxed whitespace-pre-wrap"><?= View::e($release['body']) ?></div>
    <?php else: ?>
        <p class="text-[#585b70] text-sm italic">No release notes provided.</p>
    <?php endif; ?>
</div>

<!-- Action buttons — ghost + danger from buttons/ghost component -->
<div class="flex items-center gap-3">
    <a href="/edit/<?= (int)$release['id'] ?>"
       class="inline-flex items-center gap-2 px-4 py-2 rounded-md font-bold text-sm
              border border-[#313244] text-[#a6adc8] bg-transparent
              hover:text-[#cdd6f4] hover:border-[#45475a]
              focus:outline-none focus:ring-2 focus:ring-[#cba6f7] focus:ring-offset-2 focus:ring-offset-[#1e1e2e]
              transition-colors duration-150">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"/>
        </svg>
        edit
    </a>
    <a href="/delete/<?= (int)$release['id'] ?>"
       onclick="return confirm('Delete this release? This cannot be undone.')"
       class="inline-flex items-center gap-2 px-4 py-2 rounded-md font-bold text-sm
              border border-[#f38ba8]/30 text-[#f38ba8] bg-transparent
              hover:bg-[#f38ba8]/10 hover:border-[#f38ba8]/60
              focus:outline-none focus:ring-2 focus:ring-[#f38ba8] focus:ring-offset-2 focus:ring-offset-[#1e1e2e]
              transition-colors duration-150">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/>
        </svg>
        delete
    </a>
</div>

<?php
$content = ob_get_clean();
$title   = $release['project'] . ' ' . $release['version'] . ' — devlog';
require __DIR__ . '/layout.php';
