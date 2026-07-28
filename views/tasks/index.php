<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <h3 class="fw-extrabold mb-1">Task Management</h3>
        <p class="text-secondary mb-0">Track, assign, and execute all enterprise project tasks.</p>
    </div>
    <button type="button" class="btn btn-primary rounded-pill px-4 shadow-sm" data-bs-toggle="modal" data-bs-target="#createTaskModal">
        <i class="bi bi-plus-lg me-1"></i> Create Task
    </button>
</div>

<!-- Filters Bar -->
<div class="card border-0 shadow-sm p-3 mb-4">
    <form action="/tasks" method="GET" class="row g-3">
        <div class="col-md-3">
            <input type="text" name="search" class="form-control" placeholder="Search task title..." value="<?= htmlspecialchars($filters['search'] ?? '') ?>">
        </div>
        <div class="col-md-3">
            <select name="project_id" class="form-select" onchange="this.form.submit()">
                <option value="">-- All Projects --</option>
                <?php foreach ($projects as $proj): ?>
                    <option value="<?= $proj['id'] ?>" <?= ((int)$filters['project_id'] === (int)$proj['id']) ? 'selected' : '' ?>><?= htmlspecialchars($proj['title']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-2">
            <select name="status" class="form-select" onchange="this.form.submit()">
                <option value="">-- Status --</option>
                <option value="todo" <?= ($filters['status'] === 'todo') ? 'selected' : '' ?>>To Do</option>
                <option value="in_progress" <?= ($filters['status'] === 'in_progress') ? 'selected' : '' ?>>In Progress</option>
                <option value="review" <?= ($filters['status'] === 'review') ? 'selected' : '' ?>>Review</option>
                <option value="done" <?= ($filters['status'] === 'done') ? 'selected' : '' ?>>Done</option>
            </select>
        </div>
        <div class="col-md-2">
            <select name="priority" class="form-select" onchange="this.form.submit()">
                <option value="">-- Priority --</option>
                <option value="low" <?= ($filters['priority'] === 'low') ? 'selected' : '' ?>>Low</option>
                <option value="medium" <?= ($filters['priority'] === 'medium') ? 'selected' : '' ?>>Medium</option>
                <option value="high" <?= ($filters['priority'] === 'high') ? 'selected' : '' ?>>High</option>
                <option value="urgent" <?= ($filters['priority'] === 'urgent') ? 'selected' : '' ?>>Urgent</option>
            </select>
        </div>
        <div class="col-md-2 d-flex gap-2">
            <button type="submit" class="btn btn-dark w-100"><i class="bi bi-funnel me-1"></i> Filter</button>
            <a href="/tasks" class="btn btn-outline-secondary"><i class="bi bi-arrow-counterclockwise"></i></a>
        </div>
    </form>
</div>

<!-- Tasks Table -->
<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light fs-xs text-uppercase text-secondary">
                <tr>
                    <th>Task ID & Title</th>
                    <th>Project</th>
                    <th>Status</th>
                    <th>Priority</th>
                    <th>Assigned To</th>
                    <th>Logged Hours</th>
                    <th>Due Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($tasks)): ?>
                    <tr><td colspan="8" class="text-center py-5 text-muted">No tasks found matching query.</td></tr>
                <?php else: ?>
                    <?php foreach ($tasks as $t): ?>
                        <tr>
                            <td>
                                <a href="/tasks/<?= $t['id'] ?>" class="fw-bold text-body text-decoration-none d-block">
                                    #<?= $t['id'] ?> <?= htmlspecialchars($t['title']) ?>
                                </a>
                                <span class="text-muted fs-xs"><i class="bi bi-chat me-1"></i><?= $t['comment_count'] ?> comments | <i class="bi bi-paperclip me-1"></i><?= $t['attachment_count'] ?> files</span>
                            </td>
                            <td class="fs-xs fw-medium text-secondary">
                                <?= htmlspecialchars($t['project_title']) ?>
                            </td>
                            <td>
                                <form action="/tasks/<?= $t['id'] ?>/status" method="POST" class="d-inline">
                                    <?= \Core\CSRF::field() ?>
                                    <select name="status" class="form-select form-select-sm border-0 bg-transparent fw-bold badge badge-status-<?= $t['status'] ?>" onchange="this.form.submit()">
                                        <option value="todo" <?= ($t['status'] === 'todo') ? 'selected' : '' ?>>TO DO</option>
                                        <option value="in_progress" <?= ($t['status'] === 'in_progress') ? 'selected' : '' ?>>IN PROGRESS</option>
                                        <option value="review" <?= ($t['status'] === 'review') ? 'selected' : '' ?>>UNDER REVIEW</option>
                                        <option value="done" <?= ($t['status'] === 'done') ? 'selected' : '' ?>>DONE</option>
                                    </select>
                                </form>
                            </td>
                            <td>
                                <span class="badge badge-priority-<?= $t['priority'] ?> rounded-pill px-2 py-1 fs-xs">
                                    <?= ucfirst($t['priority']) ?>
                                </span>
                            </td>
                            <td>
                                <?php if ($t['assigned_to']): ?>
                                    <div class="d-flex align-items-center gap-2">
                                        <img src="/uploads/<?= htmlspecialchars($t['assignee_avatar'] ?? 'default-avatar.png') ?>" class="avatar-sm" alt="avatar" onerror="this.src='https://ui-avatars.com/api/?name=<?= urlencode($t['assignee_name']) ?>'">
                                        <span class="fs-xs fw-medium"><?= htmlspecialchars($t['assignee_name']) ?></span>
                                    </div>
                                <?php else: ?>
                                    <span class="text-muted fs-xs">Unassigned</span>
                                <?php endif; ?>
                            </td>
                            <td class="fs-xs fw-semibold">
                                <?= $t['logged_hours'] ?> / <?= $t['estimated_hours'] ?> hrs
                            </td>
                            <td class="fs-xs text-muted">
                                <?= $t['due_date'] ? date('M d, Y', strtotime($t['due_date'])) : 'N/A' ?>
                            </td>
                            <td>
                                <a href="/tasks/<?= $t['id'] ?>" class="btn btn-sm btn-light border"><i class="bi bi-eye"></i> View</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Create Task Modal -->
