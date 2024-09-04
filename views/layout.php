<!DOCTYPE html>
<html lang="en" class="bg-[#1e1e2e]">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= \Devlog\View::e($title ?? 'devlog') ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { mono: ['"JetBrains Mono"', 'monospace'] },
                    colors: {
                        ctp: {
                            base:      '#1e1e2e',
                            mantle:    '#181825',
                            crust:     '#11111b',
                            surface0:  '#313244',
                            surface1:  '#45475a',
                            surface2:  '#585b70',
                            overlay0:  '#6c7086',
                            overlay1:  '#7f849c',
                            subtext0:  '#a6adc8',
                            subtext1:  '#bac2de',
                            text:      '#cdd6f4',
                            lavender:  '#b4befe',
                            blue:      '#89b4fa',
                            sapphire:  '#74c7ec',
                            sky:       '#89dceb',
                            teal:      '#94e2d5',
                            green:     '#a6e3a1',
                            yellow:    '#f9e2af',
                            peach:     '#fab387',
                            maroon:    '#eba0ac',
                            red:       '#f38ba8',
                            mauve:     '#cba6f7',
                            pink:      '#f5c2e7',
                            flamingo:  '#f2cdcd',
                            rosewater: '#f5e0dc',
                        }
                    }
                }
            }
        }
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'JetBrains Mono', monospace; }
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #181825; }
        ::-webkit-scrollbar-thumb { background: #313244; border-radius: 3px; }
    </style>
</head>
<body class="bg-[#1e1e2e] text-[#cdd6f4] min-h-screen flex flex-col">

    <!-- Sticky header — backdrop-blur from sticky_header component -->
    <header class="sticky top-0 z-50 bg-[#181825]/90 backdrop-blur border-b border-[#313244]">
        <div class="max-w-3xl mx-auto px-6 py-3 flex items-center justify-between">
            <a href="/" class="flex items-center gap-2 text-[#cba6f7] font-bold tracking-widest uppercase text-sm hover:text-[#b4befe] transition-colors duration-150">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <polyline points="4 17 10 11 4 5"></polyline>
                    <line x1="12" y1="19" x2="20" y2="19"></line>
                </svg>
                devlog
            </a>
            <nav class="flex items-center gap-1">
                <a href="/stats" class="flex items-center gap-1.5 text-xs text-[#a6adc8] hover:text-[#cdd6f4] px-3 py-1.5 rounded-md hover:bg-[#313244]/50 transition-colors duration-150">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <line x1="18" y1="20" x2="18" y2="10"></line>
                        <line x1="12" y1="20" x2="12" y2="4"></line>
                        <line x1="6" y1="20" x2="6" y2="14"></line>
                    </svg>
                    stats
                </a>
                <!-- primary button style from buttons/primary component -->
                <a href="/new" class="flex items-center gap-1.5 text-xs font-bold px-3 py-1.5 rounded-md bg-[#cba6f7] text-[#1e1e2e] hover:bg-[#b48ef0] transition-colors duration-150">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                    </svg>
                    new release
                </a>
            </nav>
        </div>
    </header>

    <main class="flex-1 max-w-3xl w-full mx-auto px-6 py-12">
        <?= $content ?>
    </main>

    <!-- Footer from layout/footer component -->
    <footer class="border-t border-[#313244] mt-auto">
        <div class="max-w-3xl mx-auto px-6 py-6 flex items-center justify-between">
            <p class="text-[#585b70] text-xs">
                &copy; <?= date('Y') ?> velynox. MIT License.
            </p>
            <p class="text-[#585b70] text-xs">Built with PHP &amp; TailwindCSS.</p>
        </div>
    </footer>

</body>
</html>
