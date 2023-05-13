<?php

declare(strict_types=1);

namespace Devlog;

use PDO;
use PDOException;

/**
 * Thin PDO wrapper. Handles connection, migrations, and query helpers.
 * One instance per request — no connection pooling needed at this scale.
 */
class Database
{
    private PDO $pdo;

    public function __construct(string $path)
    {
        $dir = dirname($path);
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        try {
            $this->pdo = new PDO('sqlite:' . $path, options: [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);
        } catch (PDOException $e) {
            throw new \RuntimeException('Database connection failed: ' . $e->getMessage());
        }

        $this->migrate();
    }

    /**
     * Run schema migrations. Idempotent — safe to call on every boot.
     */
    private function migrate(): void
    {
        $this->pdo->exec("
            CREATE TABLE IF NOT EXISTS releases (
                id          INTEGER PRIMARY KEY AUTOINCREMENT,
                project     TEXT    NOT NULL,
                version     TEXT    NOT NULL,
                tag         TEXT    NOT NULL DEFAULT 'feat',
                body        TEXT    NOT NULL DEFAULT '',
                released_at TEXT    NOT NULL DEFAULT (datetime('now'))
            );
        ");
    }

    /**
     * Execute a SELECT and return all rows.
     *
     * @param array<string, mixed> $params
     * @return array<int, array<string, mixed>>
     */
    public function fetchAll(string $sql, array $params = []): array
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    /**
     * Execute a SELECT and return a single row, or null if not found.
     *
     * @param array<string, mixed> $params
     * @return array<string, mixed>|null
     */
    public function fetchOne(string $sql, array $params = []): ?array
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        $row = $stmt->fetch();
        return $row !== false ? $row : null;
    }

    /**
     * Execute an INSERT/UPDATE/DELETE and return the last insert ID.
     *
     * @param array<string, mixed> $params
     */
    public function execute(string $sql, array $params = []): string
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $this->pdo->lastInsertId();
    }
}
