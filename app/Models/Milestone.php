<?php
namespace App\Models;

use Core\Model;

class Milestone extends Model
{
    protected string $table = 'milestones';

    public function getByProject(int $projectId): array
    {
        return $this->db->fetchAll(
            "SELECT m.*, 
                    (SELECT COUNT(*) FROM tasks t WHERE t.milestone_id = m.id) as total_tasks,
                    (SELECT COUNT(*) FROM tasks t WHERE t.milestone_id = m.id AND t.status = 'done') as completed_tasks
             FROM milestones m 
             WHERE m.project_id = ? 
             ORDER BY m.due_date ASC",
            [$projectId]
        );
    }

    public function create(array $data): int
    {
        $sql = "INSERT INTO milestones (project_id, title, description, due_date, status) VALUES (?, ?, ?, ?, ?)";
        $this->db->execute($sql, [
            $data['project_id'],
            $data['title'],
            $data['description'] ?? null,
            $data['due_date'] ?? null,
            $data['status'] ?? 'pending'
        ]);
        return (int)$this->db->lastInsertId();
    }

    public function update(int $id, array $data): bool
    {
        $sql = "UPDATE milestones SET title = ?, description = ?, due_date = ?, status = ? WHERE id = ?";
        return $this->db->execute($sql, [
            $data['title'],
            $data['description'] ?? null,
            $data['due_date'] ?? null,
            $data['status'] ?? 'pending',
            $id
        ]);
    }
}
