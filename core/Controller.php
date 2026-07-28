<?php
namespace Core;

abstract class Controller
{
    protected function render(string $view, array $data = [], string $layout = 'dashboard'): void
    {
        extract($data);
        
        // Make session & auth available globally in views
        $currentUser = Auth::user();
        $csrfToken = CSRF::token();
        $flashSuccess = Session::getFlash('success');
        $flashError = Session::getFlash('error');

        $viewFile = APP_ROOT . "/views/{$view}.php";
        
        if (!file_exists($viewFile)) {
            die("View file not found: {$viewFile}");
        }

        ob_start();
        require $viewFile;
        $content = ob_get_clean();

        if ($layout) {
            $layoutFile = APP_ROOT . "/views/layouts/{$layout}.php";
            if (file_exists($layoutFile)) {
                require $layoutFile;
                return;
            }
        }

        echo $content;
    }

    protected function jsonResponse(array $data, int $statusCode = 200): void
    {
        header_remove();
        http_response_code($statusCode);
        header("Content-Type: application/json; charset=utf-8");
        echo json_encode($data);
        exit;
    }

    protected function redirect(string $url): void
    {
        header("Location: " . $url);
        exit;
    }

    protected function requireAuth(): void
    {
        if (!Auth::check()) {
            Session::setFlash('error', 'Please sign in to access the system dashboard.');
            $this->redirect('/login');
        }
    }

    protected function requireRole(array $allowedRoleIds): void
    {
        $this->requireAuth();
        $currentRoleId = Auth::roleId();
        
        if (!in_array($currentRoleId, $allowedRoleIds)) {
            Session::setFlash('error', 'Access denied. You do not have permission for this module.');
            $this->redirect('/dashboard');
        }
    }

    protected function requireCsrf(): void
    {
        $token = $_POST['csrf_token'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? null;
        if (!CSRF::verify($token)) {
            if ($this->isAjax()) {
                $this->jsonResponse(['success' => false, 'message' => 'Invalid CSRF security token.'], 403);
            }
            Session::setFlash('error', 'Security token mismatch. Please try again.');
            $this->redirect($_SERVER['HTTP_REFERER'] ?? '/dashboard');
        }
    }

    protected function isAjax(): bool
    {
        return (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest')
            || (isset($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false);
    }
}
