<?php
namespace App\Controllers;

use Core\Controller;
use Core\Auth;
use Core\Session;
use App\Models\User;
use App\Models\ActivityLog;

class AuthController extends Controller
{
    public function showLogin(): void
    {
        if (Auth::check()) {
            $this->redirect('/dashboard');
        }

        $this->render('public/login', [
            'title' => 'Sign In - TaskFlow Enterprise'
        ], 'auth');
    }

    public function login(): void
    {
        $this->requireCsrf();

        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if (empty($email) || empty($password)) {
            Session::setFlash('error', 'Please provide both email and password.');
            $this->redirect('/login');
        }

        $userModel = new User();
        $user = $userModel->findByEmail($email);

        if (!$user || !password_verify($password, $user['password_hash'])) {
            Session::setFlash('error', 'Invalid email address or password.');
            $this->redirect('/login');
        }

        if ($user['status'] !== 'active') {
            Session::setFlash('error', 'Your account is inactive. Please contact system administrator.');
            $this->redirect('/login');
        }

        Auth::login($user);
        ActivityLog::log($user['id'], 'USER_LOGIN', "{$user['name']} signed in to system dashboard.");

        Session::setFlash('success', "Welcome back, {$user['name']}!");
        $this->redirect('/dashboard');
    }

    public function showRegister(): void
    {
        if (Auth::check()) {
            $this->redirect('/dashboard');
        }

        $this->render('public/register', [
            'title' => 'Create Account - TaskFlow Enterprise'
        ], 'auth');
    }

    public function register(): void
    {
        $this->requireCsrf();

        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';

        if (empty($name) || empty($email) || empty($password)) {
            Session::setFlash('error', 'All fields are required.');
            $this->redirect('/register');
        }

        if ($password !== $confirmPassword) {
            Session::setFlash('error', 'Password confirmation does not match.');
            $this->redirect('/register');
        }

        if (strlen($password) < 6) {
            Session::setFlash('error', 'Password must be at least 6 characters.');
            $this->redirect('/register');
        }

        $userModel = new User();
        if ($userModel->findByEmail($email)) {
            Session::setFlash('error', 'An account with this email already exists.');
            $this->redirect('/register');
        }

        $userId = $userModel->create([
            'role_id' => 3, // Default Team Member
            'name' => $name,
            'email' => $email,
            'password_hash' => password_hash($password, PASSWORD_BCRYPT)
        ]);

        $newUser = $userModel->findWithRole($userId);
        Auth::login($newUser);

        ActivityLog::log($userId, 'USER_REGISTERED', "New account registered: {$name}");

        Session::setFlash('success', 'Account created successfully! Welcome to TaskFlow.');
        $this->redirect('/dashboard');
    }

    public function logout(): void
    {
        if (Auth::check()) {
            $user = Auth::user();
            ActivityLog::log($user['id'], 'USER_LOGOUT', "{$user['name']} logged out.");
        }

        Auth::logout();
        Session::setFlash('success', 'You have been signed out safely.');
        $this->redirect('/login');
    }
}
