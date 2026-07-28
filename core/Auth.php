<?php
namespace Core;

class Auth
{
    public static function check(): bool
    {
        return Session::has('user_id');
    }

    public static function user(): ?array
    {
        return Session::get('user_data');
    }

    public static function id(): ?int
    {
        return Session::get('user_id');
    }

    public static function role(): ?string
    {
        $user = self::user();
        return $user['role_name'] ?? null;
    }

    public static function roleId(): ?int
    {
        $user = self::user();
        return $user['role_id'] ?? null;
    }

    public static function isAdmin(): bool
    {
        return self::roleId() === 1 || self::role() === 'Admin';
    }

    public static function isManager(): bool
    {
        return self::roleId() === 2 || self::role() === 'Project Manager';
    }

    public static function isMember(): bool
    {
        return self::roleId() === 3 || self::role() === 'Team Member';
    }

    public static function login(array $user): void
    {
        Session::start();
        session_regenerate_id(true);
        Session::set('user_id', (int)$user['id']);
        Session::set('user_data', [
            'id' => (int)$user['id'],
            'name' => $user['name'],
            'email' => $user['email'],
            'role_id' => (int)$user['role_id'],
            'role_name' => $user['role_name'] ?? 'Team Member',
            'avatar' => $user['avatar'] ?? 'default-avatar.png',
            'theme_mode' => $user['theme_mode'] ?? 'light'
        ]);
    }

    public static function logout(): void
    {
        Session::destroy();
    }
}
