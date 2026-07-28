<?php
namespace App\Models;

use Core\Model;

class ActivityLog extends Model
{
    protected string $table = 'activity_logs';

    public function getRecent(int $limit = 20, ?int $userId = null): array
    {
        $sql = "SELECT a.*, u.name as user_name, u.avatar as user_avatar, p.title as project_title, t.title as task_title
                FROM activity_logs a
                JOIN users u ON a.user_id = u.id
                LEFT JOIN projects p ON a.project_id = p.id
                LEFT JOIN tasks t ON a.task_id = t.id";

        $params = [];

        if ($userId) {
            $sql .= " WHERE a.user_id = ?";
            $params[] = $userId;
        }

        $sql .= " ORDER BY a.id DESC LIMIT ?";
        $params[] = $limit;

        return $this->db->fetchAll($sql, $params);
    }

    public static function log(int $userId, string $action, string $description, ?int $projectId = null, ?int $taskId = null): bool
    {
        $db = \Core\Database::getInstance();
        return $db->execute(
            "INSERT INTO activity_logs (user_id, project_id, task_id, action, description) VALUES (?, ?, ?, ?, ?)",
            [$userId, $projectId, $taskId, $action, $description]
        );
    }
}
