<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card border-0 shadow-lg p-4 p-md-5 rounded-4">
                <div class="text-center mb-4">
                    <i class="bi bi-person-plus-fill text-primary display-4 mb-2 d-inline-block"></i>
                    <h3 class="fw-bold">Create TaskFlow Account</h3>
                    <p class="text-secondary small">Join your team on TaskFlow SaaS management platform.</p>
                </div>

                <form action="/register" method="POST">
                    <?= \Core\CSRF::field() ?>

                    <div class="mb-3">
                        <label class="form-label fw-medium">Full Name</label>
                        <input type="text" name="name" class="form-control form-control-lg" placeholder="John Doe" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-medium">Email Address</label>
                        <input type="email" name="email" class="form-control form-control-lg" placeholder="john@example.com" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-medium">Password</label>
                        <input type="password" name="password" class="form-control form-control-lg" placeholder="At least 6 characters" required minlength="6">
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-medium">Confirm Password</label>
                        <input type="password" name="confirm_password" class="form-control form-control-lg" placeholder="Repeat password" required>
                    </div>

                    <button type="submit" class="btn btn-primary btn-lg w-100 rounded-pill shadow-sm mb-3">
                        Create Account <i class="bi bi-arrow-right ms-2"></i>
                    </button>
                </form>

                <div class="mt-3 text-center text-secondary small">
                    Already registered? <a href="/login" class="text-primary fw-semibold text-decoration-none">Sign In</a>
                </div>
            </div>
        </div>
    </div>
</div>
