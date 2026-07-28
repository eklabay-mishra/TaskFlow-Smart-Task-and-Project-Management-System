<?php
namespace App\Models;

use Core\Model;

class Comment extends Model
{
    protected string $table = 'comments';

    public function getByTask(int $taskId): array
    {
        return $this->db->fetchAll(
            "SELECT c.*, u.name as user_name, u.avatar as user_avatar, r.name as role_name
             FROM comments c
             JOIN users u ON c.user_id = u.id
             JOIN roles r ON u.role_id = r.id
             WHERE c.task_id = ?
             ORDER BY c.created_at ASC",
            [$taskId]
        );
    }

    public function create(int $taskId, int $userId, string $comment): int
    {
        $this->db->execute(
            "INSERT INTO comments (task_id, user_id, comment) VALUES (?, ?, ?)",
            [$taskId, $userId, $comment]
        );
        return (int)$this->db->lastInsertId();
    }
}
