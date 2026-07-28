<div class="mb-4">
    <h3 class="fw-extrabold mb-1">User Profile & Security</h3>
    <p class="text-secondary mb-0">Manage your personal information, security credentials, and theme settings.</p>
</div>

<div class="row g-4">
    <!-- Left Column: Avatar & Quick Info -->
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm p-4 text-center mb-4">
            <div class="position-relative d-inline-block mx-auto mb-3">
                <img src="/uploads/<?= htmlspecialchars($user['avatar'] ?? 'default-avatar.png') ?>" class="avatar-lg shadow" alt="avatar" onerror="this.src='https://ui-avatars.com/api/?name=<?= urlencode($user['name']) ?>'">
            </div>
            <h5 class="fw-bold mb-1"><?= htmlspecialchars($user['name']) ?></h5>
            <p class="text-muted fs-sm mb-2"><?= htmlspecialchars($user['email']) ?></p>
            <span class="badge bg-primary rounded-pill px-3 py-2 fs-xs mx-auto mb-3"><?= htmlspecialchars($user['role_name']) ?></span>

            <form action="/profile/avatar" method="POST" enctype="multipart/form-data" class="mt-3">
                <?= \Core\CSRF::field() ?>
                <label class="form-label text-muted fs-xs d-block">Upload Profile Photo</label>
                <div class="input-group input-group-sm mb-2">
                    <input type="file" name="avatar" class="form-control" accept="image/*" required>
                    <button type="submit" class="btn btn-dark">Upload</button>
                </div>
            </form>
        </div>

        <!-- Theme Preference Card -->
        <div class="card border-0 shadow-sm p-4">
            <h6 class="fw-bold mb-3"><i class="bi bi-palette-fill text-primary me-2"></i>Interface Appearance Theme</h6>
            <form action="/profile/theme" method="POST">
                <?= \Core\CSRF::field() ?>
                <div class="d-flex gap-3">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="theme" id="themeLight" value="light" <?= ($user['theme_mode'] === 'light') ? 'checked' : '' ?> onchange="this.form.submit()">
                        <label class="form-check-label fw-medium" for="themeLight"><i class="bi bi-sun-fill text-warning me-1"></i> Light Theme</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="theme" id="themeDark" value="dark" <?= ($user['theme_mode'] === 'dark') ? 'checked' : '' ?> onchange="this.form.submit()">
                        <label class="form-check-label fw-medium" for="themeDark"><i class="bi bi-moon-stars-fill text-primary me-1"></i> Dark Theme</label>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Right Column: Profile Form & Password Change -->
    <div class="col-lg-8">
        <!-- Edit Profile Form -->
        <div class="card border-0 shadow-sm p-4 mb-4">
            <h5 class="fw-bold mb-3"><i class="bi bi-person-lines-fill text-primary me-2"></i>Personal Profile Information</h5>
            <form action="/profile/update" method="POST">
                <?= \Core\CSRF::field() ?>
                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label fw-medium">Full Name</label>
                        <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($user['name']) ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-medium">Email Address</label>
                        <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($user['email']) ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-medium">Phone Number</label>
                        <input type="text" name="phone" class="form-control" value="<?= htmlspecialchars($user['phone'] ?? '') ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-medium">System Role</label>
                        <input type="text" class="form-control bg-light" value="<?= htmlspecialchars($user['role_name']) ?>" disabled>
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-medium">Biography / Professional Title</label>
                        <textarea name="bio" class="form-control" rows="3"><?= htmlspecialchars($user['bio'] ?? '') ?></textarea>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary rounded-pill px-4"><i class="bi bi-save me-1"></i> Save Changes</button>
            </form>
        </div>

        <!-- Security & Password Change Form -->
        <div class="card border-0 shadow-sm p-4">
            <h5 class="fw-bold mb-3 text-danger"><i class="bi bi-shield-lock-fill me-2"></i>Change Password</h5>
            <form action="/profile/password" method="POST">
                <?= \Core\CSRF::field() ?>
                <div class="mb-3">
                    <label class="form-label fw-medium">Current Password</label>
                    <input type="password" name="current_password" class="form-control" required placeholder="••••••••">
                </div>
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="form-label fw-medium">New Password</label>
                        <input type="password" name="new_password" class="form-control" required minlength="6" placeholder="At least 6 chars">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-medium">Confirm New Password</label>
                        <input type="password" name="confirm_password" class="form-control" required placeholder="Repeat new password">
                    </div>
                </div>
                <button type="submit" class="btn btn-outline-danger rounded-pill px-4"><i class="bi bi-key-fill me-1"></i> Update Password</button>
            </form>
        </div>
    </div>
</div>
