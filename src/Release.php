<?php

declare(strict_types=1);

namespace Devlog;

/**
 * Release model. Wraps database queries related to the `releases` table.
 * No magic, no ORM — just named methods that return plain arrays.
 */
class Release
{
    /** Valid tag values. Enforced on insert. */
    public const TAGS = ['feat', 'fix', 'chore', 'breaking', 'security', 'docs'];

    public function __construct(private readonly Database $db) {}

    /**
     * Return all releases, newest first.
     * Optionally filter by project, tag, and/or full-text search query.
     *
     * @return array<int, array<string, mixed>>
     */
    public function all(
        ?string $project = null,
        ?string $tag     = null,
        ?string $search  = null,
        int     $limit   = 0,
        int     $offset  = 0,
    ): array {
        [$sql, $params] = $this->buildQuery($project, $tag, $search);
        $sql .= ' ORDER BY released_at DESC, id DESC';

        if ($limit > 0) {
            $sql           .= ' LIMIT :limit OFFSET :offset';
            $params[':limit']  = $limit;
            $params[':offset'] = $offset;
        }

        return $this->db->fetchAll($sql, $params);
    }

    /**
     * Count releases matching the given filters (for pagination).
     */
    public function count(
        ?string $project = null,
        ?string $tag     = null,
        ?string $search  = null,
    ): int {
        [$sql, $params] = $this->buildQuery($project, $tag, $search);
        $countSql = 'SELECT COUNT(*) as n FROM (' . $sql . ')';
        $row      = $this->db->fetchOne($countSql, $params);
        return (int) ($row['n'] ?? 0);
    }

    /**
     * Build the base SELECT + WHERE clause shared by all() and count().
     *
     * @return array{string, array<string, mixed>}
     */
    private function buildQuery(
        ?string $project,
        ?string $tag,
        ?string $search,
    ): array {
        $where  = [];
        $params = [];

        if ($project !== null && $project !== '') {
            $where[]            = 'project = :project';
            $params[':project'] = $project;
        }

        if ($tag !== null && $tag !== '') {
            $where[]        = 'tag = :tag';
            $params[':tag'] = $tag;
        }

        if ($search !== null && $search !== '') {
            $where[]           = '(project LIKE :search OR version LIKE :search OR body LIKE :search)';
            $params[':search'] = '%' . $search . '%';
        }

        $sql = 'SELECT * FROM releases';
        if ($where) {
            $sql .= ' WHERE ' . implode(' AND ', $where);
        }

        return [$sql, $params];
    }

    /**
     * Find a single release by ID. Returns null if not found.
     *
     * @return array<string, mixed>|null
     */
    public function find(int $id): ?array
    {
        return $this->db->fetchOne(
            'SELECT * FROM releases WHERE id = :id',
            [':id' => $id]
        );
    }

    /**
     * Insert a new release. Returns the new row's ID.
     *
     * @param array<string, string> $data Keys: project, version, tag, body, released_at
     */
    public function create(array $data): int
    {
        $tag = in_array($data['tag'], self::TAGS, true) ? $data['tag'] : 'feat';

        return (int) $this->db->execute(
            'INSERT INTO releases (project, version, tag, body, released_at)
             VALUES (:project, :version, :tag, :body, :released_at)',
            [
                ':project'     => trim($data['project']),
                ':version'     => trim($data['version']),
                ':tag'         => $tag,
                ':body'        => trim($data['body']),
                ':released_at' => $data['released_at'] ?? date('Y-m-d H:i:s'),
            ]
        );
    }

    /**
     * Delete a release by ID.
     */
    public function delete(int $id): void
    {
        $this->db->execute(
            'DELETE FROM releases WHERE id = :id',
            [':id' => $id]
        );
    }

    /**
     * Return a distinct list of project names for the filter UI.
     *
     * @return string[]
     */
    public function projects(): array
    {
        $rows = $this->db->fetchAll('SELECT DISTINCT project FROM releases ORDER BY project ASC');
        return array_column($rows, 'project');
    }

    /**
     * Return aggregate stats for the stats page.
     *
     * @return array<string, mixed>
     */
    public function stats(): array
    {
        $total = $this->db->fetchOne('SELECT COUNT(*) as n FROM releases');

        $byTag = $this->db->fetchAll(
            'SELECT tag, COUNT(*) as n FROM releases GROUP BY tag ORDER BY n DESC'
        );

        $byProject = $this->db->fetchAll(
            'SELECT project, COUNT(*) as n FROM releases GROUP BY project ORDER BY n DESC'
        );

        $latest = $this->db->fetchOne(
            'SELECT released_at FROM releases ORDER BY released_at DESC LIMIT 1'
        );

        $oldest = $this->db->fetchOne(
            'SELECT released_at FROM releases ORDER BY released_at ASC LIMIT 1'
        );

        return [
            'total'      => (int) ($total['n'] ?? 0),
            'by_tag'     => $byTag,
            'by_project' => $byProject,
            'latest'     => $latest['released_at'] ?? null,
            'oldest'     => $oldest['released_at'] ?? null,
        ];
    }
}
