<?php
namespace App\Models;

use Core\Model;

class Notification extends Model
{
    protected string $table = 'notifications';

    public function getForUser(int $userId, int $limit = 10): array
    {
        return $this->db->fetchAll(
            "SELECT * FROM notifications WHERE user_id = ? ORDER BY id DESC LIMIT ?",
            [$userId, $limit]
        );
    }

    public function getUnreadCount(int $userId): int
    {
        $row = $this->db->fetch(
            "SELECT COUNT(*) as total FROM notifications WHERE user_id = ? AND is_read = 0",
            [$userId]
        );
        return (int)($row['total'] ?? 0);
    }

    public function create(int $userId, string $title, string $message, ?string $link = null): int
    {
        $this->db->execute(
            "INSERT INTO notifications (user_id, title, message, link) VALUES (?, ?, ?, ?)",
            [$userId, $title, $message, $link]
        );
        return (int)$this->db->lastInsertId();
    }

    public function markAsRead(int $id, int $userId): bool
    {
        return $this->db->execute(
            "UPDATE notifications SET is_read = 1 WHERE id = ? AND user_id = ?",
            [$id, $userId]
        );
    }

    public function markAllAsRead(int $userId): bool
    {
        return $this->db->execute(
            "UPDATE notifications SET is_read = 1 WHERE user_id = ?",
            [$userId]
        );
    }
}
