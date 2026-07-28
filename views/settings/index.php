<div class="mb-4">
    <h3 class="fw-extrabold mb-1">System Settings Configuration</h3>
    <p class="text-secondary mb-0">Manage global application variables, default registration policies, and enterprise details.</p>
</div>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm p-4 p-md-5">
            <h5 class="fw-bold mb-4 border-bottom pb-3 text-primary"><i class="bi bi-sliders me-2"></i>Global Platform Settings</h5>
            
            <form action="/settings" method="POST">
                <?= \Core\CSRF::field() ?>

                <div class="mb-3">
                    <label class="form-label fw-medium">Application Name</label>
                    <input type="text" name="app_name" class="form-control" value="<?= htmlspecialchars($settings['app_name'] ?? 'TaskFlow Enterprise') ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-medium">Support Contact Email</label>
                    <input type="email" name="app_email" class="form-control" value="<?= htmlspecialchars($settings['app_email'] ?? 'support@taskflow.com') ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-medium">Company / Organization Name</label>
                    <input type="text" name="company_name" class="form-control" value="<?= htmlspecialchars($settings['company_name'] ?? 'TaskFlow Inc.') ?>">
                </div>

                <div class="mb-4">
                    <label class="form-label fw-medium">User Public Registration Policy</label>
                    <select name="allow_registration" class="form-select">
                        <option value="1" <?= (($settings['allow_registration'] ?? '1') === '1') ? 'selected' : '' ?>>Enabled (Anyone can sign up as Team Member)</option>
                        <option value="0" <?= (($settings['allow_registration'] ?? '1') === '0') ? 'selected' : '' ?>>Disabled (Admin invitation only)</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary rounded-pill px-5 shadow-sm"><i class="bi bi-save me-1"></i> Save Platform Settings</button>
            </form>
        </div>
    </div>
</div>
