<?php
namespace App\Controllers;

use Core\Controller;
use Core\Auth;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use App\Models\Milestone;
use App\Models\Comment;
use App\Models\Attachment;
use App\Models\Notification;
use App\Models\ActivityLog;

class DashboardController extends Controller
{
    public function index(): void
    {
        $this->requireAuth();
        $user = Auth::user();
        $userId = $user['id'];
        $roleId = Auth::roleId();

        $projectModel  = new Project();
        $taskModel     = new Task();
        $userModel     = new User();
        $activityModel = new ActivityLog();
        $notifModel    = new Notification();

        if ($roleId === 1) { // 1. ADMIN DASHBOARD
            $metrics = [
                'total_projects'  => $projectModel->count("1=1"),
                'active_projects' => $projectModel->count("status='active'"),
                'completed_tasks' => $taskModel->count("status='done'"),
                'total_tasks'     => $taskModel->count("1=1"),
                'pending_tasks'   => $taskModel->count("status IN ('todo', 'in_progress', 'review')"),
                'team_members'    => $userModel->count("status='active'")
            ];

            $projects = $projectModel->getProjectsFiltered(['is_admin' => true]);
            $recentTasks = $taskModel->getTasksFiltered(['is_admin' => true]);
            
            $statusCounts = [
                'todo'        => $taskModel->count("status='todo'"),
                'in_progress' => $taskModel->count("status='in_progress'"),
                'review'      => $taskModel->count("status='review'"),
                'done'        => $taskModel->count("status='done'"),
            ];

            $priorityCounts = [
                'low'    => $taskModel->count("priority='low'"),
                'medium' => $taskModel->count("priority='medium'"),
                'high'   => $taskModel->count("priority='high'"),
                'urgent' => $taskModel->count("priority='urgent'"),
            ];

            $categoryCounts = $projectModel->getProjectsByCategory();
            $progressTrend  = $taskModel->getProjectProgressTrend();
            $upcomingDeadlines = $taskModel->getUpcomingDeadlines(5);
            $activityLogs   = $activityModel->getRecent(10);
            $systemNotifs   = $notifModel->getForUser($userId, 5);

            $this->render('dashboard/admin', [
                'title'             => 'Admin Enterprise Dashboard - TaskFlow',
                'user'              => $user,
                'metrics'           => $metrics,
                'projects'          => array_slice($projects, 0, 5),
                'recentTasks'       => array_slice($recentTasks, 0, 5),
                'statusCounts'      => $statusCounts,
                'priorityCounts'    => $priorityCounts,
                'categoryCounts'    => $categoryCounts,
                'progressTrend'     => $progressTrend,
                'upcomingDeadlines' => $upcomingDeadlines,
                'activityLogs'      => $activityLogs,
                'systemNotifs'      => $systemNotifs
            ], 'dashboard');

        } elseif ($roleId === 2) { // 2. PROJECT MANAGER DASHBOARD
            $pmProjects = $projectModel->getProjectsFiltered(['user_id' => $userId, 'is_admin' => false]);
            $pmTasks    = $taskModel->getTasksFiltered(['user_id' => $userId, 'is_admin' => false]);

            $totalEstHours = array_sum(array_column($pmTasks, 'estimated_hours'));
            $totalLoggedHours = array_sum(array_column($pmTasks, 'logged_hours'));
            $productivityPct = $totalEstHours > 0 ? round(($totalLoggedHours / $totalEstHours) * 100) : 100;

            $milestoneModel = new Milestone();
            $totalMilestones = $milestoneModel->count();
            $completedMilestones = $milestoneModel->count("status='completed'");
            $sprintProgressPct = $totalMilestones > 0 ? round(($completedMilestones / $totalMilestones) * 100) : 0;

            $metrics = [
                'assigned_projects'  => count($pmProjects),
                'active_projects'    => count(array_filter($pmProjects, fn($p) => $p['status'] === 'active')),
                'team_productivity'  => $productivityPct,
                'sprint_progress'    => $sprintProgressPct,
                'total_milestones'   => $totalMilestones,
                'total_pm_tasks'     => count($pmTasks)
            ];

            // Member workload table
            $teamMembers = $userModel->getAllWithRoles();
            $workload = [];
            foreach ($teamMembers as $member) {
                $mTasks = array_filter($pmTasks, fn($t) => (int)$t['assigned_to'] === (int)$member['id']);
                $workload[] = [
                    'id'             => $member['id'],
                    'name'           => $member['name'],
                    'role'           => $member['role_name'],
                    'avatar'         => $member['avatar'],
                    'task_count'     => count($mTasks),
                    'completed_count'=> count(array_filter($mTasks, fn($t) => $t['status'] === 'done')),
                    'est_hours'      => array_sum(array_column($mTasks, 'estimated_hours')),
                    'logged_hours'   => array_sum(array_column($mTasks, 'logged_hours'))
                ];
            }

            $statusCounts = [
                'todo'        => count(array_filter($pmTasks, fn($t) => $t['status'] === 'todo')),
                'in_progress' => count(array_filter($pmTasks, fn($t) => $t['status'] === 'in_progress')),
                'review'      => count(array_filter($pmTasks, fn($t) => $t['status'] === 'review')),
                'done'        => count(array_filter($pmTasks, fn($t) => $t['status'] === 'done')),
            ];

            $upcomingDeadlines = $taskModel->getUpcomingDeadlines(5, $userId);
            $activityLogs      = $activityModel->getRecent(10);
            $allUsers          = $teamMembers;

            $this->render('dashboard/manager', [
                'title'             => 'Project Manager Workspace - TaskFlow',
                'user'              => $user,
                'metrics'           => $metrics,
                'projects'          => array_slice($pmProjects, 0, 5),
                'workload'          => $workload,
                'statusCounts'      => $statusCounts,
                'upcomingDeadlines' => $upcomingDeadlines,
                'activityLogs'      => $activityLogs,
                'allUsers'          => $allUsers
            ], 'dashboard');

        } else { // 3. TEAM MEMBER DASHBOARD
            $myTasks = $taskModel->getTasksFiltered(['assigned_to' => $userId]);

            $completedTasksCount = count(array_filter($myTasks, fn($t) => $t['status'] === 'done'));
            $pendingTasksCount   = count(array_filter($myTasks, fn($t) => $t['status'] !== 'done'));
            
            $dueThisWeek = count(array_filter($myTasks, function($t) {
                if (empty($t['due_date'])) return false;
                $due = strtotime($t['due_date']);
                return ($due >= time() && $due <= strtotime('+7 days'));
            }));

            $totalLoggedHours = array_sum(array_column($myTasks, 'logged_hours'));

            $metrics = [
                'my_tasks'         => count($myTasks),
                'completed_tasks'  => $completedTasksCount,
                'pending_tasks'    => $pendingTasksCount,
                'due_this_week'    => $dueThisWeek,
                'logged_hours'     => $totalLoggedHours
            ];

            $todayFocusTasks = array_slice(array_filter($myTasks, fn($t) => $t['status'] !== 'done'), 0, 6);

            $myProjects = $projectModel->getProjectsFiltered(['user_id' => $userId, 'is_admin' => false]);
            $myProjects = array_slice($myProjects, 0, 4);

            $db = \Core\Database::getInstance();
            $myAttachments  = $db->fetchAll("SELECT * FROM attachments WHERE user_id = ? ORDER BY id DESC LIMIT 5", [$userId]);
            $recentComments = $db->fetchAll("SELECT c.*, t.title as task_title FROM comments c JOIN tasks t ON c.task_id = t.id WHERE c.user_id = ? ORDER BY c.id DESC LIMIT 5", [$userId]);

            $this->render('dashboard/member', [
                'title'            => 'Team Member Dashboard - TaskFlow',
                'user'             => $user,
                'metrics'          => $metrics,
                'todayFocusTasks'  => $todayFocusTasks,
                'myProjects'       => $myProjects,
                'myAttachments'    => $myAttachments,
                'recentComments'   => $recentComments
            ], 'dashboard');
        }
    }
}
