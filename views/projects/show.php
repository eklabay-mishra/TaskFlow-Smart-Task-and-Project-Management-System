<div class="mb-4">
    <a href="/projects" class="text-secondary text-decoration-none small"><i class="bi bi-arrow-left me-1"></i> Back to Projects</a>
</div>

<div class="card border-0 shadow-sm p-4 mb-4">
    <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-2">
                <span class="badge badge-status-<?= $project['status'] ?> rounded-pill px-3 py-1 fs-xs">
                    <?= strtoupper(str_replace('_', ' ', $project['status'])) ?>
                </span>
                <span class="badge badge-priority-<?= $project['priority'] ?> rounded-pill px-2 py-1 fs-xs">
                    <?= ucfirst($project['priority']) ?> Priority
                </span>
                <span class="badge bg-secondary-subtle text-secondary rounded-pill px-2 py-1 fs-xs"><?= htmlspecialchars($project['category']) ?></span>
            </div>
            <h2 class="fw-extrabold text-body mb-2"><?= htmlspecialchars($project['title']) ?></h2>
            <p class="text-secondary mb-3"><?= htmlspecialchars($project['description'] ?: 'No description provided.') ?></p>
            <div class="d-flex align-items-center gap-4 text-muted fs-sm flex-wrap">
                <div><i class="bi bi-person me-1"></i> Creator: <strong><?= htmlspecialchars($project['creator_name']) ?></strong></div>
                <div><i class="bi bi-calendar3 me-1"></i> Start: <strong><?= $project['start_date'] ?: 'N/A' ?></strong></div>
                <div><i class="bi bi-calendar-check me-1"></i> Due: <strong><?= $project['due_date'] ?: 'N/A' ?></strong></div>
                <div><i class="bi bi-cash-stack me-1"></i> Budget: <strong>$<?= number_format($project['budget'], 2) ?></strong></div>
            </div>
        </div>

        <?php if (\Core\Auth::isAdmin() || \Core\Auth::isManager()): ?>
            <div class="d-flex gap-2">
                <button class="btn btn-outline-secondary rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#editProjectModal">
                    <i class="bi bi-pencil me-1"></i> Edit Project
                </button>
                <form action="/projects/<?= $project['id'] ?>/delete" method="POST" class="d-inline">
                    <?= \Core\CSRF::field() ?>
                    <button type="submit" class="btn btn-outline-danger rounded-pill px-3 btn-confirm-delete" data-title="<?= htmlspecialchars($project['title']) ?>">
                        <i class="bi bi-trash me-1"></i> Delete
                    </button>
                </form>
            </div>
        <?php endif; ?>
    </div>

    <div class="mt-4 pt-3 border-top">
        <div class="d-flex justify-content-between fs-sm fw-bold mb-1">
            <span>Overall Completion Progress</span>
            <span><?= $project['completed_tasks'] ?> / <?= $project['total_tasks'] ?> Tasks Done (<?= $project['progress_pct'] ?>%)</span>
        </div>
        <div class="progress" style="height: 10px;">
            <div class="progress-bar" style="width: <?= $project['progress_pct'] ?>%;"></div>
        </div>
    </div>
</div>

<!-- Navigation Tabs -->
<ul class="nav nav-pills mb-4 gap-2" id="projectTab" role="tablist">
    <li class="nav-item">
        <button class="nav-link active rounded-pill px-4" data-bs-toggle="tab" data-bs-target="#tab-tasks">
            <i class="bi bi-check2-square me-1"></i> Tasks (<?= count($tasks) ?>)
        </button>
    </li>
    <li class="nav-item">
        <button class="nav-link rounded-pill px-4" data-bs-toggle="tab" data-bs-target="#tab-milestones">
            <i class="bi bi-flag-fill me-1"></i> Milestones (<?= count($milestones) ?>)
        </button>
    </li>
    <li class="nav-item">
        <button class="nav-link rounded-pill px-4" data-bs-toggle="tab" data-bs-target="#tab-members">
            <i class="bi bi-people-fill me-1"></i> Team Members (<?= count($members) ?>)
        </button>
    </li>
    <li class="nav-item">
        <button class="nav-link rounded-pill px-4" data-bs-toggle="tab" data-bs-target="#tab-files">
            <i class="bi bi-paperclip me-1"></i> Attachments (<?= count($attachments) ?>)
        </button>
    </li>
