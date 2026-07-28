<!-- Hero Section -->
<section class="py-5 hero-glass border-bottom">
    <div class="container py-4">
        <div class="row align-items-center g-5">
            <div class="col-lg-7">
                <span class="badge bg-primary-subtle text-primary px-3 py-2 rounded-pill fw-semibold mb-3">
                    <i class="bi bi-rocket-takeoff-fill me-1"></i> Full-Stack PHP 8 & MySQL SaaS
                </span>
                <h1 class="display-4 fw-extrabold lh-sm mb-3">
                    Enterprise Project Management <span class="text-primary">Reimagined.</span>
                </h1>
                <p class="lead text-secondary mb-4">
                    Streamline project planning, task execution, Kanban boards, role-based controls, dynamic charts, and real-time team collaboration in one production-quality system.
                </p>
                <div class="d-flex flex-wrap gap-3 mb-4">
                    <a href="/register" class="btn btn-primary btn-lg px-4 rounded-pill shadow-sm">
                        Start Free Trial <i class="bi bi-arrow-right ms-2"></i>
                    </a>
                    <a href="/login" class="btn btn-outline-dark btn-lg px-4 rounded-pill">
                        Demo Account Sign In
                    </a>
                </div>
                <div class="d-flex align-items-center gap-4 text-muted small">
                    <div><i class="bi bi-shield-check text-success me-1"></i> RBAC Security</div>
                    <div><i class="bi bi-kanban text-info me-1"></i> Interactive Kanban</div>
                    <div><i class="bi bi-graph-up text-primary me-1"></i> Live Chart Analytics</div>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="card border-0 shadow-lg p-3 bg-body rounded-4">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between mb-3 border-bottom pb-2">
                            <h6 class="fw-bold mb-0 text-primary"><i class="bi bi-shield-lock-fill me-2"></i>Quick Demo Credentials</h6>
                            <span class="badge bg-success-subtle text-success">Live MySQL Data</span>
                        </div>
                        <p class="text-muted small mb-3">Test every role-based dashboard, project permission, and task workflow instantly:</p>
                        
                        <div class="list-group list-group-flush mb-3">
                            <div class="list-group-item bg-light-subtle rounded mb-2 border-0 p-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong class="text-dark d-block">Admin Role</strong>
                                        <small class="text-muted">admin@taskflow.com</small>
                                    </div>
                                    <span class="badge bg-danger">Full Control</span>
                                </div>
                                <code class="d-block mt-1 text-primary">Password: Admin@123</code>
                            </div>
                            <div class="list-group-item bg-light-subtle rounded mb-2 border-0 p-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong class="text-dark d-block">Project Manager</strong>
                                        <small class="text-muted">pm@taskflow.com</small>
                                    </div>
                                    <span class="badge bg-warning text-dark">Manager</span>
                                </div>
                                <code class="d-block mt-1 text-primary">Password: Manager@123</code>
                            </div>
                            <div class="list-group-item bg-light-subtle rounded border-0 p-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong class="text-dark d-block">Team Member</strong>
                                        <small class="text-muted">member@taskflow.com</small>
                                    </div>
                                    <span class="badge bg-info text-dark">Execution</span>
                                </div>
                                <code class="d-block mt-1 text-primary">Password: Member@123</code>
                            </div>
                        </div>

                        <a href="/login" class="btn btn-dark w-100 rounded-pill py-2">
                            Launch Live Dashboard <i class="bi bi-box-arrow-in-right ms-2"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Live System Metrics -->
<section class="py-5 bg-body">
    <div class="container">
        <div class="row g-4 text-center">
            <div class="col-md-3 col-6">
                <div class="p-4 rounded-3 border bg-body-tertiary">
                    <h2 class="display-5 fw-bold text-primary mb-0"><?= $stats['total_projects'] ?></h2>
                    <div class="text-muted fw-medium mt-1">Active Projects</div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="p-4 rounded-3 border bg-body-tertiary">
                    <h2 class="display-5 fw-bold text-info mb-0"><?= $stats['total_tasks'] ?></h2>
                    <div class="text-muted fw-medium mt-1">Total Tasks</div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="p-4 rounded-3 border bg-body-tertiary">
                    <h2 class="display-5 fw-bold text-success mb-0"><?= $stats['completed_tasks'] ?></h2>
                    <div class="text-muted fw-medium mt-1">Completed Tasks</div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="p-4 rounded-3 border bg-body-tertiary">
                    <h2 class="display-5 fw-bold text-purple mb-0"><?= $stats['total_users'] ?></h2>
                    <div class="text-muted fw-medium mt-1">Active Team Users</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Key Platform Modules -->
<section class="py-5 bg-body-tertiary">
    <div class="container py-3">
        <div class="text-center mb-5 style-max-width" style="max-width: 650px; margin: 0 auto;">
            <h2 class="fw-bold">Enterprise Modules & Capabilities</h2>
            <p class="text-secondary">TaskFlow provides a complete suite of project management tools backed by pure PHP 8 OOP architecture and normalized MySQL database storage.</p>
        </div>

        <div class="row g-4">
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm card-hover p-3">
                    <div class="card-body">
                        <div class="text-primary fs-1 mb-3"><i class="bi bi-kanban"></i></div>
                        <h5 class="fw-bold mb-2">Interactive Kanban Board</h5>
                        <p class="text-secondary small">Drag and drop tasks across status columns (To Do, In Progress, Review, Done) with instant AJAX backend sync.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm card-hover p-3">
                    <div class="card-body">
                        <div class="text-info fs-1 mb-3"><i class="bi bi-shield-lock-fill"></i></div>
                        <h5 class="fw-bold mb-2">Role-Based Access Control</h5>
                        <p class="text-secondary small">Granular permissions for Admin, Project Managers, and Team Members enforcing security guards on every endpoint.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm card-hover p-3">
                    <div class="card-body">
                        <div class="text-success fs-1 mb-3"><i class="bi bi-graph-up-arrow"></i></div>
                        <h5 class="fw-bold mb-2">Analytics & CSV Reports</h5>
                        <p class="text-secondary small">Visual Chart.js status breakdown charts, project completion rate metrics, member workload logs, and 1-click CSV exports.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm card-hover p-3">
                    <div class="card-body">
                        <div class="text-warning fs-1 mb-3"><i class="bi bi-calendar-event-fill"></i></div>
                        <h5 class="fw-bold mb-2">Interactive Calendar</h5>
                        <p class="text-secondary small">Visual timeline of task due dates and project milestones with quick view modals and filtering.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm card-hover p-3">
                    <div class="card-body">
                        <div class="text-danger fs-1 mb-3"><i class="bi bi-chat-left-dots-fill"></i></div>
                        <h5 class="fw-bold mb-2">Comments & Attachments</h5>
                        <p class="text-secondary small">Collaborative task discussions thread with AJAX comments and secure file upload attachments (PDF, DOCX, Images, ZIP).</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm card-hover p-3">
                    <div class="card-body">
                        <div class="text-purple fs-1 mb-3"><i class="bi bi-journal-text"></i></div>
                        <h5 class="fw-bold mb-2">Activity Audit Logs</h5>
                        <p class="text-secondary small">Comprehensive audit logging tracking user sign-ins, project creations, task status updates, and milestone completions.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
