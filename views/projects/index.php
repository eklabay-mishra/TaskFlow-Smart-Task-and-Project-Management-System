<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <h3 class="fw-extrabold mb-1">Projects Portfolio</h3>
        <p class="text-secondary mb-0">Manage all enterprise projects, monitor progress & budgets.</p>
    </div>
    <?php if (\Core\Auth::isAdmin() || \Core\Auth::isManager()): ?>
        <button type="button" class="btn btn-primary rounded-pill px-4 shadow-sm" data-bs-toggle="modal" data-bs-target="#createProjectModal">
            <i class="bi bi-plus-lg me-1"></i> New Project
        </button>
    <?php endif; ?>
</div>

<!-- Filters Bar -->
<div class="card border-0 shadow-sm p-3 mb-4">
    <form action="/projects" method="GET" class="row g-3">
        <div class="col-md-3">
            <input type="text" name="search" class="form-control" placeholder="Search title or description..." value="<?= htmlspecialchars($filters['search'] ?? '') ?>">
        </div>
        <div class="col-md-3">
            <select name="status" class="form-select" onchange="this.form.submit()">
                <option value="">-- All Statuses --</option>
                <option value="planning" <?= ($filters['status'] === 'planning') ? 'selected' : '' ?>>Planning</option>
                <option value="active" <?= ($filters['status'] === 'active') ? 'selected' : '' ?>>Active</option>
                <option value="on_hold" <?= ($filters['status'] === 'on_hold') ? 'selected' : '' ?>>On Hold</option>
                <option value="completed" <?= ($filters['status'] === 'completed') ? 'selected' : '' ?>>Completed</option>
                <option value="cancelled" <?= ($filters['status'] === 'cancelled') ? 'selected' : '' ?>>Cancelled</option>
            </select>
        </div>
        <div class="col-md-3">
            <select name="priority" class="form-select" onchange="this.form.submit()">
                <option value="">-- All Priorities --</option>
                <option value="low" <?= ($filters['priority'] === 'low') ? 'selected' : '' ?>>Low</option>
                <option value="medium" <?= ($filters['priority'] === 'medium') ? 'selected' : '' ?>>Medium</option>
                <option value="high" <?= ($filters['priority'] === 'high') ? 'selected' : '' ?>>High</option>
                <option value="urgent" <?= ($filters['priority'] === 'urgent') ? 'selected' : '' ?>>Urgent</option>
            </select>
        </div>
        <div class="col-md-3 d-flex gap-2">
            <button type="submit" class="btn btn-dark w-100"><i class="bi bi-funnel me-1"></i> Filter</button>
            <a href="/projects" class="btn btn-outline-secondary"><i class="bi bi-arrow-counterclockwise"></i></a>
        </div>
    </form>
</div>

<!-- Projects Grid -->
<div class="row g-4">
    <?php if (empty($projects)): ?>
        <div class="col-12 text-center py-5 text-muted">
            <i class="bi bi-folder2-open display-3 mb-2 d-block"></i>
            <h5>No projects match your filter criteria.</h5>
        </div>
    <?php else: ?>
        <?php foreach ($projects as $p): ?>
            <div class="col-md-6 col-xl-4">
                <div class="card h-100 border-0 shadow-sm card-hover p-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <span class="badge badge-status-<?= $p['status'] ?> rounded-pill px-3 py-2">
                            <?= strtoupper(str_replace('_', ' ', $p['status'])) ?>
                        </span>
                        <span class="badge badge-priority-<?= $p['priority'] ?> rounded-pill px-2 py-1">
                            <?= ucfirst($p['priority']) ?> Priority
                        </span>
                    </div>

                    <h5 class="fw-bold mb-2">
                        <a href="/projects/<?= $p['id'] ?>" class="text-body text-decoration-none">
                            <?= htmlspecialchars($p['title']) ?>
                        </a>
                    </h5>
                    <p class="text-secondary fs-sm mb-3 text-truncate-2" style="min-height: 42px;">
                        <?= htmlspecialchars($p['description'] ?: 'No description provided.') ?>
                    </p>

                    <!-- Progress -->
                    <div class="mb-3">
                        <div class="d-flex justify-content-between text-muted fs-xs mb-1">
                            <span>Progress</span>
                            <strong class="text-dark"><?= $p['completed_tasks'] ?>/<?= $p['total_tasks'] ?> Tasks (<?= $p['progress_pct'] ?>%)</strong>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar" style="width: <?= $p['progress_pct'] ?>%;"></div>
                        </div>
                    </div>

                    <!-- Meta info -->
                    <div class="d-flex justify-content-between align-items-center pt-3 border-top mt-auto fs-xs text-muted">
                        <div><i class="bi bi-people me-1"></i> <?= $p['member_count'] ?> Members</div>
                        <div><i class="bi bi-cash-stack me-1"></i> $<?= number_format($p['budget'], 2) ?></div>
                    </div>

                    <div class="mt-3">
                        <a href="/projects/<?= $p['id'] ?>" class="btn btn-outline-primary btn-sm w-100 rounded-pill">
                            View Project Workspace <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<!-- Create Project Modal -->
<?php if (\Core\Auth::isAdmin() || \Core\Auth::isManager()): ?>
    <div class="modal fade" id="createProjectModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form action="/projects" method="POST">
                    <?= \Core\CSRF::field() ?>
                    <div class="modal-header">
                        <h5 class="modal-title fw-bold"><i class="bi bi-folder-plus text-primary me-2"></i>Create New Project</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body row g-3">
                        <div class="col-12">
                            <label class="form-label fw-medium">Project Title</label>
                            <input type="text" name="title" class="form-control" placeholder="e.g. NextGen Web App Redesign" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-medium">Category</label>
                            <select name="category" class="form-select">
                                <option value="Software Engineering">Software Engineering</option>
                                <option value="UI/UX Design">UI/UX Design</option>
                                <option value="DevOps & Cloud">DevOps & Cloud</option>
                                <option value="Data Science">Data Science</option>
                                <option value="Marketing">Marketing</option>
                            </select>
                        </div>
                        <div class="col-md-6">
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
                                <option value="planning" selected>Planning</option>
                                <option value="active">Active</option>
                                <option value="on_hold">On Hold</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-medium">Start Date</label>
                            <input type="date" name="start_date" class="form-control" value="<?= date('Y-m-d') ?>">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-medium">Due Date</label>
                            <input type="date" name="due_date" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-medium">Budget ($)</label>
                            <input type="number" step="0.01" name="budget" class="form-control" placeholder="10000.00">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-medium">Assign Team Members</label>
                            <select name="members[]" class="form-select" multiple style="height: 100px;">
                                <?php foreach ($allUsers as $u): ?>
                                    <option value="<?= $u['id'] ?>"><?= htmlspecialchars($u['name']) ?> (<?= $u['role_name'] ?>)</option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-medium">Project Scope / Description</label>
                            <textarea name="description" class="form-control" rows="3" placeholder="Outline deliverables..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary px-4"><i class="bi bi-save me-1"></i> Save Project</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php endif; ?>
