<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <h2 class="fw-extrabold mb-1">My Work Workspace ⚡</h2>
        <p class="text-secondary mb-0">Track assigned tasks, log development hours, and execute project deliverables.</p>
    </div>
    <!-- Member Quick Action Buttons -->
    <div class="d-flex gap-2 flex-wrap">
        <a href="/kanban" class="btn btn-primary rounded-pill px-3 shadow-sm">
            <i class="bi bi-kanban me-1"></i> My Kanban Board
        </a>
        <a href="/tasks" class="btn btn-outline-primary rounded-pill px-3">
            <i class="bi bi-check2-square me-1"></i> All My Tasks
        </a>
    </div>
</div>

<!-- 4 Member Stat Cards -->
<div class="row g-3 mb-4">
    <div class="col-xl-3 col-sm-6">
        <div class="card stat-card">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-muted fs-xs fw-bold text-uppercase">Assigned To Me</span>
                <div class="stat-icon-box text-primary">
                    <i class="bi bi-check2-square"></i>
                </div>
            </div>
            <div class="stat-number"><?= $metrics['my_tasks'] ?></div>
            <div class="stat-trend text-primary mt-1"><i class="bi bi-person-check"></i> Active task queue</div>
        </div>
    </div>

    <div class="col-xl-3 col-sm-6">
        <div class="card stat-card">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-muted fs-xs fw-bold text-uppercase">Completed Tasks</span>
                <div class="stat-icon-box text-success">
                    <i class="bi bi-check-circle"></i>
                </div>
            </div>
            <div class="stat-number"><?= $metrics['completed_tasks'] ?></div>
            <div class="stat-trend text-success mt-1"><i class="bi bi-arrow-up-short"></i> Finished deliverables</div>
        </div>
    </div>

    <div class="col-xl-3 col-sm-6">
        <div class="card stat-card">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-muted fs-xs fw-bold text-uppercase">Due This Week</span>
                <div class="stat-icon-box text-warning">
                    <i class="bi bi-calendar-event"></i>
                </div>
            </div>
            <div class="stat-number"><?= $metrics['due_this_week'] ?></div>
            <div class="stat-trend text-warning mt-1"><i class="bi bi-clock"></i> Urgent deadlines</div>
        </div>
    </div>

    <div class="col-xl-3 col-sm-6">
        <div class="card stat-card">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-muted fs-xs fw-bold text-uppercase">Logged Hours</span>
                <div class="stat-icon-box text-info">
                    <i class="bi bi-stopwatch"></i>
                </div>
            </div>
            <div class="stat-number"><?= $metrics['logged_hours'] ?> hrs</div>
            <div class="stat-trend text-info mt-1"><i class="bi bi-journal-check"></i> Total tracked work</div>
        </div>
    </div>
</div>