</ul>

<div class="tab-content" id="projectTabContent">
    <!-- Tasks Tab -->
    <div class="tab-pane fade show active" id="tab-tasks">
        <div class="card border-0 shadow-sm p-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold mb-0">Project Tasks</h5>
                <a href="/tasks?project_id=<?= $project['id'] ?>" class="btn btn-sm btn-primary rounded-pill">
                    <i class="bi bi-plus-lg me-1"></i> Add Task
                </a>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light fs-xs text-uppercase">
                        <tr>
                            <th>Task Title</th>
                            <th>Status</th>
                            <th>Priority</th>
                            <th>Assigned To</th>
                            <th>Logged / Est</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($tasks)): ?>
                            <tr><td colspan="6" class="text-center py-4 text-muted">No tasks created yet for this project.</td></tr>
                        <?php else: ?>
                            <?php foreach ($tasks as $t): ?>
                                <tr>
                                    <td>
                                        <a href="/tasks/<?= $t['id'] ?>" class="fw-bold text-body text-decoration-none">
                                            <?= htmlspecialchars($t['title']) ?>
                                        </a>
                                    </td>
                                    <td>
                                        <span class="badge badge-status-<?= $t['status'] ?> rounded-pill">
                                            <?= strtoupper(str_replace('_', ' ', $t['status'])) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge badge-priority-<?= $t['priority'] ?> rounded-pill">
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
                                    <td class="fs-xs fw-medium"><?= $t['logged_hours'] ?> / <?= $t['estimated_hours'] ?> hrs</td>
                                    <td>
                                        <a href="/tasks/<?= $t['id'] ?>" class="btn btn-sm btn-light border"><i class="bi bi-eye"></i></a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Milestones Tab -->
    <div class="tab-pane fade" id="tab-milestones">
        <div class="card border-0 shadow-sm p-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold mb-0">Project Milestones</h5>
                <?php if (\Core\Auth::isAdmin() || \Core\Auth::isManager()): ?>
                    <button class="btn btn-sm btn-primary rounded-pill" data-bs-toggle="modal" data-bs-target="#addMilestoneModal">
                        <i class="bi bi-plus-lg me-1"></i> New Milestone
                    </button>
                <?php endif; ?>
            </div>
            <div class="row g-3">
                <?php if (empty($milestones)): ?>
                    <div class="col-12 text-center py-4 text-muted">No milestones defined yet.</div>
                <?php else: ?>
                    <?php foreach ($milestones as $m): ?>
                        <div class="col-md-6">
                            <div class="p-3 border rounded bg-body-tertiary">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h6 class="fw-bold mb-0"><?= htmlspecialchars($m['title']) ?></h6>
                                    <span class="badge bg-primary rounded-pill text-uppercase fs-xs"><?= $m['status'] ?></span>
                                </div>
                                <p class="text-secondary fs-xs mb-2"><?= htmlspecialchars($m['description'] ?: 'No details') ?></p>
                                <div class="d-flex justify-content-between align-items-center fs-xs text-muted">
                                    <span>Due Date: <strong><?= $m['due_date'] ?: 'N/A' ?></strong></span>
                                    <span>Tasks Done: <strong><?= $m['completed_tasks'] ?>/<?= $m['total_tasks'] ?></strong></span>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Members Tab -->
    <div class="tab-pane fade" id="tab-members">
        <div class="card border-0 shadow-sm p-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold mb-0">Project Team Roster</h5>
                <?php if (\Core\Auth::isAdmin() || \Core\Auth::isManager()): ?>
                    <button class="btn btn-sm btn-primary rounded-pill" data-bs-toggle="modal" data-bs-target="#addMemberModal">
                        <i class="bi bi-person-plus me-1"></i> Add Member
                    </button>
                <?php endif; ?>
            </div>
            <div class="row g-3">
                <?php foreach ($members as $m): ?>
                    <div class="col-md-4">
                        <div class="p-3 border rounded d-flex align-items-center justify-content-between bg-body-tertiary">
                            <div class="d-flex align-items-center gap-3">
                                <img src="/uploads/<?= htmlspecialchars($m['avatar'] ?? 'default-avatar.png') ?>" class="avatar-md" alt="avatar" onerror="this.src='https://ui-avatars.com/api/?name=<?= urlencode($m['name']) ?>'">
                                <div>
                                    <h6 class="fw-bold mb-0"><?= htmlspecialchars($m['name']) ?></h6>
                                    <small class="text-muted d-block"><?= htmlspecialchars($m['role_in_project']) ?></small>
                                    <span class="badge bg-secondary fs-xs"><?= htmlspecialchars($m['role_name']) ?></span>
                                </div>
                            </div>
                            <?php if (\Core\Auth::isAdmin() || \Core\Auth::isManager()): ?>
                                <form action="/projects/<?= $project['id'] ?>/members/remove" method="POST">
                                    <?= \Core\CSRF::field() ?>
                                    <input type="hidden" name="user_id" value="<?= $m['user_id'] ?>">
                                    <button type="submit" class="btn btn-sm btn-outline-danger border-0"><i class="bi bi-x-circle fs-5"></i></button>
                                </form>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- Attachments Tab -->
    <div class="tab-pane fade" id="tab-files">
        <div class="card border-0 shadow-sm p-4">
            <h5 class="fw-bold mb-3">Project Attachments</h5>
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light fs-xs text-uppercase">
                        <tr>
                            <th>File Name</th>
                            <th>Task Associated</th>
                            <th>Uploaded By</th>
                            <th>Size</th>
                            <th>Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($attachments)): ?>
                            <tr><td colspan="6" class="text-center py-4 text-muted">No files attached to this project tasks yet.</td></tr>
                        <?php else: ?>
                            <?php foreach ($attachments as $a): ?>
                                <tr>
                                    <td class="fw-bold"><i class="bi bi-file-earmark me-2 text-primary"></i><?= htmlspecialchars($a['file_name']) ?></td>
                                    <td class="fs-xs"><?= htmlspecialchars($a['task_title'] ?? 'General Project File') ?></td>
                                    <td class="fs-xs"><?= htmlspecialchars($a['uploader_name']) ?></td>
                                    <td class="fs-xs"><?= round($a['file_size'] / 1024, 1) ?> KB</td>
                                    <td class="fs-xs text-muted"><?= date('Y-m-d H:i', strtotime($a['uploaded_at'])) ?></td>
                                    <td>
                                        <a href="/uploads/<?= htmlspecialchars($a['file_path']) ?>" download class="btn btn-sm btn-outline-primary"><i class="bi bi-download"></i> Download</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modals -->
