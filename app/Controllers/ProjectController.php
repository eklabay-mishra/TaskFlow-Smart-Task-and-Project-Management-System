<?php
namespace App\Controllers;

use Core\Controller;
use Core\Auth;
use Core\Session;
use App\Models\Project;
use App\Models\Task;
use App\Models\Milestone;
use App\Models\User;
use App\Models\Attachment;
use App\Models\ActivityLog;

class ProjectController extends Controller
{
    public function index(): void
    {
        $this->requireAuth();
        $user = Auth::user();

        $filters = [
            'status'   => $_GET['status'] ?? '',
            'priority' => $_GET['priority'] ?? '',
            'category' => $_GET['category'] ?? '',
            'search'   => $_GET['search'] ?? '',
            'user_id'  => $user['id'],
            'is_admin' => Auth::isAdmin()
        ];

        $projectModel = new Project();
        $projects = $projectModel->getProjectsFiltered($filters);

        $userModel = new User();
        $allUsers = $userModel->getAllWithRoles();

        $this->render('projects/index', [
            'title'    => 'Projects - TaskFlow',
            'projects' => $projects,
            'filters'  => $filters,
            'allUsers' => $allUsers
        ], 'dashboard');
    }

    public function show(string $id): void
    {
        $this->requireAuth();
        $projectId = (int)$id;

        $projectModel = new Project();
        $project = $projectModel->findWithDetails($projectId);

        if (!$project) {
            Session::setFlash('error', 'Project not found.');
            $this->redirect('/projects');
        }

        $taskModel = new Task();
        $tasks = $taskModel->getTasksFiltered(['project_id' => $projectId]);

        $milestoneModel = new Milestone();
        $milestones = $milestoneModel->getByProject($projectId);

        $members = $projectModel->getMembers($projectId);

        $attachmentModel = new Attachment();
        $attachments = $attachmentModel->getByProject($projectId);

        $userModel = new User();
        $allUsers = $userModel->getAllWithRoles();

        $this->render('projects/show', [
            'title'       => $project['title'] . ' - TaskFlow',
            'project'     => $project,
            'tasks'       => $tasks,
            'milestones'  => $milestones,
            'members'     => $members,
            'attachments' => $attachments,
            'allUsers'    => $allUsers
        ], 'dashboard');
    }

    public function store(): void
    {
        $this->requireRole([1, 2]); // Admin or PM
        $this->requireCsrf();

        $title = trim($_POST['title'] ?? '');
        if (empty($title)) {
            Session::setFlash('error', 'Project title is required.');
            $this->redirect('/projects');
        }

        $projectModel = new Project();
        $projectId = $projectModel->create([
            'title'       => $title,
            'description' => trim($_POST['description'] ?? ''),
            'category'    => $_POST['category'] ?? 'General',
            'status'      => $_POST['status'] ?? 'planning',
            'priority'    => $_POST['priority'] ?? 'medium',
            'start_date'  => (!empty($_POST['start_date'])) ? $_POST['start_date'] : null,
            'due_date'    => (!empty($_POST['due_date'])) ? $_POST['due_date'] : null,
            'budget'      => (float)($_POST['budget'] ?? 0),
            'created_by'  => Auth::id()
        ]);

        // Add selected members if provided
        if (!empty($_POST['members']) && is_array($_POST['members'])) {
            foreach ($_POST['members'] as $userId) {
                $projectModel->addMember($projectId, (int)$userId, 'Member');
            }
        }

        ActivityLog::log(Auth::id(), 'PROJECT_CREATED', "Created new project: {$title}", $projectId);

        Session::setFlash('success', 'Project created successfully!');
        $this->redirect("/projects/{$projectId}");
    }

    public function update(string $id): void
    {
        $this->requireRole([1, 2]);
        $this->requireCsrf();

        $projectId = (int)$id;
        $title = trim($_POST['title'] ?? '');

        if (empty($title)) {
            Session::setFlash('error', 'Project title is required.');
            $this->redirect("/projects/{$projectId}");
        }

        $projectModel = new Project();
        $projectModel->update($projectId, [
            'title'       => $title,
            'description' => trim($_POST['description'] ?? ''),
            'category'    => $_POST['category'] ?? 'General',
            'status'      => $_POST['status'] ?? 'planning',
            'priority'    => $_POST['priority'] ?? 'medium',
            'start_date'  => (!empty($_POST['start_date'])) ? $_POST['start_date'] : null,
            'due_date'    => (!empty($_POST['due_date'])) ? $_POST['due_date'] : null,
            'budget'      => (float)($_POST['budget'] ?? 0)
        ]);

        ActivityLog::log(Auth::id(), 'PROJECT_UPDATED', "Updated project details for: {$title}", $projectId);

        Session::setFlash('success', 'Project updated successfully.');
        $this->redirect("/projects/{$projectId}");
    }

    public function delete(string $id): void
    {
        $this->requireRole([1, 2]);
        $this->requireCsrf();

        $projectId = (int)$id;
        $projectModel = new Project();
        $project = $projectModel->find($projectId);

        if ($project) {
            $projectModel->delete($projectId);
            ActivityLog::log(Auth::id(), 'PROJECT_DELETED', "Deleted project: {$project['title']}");
            Session::setFlash('success', 'Project deleted successfully.');
        }

        $this->redirect('/projects');
    }

    public function addMember(string $id): void
    {
        $this->requireRole([1, 2]);
        $this->requireCsrf();

        $projectId = (int)$id;
        $userId = (int)($_POST['user_id'] ?? 0);
        $roleInProject = trim($_POST['role_in_project'] ?? 'Member');

        if ($userId > 0) {
            $projectModel = new Project();
            $projectModel->addMember($projectId, $userId, $roleInProject);
            ActivityLog::log(Auth::id(), 'MEMBER_ADDED', "Added team member to project #{$projectId}", $projectId);
            Session::setFlash('success', 'Team member added to project.');
        }

        $this->redirect("/projects/{$projectId}");
    }

    public function removeMember(string $id): void
    {
        $this->requireRole([1, 2]);
        $this->requireCsrf();

        $projectId = (int)$id;
        $userId = (int)($_POST['user_id'] ?? 0);

        if ($userId > 0) {
            $projectModel = new Project();
            $projectModel->removeMember($projectId, $userId);
            ActivityLog::log(Auth::id(), 'MEMBER_REMOVED', "Removed team member from project #{$projectId}", $projectId);
            Session::setFlash('success', 'Member removed from project.');
        }

        $this->redirect("/projects/{$projectId}");
    }

    public function storeMilestone(string $id): void
    {
        $this->requireRole([1, 2]);
        $this->requireCsrf();

        $projectId = (int)$id;
        $title = trim($_POST['title'] ?? '');

        if (!empty($title)) {
            $milestoneModel = new Milestone();
            $milestoneModel->create([
                'project_id'  => $projectId,
                'title'       => $title,
                'description' => trim($_POST['description'] ?? ''),
                'due_date'    => $_POST['due_date'] ?: null,
                'status'      => $_POST['status'] ?? 'pending'
            ]);

            ActivityLog::log(Auth::id(), 'MILESTONE_CREATED', "Created milestone '{$title}' for project #{$projectId}", $projectId);
            Session::setFlash('success', 'Milestone created.');
        }

        $this->redirect("/projects/{$projectId}");
    }
}
