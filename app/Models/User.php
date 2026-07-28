<?php
namespace App\Models;

use Core\Model;

class User extends Model
{
    protected string $table = 'users';

    public function findByEmail(string $email): ?array
    {
        return $this->db->fetch(
            "SELECT u.*, r.name as role_name 
             FROM users u 
             JOIN roles r ON u.role_id = r.id 
             WHERE u.email = ?",
            [$email]
        );
    }

    public function findWithRole(int $id): ?array
    {
        return $this->db->fetch(
            "SELECT u.*, r.name as role_name 
             FROM users u 
             JOIN roles r ON u.role_id = r.id 
             WHERE u.id = ?",
            [$id]
        );
    }

    public function getAllWithRoles(): array
    {
        return $this->db->fetchAll(
            "SELECT u.id, u.name, u.email, u.phone, u.avatar, u.status, u.created_at, r.name as role_name, r.id as role_id 
             FROM users u 
             JOIN roles r ON u.role_id = r.id 
             ORDER BY u.id ASC"
        );
    }

    public function create(array $data): int
    {
        $sql = "INSERT INTO users (role_id, name, email, password_hash, avatar, phone, bio, theme_mode, status) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $this->db->execute($sql, [
            $data['role_id'] ?? 3,
            $data['name'],
            $data['email'],
            $data['password_hash'],
            $data['avatar'] ?? 'default-avatar.png',
            $data['phone'] ?? null,
            $data['bio'] ?? null,
            $data['theme_mode'] ?? 'light',
            $data['status'] ?? 'active'
        ]);

        return (int)$this->db->lastInsertId();
    }

    public function updateProfile(int $id, array $data): bool
    {
        $sql = "UPDATE users SET name = ?, email = ?, phone = ?, bio = ? WHERE id = ?";
        return $this->db->execute($sql, [
            $data['name'],
            $data['email'],
            $data['phone'] ?? null,
            $data['bio'] ?? null,
            $id
        ]);
    }

    public function updateAvatar(int $id, string $avatar): bool
    {
        return $this->db->execute("UPDATE users SET avatar = ? WHERE id = ?", [$avatar, $id]);
    }

    public function updatePassword(int $id, string $passwordHash): bool
    {
        return $this->db->execute("UPDATE users SET password_hash = ? WHERE id = ?", [$passwordHash, $id]);
    }

    public function updateTheme(int $id, string $theme): bool
    {
        return $this->db->execute("UPDATE users SET theme_mode = ? WHERE id = ?", [$theme, $id]);
    }

    public function updateRoleAndStatus(int $id, int $roleId, string $status): bool
    {
        return $this->db->execute("UPDATE users SET role_id = ?, status = ? WHERE id = ?", [$roleId, $status, $id]);
    }
}
