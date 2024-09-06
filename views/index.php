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

<!-- Section header — from layout/section_header component -->
<div class="flex items-start justify-between mb-8">
    <div class="flex flex-col gap-0.5">
        <h1 class="text-[#cdd6f4] font-bold text-xl tracking-tight">Release Timeline</h1>
        <p class="text-[#585b70] text-xs">
            <?= $paginator->total ?> release<?= $paginator->total !== 1 ? 's' : '' ?> logged.
        </p>
    </div>
    <?php if ($filterProject || $filterTag || $filterSearch): ?>
        <a href="/" class="inline-flex items-center gap-1 text-xs text-[#89b4fa] hover:text-[#cba6f7] transition-colors duration-150 font-bold">
            clear filters
            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </a>
    <?php endif; ?>
</div>

<!-- Filter bar — search from forms/search, selects from forms/select -->
<form method="GET" action="/" class="flex flex-wrap gap-3 mb-10 items-end">
    <!-- Search with icon -->
    <div class="flex flex-col gap-1.5 flex-1 min-w-[180px]">
        <label class="text-[#585b70] text-xs font-bold tracking-widest uppercase" for="q">Search</label>
        <div class="relative">
            <div class="pointer-events-none absolute inset-y-0 left-3 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-[#585b70]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/>
                </svg>
            </div>
            <input
                id="q" name="q" type="search"
                value="<?= View::e($filterSearch) ?>"
                placeholder="project, version, notes..."
                class="w-full rounded-md border border-[#313244] bg-[#181825] pl-9 pr-3 py-2
                       text-sm text-[#cdd6f4] placeholder-[#585b70]
                       focus:outline-none focus:ring-2 focus:ring-[#cba6f7] focus:border-transparent
                       transition duration-150"
            >
        </div>
    </div>

    <!-- Project select -->
    <div class="flex flex-col gap-1.5">
        <label class="text-[#585b70] text-xs font-bold tracking-widest uppercase" for="project">Project</label>
        <div class="relative">
            <select id="project" name="project"
                    class="appearance-none rounded-md border border-[#313244] bg-[#181825]
                           px-3 py-2 pr-8 text-sm text-[#cdd6f4] cursor-pointer
                           focus:outline-none focus:ring-2 focus:ring-[#cba6f7] focus:border-transparent
                           transition duration-150">
                <option value="">all projects</option>
                <?php foreach ($projects as $p): ?>
                    <option value="<?= View::e($p) ?>" <?= $filterProject === $p ? 'selected' : '' ?>>
                        <?= View::e($p) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <div class="pointer-events-none absolute inset-y-0 right-2.5 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-[#585b70]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                </svg>
            </div>
        </div>
    </div>

    <!-- Tag select -->
    <div class="flex flex-col gap-1.5">
        <label class="text-[#585b70] text-xs font-bold tracking-widest uppercase" for="tag">Tag</label>
        <div class="relative">
            <select id="tag" name="tag"
                    class="appearance-none rounded-md border border-[#313244] bg-[#181825]
                           px-3 py-2 pr-8 text-sm text-[#cdd6f4] cursor-pointer
                           focus:outline-none focus:ring-2 focus:ring-[#cba6f7] focus:border-transparent
                           transition duration-150">
                <option value="">all tags</option>
                <?php foreach (Release::TAGS as $t): ?>
                    <option value="<?= View::e($t) ?>" <?= $filterTag === $t ? 'selected' : '' ?>>
                        <?= View::e($t) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <div class="pointer-events-none absolute inset-y-0 right-2.5 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-[#585b70]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                </svg>
            </div>
        </div>
    </div>

    <button type="submit"
            class="px-4 py-2 rounded-md font-bold text-sm border border-[#313244]
                   text-[#a6adc8] hover:text-[#cdd6f4] hover:border-[#45475a]
                   transition-colors duration-150">
        filter
    </button>
</form>

<!-- Timeline — spine pattern from cards/changelog component -->
<?php if (empty($releases)): ?>
    <!-- Empty state from layout/empty_state component -->
    <div class="flex flex-col items-center justify-center gap-4 py-16 text-center">
        <div class="w-14 h-14 rounded-2xl border border-[#313244] bg-[#181825] flex items-center justify-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 text-[#45475a]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z"/>
            </svg>
        </div>
        <div class="flex flex-col gap-1">
            <p class="text-[#cdd6f4] font-bold text-sm">No releases found</p>
            <p class="text-[#585b70] text-xs leading-relaxed">
                <?php if ($filterProject || $filterTag || $filterSearch): ?>
                    Try adjusting your filters.
                <?php else: ?>
                    Nothing logged yet.
                <?php endif; ?>
            </p>
        </div>
        <?php if (!$filterProject && !$filterTag && !$filterSearch): ?>
            <a href="/new"
               class="inline-flex items-center gap-2 px-4 py-2 rounded-md border border-[#313244]
                      text-[#a6adc8] text-sm font-bold hover:text-[#cdd6f4] hover:border-[#45475a]
                      transition-colors duration-150">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                </svg>
                Add first release
            </a>
        <?php endif; ?>
    </div>
<?php else: ?>
    <div class="flex flex-col gap-4">
        <?php foreach ($releases as $release):
            [$textColor, $borderColor] = View::tagColors($release['tag']);
        ?>
            <!-- Release card — rounded, hover border lift from cards/repo -->
            <div class="rounded-lg border border-[#313244] bg-[#181825] px-5 py-4
                        hover:border-[#45475a] transition-colors duration-150">
                <div class="flex items-start justify-between gap-4">
                    <div class="flex items-center gap-2.5 flex-wrap">
                        <!-- Version badge from badges/version component -->
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md
                                     border text-xs font-bold tracking-wide
                                     border-[#cba6f7]/30 bg-[#cba6f7]/8 text-[#cba6f7]">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 009.568 3z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6z"/>
                            </svg>
                            <?= View::e($release['version']) ?>
                        </span>
                        <!-- Tag pill from badges/tag component -->
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full
                                     text-xs font-bold tracking-wide border"
                              style="color:<?= $textColor ?>;border-color:<?= $borderColor ?>33;background-color:<?= $borderColor ?>18">
                            <?= View::e($release['tag']) ?>
                        </span>
                        <span class="text-[#585b70] text-xs"><?= View::e($release['project']) ?></span>
                    </div>
                    <div class="flex items-center gap-3 shrink-0">
                        <time class="text-[#585b70] text-xs" datetime="<?= View::e($release['released_at']) ?>">
                            <?= View::e(date('Y-m-d', strtotime($release['released_at']))) ?>
                        </time>
                        <!-- back_button arrow style -->
                        <a href="/release/<?= (int)$release['id'] ?>"
                           class="text-[#585b70] hover:text-[#89b4fa] transition-colors duration-150 group"
                           title="View release">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 transition-transform duration-150 group-hover:translate-x-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                    </div>
                </div>
                <?php if ($release['body'] !== ''): ?>
                    <p class="mt-2.5 text-[#a6adc8] text-xs leading-relaxed">
                        <?= View::e(mb_strimwidth($release['body'], 0, 160, '...')) ?>
                    </p>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Pagination — from navigation/pagination component -->
    <?php if ($paginator->totalPages > 1):
        $qp = array_filter([
            'q'       => $filterSearch,
            'project' => $filterProject,
            'tag'     => $filterTag,
        ]);
    ?>
        <nav aria-label="Pagination" class="flex items-center justify-between mt-10">
            <span class="text-[#585b70] text-xs">
                page <?= $paginator->currentPage ?> of <?= $paginator->totalPages ?>
            </span>
            <div class="flex items-center gap-1">
                <?php if ($paginator->hasPrev()): ?>
                    <a href="<?= View::e($paginator->pageUrl($paginator->currentPage - 1, $qp)) ?>"
                       aria-label="Previous page"
                       class="flex items-center justify-center w-8 h-8 rounded-md border
                              border-[#313244] text-[#a6adc8] hover:text-[#cdd6f4]
                              hover:border-[#45475a] transition-colors duration-100">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                        </svg>
                    </a>
                <?php endif; ?>

                <?php for ($i = 1; $i <= $paginator->totalPages; $i++):
                    if ($i === $paginator->currentPage): ?>
                        <span aria-current="page"
                              class="flex items-center justify-center w-8 h-8 rounded-md
                                     bg-[#cba6f7] text-[#1e1e2e] text-sm font-bold">
                            <?= $i ?>
                        </span>
                    <?php elseif ($i === 1 || $i === $paginator->totalPages || abs($i - $paginator->currentPage) <= 1): ?>
                        <a href="<?= View::e($paginator->pageUrl($i, $qp)) ?>"
                           class="flex items-center justify-center w-8 h-8 rounded-md border
                                  border-[#313244] text-[#a6adc8] text-sm
                                  hover:text-[#cdd6f4] hover:border-[#45475a]
                                  transition-colors duration-100">
                            <?= $i ?>
                        </a>
                    <?php elseif (abs($i - $paginator->currentPage) === 2): ?>
                        <span class="flex items-center justify-center w-8 h-8 text-[#585b70] text-sm">&hellip;</span>
                    <?php endif;
                endfor; ?>

                <?php if ($paginator->hasNext()): ?>
                    <a href="<?= View::e($paginator->pageUrl($paginator->currentPage + 1, $qp)) ?>"
                       aria-label="Next page"
                       class="flex items-center justify-center w-8 h-8 rounded-md border
                              border-[#313244] text-[#a6adc8] hover:text-[#cdd6f4]
                              hover:border-[#45475a] transition-colors duration-100">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                <?php endif; ?>
            </div>
        </nav>
    <?php endif; ?>
<?php endif; ?>

<?php
$content = ob_get_clean();
$title   = 'devlog';
require __DIR__ . '/layout.php';
