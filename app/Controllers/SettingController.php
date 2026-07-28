<?php
namespace App\Controllers;

use Core\Controller;
use Core\Auth;
use Core\Session;
use App\Models\Setting;
use App\Models\ActivityLog;

class SettingController extends Controller
{
    public function index(): void
    {
        $this->requireRole([1]); // Admin only
        $settingModel = new Setting();
        $settings = $settingModel->getAllAsKeyValue();

        $this->render('settings/index', [
            'title'    => 'System Settings - TaskFlow Admin',
            'settings' => $settings
        ], 'dashboard');
    }

    public function update(): void
    {
        $this->requireRole([1]);
        $this->requireCsrf();

        $settingModel = new Setting();

        $allowedKeys = ['app_name', 'app_email', 'allow_registration', 'default_role', 'company_name'];
        foreach ($allowedKeys as $key) {
            if (isset($_POST[$key])) {
                $settingModel->set($key, trim($_POST[$key]));
            }
        }

        ActivityLog::log(Auth::id(), 'SETTINGS_UPDATED', "Admin updated system settings configuration.");

        Session::setFlash('success', 'System settings saved.');
        $this->redirect('/settings');
    }
}
