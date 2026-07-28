<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <h3 class="fw-extrabold mb-1">Project Execution Pipeline</h3>
        <p class="text-secondary mb-0">Drag and drop tasks across workflow stages with real-time MySQL synchronization.</p>
    </div>

    <!-- Actions Bar -->
    <div class="d-flex align-items-center gap-2 flex-wrap">
        <form action="/kanban" method="GET" class="d-flex gap-2">
            <select name="project_id" class="form-select rounded-pill px-3" onchange="this.form.submit()">
                <option value="">All Projects</option>
                <?php foreach ($projects as $proj): ?>
                    <option value="<?= $proj['id'] ?>" <?= ((int)($filters['project_id'] ?? 0) === (int)$proj['id']) ? 'selected' : '' ?>><?= htmlspecialchars($proj['title']) ?></option>
                <?php endforeach; ?>
            </select>
        </form>
        <button type="button" class="btn btn-primary rounded-pill px-4 shadow-sm" data-bs-toggle="modal" data-bs-target="#createTaskModal">
            <i class="bi bi-plus-lg me-1"></i> Add Task
        </button>
    </div>
</div>

<!-- Kanban Grid -->
<div class="kanban-board">
    <?php
    $columns = [
        'todo'        => ['title' => 'Backlog', 'icon' => 'bi-circle', 'color' => 'text-secondary'],
        'in_progress' => ['title' => 'In Progress', 'icon' => 'bi-play-circle-fill', 'color' => 'text-primary'],
        'review'      => ['title' => 'Under Review', 'icon' => 'bi-eye-fill', 'color' => 'text-warning'],
        'done'        => ['title' => 'Completed', 'icon' => 'bi-check-circle-fill', 'color' => 'text-success']
    ];
    ?>

    <?php foreach ($columns as $statusKey => $colMeta): ?>
        <div class="kanban-column">
            <div class="kanban-header <?= $colMeta['color'] ?>">
                <span><i class="bi <?= $colMeta['icon'] ?> me-2"></i><?= $colMeta['title'] ?></span>
                <span class="badge bg-secondary-subtle text-secondary rounded-pill px-3 py-1 fs-xs"><?= count($board[$statusKey]) ?></span>
            </div>

            <div class="kanban-cards" data-status="<?= $statusKey ?>">
                <?php foreach ($board[$statusKey] as $task): ?>
                    <?php
                        $progressPct = ($task['estimated_hours'] > 0) ? min(100, round(($task['logged_hours'] / $task['estimated_hours']) * 100)) : 0;
                    ?>
                    <div class="kanban-card" draggable="true" data-task-id="<?= $task['id'] ?>">
                        <!-- Card Top: Avatar + Title & Subtitle + Menu -->
                        <div class="d-flex align-items-start gap-3 mb-3">
                            <img src="/uploads/<?= htmlspecialchars($task['assignee_avatar'] ?? 'default-avatar.png') ?>" class="avatar-md" alt="avatar" title="<?= htmlspecialchars($task['assignee_name'] ?? 'Unassigned') ?>" onerror="this.src='https://ui-avatars.com/api/?name=<?= urlencode($task['assignee_name'] ?? 'Task') ?>'">
                            <div class="flex-grow-1 text-truncate">
                                <h6 class="fw-bold mb-1 text-truncate">
                                    <a href="/tasks/<?= $task['id'] ?>" class="text-body text-decoration-none">
                                        #<?= $task['id'] ?> <?= htmlspecialchars($task['title']) ?>
                                    </a>
                                </h6>
                                <small class="text-muted d-block text-truncate fs-xs"><?= htmlspecialchars($task['project_title']) ?></small>
                            </div>
                        </div>

                        <!-- Tag Pills Row -->
                        <div class="d-flex flex-wrap gap-1 mb-3">
                            <span class="tag-pill"><?= htmlspecialchars($task['project_title']) ?></span>
                            <span class="badge badge-priority-<?= $task['priority'] ?> rounded-pill px-2 py-1 fs-xs"><?= ucfirst($task['priority']) ?></span>
                            <span class="tag-pill"><?= $task['estimated_hours'] ?> hrs</span>
                        </div>

                        <!-- Progress Bar Indicator -->
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center fs-xs text-muted mb-1">
                                <span>Progress</span>
                                <span class="fw-bold text-body"><?= $progressPct ?>%</span>
                            </div>
                            <div class="progress" style="height: 5px;">
                                <div class="progress-bar" style="width: <?= $progressPct ?>%;"></div>
                            </div>
                        </div>

                        <!-- Subtitle Metadata & Action Footer -->
                        <div class="d-flex justify-content-between align-items-center pt-2 border-top border-secondary border-opacity-10 fs-xs">
                            <div class="text-muted">
                                <span>Assignee: <strong class="text-body"><?= htmlspecialchars($task['assignee_name'] ?? 'Unassigned') ?></strong></span>
                            </div>
                            <div class="d-flex gap-2">
                                <a href="/tasks/<?= $task['id'] ?>" class="text-secondary text-decoration-none" title="View & Edit"><i class="bi bi-pencil-square fs-6"></i></a>
                                <span class="text-muted" title="Drag to move"><i class="bi bi-arrows-move fs-6"></i></span>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<!-- Quick Add Task Modal -->
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
                        <label class="form-label fw-medium">Priority</label>
                        <select name="priority" class="form-select">
                            <option value="low">Low</option>
                            <option value="medium" selected>Medium</option>
                            <option value="high">High</option>
                            <option value="urgent">Urgent</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-medium">Initial Stage</label>
                        <select name="status" class="form-select">
                            <option value="todo" selected>Backlog</option>
                            <option value="in_progress">In Progress</option>
                            <option value="review">Under Review</option>
                            <option value="done">Completed</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-medium">Estimated Hours</label>
                        <input type="number" step="0.5" name="estimated_hours" class="form-control" value="8.0">
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-medium">Description</label>
                        <textarea name="description" class="form-control" rows="3" placeholder="Provide task requirements..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4"><i class="bi bi-save me-1"></i> Save Task</button>
                </div>
            </form>
        </div>
    </div>
</div>
