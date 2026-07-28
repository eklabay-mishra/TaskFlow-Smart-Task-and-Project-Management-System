<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card border-0 shadow-lg p-4 p-md-5 rounded-4">
                <div class="text-center mb-4">
                    <i class="bi bi-kanban-fill text-primary display-4 mb-2 d-inline-block"></i>
                    <h3 class="fw-bold">Sign In to TaskFlow</h3>
                    <p class="text-secondary small">Enter your account credentials to access your dashboard.</p>
                </div>

                <form action="/login" method="POST">
                    <?= \Core\CSRF::field() ?>

                    <div class="mb-3">
                        <label class="form-label fw-medium">Email Address</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                            <input type="email" name="email" class="form-control form-control-lg" placeholder="admin@taskflow.com" required>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-medium">Password</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-lock"></i></span>
                            <input type="password" name="password" class="form-control form-control-lg" placeholder="••••••••" required>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary btn-lg w-100 rounded-pill shadow-sm mb-3">
                        Sign In <i class="bi bi-box-arrow-in-right ms-2"></i>
                    </button>
                </form>

                <div class="border-top pt-3 text-center">
                    <p class="text-secondary small mb-2">Demo Accounts:</p>
                    <div class="d-flex justify-content-center gap-2 flex-wrap">
                        <button type="button" class="btn btn-outline-secondary btn-xs rounded-pill" onclick="document.querySelector('input[name=email]').value='admin@taskflow.com';document.querySelector('input[name=password]').value='Admin@123';">Admin</button>
                        <button type="button" class="btn btn-outline-secondary btn-xs rounded-pill" onclick="document.querySelector('input[name=email]').value='pm@taskflow.com';document.querySelector('input[name=password]').value='Manager@123';">Manager</button>
                        <button type="button" class="btn btn-outline-secondary btn-xs rounded-pill" onclick="document.querySelector('input[name=email]').value='member@taskflow.com';document.querySelector('input[name=password]').value='Member@123';">Member</button>
                    </div>
                </div>

                <div class="mt-4 text-center text-secondary small">
                    Don't have an account? <a href="/register" class="text-primary fw-semibold text-decoration-none">Create Account</a>
                </div>
            </div>
        </div>
    </div>
</div>
