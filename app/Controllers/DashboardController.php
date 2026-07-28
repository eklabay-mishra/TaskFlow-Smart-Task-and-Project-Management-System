<?php
namespace App\Controllers;

use Core\Controller;
use Core\Auth;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use App\Models\ActivityLog;

class DashboardController extends Controller
{
    public function index(): void
    {
        $this->requireAuth();
        $user = Auth::user();
        $userId = $user['id'];
        $isAdmin = Auth::isAdmin();

        $projectModel = new Project();
        $taskModel = new Task();
        $userModel = new User();
        $activityModel = new ActivityLog();

        // High level KPI metrics
        $metrics = [
            'total_projects'  => $projectModel->count($isAdmin ? "1=1" : "id IN (SELECT project_id FROM project_members WHERE user_id = {$userId})"),
            'active_projects' => $projectModel->count(($isAdmin ? "status='active'" : "status='active' AND id IN (SELECT project_id FROM project_members WHERE user_id = {$userId})")),
            'total_tasks'     => $taskModel->count($isAdmin ? "1=1" : "(assigned_to={$userId} OR created_by={$userId})"),
            'completed_tasks' => $taskModel->count($isAdmin ? "status='done'" : "status='done' AND (assigned_to={$userId} OR created_by={$userId})"),
            'my_pending_tasks'=> $taskModel->count("status IN ('todo', 'in_progress', 'review') AND assigned_to={$userId}")
        ];

        // Fetch recent projects & tasks
        $projects = $projectModel->getProjectsFiltered([
            'user_id' => $userId,
            'is_admin' => $isAdmin
        ]);
        $projects = array_slice($projects, 0, 5);

        $recentTasks = $taskModel->getTasksFiltered([
            'user_id' => $userId,
            'is_admin' => $isAdmin
        ]);
        $recentTasks = array_slice($recentTasks, 0, 5);

        // Chart Data 1: Task status counts (Todo, In Progress, Review, Done)
        $statusCounts = [
            'todo'        => $taskModel->count(($isAdmin ? "status='todo'" : "status='todo' AND assigned_to={$userId}")),
            'in_progress' => $taskModel->count(($isAdmin ? "status='in_progress'" : "status='in_progress' AND assigned_to={$userId}")),
            'review'      => $taskModel->count(($isAdmin ? "status='review'" : "status='review' AND assigned_to={$userId}")),
            'done'        => $taskModel->count(($isAdmin ? "status='done'" : "status='done' AND assigned_to={$userId}")),
        ];

        // Chart Data 2: Task Priority distribution
        $priorityCounts = [
            'low'    => $taskModel->count(($isAdmin ? "priority='low'" : "priority='low' AND assigned_to={$userId}")),
            'medium' => $taskModel->count(($isAdmin ? "priority='medium'" : "priority='medium' AND assigned_to={$userId}")),
            'high'   => $taskModel->count(($isAdmin ? "priority='high'" : "priority='high' AND assigned_to={$userId}")),
            'urgent' => $taskModel->count(($isAdmin ? "priority='urgent'" : "priority='urgent' AND assigned_to={$userId}")),
        ];

        // Recent activity logs
        $activityLogs = $activityModel->getRecent(10, $isAdmin ? null : $userId);

        $this->render('dashboard/index', [
            'title'          => 'Dashboard - TaskFlow Enterprise',
            'user'           => $user,
            'metrics'        => $metrics,
            'projects'       => $projects,
            'recentTasks'    => $recentTasks,
            'statusCounts'   => $statusCounts,
            'priorityCounts' => $priorityCounts,
            'activityLogs'   => $activityLogs
        ], 'dashboard');
    }
}
