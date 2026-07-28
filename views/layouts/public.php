<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?= htmlspecialchars($csrfToken ?? '') ?>">
    <title><?= htmlspecialchars($title ?? 'TaskFlow Enterprise') ?></title>
    
    <!-- Bootstrap 5 & Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>

    <!-- Public Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top py-3">
        <div class="container">
            <a class="navbar-brand fw-bold text-primary d-flex align-items-center gap-2 fs-4" href="/">
                <i class="bi bi-kanban-fill fs-3"></i> TaskFlow<span class="text-white fs-6 badge bg-primary rounded-pill">SaaS</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#publicNavbar">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="publicNavbar">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-4">
                    <li class="nav-item"><a class="nav-link px-3" href="/">Home</a></li>
                    <li class="nav-item"><a class="nav-link px-3" href="/features">Features</a></li>
                    <li class="nav-item"><a class="nav-link px-3" href="/about">About</a></li>
                    <li class="nav-item"><a class="nav-link px-3" href="/contact">Contact</a></li>
                </ul>
                <div class="d-flex align-items-center gap-2">
                    <?php if ($currentUser): ?>
                        <a href="/dashboard" class="btn btn-primary px-4 rounded-pill fw-semibold">
                            <i class="bi bi-speedometer2 me-1"></i> Go to Dashboard
                        </a>
                    <?php else: ?>
                        <a href="/login" class="btn btn-outline-light px-4 rounded-pill fw-medium">Sign In</a>
                        <a href="/register" class="btn btn-primary px-4 rounded-pill fw-medium">Get Started Free</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>

    <!-- Flash Messages -->
    <?php if ($flashSuccess): ?>
        <div class="alert alert-success alert-dismissible fade show container mt-3 mb-0" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> <?= htmlspecialchars($flashSuccess) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if ($flashError): ?>
        <div class="alert alert-danger alert-dismissible fade show container mt-3 mb-0" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i> <?= htmlspecialchars($flashError) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- Main Public Content -->
    <main>
        <?= $content ?>
    </main>

    <!-- Public Footer -->
    <footer class="bg-dark text-white pt-5 pb-4 mt-5 border-top border-secondary">
        <div class="container">
            <div class="row g-4 mb-4">
                <div class="col-lg-4 col-md-6">
                    <h5 class="fw-bold text-primary mb-3"><i class="bi bi-kanban-fill me-2"></i>TaskFlow Enterprise</h5>
                    <p class="text-secondary">Production-quality PHP 8 MVC project management platform with MySQL integration, role-based dashboards, Kanban workflow, dynamic analytics, and security controls.</p>
                </div>
                <div class="col-lg-2 col-md-6">
                    <h6 class="fw-bold text-uppercase text-light mb-3">Quick Links</h6>
                    <ul class="list-unstyled text-secondary">
                        <li class="mb-2"><a href="/" class="text-secondary text-decoration-none">Home</a></li>
                        <li class="mb-2"><a href="/features" class="text-secondary text-decoration-none">Features</a></li>
                        <li class="mb-2"><a href="/about" class="text-secondary text-decoration-none">About System</a></li>
                        <li class="mb-2"><a href="/contact" class="text-secondary text-decoration-none">Contact Support</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-6">
                    <h6 class="fw-bold text-uppercase text-light mb-3">Demo Accounts</h6>
                    <ul class="list-unstyled text-secondary small">
                        <li class="mb-2"><strong class="text-white">Admin:</strong> admin@taskflow.com</li>
                        <li class="mb-2"><strong class="text-white">Manager:</strong> pm@taskflow.com</li>
                        <li class="mb-2"><strong class="text-white">Member:</strong> member@taskflow.com</li>
                        <li class="mb-2"><strong class="text-white">Pass:</strong> Admin@123 / Manager@123</li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-6">
                    <h6 class="fw-bold text-uppercase text-light mb-3">Tech Stack</h6>
                    <span class="badge bg-primary me-1 mb-1">PHP 8 OOP</span>
                    <span class="badge bg-secondary me-1 mb-1">MySQL 9</span>
                    <span class="badge bg-info me-1 mb-1">Bootstrap 5</span>
                    <span class="badge bg-success me-1 mb-1">Chart.js</span>
                    <span class="badge bg-warning text-dark me-1 mb-1">SweetAlert2</span>
                    <span class="badge bg-danger me-1 mb-1">AJAX</span>
                </div>
            </div>
            <hr class="border-secondary mb-4">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 text-secondary small">
                <div>&copy; <?= date('Y') ?> TaskFlow Enterprise. Built for software engineering portfolio & deployment.</div>
                <div>Interview-ready PHP MVC + MySQL Architecture.</div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap & SweetAlert JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="/assets/js/app.js"></script>
</body>
</html>
