<?php
namespace App\Controllers;

use Core\Controller;
use Core\Auth;
use Core\Session;
use App\Models\Task;
use App\Models\Project;
use App\Models\Milestone;
use App\Models\User;
use App\Models\Comment;
use App\Models\Attachment;
use App\Models\Notification;
use App\Models\ActivityLog;

class TaskController extends Controller
{
    public function index(): void
    {
        $this->requireAuth();
        $user = Auth::user();

        $filters = [
            'project_id'  => $_GET['project_id'] ?? '',
            'status'      => $_GET['status'] ?? '',
            'priority'    => $_GET['priority'] ?? '',
            'assigned_to' => $_GET['assigned_to'] ?? '',
            'search'      => $_GET['search'] ?? '',
            'user_id'     => $user['id'],
            'is_admin'    => Auth::isAdmin()
        ];

        $taskModel = new Task();
        $tasks = $taskModel->getTasksFiltered($filters);

        $projectModel = new Project();
        $projects = $projectModel->findAll('title ASC');

        $userModel = new User();
        $allUsers = $userModel->getAllWithRoles();

        $this->render('tasks/index', [
            'title'    => 'Tasks Management - TaskFlow',
            'tasks'    => $tasks,
            'projects' => $projects,
            'allUsers' => $allUsers,
            'filters'  => $filters
        ], 'dashboard');
    }

    public function show(string $id): void
    {
        $this->requireAuth();
        $taskId = (int)$id;

        $taskModel = new Task();
        $task = $taskModel->findWithDetails($taskId);

        if (!$task) {
            if ($this->isAjax()) {
                $this->jsonResponse(['success' => false, 'message' => 'Task not found.'], 404);
            }
            Session::setFlash('error', 'Task not found.');
            $this->redirect('/tasks');
        }

        $commentModel = new Comment();
        $comments = $commentModel->getByTask($taskId);

        $attachmentModel = new Attachment();
        $attachments = $attachmentModel->getByTask($taskId);

        if ($this->isAjax()) {
            $this->jsonResponse([
                'success'     => true,
                'task'        => $task,
                'comments'    => $comments,
                'attachments' => $attachments
            ]);
        }

        $this->render('tasks/show', [
            'title'       => 'Task #' . $task['id'] . ': ' . $task['title'],
            'task'        => $task,
            'comments'    => $comments,
            'attachments' => $attachments
        ], 'dashboard');
    }

    public function store(): void
    {
        $this->requireAuth();
        $this->requireCsrf();

        $title = trim($_POST['title'] ?? '');
        $projectId = (int)($_POST['project_id'] ?? 0);

        if (empty($title) || $projectId <= 0) {
            Session::setFlash('error', 'Task title and project selection are required.');
            $this->redirect('/tasks');
        }

        $taskModel = new Task();
        $taskId = $taskModel->create([
            'project_id'      => $projectId,
            'milestone_id'   => (!empty($_POST['milestone_id'])) ? $_POST['milestone_id'] : null,
            'assigned_to'    => (!empty($_POST['assigned_to'])) ? $_POST['assigned_to'] : null,
            'created_by'     => Auth::id(),
            'title'          => $title,
            'description'    => trim($_POST['description'] ?? ''),
            'status'         => $_POST['status'] ?? 'todo',
            'priority'       => $_POST['priority'] ?? 'medium',
            'due_date'       => (!empty($_POST['due_date'])) ? $_POST['due_date'] : null,
            'estimated_hours'=> (float)($_POST['estimated_hours'] ?? 0),
            'logged_hours'   => (float)($_POST['logged_hours'] ?? 0)
        ]);

        // Send notification to assignee if assigned
        if (!empty($_POST['assigned_to'])) {
            $assignedUserId = (int)$_POST['assigned_to'];
            if ($assignedUserId !== Auth::id()) {
                $notif = new Notification();
                $notif->create(
                    $assignedUserId,
                    'New Task Assigned',
                    Auth::user()['name'] . " assigned you to task: {$title}",
                    "/tasks?search=" . urlencode($title)
                );
            }
        }

        ActivityLog::log(Auth::id(), 'TASK_CREATED', "Created task: {$title}", $projectId, $taskId);

        Session::setFlash('success', 'Task created successfully!');
        $this->redirect($_SERVER['HTTP_REFERER'] ?? '/tasks');
    }

