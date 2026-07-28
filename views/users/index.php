<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <h3 class="fw-extrabold mb-1">User Accounts & Roles</h3>
        <p class="text-secondary mb-0">System administrator management of user access, roles, and statuses.</p>
    </div>
    <button type="button" class="btn btn-primary rounded-pill px-4 shadow-sm" data-bs-toggle="modal" data-bs-target="#addUserModal">
        <i class="bi bi-person-plus-fill me-1"></i> Add New User
    </button>
</div>

<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light fs-xs text-uppercase text-secondary">
                <tr>
                    <th>User</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Phone</th>
                    <th>Status</th>
                    <th>Created Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $u): ?>
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <img src="/uploads/<?= htmlspecialchars($u['avatar'] ?? 'default-avatar.png') ?>" class="avatar-sm" alt="avatar" onerror="this.src='https://ui-avatars.com/api/?name=<?= urlencode($u['name']) ?>'">
                                <strong class="text-body"><?= htmlspecialchars($u['name']) ?></strong>
                            </div>
                        </td>
                        <td class="fs-xs"><?= htmlspecialchars($u['email']) ?></td>
                        <td>
                            <span class="badge <?= $u['role_id'] === 1 ? 'bg-danger' : ($u['role_id'] === 2 ? 'bg-warning text-dark' : 'bg-info text-dark') ?> rounded-pill px-3 py-1">
                                <?= htmlspecialchars($u['role_name']) ?>
                            </span>
                        </td>
                        <td class="fs-xs text-muted"><?= htmlspecialchars($u['phone'] ?: 'N/A') ?></td>
                        <td>
                            <span class="badge <?= $u['status'] === 'active' ? 'bg-success' : 'bg-secondary' ?> rounded-pill">
                                <?= strtoupper($u['status']) ?>
                            </span>
                        </td>
                        <td class="fs-xs text-muted"><?= date('M d, Y', strtotime($u['created_at'])) ?></td>
                        <td>
                            <div class="d-flex gap-2">
                                <!-- Update Modal Trigger -->
                                <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#editUserModal<?= $u['id'] ?>"><i class="bi bi-pencil"></i></button>
                                <?php if ($u['id'] !== \Core\Auth::id()): ?>
                                    <form action="/users/<?= $u['id'] ?>/delete" method="POST" class="d-inline">
                                        <?= \Core\CSRF::field() ?>
                                        <button type="submit" class="btn btn-sm btn-outline-danger btn-confirm-delete" data-title="<?= htmlspecialchars($u['name']) ?>"><i class="bi bi-trash"></i></button>
                                    </form>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>

                    <!-- Edit User Modal -->
                    <div class="modal fade" id="editUserModal<?= $u['id'] ?>" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form action="/users/<?= $u['id'] ?>/update" method="POST">
                                    <?= \Core\CSRF::field() ?>
                                    <div class="modal-header">
                                        <h5 class="modal-title fw-bold">Update Permissions for <?= htmlspecialchars($u['name']) ?></h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label class="form-label fw-medium">Select Role</label>
                                            <select name="role_id" class="form-select">
                                                <option value="1" <?= $u['role_id'] === 1 ? 'selected' : '' ?>>Admin</option>
                                                <option value="2" <?= $u['role_id'] === 2 ? 'selected' : '' ?>>Project Manager</option>
                                                <option value="3" <?= $u['role_id'] === 3 ? 'selected' : '' ?>>Team Member</option>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label fw-medium">Account Status</label>
                                            <select name="status" class="form-select">
                                                <option value="active" <?= $u['status'] === 'active' ? 'selected' : '' ?>>Active</option>
                                                <option value="inactive" <?= $u['status'] === 'inactive' ? 'selected' : '' ?>>Inactive</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-primary px-4">Update User</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Add User Modal -->
<div class="modal fade" id="addUserModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="/users" method="POST">
                <?= \Core\CSRF::field() ?>
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Create New User Account</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body row g-3">
                    <div class="col-12">
                        <label class="form-label fw-medium">Full Name</label>
                        <input type="text" name="name" class="form-control" placeholder="Jane Doe" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-medium">Email Address</label>
                        <input type="email" name="email" class="form-control" placeholder="jane@example.com" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-medium">Password</label>
                        <input type="password" name="password" class="form-control" placeholder="••••••••" required minlength="6">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-medium">Assign Role</label>
                        <select name="role_id" class="form-select">
                            <option value="1">Admin</option>
                            <option value="2">Project Manager</option>
                            <option value="3" selected>Team Member</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-medium">Phone</label>
                        <input type="text" name="phone" class="form-control" placeholder="+1 (555) 000-0000">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary px-4">Create User</button>
                </div>
            </form>
        </div>
    </div>
</div>
