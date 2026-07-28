<!DOCTYPE html>
<html lang="en" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?= htmlspecialchars($csrfToken ?? '') ?>">
    <title><?= htmlspecialchars($title ?? 'Sign In - TaskFlow Enterprise') ?></title>
    
    <!-- Bootstrap 5 & Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Google Fonts Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="/assets/css/style.css">

    <style>
        body {
            background-color: #05070b !important;
            color: #d1d5db;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .auth-nav {
            padding: 1.5rem 2rem;
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            z-index: 10;
        }

        .auth-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 5rem 1.5rem 3rem 1.5rem;
            position: relative;
            z-index: 1;
        }

        .auth-card {
            background: #0d101d !important;
            border: 1px solid rgba(255, 255, 255, 0.08) !important;
            border-radius: 1.25rem !important;
            box-shadow: 0 25px 60px -10px rgba(0, 0, 0, 0.8) !important;
        }

        .demo-card-btn {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.07);
            border-radius: 0.75rem;
            padding: 0.65rem 0.85rem;
            color: #9ca3af;
            text-align: left;
            transition: all 0.2s ease;
            cursor: pointer;
            width: 100%;
        }

        .demo-card-btn:hover, .demo-card-btn.active {
            background: rgba(124, 58, 237, 0.15);
            border-color: rgba(124, 58, 237, 0.4);
            color: #ffffff;
        }

        .demo-card-btn .demo-role {
            font-size: 0.7rem;
            font-weight: 700;
            text-uppercase: uppercase;
            letter-spacing: 0.05em;
        }

        .demo-card-btn .demo-name {
            font-size: 0.85rem;
            font-weight: 700;
            color: #ffffff;
        }

        .auth-input-group {
            position: relative;
        }

        .auth-input-group .form-control {
            background-color: #070914 !important;
            border: 1px solid rgba(255, 255, 255, 0.09) !important;
            color: #ffffff !important;
            border-radius: 0.75rem !important;
            padding: 0.75rem 1rem 0.75rem 2.75rem !important;
            font-size: 0.9rem;
        }

        .auth-input-group .form-control:focus {
            border-color: rgba(124, 58, 237, 0.5) !important;
            box-shadow: 0 0 0 3px rgba(124, 58, 237, 0.2) !important;
        }

        .auth-input-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #6b7280;
            font-size: 1rem;
            z-index: 5;
        }

        .auth-show-pass {
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #6b7280;
            cursor: pointer;
            z-index: 5;
            transition: color 0.2s ease;
        }

        .auth-show-pass:hover {
            color: #ffffff;
        }
    </style>
</head>
<body>

    <!-- Auth Topbar Header -->
    <header class="auth-nav d-flex justify-content-between align-items-center">
        <a href="/" class="text-white text-decoration-none d-flex align-items-center gap-3">
            <div class="bg-primary text-white rounded-3 p-2 d-flex align-items-center justify-content-center" style="width: 38px; height: 38px; background: linear-gradient(135deg, #7c3aed, #6366f1) !important;">
                <i class="bi bi-kanban-fill fs-5"></i>
            </div>
            <span class="fw-extrabold tracking-tight fs-4">TaskFlow <span class="fs-xs badge bg-primary-subtle text-primary rounded-pill px-2 py-1 align-middle ms-1">Enterprise</span></span>
        </a>
        <a href="/" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
            <i class="bi bi-house me-1"></i> Main Site
        </a>
    </header>

    <!-- Main Auth Content Area -->
    <div class="auth-container">
        <div class="w-100" style="max-width: 1100px;">
            <!-- Flash Messages -->
            <?php if ($flashSuccess): ?>
                <div class="alert alert-success alert-dismissible fade show shadow-sm mb-4 border-0 rounded-4" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i> <?= htmlspecialchars($flashSuccess) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php if ($flashError): ?>
                <div class="alert alert-danger alert-dismissible fade show shadow-sm mb-4 border-0 rounded-4" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i> <?= htmlspecialchars($flashError) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?= $content ?>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