<!-- Today's Focus Checklist & Active Projects -->
<div class="row g-4 mb-4">
    <!-- Today's Focus Tasks Checklist -->
    <div class="col-lg-7">
        <div class="card p-4 h-100">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold mb-0"><i class="bi bi-card-checklist text-primary me-2"></i>Today's Focus Tasks</h5>
                <a href="/tasks" class="text-primary fs-xs text-decoration-none">View All Tasks</a>
            </div>
            <div class="list-group list-group-flush fs-sm">
                <?php if (empty($todayFocusTasks)): ?>
                    <div class="text-center py-4 text-muted">You have no pending tasks today! Great job 🎉</div>
                <?php else: ?>
                    <?php foreach ($todayFocusTasks as $t): ?>
                        <div class="list-group-item bg-transparent px-0 py-3 d-flex align-items-center justify-content-between border-0 border-bottom border-secondary border-opacity-10">
                            <div class="d-flex align-items-center gap-3">
                                <form action="/tasks/<?= $t['id'] ?>/status" method="POST" class="d-inline">
                                    <?= \Core\CSRF::field() ?>
                                    <input type="hidden" name="status" value="done">
                                    <button type="submit" class="btn btn-outline-success btn-sm rounded-circle p-1" title="Mark Done"><i class="bi bi-check fs-6"></i></button>
                                </form>
                                <div>
                                    <h6 class="fw-bold mb-1">
                                        <a href="/tasks/<?= $t['id'] ?>" class="text-body text-decoration-none">#<?= $t['id'] ?> <?= htmlspecialchars($t['title']) ?></a>
                                    </h6>
                                    <span class="badge badge-priority-<?= $t['priority'] ?> rounded-pill fs-xs me-2"><?= ucfirst($t['priority']) ?></span>
                                    <small class="text-muted fs-xs"><?= htmlspecialchars($t['project_title']) ?></small>
                                </div>
                            </div>
                            <div class="text-end">
                                <span class="badge badge-status-<?= $t['status'] ?> rounded-pill fs-xs d-block mb-1"><?= strtoupper(str_replace('_', ' ', $t['status'])) ?></span>
                                <small class="text-muted fs-xs"><?= $t['due_date'] ? date('M d', strtotime($t['due_date'])) : 'No Date' ?></small>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Assigned Projects Progress -->
    <div class="col-lg-5">
        <div class="card p-4 h-100">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold mb-0"><i class="bi bi-folder-fill text-warning me-2"></i>My Assigned Projects</h5>
                <a href="/projects" class="text-primary fs-xs text-decoration-none">View All</a>
            </div>
            <div class="list-group list-group-flush fs-sm">
                <?php if (empty($myProjects)): ?>
                    <div class="text-center py-4 text-muted">No assigned projects yet.</div>
                <?php else: ?>
                    <?php foreach ($myProjects as $p): ?>
                        <div class="list-group-item bg-transparent px-0 py-3 border-0 border-bottom border-secondary border-opacity-10">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <h6 class="fw-bold mb-0">
                                    <a href="/projects/<?= $p['id'] ?>" class="text-body text-decoration-none"><?= htmlspecialchars($p['title']) ?></a>
                                </h6>
                                <strong class="fs-xs text-primary"><?= $p['progress_pct'] ?>%</strong>
                            </div>
                            <div class="progress" style="height: 6px;">
                                <div class="progress-bar" style="width: <?= $p['progress_pct'] ?>%;"></div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- My Attachments & Recent Discussion Thread -->
<div class="row g-4 mb-4">
    <!-- My Uploaded Files -->
    <div class="col-lg-6">
        <div class="card p-4 h-100">
            <h5 class="fw-bold mb-3"><i class="bi bi-paperclip text-info me-2"></i>My Uploaded Attachments</h5>
            <div class="list-group list-group-flush fs-sm">
                <?php if (empty($myAttachments)): ?>
                    <div class="text-center py-4 text-muted">No files uploaded yet.</div>
                <?php else: ?>
                    <?php foreach ($myAttachments as $att): ?>
                        <div class="list-group-item bg-transparent px-0 py-2 d-flex align-items-center justify-content-between border-0">
                            <div>
                                <i class="bi bi-file-earmark-text text-primary me-2"></i>
                                <strong class="text-body"><?= htmlspecialchars($att['file_name']) ?></strong>
                                <small class="text-muted d-block fs-xs"><?= round($att['file_size']/1024, 1) ?> KB | <?= date('M d, H:i', strtotime($att['uploaded_at'])) ?></small>
                            </div>
                            <a href="/uploads/<?= htmlspecialchars($att['file_path']) ?>" download class="btn btn-xs btn-outline-primary"><i class="bi bi-download"></i></a>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Recent Task Comments -->
    <div class="col-lg-6">
        <div class="card p-4 h-100">
            <h5 class="fw-bold mb-3"><i class="bi bi-chat-left-text text-success me-2"></i>My Recent Comments</h5>
            <div class="list-group list-group-flush fs-sm">
                <?php if (empty($recentComments)): ?>
                    <div class="text-center py-4 text-muted">No comments posted yet.</div>
                <?php else: ?>
                    <?php foreach ($recentComments as $c): ?>
                        <div class="list-group-item bg-transparent px-0 py-2 border-0">
                            <div class="d-flex justify-content-between mb-1">
                                <small class="text-muted fs-xs"><?= date('M d, Y H:i', strtotime($c['created_at'])) ?></small>
                            </div>
                            <p class="mb-0 text-body fs-xs italic">"<?= htmlspecialchars($c['comment']) ?>"</p>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
