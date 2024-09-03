<?php

declare(strict_types=1);

namespace Devlog;

/**
 * Minimal view renderer. Renders PHP template files from the views/ directory.
 * Passes data as extracted local variables.
 */
class View
{
    private string $viewsPath;

    public function __construct(string $viewsPath)
    {
        $this->viewsPath = rtrim($viewsPath, '/');
    }

    /**
     * Render a view file and echo the output.
     *
     * @param array<string, mixed> $data
     */
    public function render(string $name, array $data = []): void
    {
        $file = $this->viewsPath . '/' . $name . '.php';

        if (!file_exists($file)) {
            throw new \RuntimeException("View not found: {$name}");
        }

        extract($data, EXTR_SKIP);
        require $file;
    }

    /**
     * Escape a string for safe HTML output.
     */
    public static function e(mixed $value): string
    {
        return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }

    /**
     * Map a release tag to its Catppuccin Mocha color class (legacy, kept for compat).
     */
    public static function tagColor(string $tag): string
    {
        return match ($tag) {
            'feat'     => 'text-ctp-green  border-ctp-green',
            'fix'      => 'text-ctp-yellow border-ctp-yellow',
            'breaking' => 'text-ctp-red    border-ctp-red',
            'security' => 'text-ctp-peach  border-ctp-peach',
            'docs'     => 'text-ctp-blue   border-ctp-blue',
            default    => 'text-ctp-subtext0 border-ctp-surface1',
        };
    }

    /**
     * Return [textHex, accentHex] for inline-style pill badges (component library style).
     *
     * @return array{string, string}
     */
    public static function tagColors(string $tag): array
    {
        return match ($tag) {
            'feat'     => ['#a6e3a1', '#a6e3a1'],
            'fix'      => ['#f9e2af', '#f9e2af'],
            'breaking' => ['#f38ba8', '#f38ba8'],
            'security' => ['#fab387', '#fab387'],
            'docs'     => ['#89b4fa', '#89b4fa'],
            'chore'    => ['#a6adc8', '#a6adc8'],
            default    => ['#a6adc8', '#a6adc8'],
        };
    }
}
