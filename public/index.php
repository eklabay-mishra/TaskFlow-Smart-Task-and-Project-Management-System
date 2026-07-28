<?php
/**
 * TaskFlow Enterprise Front Controller
 */

require_once __DIR__ . '/../config/config.php';

// PSR-4 Autoloader
spl_autoload_register(function ($class) {
    $prefix = '';
    $baseDir = __DIR__ . '/../';

    // Replace namespace separators with directory separators
    $file = $baseDir . str_replace('\\', '/', $class) . '.php';

    if (file_exists($file)) {
        require_once $file;
    }
});

use Core\Router;

$router = new Router();

// Public Routes
$router->get('/', [\App\Controllers\HomeController::class, 'index']);
$router->get('/about', [\App\Controllers\HomeController::class, 'about']);
$router->get('/features', [\App\Controllers\HomeController::class, 'features']);
$router->get('/contact', [\App\Controllers\HomeController::class, 'contact']);
$router->post('/contact', [\App\Controllers\HomeController::class, 'submitContact']);

// Auth Routes
$router->get('/login', [\App\Controllers\AuthController::class, 'showLogin']);
$router->post('/login', [\App\Controllers\AuthController::class, 'login']);
$router->get('/register', [\App\Controllers\AuthController::class, 'showRegister']);
$router->post('/register', [\App\Controllers\AuthController::class, 'register']);
$router->get('/logout', [\App\Controllers\AuthController::class, 'logout']);

// Dashboard
$router->get('/dashboard', [\App\Controllers\DashboardController::class, 'index']);

// Projects
$router->get('/projects', [\App\Controllers\ProjectController::class, 'index']);
$router->post('/projects', [\App\Controllers\ProjectController::class, 'store']);
$router->get('/projects/{id}', [\App\Controllers\ProjectController::class, 'show']);
$router->post('/projects/{id}/update', [\App\Controllers\ProjectController::class, 'update']);
$router->post('/projects/{id}/delete', [\App\Controllers\ProjectController::class, 'delete']);
$router->post('/projects/{id}/members', [\App\Controllers\ProjectController::class, 'addMember']);
$router->post('/projects/{id}/members/remove', [\App\Controllers\ProjectController::class, 'removeMember']);
$router->post('/projects/{id}/milestones', [\App\Controllers\ProjectController::class, 'storeMilestone']);

// Tasks
$router->get('/tasks', [\App\Controllers\TaskController::class, 'index']);
$router->post('/tasks', [\App\Controllers\TaskController::class, 'store']);
$router->get('/tasks/{id}', [\App\Controllers\TaskController::class, 'show']);
$router->post('/tasks/{id}/update', [\App\Controllers\TaskController::class, 'update']);
$router->post('/tasks/{id}/status', [\App\Controllers\TaskController::class, 'updateStatus']);
$router->post('/tasks/{id}/log-hours', [\App\Controllers\TaskController::class, 'logHours']);
$router->post('/tasks/{id}/comments', [\App\Controllers\TaskController::class, 'addComment']);
$router->post('/tasks/{id}/attachments', [\App\Controllers\TaskController::class, 'uploadAttachment']);
$router->post('/tasks/{id}/delete', [\App\Controllers\TaskController::class, 'delete']);

// Kanban
$router->get('/kanban', [\App\Controllers\KanbanController::class, 'index']);

// Calendar
$router->get('/calendar', [\App\Controllers\CalendarController::class, 'index']);
$router->get('/calendar/events', [\App\Controllers\CalendarController::class, 'getEvents']);

// Reports
$router->get('/reports', [\App\Controllers\ReportController::class, 'index']);
$router->get('/reports/export', [\App\Controllers\ReportController::class, 'exportCsv']);

// Notifications
$router->get('/notifications', [\App\Controllers\NotificationController::class, 'index']);
$router->post('/notifications/{id}/read', [\App\Controllers\NotificationController::class, 'markRead']);
$router->post('/notifications/read-all', [\App\Controllers\NotificationController::class, 'markAllRead']);

// Profile
$router->get('/profile', [\App\Controllers\ProfileController::class, 'index']);
$router->post('/profile/update', [\App\Controllers\ProfileController::class, 'update']);
$router->post('/profile/password', [\App\Controllers\ProfileController::class, 'updatePassword']);
$router->post('/profile/avatar', [\App\Controllers\ProfileController::class, 'updateAvatar']);
$router->post('/profile/theme', [\App\Controllers\ProfileController::class, 'toggleTheme']);

// Admin User Management
$router->get('/users', [\App\Controllers\UserController::class, 'index']);
$router->post('/users', [\App\Controllers\UserController::class, 'store']);
$router->post('/users/{id}/update', [\App\Controllers\UserController::class, 'update']);
$router->post('/users/{id}/delete', [\App\Controllers\UserController::class, 'delete']);

// Admin Settings
$router->get('/settings', [\App\Controllers\SettingController::class, 'index']);
$router->post('/settings', [\App\Controllers\SettingController::class, 'update']);

// Dispatch Request
$router->dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
