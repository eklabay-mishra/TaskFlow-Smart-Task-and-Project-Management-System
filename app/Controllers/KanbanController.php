<?php
namespace App\Controllers;

use Core\Controller;
use Core\Auth;
use App\Models\Task;
use App\Models\Project;

class KanbanController extends Controller
{
    public function index(): void
    {
        $this->requireAuth();
        $user = Auth::user();

        $filters = [
            'project_id' => $_GET['project_id'] ?? '',
            'priority'   => $_GET['priority'] ?? '',
            'search'     => $_GET['search'] ?? '',
            'user_id'    => $user['id'],
            'is_admin'   => Auth::isAdmin()
        ];

        $taskModel = new Task();
        $board = $taskModel->getKanbanTasks($filters);

        $projectModel = new Project();
        $projects = $projectModel->findAll('title ASC');

        $this->render('kanban/index', [
            'title'    => 'Kanban Board - TaskFlow Enterprise',
            'board'    => $board,
            'projects' => $projects,
            'filters'  => $filters
        ], 'dashboard');
    }
}