    public function update(string $id): void
    {
        $this->requireAuth();
        $this->requireCsrf();

        $taskId = (int)$id;
        $title = trim($_POST['title'] ?? '');
        $projectId = (int)($_POST['project_id'] ?? 0);

        if (empty($title) || $projectId <= 0) {
            Session::setFlash('error', 'Task title and project are required.');
            $this->redirect('/tasks');
        }

        $taskModel = new Task();
        $taskModel->update($taskId, [
            'project_id'      => $projectId,
            'milestone_id'   => (!empty($_POST['milestone_id'])) ? $_POST['milestone_id'] : null,
            'assigned_to'    => (!empty($_POST['assigned_to'])) ? $_POST['assigned_to'] : null,
            'title'          => $title,
            'description'    => trim($_POST['description'] ?? ''),
            'status'         => $_POST['status'] ?? 'todo',
            'priority'       => $_POST['priority'] ?? 'medium',
            'due_date'       => (!empty($_POST['due_date'])) ? $_POST['due_date'] : null,
            'estimated_hours'=> (float)($_POST['estimated_hours'] ?? 0),
            'logged_hours'   => (float)($_POST['logged_hours'] ?? 0)
        ]);

        ActivityLog::log(Auth::id(), 'TASK_UPDATED', "Updated task details for: {$title}", $projectId, $taskId);

        Session::setFlash('success', 'Task updated successfully.');
        $this->redirect($_SERVER['HTTP_REFERER'] ?? '/tasks');
    }

    public function updateStatus(string $id): void
    {
        $this->requireAuth();
        $this->requireCsrf();

        $taskId = (int)$id;
        $status = $_POST['status'] ?? 'todo';

        $validStatuses = ['todo', 'in_progress', 'review', 'done'];
        if (!in_array($status, $validStatuses)) {
            if ($this->isAjax()) {
                $this->jsonResponse(['success' => false, 'message' => 'Invalid status value.'], 400);
            }
            $this->redirect('/tasks');
        }

        $taskModel = new Task();
        $task = $taskModel->find($taskId);

        if ($task) {
            $taskModel->updateStatus($taskId, $status);
            ActivityLog::log(Auth::id(), 'STATUS_UPDATE', "Updated task #{$taskId} status to {$status}", $task['project_id'], $taskId);
            
            // Notify task creator or assignee if updated by someone else
            $targetUserId = ($task['created_by'] !== Auth::id()) ? $task['created_by'] : $task['assigned_to'];
            if ($targetUserId && $targetUserId !== Auth::id()) {
                $notif = new Notification();
                $notif->create(
                    $targetUserId,
                    'Task Status Changed',
                    Auth::user()['name'] . " moved task '{$task['title']}' to " . strtoupper(str_replace('_', ' ', $status)),
                    "/tasks"
                );
            }
        }

        if ($this->isAjax()) {
            $this->jsonResponse(['success' => true, 'message' => 'Task status updated cleanly.']);
        }

        Session::setFlash('success', 'Task status updated.');
        $this->redirect($_SERVER['HTTP_REFERER'] ?? '/tasks');
    }

    public function logHours(string $id): void
    {
        $this->requireAuth();
        $this->requireCsrf();

        $taskId = (int)$id;
        $hours = (float)($_POST['hours'] ?? 0);

        if ($hours > 0) {
            $taskModel = new Task();
            $task = $taskModel->find($taskId);
            if ($task) {
                $taskModel->logHours($taskId, $hours);
                ActivityLog::log(Auth::id(), 'HOURS_LOGGED', "Logged {$hours} hrs on task #{$taskId}", $task['project_id'], $taskId);
                Session::setFlash('success', "Logged {$hours} hours to task.");
            }
        }

        $this->redirect($_SERVER['HTTP_REFERER'] ?? '/tasks');
    }

