<?php
namespace App\Controllers;

use Core\Controller;
use Core\Session;
use Core\CSRF;
use App\Models\ContactInquiry;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;

class HomeController extends Controller
{
    public function index(): void
    {
        $projectModel = new Project();
        $taskModel = new Task();
        $userModel = new User();

        $stats = [
            'total_projects' => $projectModel->count(),
            'total_tasks' => $taskModel->count(),
            'total_users' => $userModel->count("status = 'active'"),
            'completed_tasks' => $taskModel->count("status = 'done'")
        ];

        $this->render('public/home', [
            'title' => 'TaskFlow - Enterprise Project Management System',
            'stats' => $stats
        ], 'public');
    }

    public function about(): void
    {
        $this->render('public/about', [
            'title' => 'About TaskFlow Enterprise'
        ], 'public');
    }

    public function features(): void
    {
        $this->render('public/features', [
            'title' => 'TaskFlow Features & Architecture'
        ], 'public');
    }

    public function contact(): void
    {
        $this->render('public/contact', [
            'title' => 'Contact Us - TaskFlow Enterprise'
        ], 'public');
    }

    public function submitContact(): void
    {
        $this->requireCsrf();

        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $subject = trim($_POST['subject'] ?? '');
        $message = trim($_POST['message'] ?? '');

        if (empty($name) || empty($email) || empty($subject) || empty($message)) {
            if ($this->isAjax()) {
                $this->jsonResponse(['success' => false, 'message' => 'All fields are required.'], 400);
            }
            Session::setFlash('error', 'All fields are required.');
            $this->redirect('/contact');
        }

        $inquiryModel = new ContactInquiry();
        $inquiryModel->create([
            'name' => $name,
            'email' => $email,
            'subject' => $subject,
            'message' => $message
        ]);

        if ($this->isAjax()) {
            $this->jsonResponse(['success' => true, 'message' => 'Thank you! Your message has been received. Our team will get back to you shortly.']);
        }

        Session::setFlash('success', 'Thank you! Your message has been received.');
        $this->redirect('/contact');
    }
}
