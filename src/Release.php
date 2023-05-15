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
     * Optionally filter by project slug and/or tag.
     *
     * @return array<int, array<string, mixed>>
     */
    public function all(?string $project = null, ?string $tag = null): array
    {
        $where  = [];
        $params = [];

        if ($project !== null && $project !== '') {
            $where[]            = 'project = :project';
            $params[':project'] = $project;
        }

        if ($tag !== null && $tag !== '') {
            $where[]         = 'tag = :tag';
            $params[':tag']  = $tag;
        }

        $sql = 'SELECT * FROM releases';
        if ($where) {
            $sql .= ' WHERE ' . implode(' AND ', $where);
        }
        $sql .= ' ORDER BY released_at DESC, id DESC';

        return $this->db->fetchAll($sql, $params);
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
}
