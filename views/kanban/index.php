<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <h3 class="fw-extrabold mb-1">Interactive Kanban Board</h3>
        <p class="text-secondary mb-0">Drag and drop tasks between workflow stages with instant database synchronization.</p>
    </div>

    <!-- Filter Form -->
    <form action="/kanban" method="GET" class="d-flex gap-2">
        <select name="project_id" class="form-select" onchange="this.form.submit()">
            <option value="">-- All Projects --</option>
            <?php foreach ($projects as $proj): ?>
                <option value="<?= $proj['id'] ?>" <?= ((int)($filters['project_id'] ?? 0) === (int)$proj['id']) ? 'selected' : '' ?>><?= htmlspecialchars($proj['title']) ?></option>
            <?php endforeach; ?>
        </select>
        <a href="/tasks" class="btn btn-primary text-nowrap rounded-pill"><i class="bi bi-plus-lg me-1"></i> New Task</a>
    </form>
</div>

<!-- Kanban Grid -->
<div class="kanban-board">
    <!-- Column 1: TO DO -->
    <div class="kanban-column">
        <div class="kanban-header text-secondary">
            <span><i class="bi bi-circle me-1"></i> To Do</span>
            <span class="badge bg-secondary rounded-pill"><?= count($board['todo']) ?></span>
        </div>
        <div class="kanban-cards" data-status="todo">
            <?php foreach ($board['todo'] as $task): ?>
                <div class="kanban-card" draggable="true" data-task-id="<?= $task['id'] ?>">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="badge bg-secondary-subtle text-secondary fs-xs"><?= htmlspecialchars($task['project_title']) ?></span>
                        <span class="badge badge-priority-<?= $task['priority'] ?> fs-xs"><?= ucfirst($task['priority']) ?></span>
                    </div>
                    <h6 class="fw-bold mb-2">
                        <a href="/tasks/<?= $task['id'] ?>" class="text-body text-decoration-none">#<?= $task['id'] ?> <?= htmlspecialchars($task['title']) ?></a>
                    </h6>
                    <div class="d-flex justify-content-between align-items-center pt-2 mt-2 border-top fs-xs text-muted">
                        <div><i class="bi bi-chat me-1"></i><?= $task['comment_count'] ?></div>
                        <?php if ($task['assigned_to']): ?>
                            <img src="/uploads/<?= htmlspecialchars($task['assignee_avatar'] ?? 'default-avatar.png') ?>" class="avatar-sm" title="<?= htmlspecialchars($task['assignee_name']) ?>" alt="assignee" onerror="this.src='https://ui-avatars.com/api/?name=<?= urlencode($task['assignee_name']) ?>'">
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Column 2: IN PROGRESS -->
    <div class="kanban-column">
        <div class="kanban-header text-primary">
            <span><i class="bi bi-play-circle-fill me-1"></i> In Progress</span>
            <span class="badge bg-primary rounded-pill"><?= count($board['in_progress']) ?></span>
        </div>
        <div class="kanban-cards" data-status="in_progress">
            <?php foreach ($board['in_progress'] as $task): ?>
                <div class="kanban-card" draggable="true" data-task-id="<?= $task['id'] ?>">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="badge bg-secondary-subtle text-secondary fs-xs"><?= htmlspecialchars($task['project_title']) ?></span>
                        <span class="badge badge-priority-<?= $task['priority'] ?> fs-xs"><?= ucfirst($task['priority']) ?></span>
                    </div>
                    <h6 class="fw-bold mb-2">
                        <a href="/tasks/<?= $task['id'] ?>" class="text-body text-decoration-none">#<?= $task['id'] ?> <?= htmlspecialchars($task['title']) ?></a>
                    </h6>
                    <div class="d-flex justify-content-between align-items-center pt-2 mt-2 border-top fs-xs text-muted">
                        <div><i class="bi bi-chat me-1"></i><?= $task['comment_count'] ?></div>
                        <?php if ($task['assigned_to']): ?>
                            <img src="/uploads/<?= htmlspecialchars($task['assignee_avatar'] ?? 'default-avatar.png') ?>" class="avatar-sm" title="<?= htmlspecialchars($task['assignee_name']) ?>" alt="assignee" onerror="this.src='https://ui-avatars.com/api/?name=<?= urlencode($task['assignee_name']) ?>'">
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Column 3: UNDER REVIEW -->
    <div class="kanban-column">
        <div class="kanban-header text-warning">
            <span><i class="bi bi-eye-fill me-1"></i> Under Review</span>
            <span class="badge bg-warning text-dark rounded-pill"><?= count($board['review']) ?></span>
        </div>
        <div class="kanban-cards" data-status="review">
            <?php foreach ($board['review'] as $task): ?>
                <div class="kanban-card" draggable="true" data-task-id="<?= $task['id'] ?>">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="badge bg-secondary-subtle text-secondary fs-xs"><?= htmlspecialchars($task['project_title']) ?></span>
                        <span class="badge badge-priority-<?= $task['priority'] ?> fs-xs"><?= ucfirst($task['priority']) ?></span>
                    </div>
                    <h6 class="fw-bold mb-2">
                        <a href="/tasks/<?= $task['id'] ?>" class="text-body text-decoration-none">#<?= $task['id'] ?> <?= htmlspecialchars($task['title']) ?></a>
                    </h6>
                    <div class="d-flex justify-content-between align-items-center pt-2 mt-2 border-top fs-xs text-muted">
                        <div><i class="bi bi-chat me-1"></i><?= $task['comment_count'] ?></div>
                        <?php if ($task['assigned_to']): ?>
                            <img src="/uploads/<?= htmlspecialchars($task['assignee_avatar'] ?? 'default-avatar.png') ?>" class="avatar-sm" title="<?= htmlspecialchars($task['assignee_name']) ?>" alt="assignee" onerror="this.src='https://ui-avatars.com/api/?name=<?= urlencode($task['assignee_name']) ?>'">
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Column 4: DONE -->
    <div class="kanban-column">
        <div class="kanban-header text-success">
            <span><i class="bi bi-check-circle-fill me-1"></i> Completed</span>
            <span class="badge bg-success rounded-pill"><?= count($board['done']) ?></span>
        </div>
        <div class="kanban-cards" data-status="done">
            <?php foreach ($board['done'] as $task): ?>
                <div class="kanban-card" draggable="true" data-task-id="<?= $task['id'] ?>">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="badge bg-secondary-subtle text-secondary fs-xs"><?= htmlspecialchars($task['project_title']) ?></span>
                        <span class="badge badge-priority-<?= $task['priority'] ?> fs-xs"><?= ucfirst($task['priority']) ?></span>
                    </div>
                    <h6 class="fw-bold mb-2 text-decoration-line-through text-muted">
                        <a href="/tasks/<?= $task['id'] ?>" class="text-muted text-decoration-none">#<?= $task['id'] ?> <?= htmlspecialchars($task['title']) ?></a>
                    </h6>
                    <div class="d-flex justify-content-between align-items-center pt-2 mt-2 border-top fs-xs text-muted">
                        <div><i class="bi bi-chat me-1"></i><?= $task['comment_count'] ?></div>
                        <?php if ($task['assigned_to']): ?>
                            <img src="/uploads/<?= htmlspecialchars($task['assignee_avatar'] ?? 'default-avatar.png') ?>" class="avatar-sm" title="<?= htmlspecialchars($task['assignee_name']) ?>" alt="assignee" onerror="this.src='https://ui-avatars.com/api/?name=<?= urlencode($task['assignee_name']) ?>'">
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
