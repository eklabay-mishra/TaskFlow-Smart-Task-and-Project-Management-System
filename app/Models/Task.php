<?php
namespace App\Models;

use Core\Model;

class Task extends Model
{
    protected string $table = 'tasks';

    public function getTasksFiltered(array $filters = []): array
    {
        $sql = "SELECT t.*, p.title as project_title, m.title as milestone_title,
                u.name as assignee_name, u.avatar as assignee_avatar,
                c.name as creator_name,
                (SELECT COUNT(*) FROM comments com WHERE com.task_id = t.id) as comment_count,
                (SELECT COUNT(*) FROM attachments att WHERE att.task_id = t.id) as attachment_count
                FROM tasks t
                JOIN projects p ON t.project_id = p.id
                LEFT JOIN milestones m ON t.milestone_id = m.id
                LEFT JOIN users u ON t.assigned_to = u.id
                LEFT JOIN users c ON t.created_by = c.id
                WHERE 1=1";

        $params = [];

        if (!empty($filters['project_id'])) {
            $sql .= " AND t.project_id = ?";
            $params[] = $filters['project_id'];
        }

        if (!empty($filters['assigned_to'])) {
            $sql .= " AND t.assigned_to = ?";
            $params[] = $filters['assigned_to'];
        }

        if (!empty($filters['status'])) {
            $sql .= " AND t.status = ?";
            $params[] = $filters['status'];
        }

        if (!empty($filters['priority'])) {
            $sql .= " AND t.priority = ?";
            $params[] = $filters['priority'];
        }

        if (!empty($filters['search'])) {
            $sql .= " AND (t.title LIKE ? OR t.description LIKE ?)";
            $searchTerm = '%' . $filters['search'] . '%';
            $params[] = $searchTerm;
            $params[] = $searchTerm;
        }

        if (!empty($filters['user_id']) && empty($filters['is_admin'])) {
            $sql .= " AND (t.assigned_to = ? OR t.created_by = ? OR t.project_id IN (SELECT project_id FROM project_members WHERE user_id = ?))";
            $params[] = $filters['user_id'];
            $params[] = $filters['user_id'];
            $params[] = $filters['user_id'];
        }

        $sql .= " ORDER BY t.id DESC";

        return $this->db->fetchAll($sql, $params);
    }

    public function findWithDetails(int $id): ?array
    {
        return $this->db->fetch(
            "SELECT t.*, p.title as project_title, m.title as milestone_title,
                    u.name as assignee_name, u.email as assignee_email, u.avatar as assignee_avatar,
                    c.name as creator_name
             FROM tasks t
             JOIN projects p ON t.project_id = p.id
             LEFT JOIN milestones m ON t.milestone_id = m.id
             LEFT JOIN users u ON t.assigned_to = u.id
             LEFT JOIN users c ON t.created_by = c.id
             WHERE t.id = ?",
            [$id]
        );
    }

    public function create(array $data): int
    {
        $sql = "INSERT INTO tasks (project_id, milestone_id, assigned_to, created_by, title, description, status, priority, due_date, estimated_hours, logged_hours)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $this->db->execute($sql, [
            $data['project_id'],
            (!empty($data['milestone_id'])) ? $data['milestone_id'] : null,
            (!empty($data['assigned_to'])) ? $data['assigned_to'] : null,
            $data['created_by'],
            $data['title'],
            $data['description'] ?? null,
            $data['status'] ?? 'todo',
            $data['priority'] ?? 'medium',
            (!empty($data['due_date'])) ? $data['due_date'] : null,
            $data['estimated_hours'] ?? 0.00,
            $data['logged_hours'] ?? 0.00
        ]);

        return (int)$this->db->lastInsertId();
    }

    public function update(int $id, array $data): bool
    {
        $sql = "UPDATE tasks SET 
                    project_id = ?,
                    milestone_id = ?,
                    assigned_to = ?,
                    title = ?,
                    description = ?,
                    status = ?,
                    priority = ?,
                    due_date = ?,
                    estimated_hours = ?,
                    logged_hours = ?
                WHERE id = ?";

        return $this->db->execute($sql, [
            $data['project_id'],
            (!empty($data['milestone_id'])) ? $data['milestone_id'] : null,
            (!empty($data['assigned_to'])) ? $data['assigned_to'] : null,
            $data['title'],
            $data['description'] ?? null,
            $data['status'] ?? 'todo',
            $data['priority'] ?? 'medium',
            (!empty($data['due_date'])) ? $data['due_date'] : null,
            $data['estimated_hours'] ?? 0.00,
            $data['logged_hours'] ?? 0.00,
            $id
        ]);
    }

    public function updateStatus(int $id, string $status): bool
    {
        return $this->db->execute("UPDATE tasks SET status = ? WHERE id = ?", [$status, $id]);
    }

    public function logHours(int $id, float $additionalHours): bool
    {
        return $this->db->execute("UPDATE tasks SET logged_hours = logged_hours + ? WHERE id = ?", [$additionalHours, $id]);
    }

    public function getKanbanTasks(array $filters = []): array
    {
        $tasks = $this->getTasksFiltered($filters);
        
        $board = [
            'todo' => [],
            'in_progress' => [],
            'review' => [],
            'done' => []
        ];

        foreach ($tasks as $task) {
            $status = $task['status'];
            if (isset($board[$status])) {
                $board[$status][] = $task;
            }
        }

        return $board;
    }

    public function getCalendarEvents(?int $userId = null, bool $isAdmin = false): array
    {
        $sql = "SELECT t.id, t.title, t.due_date as start, t.status, t.priority, p.title as project_title 
                FROM tasks t
                JOIN projects p ON t.project_id = p.id
                WHERE t.due_date IS NOT NULL";
        $params = [];

        if ($userId && !$isAdmin) {
            $sql .= " AND (t.assigned_to = ? OR t.created_by = ? OR t.project_id IN (SELECT project_id FROM project_members WHERE user_id = ?))";
            $params = [$userId, $userId, $userId];
        }

        return $this->db->fetchAll($sql, $params);
    }

    // Analytics Methods
    public function getUpcomingDeadlines(int $limit = 5, ?int $userId = null): array
    {
        $sql = "SELECT t.*, p.title as project_title 
                FROM tasks t
                JOIN projects p ON t.project_id = p.id
                WHERE t.due_date >= CURDATE() AND t.status != 'done'";
        $params = [];

        if ($userId) {
            $sql .= " AND (t.assigned_to = ? OR t.created_by = ?)";
            $params = [$userId, $userId];
        }

        $sql .= " ORDER BY t.due_date ASC LIMIT ?";
        $params[] = $limit;

        return $this->db->fetchAll($sql, $params);
    }

    public function getProjectProgressTrend(): array
    {
        // 7-day trend sample generator backed by database totals
        $dates = [];
        $completedSeries = [];
        $inProgressSeries = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = date('Y-m-d', strtotime("-{$i} days"));
            $dates[] = date('d M', strtotime($date));
            
            $comp = $this->db->fetch("SELECT COUNT(*) as c FROM tasks WHERE status='done' AND DATE(updated_at) <= ?", [$date]);
            $inProg = $this->db->fetch("SELECT COUNT(*) as c FROM tasks WHERE status='in_progress' AND DATE(updated_at) <= ?", [$date]);
            
            $completedSeries[] = (int)($comp['c'] ?? 0);
            $inProgressSeries[] = (int)($inProg['c'] ?? 0);
        }

        return [
            'labels'      => $dates,
            'completed'   => $completedSeries,
            'in_progress' => $inProgressSeries
        ];
    }
}
