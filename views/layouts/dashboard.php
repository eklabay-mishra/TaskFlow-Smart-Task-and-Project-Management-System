<?php
$notifModel = new \App\Models\Notification();
$unreadCount = $currentUser ? $notifModel->getUnreadCount($currentUser['id']) : 0;
$userTheme = $currentUser['theme_mode'] ?? 'light';
?>
<!DOCTYPE html>
<html lang="en" data-theme="<?= htmlspecialchars($userTheme) ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?= htmlspecialchars($csrfToken ?? '') ?>">
    <title><?= htmlspecialchars($title ?? 'Dashboard - TaskFlow Enterprise') ?></title>
    
    <!-- Bootstrap 5 & Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
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
        <div id="sidebar-wrapper">
            <div class="sidebar-heading d-flex align-items-center justify-content-between">
                <a href="/dashboard" class="text-white text-decoration-none d-flex align-items-center gap-2">
                    <i class="bi bi-kanban-fill text-primary"></i>
                    <span>TaskFlow</span>
                </a>
                <span class="badge bg-primary text-white fs-xs">v1.0</span>
            </div>
            
            <div class="list-group list-group-flush my-3">
                <a href="/dashboard" class="list-group-item list-group-item-action <?= $_SERVER['REQUEST_URI'] === '/dashboard' ? 'active' : '' ?>">
                    <i class="bi bi-grid-1x2-fill"></i> Dashboard
                </a>
                <a href="/projects" class="list-group-item list-group-item-action <?= str_starts_with($_SERVER['REQUEST_URI'], '/projects') ? 'active' : '' ?>">
                    <i class="bi bi-folder-fill"></i> Projects
                </a>
                <a href="/tasks" class="list-group-item list-group-item-action <?= str_starts_with($_SERVER['REQUEST_URI'], '/tasks') ? 'active' : '' ?>">
                    <i class="bi bi-check2-square"></i> Tasks List
                </a>
                <a href="/kanban" class="list-group-item list-group-item-action <?= $_SERVER['REQUEST_URI'] === '/kanban' ? 'active' : '' ?>">
                    <i class="bi bi-kanban"></i> Kanban Board
                </a>
                <a href="/calendar" class="list-group-item list-group-item-action <?= $_SERVER['REQUEST_URI'] === '/calendar' ? 'active' : '' ?>">
                    <i class="bi bi-calendar3"></i> Calendar
                </a>
                <a href="/reports" class="list-group-item list-group-item-action <?= $_SERVER['REQUEST_URI'] === '/reports' ? 'active' : '' ?>">
                    <i class="bi bi-graph-up-arrow"></i> Reports & CSV
                </a>
                <a href="/notifications" class="list-group-item list-group-item-action <?= $_SERVER['REQUEST_URI'] === '/notifications' ? 'active' : '' ?>">
                    <i class="bi bi-bell-fill"></i> Notifications
                    <?php if ($unreadCount > 0): ?>
                        <span class="badge bg-danger rounded-pill ms-auto" id="sidebar-unread-count"><?= $unreadCount ?></span>
                    <?php endif; ?>
                </a>

                <?php if (\Core\Auth::isAdmin()): ?>
                    <div class="sidebar-heading text-uppercase text-secondary mt-3 mb-1 px-3 fs-xs fw-bold">Administration</div>
                    <a href="/users" class="list-group-item list-group-item-action <?= $_SERVER['REQUEST_URI'] === '/users' ? 'active' : '' ?>">
                        <i class="bi bi-people-fill"></i> User Accounts
                    </a>
                    <a href="/settings" class="list-group-item list-group-item-action <?= $_SERVER['REQUEST_URI'] === '/settings' ? 'active' : '' ?>">
                        <i class="bi bi-gear-fill"></i> System Settings
                    </a>
                <?php endif; ?>

                <div class="sidebar-heading text-uppercase text-secondary mt-3 mb-1 px-3 fs-xs fw-bold">Account</div>
                <a href="/profile" class="list-group-item list-group-item-action <?= $_SERVER['REQUEST_URI'] === '/profile' ? 'active' : '' ?>">
                    <i class="bi bi-person-circle"></i> Profile & Security
                </a>
                <a href="/logout" class="list-group-item list-group-item-action text-danger mt-2">
                    <i class="bi bi-box-arrow-right"></i> Sign Out
                </a>
            </div>
        </div>

        <!-- Page Content Wrapper -->
        <div id="page-content-wrapper" class="w-100 d-flex flex-column">
            <!-- Topbar Header -->
            <header class="topbar d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-3">
                    <button class="btn btn-light btn-sm border" id="sidebar-toggle">
                        <i class="bi bi-list fs-5"></i>
                    </button>
                    <!-- Global Search Form -->
                    <form action="/tasks" method="GET" class="d-none d-md-flex align-items-center" style="max-width: 320px;">
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-body-tertiary border-end-0"><i class="bi bi-search"></i></span>
                            <input type="text" name="search" class="form-control bg-body-tertiary border-start-0" placeholder="Search tasks, projects..." value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
                        </div>
                    </form>
                </div>

                <div class="d-flex align-items-center gap-3">
                    <!-- Dark Mode Toggle Button -->
                    <button class="btn btn-outline-secondary btn-sm rounded-circle p-2 d-flex align-items-center justify-content-center" id="theme-toggle-btn" title="Toggle Light/Dark Theme">
                        <i class="bi bi-moon-stars-fill"></i>
                    </button>

                    <!-- Notifications Dropdown -->
                    <div class="dropdown">
                        <button class="btn btn-outline-secondary btn-sm position-relative rounded-circle p-2 d-flex align-items-center justify-content-center" data-bs-toggle="dropdown">
                            <i class="bi bi-bell"></i>
                            <?php if ($unreadCount > 0): ?>
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" id="nav-unread-badge">
                                    <?= $unreadCount ?>
                                </span>
                            <?php endif; ?>
                        </button>
                        <div class="dropdown-menu dropdown-menu-end shadow border-0 p-0" style="width: 320px;">
                            <div class="p-3 border-bottom d-flex justify-content-between align-items-center bg-body-tertiary">
                                <h6 class="mb-0 fw-bold">Notifications</h6>
                                <a href="/notifications" class="text-primary fs-xs text-decoration-none">View All</a>
                            </div>
                            <div class="list-group list-group-flush fs-sm style-scroll" style="max-height: 280px; overflow-y: auto;">
                                <a href="/notifications" class="list-group-item list-group-item-action py-3 text-center text-muted">
                                    Click View All to check your notification inbox.
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- User Profile Dropdown -->
                    <div class="dropdown">
                        <a href="#" class="d-flex align-items-center gap-2 text-decoration-none dropdown-toggle" data-bs-toggle="dropdown">
                            <img src="/uploads/<?= htmlspecialchars($currentUser['avatar'] ?? 'default-avatar.png') ?>" class="avatar-sm" alt="User Avatar" onerror="this.src='https://ui-avatars.com/api/?name=<?= urlencode($currentUser['name']) ?>'">
                            <div class="d-none d-lg-block text-start">
                                <div class="fw-bold fs-sm text-body lh-1"><?= htmlspecialchars($currentUser['name']) ?></div>
                                <div class="text-muted fs-xs lh-1 mt-1"><?= htmlspecialchars($currentUser['role_name'] ?? 'Team Member') ?></div>
                            </div>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow border-0">
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
                    <div class="alert alert-success alert-dismissible fade show shadow-sm mb-4" role="alert">
                        <i class="bi bi-check-circle-fill me-2"></i> <?= htmlspecialchars($flashSuccess) ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?php if ($flashError): ?>
                    <div class="alert alert-danger alert-dismissible fade show shadow-sm mb-4" role="alert">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i> <?= htmlspecialchars($flashError) ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?= $content ?>
            </div>

            <!-- Footer -->
            <footer class="bg-card py-3 border-top px-4 text-muted small d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div>TaskFlow Enterprise &copy; <?= date('Y') ?> | Full-Stack PHP 8 MVC & MySQL System</div>
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
