<?php
namespace App\Controllers;

use Core\Controller;
use Core\Auth;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;

class ReportController extends Controller
{
    public function index(): void
    {
        $this->requireAuth();
        $user = Auth::user();
        $isAdmin = Auth::isAdmin();

        $projectModel = new Project();
        $projects = $projectModel->getProjectsFiltered([
            'user_id' => $user['id'],
            'is_admin' => $isAdmin
        ]);

        $taskModel = new Task();
        $tasks = $taskModel->getTasksFiltered([
            'user_id' => $user['id'],
            'is_admin' => $isAdmin
        ]);

        $userModel = new User();
        $teamMembers = $userModel->getAllWithRoles();

        // Calculate member workload
        $workload = [];
        foreach ($teamMembers as $member) {
            $memberId = $member['id'];
            $memberTasks = array_filter($tasks, fn($t) => (int)$t['assigned_to'] === $memberId);
            $totalEst = array_sum(array_column($memberTasks, 'estimated_hours'));
            $totalLogged = array_sum(array_column($memberTasks, 'logged_hours'));
            
            $workload[] = [
                'id'             => $memberId,
                'name'           => $member['name'],
                'role'           => $member['role_name'],
                'avatar'         => $member['avatar'],
                'task_count'     => count($memberTasks),
                'completed_count'=> count(array_filter($memberTasks, fn($t) => $t['status'] === 'done')),
                'est_hours'      => $totalEst,
                'logged_hours'   => $totalLogged
            ];
        }

        $this->render('reports/index', [
            'title'     => 'Reports & Analytics - TaskFlow',
            'projects'  => $projects,
            'tasks'     => $tasks,
            'workload'  => $workload
        ], 'dashboard');
    }

    public function exportCsv(): void
    {
        $this->requireAuth();
        $type = $_GET['type'] ?? 'projects';

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=taskflow_' . $type . '_report_' . date('Y-m-d') . '.csv');

        $output = fopen('php://output', 'w');

        if (ob_get_length()) {
            ob_clean();
        }

        if ($type === 'tasks') {
            fputcsv($output, ['Task ID', 'Project', 'Title', 'Status', 'Priority', 'Assigned To', 'Est. Hours', 'Logged Hours', 'Due Date'], ',', '"', "\\");
            $taskModel = new Task();
            $tasks = $taskModel->getTasksFiltered(['user_id' => Auth::id(), 'is_admin' => Auth::isAdmin()]);
            foreach ($tasks as $t) {
                fputcsv($output, [
                    $t['id'],
                    $t['project_title'],
                    $t['title'],
                    strtoupper(str_replace('_', ' ', $t['status'])),
                    ucfirst($t['priority']),
                    $t['assignee_name'] ?? 'Unassigned',
                    $t['estimated_hours'],
                    $t['logged_hours'],
                    $t['due_date'] ?? 'N/A'
                ], ',', '"', "\\");
            }
        } else { // Projects CSV
            fputcsv($output, ['Project ID', 'Title', 'Category', 'Status', 'Priority', 'Progress %', 'Budget ($)', 'Start Date', 'Due Date'], ',', '"', "\\");
            $projectModel = new Project();
            $projects = $projectModel->getProjectsFiltered(['user_id' => Auth::id(), 'is_admin' => Auth::isAdmin()]);
            foreach ($projects as $p) {
                fputcsv($output, [
                    $p['id'],
                    $p['title'],
                    $p['category'],
                    strtoupper(str_replace('_', ' ', $p['status'])),
                    ucfirst($p['priority']),
                    $p['progress_pct'] . '%',
                    number_format($p['budget'], 2),
                    $p['start_date'] ?? 'N/A',
                    $p['due_date'] ?? 'N/A'
                ], ',', '"', "\\");
            }
        }

        fclose($output);
        exit;
    }
}