<div class="modal fade" id="createTaskModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="/tasks" method="POST">
                <?= \Core\CSRF::field() ?>
                <div class="modal-header">
                    <h5 class="modal-title fw-bold"><i class="bi bi-check2-square text-primary me-2"></i>Create New Task</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body row g-3">
                    <div class="col-md-8">
                        <label class="form-label fw-medium">Task Title</label>
                        <input type="text" name="title" class="form-control" placeholder="e.g. Implement OAuth2 login flow" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-medium">Project</label>
                        <select name="project_id" class="form-select" required>
                            <?php foreach ($projects as $proj): ?>
                                <option value="<?= $proj['id'] ?>"><?= htmlspecialchars($proj['title']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-medium">Assigned To</label>
                        <select name="assigned_to" class="form-select">
                            <option value="">-- Unassigned --</option>
                            <?php foreach ($allUsers as $u): ?>
                                <option value="<?= $u['id'] ?>"><?= htmlspecialchars($u['name']) ?> (<?= $u['role_name'] ?>)</option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-medium">Priority</label>
                        <select name="priority" class="form-select">
                            <option value="low">Low</option>
                            <option value="medium" selected>Medium</option>
                            <option value="high">High</option>
                            <option value="urgent">Urgent</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-medium">Initial Status</label>
                        <select name="status" class="form-select">
                            <option value="todo" selected>To Do</option>
                            <option value="in_progress">In Progress</option>
                            <option value="review">Under Review</option>
                            <option value="done">Done</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-medium">Estimated Hours</label>
                        <input type="number" step="0.5" name="estimated_hours" class="form-control" value="8.0">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-medium">Logged Hours</label>
                        <input type="number" step="0.5" name="logged_hours" class="form-control" value="0.0">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-medium">Due Date</label>
                        <input type="date" name="due_date" class="form-control" value="<?= date('Y-m-d', strtotime('+7 days')) ?>">
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-medium">Task Description & Requirements</label>
                        <textarea name="description" class="form-control" rows="3" placeholder="Provide detailed task requirements..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary px-4"><i class="bi bi-save me-1"></i> Save Task</button>
                </div>
            </form>
        </div>
    </div>
</div>
