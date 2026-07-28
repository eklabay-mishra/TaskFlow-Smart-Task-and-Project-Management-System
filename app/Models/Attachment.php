<?php
namespace App\Models;

use Core\Model;

class Attachment extends Model
{
    protected string $table = 'attachments';

    public function getByTask(int $taskId): array
    {
        return $this->db->fetchAll(
            "SELECT a.*, u.name as uploader_name
             FROM attachments a
             JOIN users u ON a.user_id = u.id
             WHERE a.task_id = ?
             ORDER BY a.uploaded_at DESC",
            [$taskId]
        );
    }

    public function getByProject(int $projectId): array
    {
        return $this->db->fetchAll(
            "SELECT a.*, u.name as uploader_name, t.title as task_title
             FROM attachments a
             JOIN users u ON a.user_id = u.id
             LEFT JOIN tasks t ON a.task_id = t.id
             WHERE a.project_id = ? OR t.project_id = ?
             ORDER BY a.uploaded_at DESC",
            [$projectId, $projectId]
        );
    }

    public function create(array $data): int
    {
        $this->db->execute(
            "INSERT INTO attachments (task_id, project_id, user_id, file_name, file_path, file_size, file_type)
             VALUES (?, ?, ?, ?, ?, ?, ?)",
            [
                $data['task_id'] ?? null,
                $data['project_id'] ?? null,
                $data['user_id'],
                $data['file_name'],
                $data['file_path'],
                $data['file_size'],
                $data['file_type']
            ]
        );
        return (int)$this->db->lastInsertId();
    }
}
