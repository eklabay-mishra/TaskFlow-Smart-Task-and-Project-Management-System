<?php
namespace App\Controllers;

use Core\Controller;
use Core\Auth;
use Core\Session;
use App\Models\User;
use App\Models\ActivityLog;

class ProfileController extends Controller
{
    public function index(): void
    {
        $this->requireAuth();
        $userModel = new User();
        $user = $userModel->findWithRole(Auth::id());

        $this->render('profile/index', [
            'title' => 'User Profile - TaskFlow',
            'user'  => $user
        ], 'dashboard');
    }

    public function update(): void
    {
        $this->requireAuth();
        $this->requireCsrf();

        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $bio = trim($_POST['bio'] ?? '');

        if (empty($name) || empty($email)) {
            Session::setFlash('error', 'Name and email address are required.');
            $this->redirect('/profile');
        }

        $userModel = new User();
        $existing = $userModel->findByEmail($email);
        if ($existing && (int)$existing['id'] !== Auth::id()) {
            Session::setFlash('error', 'Email is already used by another account.');
            $this->redirect('/profile');
        }

        $userModel->updateProfile(Auth::id(), [
            'name'  => $name,
            'email' => $email,
            'phone' => $phone,
            'bio'   => $bio
        ]);

        // Refresh session
        $updatedUser = $userModel->findWithRole(Auth::id());
        Auth::login($updatedUser);

        ActivityLog::log(Auth::id(), 'PROFILE_UPDATED', "Updated personal profile info.");

        Session::setFlash('success', 'Profile details updated successfully.');
        $this->redirect('/profile');
    }

    public function updatePassword(): void
    {
        $this->requireAuth();
        $this->requireCsrf();

        $currentPassword = $_POST['current_password'] ?? '';
        $newPassword = $_POST['new_password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';

        if (empty($currentPassword) || empty($newPassword)) {
            Session::setFlash('error', 'All password fields are required.');
            $this->redirect('/profile');
        }

        if ($newPassword !== $confirmPassword) {
            Session::setFlash('error', 'New password confirmation does not match.');
            $this->redirect('/profile');
        }

        if (strlen($newPassword) < 6) {
            Session::setFlash('error', 'New password must be at least 6 characters.');
            $this->redirect('/profile');
        }

        $userModel = new User();
        $user = $userModel->find(Auth::id());

        if (!password_verify($currentPassword, $user['password_hash'])) {
            Session::setFlash('error', 'Current password is incorrect.');
            $this->redirect('/profile');
        }

        $userModel->updatePassword(Auth::id(), password_hash($newPassword, PASSWORD_BCRYPT));
        ActivityLog::log(Auth::id(), 'PASSWORD_CHANGED', "Changed account password.");

        Session::setFlash('success', 'Password updated successfully.');
        $this->redirect('/profile');
    }

    public function updateAvatar(): void
    {
        $this->requireAuth();
        $this->requireCsrf();

        if (empty($_FILES['avatar']) || $_FILES['avatar']['error'] !== UPLOAD_ERR_OK) {
            Session::setFlash('error', 'Please select an image file for your avatar.');
            $this->redirect('/profile');
        }

        $file = $_FILES['avatar'];
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

        if (!in_array($ext, $allowed)) {
            Session::setFlash('error', 'Invalid image format. Allowed: JPG, PNG, GIF, WEBP.');
            $this->redirect('/profile');
        }

        if (!is_dir(UPLOAD_DIR)) {
            mkdir(UPLOAD_DIR, 0755, true);
        }

        $newAvatar = uniqid('avatar_') . '.' . $ext;
        if (move_uploaded_file($file['tmp_name'], UPLOAD_DIR . $newAvatar)) {
            $userModel = new User();
            $userModel->updateAvatar(Auth::id(), $newAvatar);

            $updatedUser = $userModel->findWithRole(Auth::id());
            Auth::login($updatedUser);

            ActivityLog::log(Auth::id(), 'AVATAR_UPDATED', "Updated profile picture.");
            Session::setFlash('success', 'Profile photo updated.');
        } else {
            Session::setFlash('error', 'Failed to upload photo.');
        }

        $this->redirect('/profile');
    }

    public function toggleTheme(): void
    {
        $this->requireAuth();
        $theme = $_POST['theme'] ?? 'light';
        if (!in_array($theme, ['light', 'dark'])) {
            $theme = 'light';
        }

        $userModel = new User();
        $userModel->updateTheme(Auth::id(), $theme);

        $_SESSION['user_data']['theme_mode'] = $theme;

        if ($this->isAjax()) {
            $this->jsonResponse(['success' => true, 'theme' => $theme]);
        }

        $this->redirect($_SERVER['HTTP_REFERER'] ?? '/dashboard');
    }
}
