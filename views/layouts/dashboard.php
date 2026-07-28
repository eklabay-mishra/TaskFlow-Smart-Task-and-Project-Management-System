<?php
$notifModel = new \App\Models\Notification();
$unreadCount = $currentUser ? $notifModel->getUnreadCount($currentUser['id']) : 0;
$userTheme = 'dark'; // Force pitch-black luxury dark mode
?>
<!DOCTYPE html>
<html lang="en" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?= htmlspecialchars($csrfToken ?? '') ?>">
    <title><?= htmlspecialchars($title ?? 'TaskFlow Enterprise') ?></title>
    
    <!-- Bootstrap 5 & Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Google Fonts Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Custom CSS -->
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>

    <div id="wrapper">
        <!-- Sidebar Navigation -->
        <div id="sidebar-wrapper" class="d-flex flex-column justify-content-between">
            <div>
                <div class="sidebar-heading d-flex align-items-center justify-content-between">
                    <a href="/dashboard" class="text-white text-decoration-none d-flex align-items-center gap-3">
                        <div class="bg-primary text-white rounded-3 p-2 d-flex align-items-center justify-content-center" style="width: 38px; height: 38px; background: linear-gradient(135deg, #6366f1, #8b5cf6) !important;">
                            <i class="bi bi-kanban-fill fs-5"></i>
                        </div>
                        <span class="fw-extrabold tracking-tight fs-4">TaskFlow</span>
                    </a>
                    <span class="badge bg-primary-subtle text-primary rounded-pill px-2 py-1 fs-xs">Enterprise</span>
                </div>
                
                <div class="list-group list-group-flush my-3">
                    <div class="sidebar-heading text-uppercase text-muted px-3 fs-xs fw-bold mb-1">MAIN</div>
                    <a href="/dashboard" class="list-group-item list-group-item-action <?= $_SERVER['REQUEST_URI'] === '/dashboard' ? 'active' : '' ?>">
                        <i class="bi bi-grid-1x2-fill text-primary"></i> Dashboard
                    </a>
                    <a href="/kanban" class="list-group-item list-group-item-action <?= $_SERVER['REQUEST_URI'] === '/kanban' ? 'active' : '' ?>">
                        <i class="bi bi-kanban-fill text-info"></i> Execution Pipeline
                    </a>
                    <a href="/projects" class="list-group-item list-group-item-action <?= str_starts_with($_SERVER['REQUEST_URI'], '/projects') ? 'active' : '' ?>">
                        <i class="bi bi-folder-fill text-warning"></i> Projects & Scope
                    </a>
                    <a href="/tasks" class="list-group-item list-group-item-action <?= str_starts_with($_SERVER['REQUEST_URI'], '/tasks') ? 'active' : '' ?>">
                        <i class="bi bi-check2-square text-success"></i> Tasks
                    </a>
                    <a href="/calendar" class="list-group-item list-group-item-action <?= $_SERVER['REQUEST_URI'] === '/calendar' ? 'active' : '' ?>">
                        <i class="bi bi-calendar3 text-danger"></i> Timeline Calendar
                    </a>

                    <div class="sidebar-heading text-uppercase text-muted mt-3 px-3 fs-xs fw-bold mb-1">ANALYTICS</div>
                    <a href="/reports" class="list-group-item list-group-item-action <?= $_SERVER['REQUEST_URI'] === '/reports' ? 'active' : '' ?>">
                        <i class="bi bi-graph-up-arrow text-primary"></i> Reports & Analytics
                    </a>

                    <div class="sidebar-heading text-uppercase text-muted mt-3 px-3 fs-xs fw-bold mb-1">MANAGEMENT</div>
                    <a href="/users" class="list-group-item list-group-item-action <?= $_SERVER['REQUEST_URI'] === '/users' ? 'active' : '' ?>">
                        <i class="bi bi-people-fill text-info"></i> Team Members
                    </a>

                    <div class="sidebar-heading text-uppercase text-muted mt-3 px-3 fs-xs fw-bold mb-1">SYSTEM</div>
                    <a href="/notifications" class="list-group-item list-group-item-action <?= $_SERVER['REQUEST_URI'] === '/notifications' ? 'active' : '' ?>">
                        <i class="bi bi-bell-fill text-warning"></i> Notifications
                        <?php if ($unreadCount > 0): ?>
                            <span class="badge bg-danger rounded-pill ms-auto" id="sidebar-unread-count"><?= $unreadCount ?></span>
                        <?php endif; ?>
                    </a>
                    <a href="/settings" class="list-group-item list-group-item-action <?= $_SERVER['REQUEST_URI'] === '/settings' ? 'active' : '' ?>">
                        <i class="bi bi-gear-fill text-secondary"></i> Settings
                    </a>
                </div>
            </div>

            <!-- Upgrade to Pro Card Widget -->
            <div class="p-3 m-3 rounded-4 glass-panel border border-primary border-opacity-25 text-center">
                <div class="text-warning mb-1"><i class="bi bi-stars fs-4"></i></div>
                <h6 class="fw-bold mb-1 fs-sm">Upgrade to Pro</h6>
                <p class="text-muted fs-xs mb-3">Unlock advanced analytics, AI workload predictions & custom workflows.</p>
                <button type="button" class="btn btn-primary btn-sm rounded-pill w-100 py-2">Upgrade Now</button>
            </div>
        </div>

        <!-- Page Content Wrapper -->
        <div id="page-content-wrapper" class="w-100 d-flex flex-column">
            <!-- Topbar Header -->
            <header class="topbar d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-3">
                    <button class="btn btn-outline-secondary btn-sm border-0 rounded-circle" id="sidebar-toggle">
                        <i class="bi bi-list fs-5"></i>
                    </button>

                    <!-- Search Input with Keyboard Shortcut Pill -->
                    <form action="/tasks" method="GET" class="d-none d-md-flex align-items-center" style="min-width: 340px;">
                        <div class="w-100 position-relative">
                            <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
                            <input type="text" name="search" class="form-control search-input-pill ps-5 pe-5" placeholder="Search tasks, projects, pipelines..." value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
                            <span class="position-absolute top-50 end-0 translate-middle-y me-3 badge bg-secondary-subtle text-muted fs-xs">⌘K</span>
                        </div>
                    </form>
                </div>

                <div class="d-flex align-items-center gap-3">
                    <!-- Create New Task Button -->
                    <a href="/tasks" class="btn btn-primary rounded-pill px-3 py-2 d-flex align-items-center gap-2 shadow-sm fs-xs fw-bold">
                        <i class="bi bi-plus-lg"></i> <span>Create New Task</span>
                    </a>

                    <!-- Dark Mode Toggle -->
                    <button class="btn btn-outline-secondary btn-sm rounded-circle p-2 d-flex align-items-center justify-content-center" id="theme-toggle-btn" title="Toggle Light/Dark Theme">
                        <i class="bi bi-moon-stars-fill"></i>
                    </button>

                    <!-- Messages Icon -->
                    <a href="/tasks" class="btn btn-outline-secondary btn-sm rounded-circle p-2 d-flex align-items-center justify-content-center text-body" title="Task Discussions">
                        <i class="bi bi-chat-text fs-6"></i>
                    </a>

                    <!-- Notifications Dropdown -->
                    <div class="dropdown">
                        <button class="btn btn-outline-secondary btn-sm position-relative rounded-circle p-2 d-flex align-items-center justify-content-center" data-bs-toggle="dropdown">
                            <i class="bi bi-bell fs-6"></i>
                            <?php if ($unreadCount > 0): ?>
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" id="nav-unread-badge">
                                    <?= $unreadCount ?>
                                </span>
                            <?php endif; ?>
                        </button>
                        <div class="dropdown-menu dropdown-menu-end shadow-lg border-0 p-0" style="width: 320px;">
                            <div class="p-3 border-bottom d-flex justify-content-between align-items-center">
                                <h6 class="mb-0 fw-bold">Notifications Inbox</h6>
                                <a href="/notifications" class="text-primary fs-xs text-decoration-none">View All</a>
                            </div>
                            <div class="list-group list-group-flush fs-sm" style="max-height: 280px; overflow-y: auto;">
                                <a href="/notifications" class="list-group-item list-group-item-action py-3 text-center text-muted">
                                    Click to view notification center
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- User Profile Dropdown -->
                    <div class="dropdown">
                        <a href="#" class="d-flex align-items-center gap-2 text-decoration-none dropdown-toggle text-body" data-bs-toggle="dropdown">
                            <img src="/uploads/<?= htmlspecialchars($currentUser['avatar'] ?? 'default-avatar.png') ?>" class="avatar-sm" alt="User Avatar" onerror="this.src='https://ui-avatars.com/api/?name=<?= urlencode($currentUser['name']) ?>'">
                            <div class="d-none d-lg-block text-start">
                                <div class="fw-bold fs-sm lh-1"><?= htmlspecialchars($currentUser['name']) ?></div>
                                <div class="text-muted fs-xs lh-1 mt-1"><?= htmlspecialchars($currentUser['role_name'] ?? 'Team Member') ?></div>
                            </div>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0">
                            <li><a class="dropdown-item" href="/profile"><i class="bi bi-person me-2"></i> My Profile</a></li>
                            <li><a class="dropdown-item" href="/tasks"><i class="bi bi-check2-square me-2"></i> My Tasks</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-danger" href="/logout"><i class="bi bi-box-arrow-right me-2"></i> Sign Out</a></li>
                        </ul>
                    </div>
                </div>
            </header>

            <!-- Dynamic Content Area -->
            <div class="container-fluid p-4 flex-grow-1">
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

            <!-- Footer -->
            <footer class="py-3 border-top border-secondary border-opacity-10 px-4 text-muted small d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div>TaskFlow Enterprise &copy; <?= date('Y') ?> | Full-Stack Commercial SaaS Application</div>
                <div>Connected User: <strong><?= htmlspecialchars($currentUser['name']) ?></strong> (<?= htmlspecialchars($currentUser['role_name']) ?>)</div>
            </footer>
        </div>
    </div>

    <!-- Bootstrap & JS Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="/assets/js/app.js"></script>
</body>
</html>
