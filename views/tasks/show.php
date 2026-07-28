<div class="mb-4">
    <a href="/tasks" class="text-secondary text-decoration-none small"><i class="bi bi-arrow-left me-1"></i> Back to Tasks List</a>
</div>

<div class="row g-4">
    <!-- Left Column: Task Overview & Discussion -->
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm p-4 mb-4">
            <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-3">
                <div>
                    <span class="badge bg-secondary-subtle text-secondary rounded-pill px-3 py-1 fs-xs mb-2">
                        Project: <?= htmlspecialchars($task['project_title']) ?>
                    </span>
                    <h3 class="fw-extrabold text-body mb-2">#<?= $task['id'] ?> - <?= htmlspecialchars($task['title']) ?></h3>
                </div>
                <div class="d-flex gap-2">
                    <span class="badge badge-status-<?= $task['status'] ?> rounded-pill px-3 py-2 fs-xs">
                        <?= strtoupper(str_replace('_', ' ', $task['status'])) ?>
                    </span>
                    <span class="badge badge-priority-<?= $task['priority'] ?> rounded-pill px-3 py-2 fs-xs">
                        <?= ucfirst($task['priority']) ?> Priority
                    </span>
                </div>
            </div>

            <h6 class="fw-bold text-secondary text-uppercase fs-xs mb-2">Description & Requirements</h6>
            <div class="p-3 bg-body-tertiary rounded text-secondary mb-4" style="white-space: pre-line;">
                <?= htmlspecialchars($task['description'] ?: 'No detailed description specified for this task.') ?>
            </div>

            <!-- Task Actions Bar -->
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 pt-3 border-top">
                <form action="/tasks/<?= $task['id'] ?>/status" method="POST" class="d-flex align-items-center gap-2">
                    <?= \Core\CSRF::field() ?>
                    <label class="form-label mb-0 fw-medium fs-xs text-muted">Change Status:</label>
                    <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                        <option value="todo" <?= ($task['status'] === 'todo') ? 'selected' : '' ?>>To Do</option>
                        <option value="in_progress" <?= ($task['status'] === 'in_progress') ? 'selected' : '' ?>>In Progress</option>
                        <option value="review" <?= ($task['status'] === 'review') ? 'selected' : '' ?>>Under Review</option>
                        <option value="done" <?= ($task['status'] === 'done') ? 'selected' : '' ?>>Done</option>
                    </select>
                </form>

                <?php if (\Core\Auth::isAdmin() || \Core\Auth::isManager()): ?>
                    <form action="/tasks/<?= $task['id'] ?>/delete" method="POST" class="d-inline">
                        <?= \Core\CSRF::field() ?>
                        <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill px-3 btn-confirm-delete" data-title="<?= htmlspecialchars($task['title']) ?>">
                            <i class="bi bi-trash me-1"></i> Delete Task
                        </button>
                    </form>
                <?php endif; ?>
            </div>
        </div>

        <!-- Comments Discussion Thread -->
        <div class="card border-0 shadow-sm p-4 mb-4">
            <h5 class="fw-bold mb-3"><i class="bi bi-chat-left-dots-fill text-primary me-2"></i>Task Discussion Thread</h5>
            
            <div id="comments-thread" class="mb-4">
                <?php if (empty($comments)): ?>
                    <div class="text-center py-4 text-muted fs-sm">No comments posted yet. Be the first to start the discussion!</div>
                <?php else: ?>
                    <?php foreach ($comments as $c): ?>
                        <div class="d-flex gap-3 mb-3 p-3 rounded bg-body-tertiary">
                            <img src="/uploads/<?= htmlspecialchars($c['user_avatar'] ?? 'default-avatar.png') ?>" class="avatar-sm" alt="avatar" onerror="this.src='https://ui-avatars.com/api/?name=<?= urlencode($c['user_name']) ?>'">
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <h6 class="mb-0 fw-bold"><?= htmlspecialchars($c['user_name']) ?> <span class="badge bg-secondary ms-1 fs-xs"><?= htmlspecialchars($c['role_name']) ?></span></h6>
                                    <small class="text-muted fs-xs"><?= date('M d, Y H:i', strtotime($c['created_at'])) ?></small>
                                </div>
                                <p class="mb-0 text-secondary"><?= htmlspecialchars($c['comment']) ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <!-- Post New Comment Form -->
            <form action="/tasks/<?= $task['id'] ?>/comments" method="POST" id="ajax-comment-form" data-task-id="<?= $task['id'] ?>">
                <?= \Core\CSRF::field() ?>
                <div class="mb-3">
                    <textarea name="comment" id="comment-text" class="form-control" rows="3" placeholder="Write a comment or status update..." required></textarea>
                </div>
                <button type="submit" class="btn btn-primary rounded-pill px-4 float-end">
                    <i class="bi bi-send-fill me-1"></i> Post Comment
                </button>
            </form>
        </div>
    </div>

    <!-- Right Column: Meta Info, Hours Logger & File Attachments -->
    <div class="col-lg-4">
        <!-- Assignee & Dates Card -->
        <div class="card border-0 shadow-sm p-4 mb-4">
            <h6 class="fw-bold text-uppercase fs-xs text-secondary mb-3">Task Meta Information</h6>
            
            <div class="mb-3">
                <small class="text-muted d-block mb-1">Assigned Developer</small>
                <?php if ($task['assigned_to']): ?>
                    <div class="d-flex align-items-center gap-2">
                        <img src="/uploads/<?= htmlspecialchars($task['assignee_avatar'] ?? 'default-avatar.png') ?>" class="avatar-md" alt="avatar" onerror="this.src='https://ui-avatars.com/api/?name=<?= urlencode($task['assignee_name']) ?>'">
                        <div>
                            <strong class="d-block text-body"><?= htmlspecialchars($task['assignee_name']) ?></strong>
                            <small class="text-muted"><?= htmlspecialchars($task['assignee_email']) ?></small>
                        </div>
                    </div>
                <?php else: ?>
                    <span class="badge bg-secondary-subtle text-secondary px-3 py-2">Unassigned</span>
                <?php endif; ?>
            </div>

            <div class="mb-3 border-top pt-3">
                <small class="text-muted d-block mb-1">Created By</small>
                <strong class="text-body"><?= htmlspecialchars($task['creator_name']) ?></strong>
            </div>

            <div class="mb-3 border-top pt-3">
                <small class="text-muted d-block mb-1">Due Date</small>
                <strong class="text-body"><?= $task['due_date'] ? date('F d, Y', strtotime($task['due_date'])) : 'No due date set' ?></strong>
            </div>
        </div>

        <!-- Log Hours Card -->
        <div class="card border-0 shadow-sm p-4 mb-4">
            <h6 class="fw-bold text-uppercase fs-xs text-secondary mb-3">Time & Hours Tracking</h6>
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <span class="text-muted fs-xs d-block">Logged Hours</span>
                    <h4 class="fw-bold mb-0 text-primary"><?= $task['logged_hours'] ?> hrs</h4>
                </div>
                <div class="text-end">
                    <span class="text-muted fs-xs d-block">Estimated</span>
                    <h4 class="fw-bold mb-0 text-secondary"><?= $task['estimated_hours'] ?> hrs</h4>
                </div>
            </div>

            <form action="/tasks/<?= $task['id'] ?>/log-hours" method="POST" class="d-flex gap-2">
                <?= \Core\CSRF::field() ?>
                <input type="number" step="0.5" min="0.5" name="hours" class="form-control" placeholder="Hours to log" required>
                <button type="submit" class="btn btn-outline-primary fw-semibold text-nowrap"><i class="bi bi-plus-lg"></i> Log Time</button>
            </form>
        </div>

        <!-- Upload Attachments Card -->
        <div class="card border-0 shadow-sm p-4 mb-4">
            <h6 class="fw-bold text-uppercase fs-xs text-secondary mb-3">File Attachments (<?= count($attachments) ?>)</h6>
            
            <form action="/tasks/<?= $task['id'] ?>/attachments" method="POST" enctype="multipart/form-data" class="mb-3">
                <?= \Core\CSRF::field() ?>
                <div class="mb-2">
                    <input type="file" name="file" class="form-control form-control-sm" required>
                </div>
                <button type="submit" class="btn btn-sm btn-dark w-100 rounded-pill"><i class="bi bi-upload me-1"></i> Upload Attachment</button>
            </form>

            <div class="list-group list-group-flush fs-xs">
                <?php foreach ($attachments as $a): ?>
                    <div class="list-group-item d-flex justify-content-between align-items-center px-0 py-2 border-0">
                        <div class="text-truncate me-2">
                            <i class="bi bi-file-earmark-text text-primary me-1"></i>
                            <strong class="text-body"><?= htmlspecialchars($a['file_name']) ?></strong>
                            <div class="text-muted fs-xs"><?= round($a['file_size']/1024, 1) ?> KB | <?= htmlspecialchars($a['uploader_name']) ?></div>
                        </div>
                        <a href="/uploads/<?= htmlspecialchars($a['file_path']) ?>" download class="btn btn-xs btn-outline-secondary"><i class="bi bi-download"></i></a>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>
