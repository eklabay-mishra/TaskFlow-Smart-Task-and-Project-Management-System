<?php
namespace App\Controllers;

use Core\Controller;
use Core\Auth;
use App\Models\Task;

class CalendarController extends Controller
{
    public function index(): void
    {
        $this->requireAuth();
        $this->render('calendar/index', [
            'title' => 'Project Calendar - TaskFlow'
        ], 'dashboard');
    }

    public function getEvents(): void
    {
        $this->requireAuth();
        $taskModel = new Task();
        $rawEvents = $taskModel->getCalendarEvents(Auth::id(), Auth::isAdmin());

        $events = [];
        foreach ($rawEvents as $event) {
            $statusColors = [
                'todo'        => '#6c757d',
                'in_progress' => '#0d6efd',
                'review'      => '#fd7e14',
                'done'        => '#198754'
            ];

            $events[] = [
                'id'          => $event['id'],
                'title'       => '[' . $event['project_title'] . '] ' . $event['title'],
                'start'       => $event['start'],
                'color'       => $statusColors[$event['status']] ?? '#0d6efd',
                'url'         => '/tasks?search=' . urlencode($event['title']),
                'description' => 'Priority: ' . ucfirst($event['priority']) . ' | Status: ' . strtoupper(str_replace('_', ' ', $event['status']))
            ];
        }

        $this->jsonResponse($events);
    }
}
