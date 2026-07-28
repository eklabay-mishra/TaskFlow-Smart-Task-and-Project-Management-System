<div class="row g-5 align-items-center">
    <!-- Left Column: High-Impact Brand & Feature Showcase -->
    <div class="col-lg-6 d-none d-lg-block">
        <div class="pe-xl-4">
            <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-2 fs-xs fw-bold mb-3 border border-primary border-opacity-25">
                <i class="bi bi-stars me-1"></i> TASKFLOW ENTERPRISE v2.5
            </span>
            <h1 class="fw-extrabold text-white display-5 mb-3 lh-sm tracking-tight">
                Accelerate software delivery with real-time project intelligence.
            </h1>
            <p class="text-secondary fs-6 mb-4 lh-relaxed">
                Empower your organization with execution pipelines, Gantt timelines, real-time MySQL analytics, and role-tailored workspaces.
            </p>

            <!-- Feature Bullet Cards -->
            <div class="d-flex flex-column gap-3 mb-4">
                <div class="d-flex align-items-start gap-3 p-3 rounded-4" style="background: rgba(255, 255, 255, 0.02); border: 1px solid rgba(255, 255, 255, 0.05);">
                    <div class="p-2 rounded-3 text-primary" style="background: rgba(124, 58, 237, 0.12);">
                        <i class="bi bi-kanban fs-4"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold text-white mb-1">Real-Time Pipeline Kanban</h6>
                        <small class="text-secondary">Drag & drop tasks across workflow stages with instant database synchronization.</small>
                    </div>
                </div>

                <div class="d-flex align-items-start gap-3 p-3 rounded-4" style="background: rgba(255, 255, 255, 0.02); border: 1px solid rgba(255, 255, 255, 0.05);">
                    <div class="p-2 rounded-3 text-info" style="background: rgba(59, 130, 246, 0.12);">
                        <i class="bi bi-graph-up-arrow fs-4"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold text-white mb-1">Role-Based Intelligence Dashboards</h6>
                        <small class="text-secondary">Distinct analytics, workloads, and performance gauges tailored for Admin, PMs, and Engineers.</small>
                    </div>
                </div>
            </div>

            <!-- Trust Bar -->
            <div class="pt-3 border-top border-secondary border-opacity-10 d-flex align-items-center gap-3 text-muted fs-xs">
                <i class="bi bi-shield-check text-success fs-5"></i>
                <span>Trusted by over 10,000+ engineering teams worldwide</span>
            </div>
        </div>
    </div>

    <!-- Right Column: Auth Card -->
    <div class="col-lg-6">
        <div class="auth-card p-4 p-md-5">
            <div class="text-center mb-4">
                <div class="d-inline-flex align-items-center justify-content-center p-3 rounded-4 mb-3" style="background: linear-gradient(135deg, rgba(124, 58, 237, 0.2), rgba(99, 102, 241, 0.1)); border: 1px solid rgba(124, 58, 237, 0.3);">
                    <i class="bi bi-kanban-fill text-primary display-6"></i>
                </div>
                <h3 class="fw-extrabold text-white mb-1">Welcome back</h3>
                <p class="text-secondary fs-sm">Select a demo profile or enter your credentials to access your dashboard.</p>
            </div>

            <!-- Quick 1-Click Demo Profiles -->
            <div class="mb-4">
                <label class="form-label fs-xs fw-bold text-muted text-uppercase mb-2">Instant Demo Login Profiles</label>
                <div class="row g-2">
                    <div class="col-4">
                        <button type="button" class="demo-card-btn active" id="demo-admin-btn" onclick="selectDemo('admin@taskflow.com', 'Admin@123', this)">
                            <div class="demo-role text-primary">Admin</div>
                            <div class="demo-name text-truncate">Eklabay</div>
                        </button>
                    </div>
                    <div class="col-4">
                        <button type="button" class="demo-card-btn" id="demo-pm-btn" onclick="selectDemo('pm@taskflow.com', 'Manager@123', this)">
                            <div class="demo-role text-info">Manager</div>
                            <div class="demo-name text-truncate">Sophia</div>
                        </button>
                    </div>
                    <div class="col-4">
                        <button type="button" class="demo-card-btn" id="demo-member-btn" onclick="selectDemo('member@taskflow.com', 'Member@123', this)">
                            <div class="demo-role text-success">Member</div>
                            <div class="demo-name text-truncate">David</div>
                        </button>
                    </div>
                </div>
            </div>

            <form action="/login" method="POST" id="loginForm">
                <?= \Core\CSRF::field() ?>

                <div class="mb-3">
                    <label class="form-label fs-xs fw-bold text-muted text-uppercase">Email Address</label>
                    <div class="auth-input-group">
                        <i class="bi bi-envelope auth-input-icon"></i>
                        <input type="email" name="email" id="emailInput" class="form-control" placeholder="name@company.com" value="admin@taskflow.com" required>
                    </div>
                </div>

                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <label class="form-label fs-xs fw-bold text-muted text-uppercase mb-0">Password</label>
                        <a href="#" class="text-primary fs-xs text-decoration-none" onclick="Swal.fire('Password Reset', 'Please contact your system administrator or use demo credentials.', 'info')">Forgot Password?</a>
                    </div>
                    <div class="auth-input-group">
                        <i class="bi bi-lock auth-input-icon"></i>
                        <input type="password" name="password" id="passwordInput" class="form-control" placeholder="••••••••" value="Admin@123" required>
                        <i class="bi bi-eye auth-show-pass" id="togglePasswordBtn" onclick="togglePasswordVisibility()"></i>
                    </div>
                </div>

                <div class="form-check mb-4">
                    <input class="form-check-input" type="checkbox" id="rememberMe" checked>
                    <label class="form-check-label fs-xs text-secondary" for="rememberMe">
                        Remember me for 30 days
                    </label>
                </div>

                <button type="submit" class="btn btn-primary btn-lg w-100 rounded-pill shadow-sm mb-3">
                    Sign In to Workspace <i class="bi bi-arrow-right ms-2"></i>
                </button>
            </form>

            <div class="mt-4 text-center text-secondary fs-xs">
                Don't have an account? <a href="/register" class="text-primary fw-bold text-decoration-none">Create Account</a>
            </div>

            <div class="mt-3 pt-3 border-top border-secondary border-opacity-10 text-center text-muted fs-xs">
                <i class="bi bi-lock-fill text-success me-1"></i> 256-bit SSL Encrypted & Protected Connection
            </div>
        </div>
    </div>
</div>

<script>
function selectDemo(email, pass, btn) {
    document.querySelectorAll('.demo-card-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    document.getElementById('emailInput').value = email;
    document.getElementById('passwordInput').value = pass;
}

function togglePasswordVisibility() {
    const input = document.getElementById('passwordInput');
    const icon = document.getElementById('togglePasswordBtn');
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.replace('bi-eye', 'bi-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.replace('bi-eye-slash', 'bi-eye');
    }
}
</script>
