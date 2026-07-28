<?php
namespace App\Controllers;

use Core\Controller;
use Core\Auth;
use Core\Session;
use App\Models\User;
use App\Models\ActivityLog;

class UserController extends Controller
{
    public function index(): void
    {
        $this->requireRole([1]); // Admin only
        $userModel = new User();
        $users = $userModel->getAllWithRoles();

        $this->render('users/index', [
            'title' => 'User & Role Management - TaskFlow Admin',
            'users' => $users
        ], 'dashboard');
    }

    public function store(): void
    {
        $this->requireRole([1]);
        $this->requireCsrf();

        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $roleId = (int)($_POST['role_id'] ?? 3);

        if (empty($name) || empty($email) || empty($password)) {
            Session::setFlash('error', 'Name, email and password are required.');
            $this->redirect('/users');
        }

        $userModel = new User();
        if ($userModel->findByEmail($email)) {
            Session::setFlash('error', 'User email already exists.');
            $this->redirect('/users');
        }

        $userId = $userModel->create([
            'role_id'       => $roleId,
            'name'          => $name,
            'email'         => $email,
            'password_hash' => password_hash($password, PASSWORD_BCRYPT),
            'phone'         => trim($_POST['phone'] ?? ''),
            'status'        => $_POST['status'] ?? 'active'
        ]);

        ActivityLog::log(Auth::id(), 'ADMIN_USER_CREATED', "Admin created new user account: {$name} (#{$userId})");

        Session::setFlash('success', 'User account created successfully.');
        $this->redirect('/users');
    }

    public function update(string $id): void
    {
        $this->requireRole([1]);
        $this->requireCsrf();

        $targetUserId = (int)$id;
        $roleId = (int)($_POST['role_id'] ?? 3);
        $status = $_POST['status'] ?? 'active';

        $userModel = new User();
        $userModel->updateRoleAndStatus($targetUserId, $roleId, $status);

        ActivityLog::log(Auth::id(), 'ADMIN_USER_UPDATED', "Admin updated permissions for user #{$targetUserId}");

        Session::setFlash('success', 'User permissions updated.');
        $this->redirect('/users');
    }

    public function delete(string $id): void
    {
        $this->requireRole([1]);
        $this->requireCsrf();

        $targetUserId = (int)$id;

        if ($targetUserId === Auth::id()) {
            Session::setFlash('error', 'You cannot delete your own admin account.');
            $this->redirect('/users');
        }

        $userModel = new User();
        $userModel->delete($targetUserId);

        ActivityLog::log(Auth::id(), 'ADMIN_USER_DELETED', "Admin deleted user #{$targetUserId}");

        Session::setFlash('success', 'User deleted from system.');
        $this->redirect('/users');
    }
}
