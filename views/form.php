<?php
/**
 * Shared form view for creating and editing releases.
 *
 * @var array<string, mixed>|null $release
 * @var array<string, string>     $errors
 * @var array<string, string>     $old
 * @var string                    $action
 * @var string                    $heading
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

<!-- Back button — from navigation/back_button component -->
<a href="/"
   class="inline-flex items-center gap-1.5 text-sm text-[#a6adc8]
          hover:text-[#cdd6f4] transition-colors duration-100 group w-fit mb-8">
    <svg xmlns="http://www.w3.org/2000/svg"
         class="w-4 h-4 transition-transform duration-150 group-hover:-translate-x-0.5"
         fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
    </svg>
    back
</a>

<!-- Section header from layout/section_header -->
<div class="mb-8">
    <h1 class="text-[#cdd6f4] font-bold text-xl"><?= View::e($heading) ?></h1>
</div>

<!-- Error alert — from alerts/success (error variant) -->
<?php if (!empty($errors)): ?>
    <div class="flex items-start gap-3 rounded-lg border border-[#f38ba8]/30
                bg-[#f38ba8]/5 px-4 py-3 mb-6">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-[#f38ba8] shrink-0 mt-0.5"
             fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
            <path stroke-linecap="round" stroke-linejoin="round"
                  d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/>
        </svg>
        <div class="flex flex-col gap-0.5">
            <?php foreach ($errors as $err): ?>
                <p class="text-[#f38ba8] text-sm"><?= View::e($err) ?></p>
            <?php endforeach; ?>
        </div>
    </div>
<?php endif; ?>

<!-- Form — inputs from forms/input_group, select from forms/select, textarea from forms/textarea -->
<form method="POST" action="<?= View::e($action) ?>" class="flex flex-col gap-5">

    <!-- Project -->
    <div class="flex flex-col gap-1.5">
        <label for="project" class="text-xs font-bold tracking-widest uppercase text-[#a6adc8]">
            Project
        </label>
        <input
            id="project" name="project" type="text"
            value="<?= $val('project') ?>"
            placeholder="velynox/devlog"
            class="w-full rounded-md border border-[#313244] bg-[#181825]
                   px-3 py-2 text-sm text-[#cdd6f4] placeholder-[#585b70]
                   focus:outline-none focus:ring-2 focus:ring-[#cba6f7] focus:border-transparent
                   transition duration-150"
            required
        >
    </div>

    <!-- Version + Tag row -->
    <div class="flex gap-4">
        <div class="flex flex-col gap-1.5 flex-1">
            <label for="version" class="text-xs font-bold tracking-widest uppercase text-[#a6adc8]">
                Version
            </label>
            <input
                id="version" name="version" type="text"
                value="<?= $val('version') ?>"
                placeholder="1.0.0"
                class="w-full rounded-md border border-[#313244] bg-[#181825]
                       px-3 py-2 text-sm text-[#cdd6f4] placeholder-[#585b70]
                       focus:outline-none focus:ring-2 focus:ring-[#cba6f7] focus:border-transparent
                       transition duration-150"
                required
            >
        </div>
        <div class="flex flex-col gap-1.5">
            <label for="tag" class="text-xs font-bold tracking-widest uppercase text-[#a6adc8]">
                Tag
            </label>
            <div class="relative">
                <select
                    id="tag" name="tag"
                    class="appearance-none rounded-md border border-[#313244] bg-[#181825]
                           px-3 py-2 pr-8 text-sm text-[#cdd6f4] cursor-pointer
                           focus:outline-none focus:ring-2 focus:ring-[#cba6f7] focus:border-transparent
                           transition duration-150"
                >
                    <?php foreach (Release::TAGS as $t): ?>
                        <option value="<?= View::e($t) ?>" <?= ($val('tag', 'feat') === $t) ? 'selected' : '' ?>>
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
    </div>

    <!-- Released at -->
    <div class="flex flex-col gap-1.5">
        <label for="released_at" class="text-xs font-bold tracking-widest uppercase text-[#a6adc8]">
            Released at
        </label>
        <input
            id="released_at" name="released_at" type="datetime-local"
            value="<?= $val('released_at', date('Y-m-d\TH:i')) ?>"
            class="w-full rounded-md border border-[#313244] bg-[#181825]
                   px-3 py-2 text-sm text-[#cdd6f4]
                   focus:outline-none focus:ring-2 focus:ring-[#cba6f7] focus:border-transparent
                   transition duration-150"
        >
    </div>

    <!-- Body — from forms/textarea component -->
    <div class="flex flex-col gap-1.5">
        <label for="body" class="text-xs font-bold tracking-widest uppercase text-[#a6adc8]">
            Release notes
        </label>
        <textarea
            id="body" name="body"
            rows="8"
            placeholder="What changed in this release..."
            class="w-full rounded-md border border-[#313244] bg-[#181825]
                   px-3 py-2 text-sm text-[#cdd6f4] placeholder-[#585b70]
                   leading-relaxed resize-y
                   focus:outline-none focus:ring-2 focus:ring-[#cba6f7] focus:border-transparent
                   transition duration-150"
        ><?= $val('body') ?></textarea>
        <p class="text-xs text-[#585b70]">Plain text. Newlines are preserved.</p>
    </div>

    <!-- Actions — primary + ghost buttons from component library -->
    <div class="flex items-center gap-3 pt-2">
        <button type="submit"
                class="px-5 py-2 rounded-md font-bold text-sm
                       bg-[#cba6f7] text-[#1e1e2e]
                       hover:bg-[#b48ef0]
                       focus:outline-none focus:ring-2 focus:ring-[#cba6f7] focus:ring-offset-2 focus:ring-offset-[#1e1e2e]
                       transition-colors duration-150">
            save
        </button>
        <a href="/"
           class="px-5 py-2 rounded-md font-bold text-sm
                  border border-[#313244] text-[#a6adc8] bg-transparent
                  hover:text-[#cdd6f4] hover:border-[#45475a]
                  transition-colors duration-150">
            cancel
        </a>
    </div>

</form>

<?php
$content = ob_get_clean();
require __DIR__ . '/layout.php';
