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
                    fontFamily: {
                        mono: ['"JetBrains Mono"', 'monospace'],
                    },
                    colors: {
                        ctp: {
                            base:     '#1e1e2e',
                            mantle:   '#181825',
                            crust:    '#11111b',
                            surface0: '#313244',
                            surface1: '#45475a',
                            surface2: '#585b70',
                            overlay0: '#6c7086',
                            overlay1: '#7f849c',
                            subtext0: '#a6adc8',
                            subtext1: '#bac2de',
                            text:     '#cdd6f4',
                            lavender: '#b4befe',
                            blue:     '#89b4fa',
                            sapphire: '#74c7ec',
                            sky:      '#89dceb',
                            teal:     '#94e2d5',
                            green:    '#a6e3a1',
                            yellow:   '#f9e2af',
                            peach:    '#fab387',
                            maroon:   '#eba0ac',
                            red:      '#f38ba8',
                            mauve:    '#cba6f7',
                            pink:     '#f5c2e7',
                            flamingo: '#f2cdcd',
                            rosewater:'#f5e0dc',
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
<body class="bg-ctp-base text-ctp-text min-h-screen">

    <header class="border-b border-ctp-surface0 bg-ctp-mantle">
        <div class="max-w-3xl mx-auto px-6 py-4 flex items-center justify-between">
            <a href="/" class="flex items-center gap-2 text-ctp-mauve font-bold tracking-widest uppercase text-sm hover:text-ctp-lavender transition-colors">
                <!-- terminal icon -->
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <polyline points="4 17 10 11 4 5"></polyline>
                    <line x1="12" y1="19" x2="20" y2="19"></line>
                </svg>
                devlog
            </a>
            <div class="flex items-center gap-2">
                <a href="/stats" class="flex items-center gap-1.5 text-xs text-ctp-subtext0 hover:text-ctp-text px-3 py-1.5 transition-colors">
                    <!-- bar-chart icon -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <line x1="18" y1="20" x2="18" y2="10"></line>
                        <line x1="12" y1="20" x2="12" y2="4"></line>
                        <line x1="6" y1="20" x2="6" y2="14"></line>
                    </svg>
                    stats
                </a>
                <a href="/new" class="flex items-center gap-1.5 text-xs text-ctp-subtext0 hover:text-ctp-text border border-ctp-surface0 hover:border-ctp-surface1 px-3 py-1.5 transition-colors">
                    <!-- plus icon -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <line x1="12" y1="5" x2="12" y2="19"></line>
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                    </svg>
                    new release
                </a>
            </div>
        </div>
    </header>

    <main class="max-w-3xl mx-auto px-6 py-12">
        <?= $content ?>
    </main>

    <footer class="border-t border-ctp-surface0 mt-20">
        <div class="max-w-3xl mx-auto px-6 py-6 text-ctp-overlay0 text-xs tracking-widest uppercase">
            devlog &mdash; velynox.de
        </div>
    </footer>

</body>
</html>
