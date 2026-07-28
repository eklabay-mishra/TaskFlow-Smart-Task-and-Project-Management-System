<?php
namespace Core;

class CSRF
{
    public static function token(): string
    {
        Session::start();
        if (!Session::has('csrf_token')) {
            $token = bin2hex(random_bytes(32));
            Session::set('csrf_token', $token);
        }
        return Session::get('csrf_token');
    }

    public static function field(): string
    {
        return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars(self::token()) . '">';
    }

    public static function verify(?string $token): bool
    {
        Session::start();
        $sessionToken = Session::get('csrf_token');
        if (!$sessionToken || !$token) {
            return false;
        }
        return hash_equals($sessionToken, $token);
    }
}