<?php if (\Core\Auth::isAdmin() || \Core\Auth::isManager()): ?>
    <!-- Add Member Modal -->
    <div class="modal fade" id="addMemberModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="/projects/<?= $project['id'] ?>/members" method="POST">
                    <?= \Core\CSRF::field() ?>
                    <div class="modal-header">
                        <h5 class="modal-title fw-bold">Add Team Member</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-medium">Select User</label>
                            <select name="user_id" class="form-select" required>
                                <?php foreach ($allUsers as $u): ?>
                                    <option value="<?= $u['id'] ?>"><?= htmlspecialchars($u['name']) ?> (<?= $u['role_name'] ?> - <?= $u['email'] ?>)</option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-medium">Role in Project</label>
                            <input type="text" name="role_in_project" class="form-control" placeholder="e.g. Lead Developer, QA Tester" value="Member" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary px-4">Add Member</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Add Milestone Modal -->
    <div class="modal fade" id="addMilestoneModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="/projects/<?= $project['id'] ?>/milestones" method="POST">
                    <?= \Core\CSRF::field() ?>
                    <div class="modal-header">
                        <h5 class="modal-title fw-bold">Add New Milestone</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-medium">Milestone Title</label>
                            <input type="text" name="title" class="form-control" required placeholder="e.g. Phase 1 Release">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-medium">Due Date</label>
                            <input type="date" name="due_date" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-medium">Description</label>
                            <textarea name="description" class="form-control" rows="2"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary px-4">Create Milestone</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php endif; ?>
