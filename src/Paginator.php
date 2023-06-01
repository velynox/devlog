<?php

declare(strict_types=1);

namespace Devlog;

/**
 * Simple offset-based paginator.
 * Calculates page boundaries and generates page link data.
 */
class Paginator
{
    public readonly int $totalPages;
    public readonly int $offset;

    public function __construct(
        public readonly int $total,
        public readonly int $perPage,
        public readonly int $currentPage,
    ) {
        $this->totalPages = (int) ceil($total / max(1, $perPage));
        $this->offset     = ($currentPage - 1) * $perPage;
    }

    public function hasPrev(): bool
    {
        return $this->currentPage > 1;
    }

    public function hasNext(): bool
    {
        return $this->currentPage < $this->totalPages;
    }

    /**
     * Build a query string preserving existing params but overriding `page`.
     */
    public function pageUrl(int $page, array $existing = []): string
    {
        $params = array_merge($existing, ['page' => $page]);
        unset($params['page']);
        $qs = http_build_query(array_merge($params, ['page' => $page]));
        return '/?' . $qs;
    }
}
