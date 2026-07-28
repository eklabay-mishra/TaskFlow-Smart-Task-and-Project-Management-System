<?php
namespace App\Controllers;

use Core\Controller;
use Core\Auth;
use App\Models\Notification;

class NotificationController extends Controller
{
    public function index(): void
    {
        $this->requireAuth();
        $notifModel = new Notification();
        $notifications = $notifModel->getForUser(Auth::id(), 50);

        if ($this::isAjax()) {
            $unreadCount = $notifModel->getUnreadCount(Auth::id());
            $this->jsonResponse([
                'success'       => true,
                'unread_count'  => $unreadCount,
                'notifications' => $notifications
            ]);
        }

        $this->render('notifications/index', [
            'title'         => 'Notification Center - TaskFlow',
            'notifications' => $notifications
        ], 'dashboard');
    }

    public function markRead(string $id): void
    {
        $this->requireAuth();
        $this->requireCsrf();

        $notifId = (int)$id;
        $notifModel = new Notification();
        $notifModel->markAsRead($notifId, Auth::id());

        if ($this->isAjax()) {
            $this->jsonResponse(['success' => true, 'unread_count' => $notifModel->getUnreadCount(Auth::id())]);
        }

        $this->redirect('/notifications');
    }

    public function markAllRead(): void
    {
        $this->requireAuth();
        $this->requireCsrf();

        $notifModel = new Notification();
        $notifModel->markAllAsRead(Auth::id());

        if ($this->isAjax()) {
            $this->jsonResponse(['success' => true, 'unread_count' => 0]);
        }

        $this->redirect('/notifications');
    }
}