    public function addComment(string $id): void
    {
        $this->requireAuth();
        $this->requireCsrf();

        $taskId = (int)$id;
        $commentText = trim($_POST['comment'] ?? '');

        if (empty($commentText)) {
            if ($this->isAjax()) {
                $this->jsonResponse(['success' => false, 'message' => 'Comment cannot be empty.'], 400);
            }
            Session::setFlash('error', 'Comment content required.');
            $this->redirect("/tasks/{$taskId}");
        }

        $commentModel = new Comment();
        $commentId = $commentModel->create($taskId, Auth::id(), $commentText);

        $taskModel = new Task();
        $task = $taskModel->find($taskId);

        if ($task) {
            ActivityLog::log(Auth::id(), 'COMMENT_ADDED', "Added comment to task: {$task['title']}", $task['project_id'], $taskId);
        }

        if ($this->isAjax()) {
            $this->jsonResponse([
                'success' => true,
                'comment' => [
                    'id'          => $commentId,
                    'user_name'   => Auth::user()['name'],
                    'user_avatar' => Auth::user()['avatar'],
                    'role_name'   => Auth::role(),
                    'comment'     => htmlspecialchars($commentText),
                    'created_at'  => date('Y-m-d H:i:s')
                ]
            ]);
        }

        Session::setFlash('success', 'Comment posted.');
        $this->redirect($_SERVER['HTTP_REFERER'] ?? "/tasks/{$taskId}");
    }

    public function uploadAttachment(string $id): void
    {
        $this->requireAuth();
        $this->requireCsrf();

        $taskId = (int)$id;
        $taskModel = new Task();
        $task = $taskModel->find($taskId);

        if (!$task) {
            Session::setFlash('error', 'Task not found.');
            $this->redirect('/tasks');
        }

        if (empty($_FILES['file']) || $_FILES['file']['error'] !== UPLOAD_ERR_OK) {
            Session::setFlash('error', 'Please select a valid file to upload.');
            $this->redirect($_SERVER['HTTP_REFERER'] ?? "/tasks/{$taskId}");
        }

        $file = $_FILES['file'];
        if ($file['size'] > MAX_FILE_SIZE) {
            Session::setFlash('error', 'File size exceeds maximum 10MB limit.');
            $this->redirect($_SERVER['HTTP_REFERER'] ?? "/tasks/{$taskId}");
        }

        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $allowedExts = ['png', 'jpg', 'jpeg', 'gif', 'pdf', 'doc', 'docx', 'zip', 'txt', 'csv'];

        if (!in_array($ext, $allowedExts)) {
            Session::setFlash('error', 'File format not allowed.');
            $this->redirect($_SERVER['HTTP_REFERER'] ?? "/tasks/{$taskId}");
        }

        if (!is_dir(UPLOAD_DIR)) {
            mkdir(UPLOAD_DIR, 0755, true);
        }

        $newFileName = uniqid('task_') . '.' . $ext;
        $destination = UPLOAD_DIR . $newFileName;

        if (move_uploaded_file($file['tmp_name'], $destination)) {
            $attachmentModel = new Attachment();
            $attachmentModel->create([
                'task_id'    => $taskId,
                'project_id' => $task['project_id'],
                'user_id'    => Auth::id(),
                'file_name'  => $file['name'],
                'file_path'  => $newFileName,
                'file_size'  => $file['size'],
                'file_type'  => $file['type']
            ]);

            ActivityLog::log(Auth::id(), 'ATTACHMENT_UPLOADED', "Uploaded file '{$file['name']}' to task #{$taskId}", $task['project_id'], $taskId);
            Session::setFlash('success', 'File attached successfully.');
        } else {
            Session::setFlash('error', 'Failed to save uploaded file.');
        }

        $this->redirect($_SERVER['HTTP_REFERER'] ?? "/tasks/{$taskId}");
    }

    public function delete(string $id): void
    {
        $this->requireRole([1, 2]);
        $this->requireCsrf();

        $taskId = (int)$id;
        $taskModel = new Task();
        $task = $taskModel->find($taskId);

        if ($task) {
            $taskModel->delete($taskId);
            ActivityLog::log(Auth::id(), 'TASK_DELETED', "Deleted task: {$task['title']}", $task['project_id']);
            Session::setFlash('success', 'Task deleted successfully.');
        }

        $this->redirect('/tasks');
    }
}
