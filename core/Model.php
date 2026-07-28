<?php
namespace Core;

abstract class Model
{
    protected Database $db;
    protected string $table;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function find(int $id): ?array
    {
        return $this->db->fetch("SELECT * FROM {$this->table} WHERE id = ?", [$id]);
    }

    public function findAll(string $orderBy = 'id DESC'): array
    {
        return $this->db->fetchAll("SELECT * FROM {$this->table} ORDER BY {$orderBy}");
    }

    public function count(string $where = '1=1', array $params = []): int
    {
        $row = $this->db->fetch("SELECT COUNT(*) as total FROM {$this->table} WHERE {$where}", $params);
        return (int)($row['total'] ?? 0);
    }

    public function delete(int $id): bool
    {
        return $this->db->execute("DELETE FROM {$this->table} WHERE id = ?", [$id]);
    }
}
