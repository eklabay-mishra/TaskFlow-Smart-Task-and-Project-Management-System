<?php
namespace App\Models;

use Core\Model;

class Project extends Model
{
    protected string $table = 'projects';

    public function getProjectsFiltered(array $filters = []): array
    {
        $sql = "SELECT p.*, u.name as creator_name, 
                (SELECT COUNT(*) FROM tasks t WHERE t.project_id = p.id) as total_tasks,
                (SELECT COUNT(*) FROM tasks t WHERE t.project_id = p.id AND t.status = 'done') as completed_tasks,
                (SELECT COUNT(*) FROM project_members pm WHERE pm.project_id = p.id) as member_count
                FROM projects p 
                LEFT JOIN users u ON p.created_by = u.id 
                WHERE 1=1";
        
        $params = [];

        if (!empty($filters['status'])) {
            $sql .= " AND p.status = ?";
            $params[] = $filters['status'];
        }

        if (!empty($filters['priority'])) {
            $sql .= " AND p.priority = ?";
            $params[] = $filters['priority'];
        }

        if (!empty($filters['category'])) {
            $sql .= " AND p.category = ?";
            $params[] = $filters['category'];
        }

        if (!empty($filters['search'])) {
            $sql .= " AND (p.title LIKE ? OR p.description LIKE ?)";
            $searchTerm = '%' . $filters['search'] . '%';
            $params[] = $searchTerm;
            $params[] = $searchTerm;
        }

        if (!empty($filters['user_id']) && empty($filters['is_admin'])) {
            $sql .= " AND p.id IN (SELECT project_id FROM project_members WHERE user_id = ?)";
            $params[] = $filters['user_id'];
        }

        $sql .= " ORDER BY p.id DESC";

        $projects = $this->db->fetchAll($sql, $params);

        foreach ($projects as &$proj) {
            $total = (int)$proj['total_tasks'];
            $completed = (int)$proj['completed_tasks'];
            $proj['progress_pct'] = $total > 0 ? round(($completed / $total) * 100) : 0;
        }

        return $projects;
    }

    public function findWithDetails(int $id): ?array
    {
        $project = $this->db->fetch(
            "SELECT p.*, u.name as creator_name, u.email as creator_email 
             FROM projects p 
             LEFT JOIN users u ON p.created_by = u.id 
             WHERE p.id = ?",
            [$id]
        );

        if (!$project) return null;

        $taskStats = $this->db->fetch(
            "SELECT 
                COUNT(*) as total_tasks,
                SUM(CASE WHEN status = 'done' THEN 1 ELSE 0 END) as completed_tasks,
                SUM(CASE WHEN status = 'in_progress' THEN 1 ELSE 0 END) as in_progress_tasks,
                SUM(CASE WHEN status = 'todo' THEN 1 ELSE 0 END) as todo_tasks,
                SUM(estimated_hours) as total_estimated_hours,
                SUM(logged_hours) as total_logged_hours
             FROM tasks WHERE project_id = ?",
            [$id]
        );

        $project['total_tasks'] = (int)($taskStats['total_tasks'] ?? 0);
        $project['completed_tasks'] = (int)($taskStats['completed_tasks'] ?? 0);
        $project['in_progress_tasks'] = (int)($taskStats['in_progress_tasks'] ?? 0);
        $project['todo_tasks'] = (int)($taskStats['todo_tasks'] ?? 0);
        $project['total_estimated_hours'] = (float)($taskStats['total_estimated_hours'] ?? 0);
        $project['total_logged_hours'] = (float)($taskStats['total_logged_hours'] ?? 0);
        
        $total = $project['total_tasks'];
        $completed = $project['completed_tasks'];
        $project['progress_pct'] = $total > 0 ? round(($completed / $total) * 100) : 0;

        return $project;
    }

    public function getMembers(int $projectId): array
    {
        return $this->db->fetchAll(
            "SELECT pm.*, u.name, u.email, u.avatar, r.name as role_name 
             FROM project_members pm 
             JOIN users u ON pm.user_id = u.id 
             JOIN roles r ON u.role_id = r.id 
             WHERE pm.project_id = ? 
             ORDER BY u.name ASC",
            [$projectId]
        );
    }

    public function addMember(int $projectId, int $userId, string $roleInProject = 'Member'): bool
    {
        return $this->db->execute(
            "INSERT INTO project_members (project_id, user_id, role_in_project) VALUES (?, ?, ?)
             ON DUPLICATE KEY UPDATE role_in_project = VALUES(role_in_project)",
            [$projectId, $userId, $roleInProject]
        );
    }

    public function removeMember(int $projectId, int $userId): bool
    {
        return $this->db->execute(
            "DELETE FROM project_members WHERE project_id = ? AND user_id = ?",
            [$projectId, $userId]
        );
    }

    public function create(array $data): int
    {
        $sql = "INSERT INTO projects (title, description, category, status, priority, start_date, due_date, budget, created_by) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $this->db->execute($sql, [
            $data['title'],
            $data['description'] ?? null,
            $data['category'] ?? 'General',
            $data['status'] ?? 'planning',
            $data['priority'] ?? 'medium',
            (!empty($data['start_date'])) ? $data['start_date'] : null,
            (!empty($data['due_date'])) ? $data['due_date'] : null,
            $data['budget'] ?? 0.00,
            $data['created_by']
        ]);

        $projectId = (int)$this->db->lastInsertId();

        // Creator automatically added as owner
        $this->addMember($projectId, $data['created_by'], 'Project Owner');

        return $projectId;
    }

    public function update(int $id, array $data): bool
    {
        $sql = "UPDATE projects SET 
                    title = ?, 
                    description = ?, 
                    category = ?, 
                    status = ?, 
                    priority = ?, 
                    start_date = ?, 
                    due_date = ?, 
                    budget = ? 
                WHERE id = ?";
        
        return $this->db->execute($sql, [
            $data['title'],
            $data['description'] ?? null,
            $data['category'] ?? 'General',
            $data['status'] ?? 'planning',
            $data['priority'] ?? 'medium',
            (!empty($data['start_date'])) ? $data['start_date'] : null,
            (!empty($data['due_date'])) ? $data['due_date'] : null,
            $data['budget'] ?? 0.00,
            $id
        ]);
    }

    // Analytics Methods
    public function getProjectsByCategory(): array
    {
        return $this->db->fetchAll(
            "SELECT category, COUNT(*) as count 
             FROM projects 
             GROUP BY category 
             ORDER BY count DESC"
        );
    }
}
