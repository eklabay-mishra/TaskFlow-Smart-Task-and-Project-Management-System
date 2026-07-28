<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <h3 class="fw-extrabold mb-1">Welcome back, <?= htmlspecialchars($user['name']) ?>!</h3>
        <p class="text-secondary mb-0">Here is your project & task activity performance overview.</p>
    </div>
    <div class="d-flex gap-2">
        <a href="/kanban" class="btn btn-primary rounded-pill px-4 shadow-sm">
            <i class="bi bi-kanban me-1"></i> Open Pipeline Board
        </a>
    </div>
</div>

<!-- KPI Summary Cards -->
<div class="row g-3 mb-4">
    <div class="col-xl-3 col-sm-6">
        <div class="card p-3">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted fs-xs fw-semibold d-block mb-1">TOTAL PROJECTS</span>
                    <h2 class="fw-extrabold mb-0 text-primary"><?= $metrics['total_projects'] ?></h2>
                </div>
                <div class="badge bg-primary-subtle text-primary p-3 rounded-3 fs-4">
                    <i class="bi bi-folder-fill"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6">
        <div class="card p-3">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted fs-xs fw-semibold d-block mb-1">ACTIVE PIPELINES</span>
                    <h2 class="fw-extrabold mb-0 text-info"><?= $metrics['active_projects'] ?></h2>
                </div>
                <div class="badge bg-info-subtle text-info p-3 rounded-3 fs-4">
                    <i class="bi bi-activity"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6">
        <div class="card p-3">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted fs-xs fw-semibold d-block mb-1">COMPLETED TASKS</span>
                    <h2 class="fw-extrabold mb-0 text-success"><?= $metrics['completed_tasks'] ?> <span class="fs-6 text-muted font-normal">/ <?= $metrics['total_tasks'] ?></span></h2>
                </div>
                <div class="badge bg-success-subtle text-success p-3 rounded-3 fs-4">
                    <i class="bi bi-check-circle-fill"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6">
        <div class="card p-3">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted fs-xs fw-semibold d-block mb-1">PENDING TASKS</span>
                    <h2 class="fw-extrabold mb-0 text-warning"><?= $metrics['my_pending_tasks'] ?></h2>
                </div>
                <div class="badge bg-warning-subtle text-warning p-3 rounded-3 fs-4">
                    <i class="bi bi-clock-history"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Charts Section -->
<div class="row g-4 mb-4">
    <div class="col-lg-6">
        <div class="card p-4 h-100">
            <h5 class="fw-bold mb-3"><i class="bi bi-pie-chart-fill text-primary me-2"></i>Tasks by Status Breakdown</h5>
            <div style="height: 270px; position: relative;">
                <canvas id="statusChart"></canvas>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card p-4 h-100">
            <h5 class="fw-bold mb-3"><i class="bi bi-bar-chart-fill text-info me-2"></i>Tasks by Priority Distribution</h5>
            <div style="height: 270px; position: relative;">
                <canvas id="priorityChart"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Recent Projects & Logs -->
<div class="row g-4 mb-4">
    <div class="col-lg-7">
        <div class="card h-100">
            <div class="card-header bg-transparent py-3 border-0 d-flex justify-content-between align-items-center">
                <h5 class="fw-bold mb-0"><i class="bi bi-folder-check text-primary me-2"></i>Recent Projects Workspace</h5>
                <a href="/projects" class="btn btn-sm btn-link text-decoration-none">View All</a>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="fs-xs text-uppercase text-muted">
                        <tr>
                            <th>Project Title</th>
                            <th>Status</th>
                            <th>Progress</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($projects)): ?>
                            <tr><td colspan="4" class="text-center py-4 text-muted">No projects found.</td></tr>
                        <?php else: ?>
                            <?php foreach ($projects as $p): ?>
                                <tr>
                                    <td>
                                        <a href="/projects/<?= $p['id'] ?>" class="fw-bold text-body text-decoration-none d-block">
                                            <?= htmlspecialchars($p['title']) ?>
                                        </a>
                                        <span class="text-muted fs-xs"><?= htmlspecialchars($p['category']) ?></span>
                                    </td>
                                    <td>
                                        <span class="badge badge-status-<?= $p['status'] ?> rounded-pill px-3 py-1">
                                            <?= strtoupper(str_replace('_', ' ', $p['status'])) ?>
                                        </span>
                                    </td>
                                    <td style="width: 140px;">
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="progress flex-grow-1" style="height: 6px;">
                                                <div class="progress-bar" style="width: <?= $p['progress_pct'] ?>%"></div>
                                            </div>
                                            <span class="fs-xs fw-bold"><?= $p['progress_pct'] ?>%</span>
                                        </div>
                                    </td>
                                    <td>
                                        <a href="/projects/<?= $p['id'] ?>" class="btn btn-sm btn-outline-secondary rounded-circle p-2 d-inline-flex align-items-center justify-content-center" style="width: 32px; height: 32px;"><i class="bi bi-eye"></i></a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Recent Activity Log -->
    <div class="col-lg-5">
        <div class="card h-100">
            <div class="card-header bg-transparent py-3 border-0">
                <h5 class="fw-bold mb-0"><i class="bi bi-clock-history text-purple me-2"></i>Audit Activity Stream</h5>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush fs-sm">
                    <?php if (empty($activityLogs)): ?>
                        <div class="p-4 text-center text-muted">No activity logs recorded yet.</div>
                    <?php else: ?>
                        <?php foreach ($activityLogs as $log): ?>
                            <div class="list-group-item py-3 bg-transparent">
                                <div class="d-flex align-items-start gap-2">
                                    <img src="/uploads/<?= htmlspecialchars($log['user_avatar'] ?? 'default-avatar.png') ?>" class="avatar-sm mt-1" alt="user" onerror="this.src='https://ui-avatars.com/api/?name=<?= urlencode($log['user_name']) ?>'">
                                    <div class="flex-grow-1">
                                        <div class="d-flex justify-content-between">
                                            <strong class="text-body"><?= htmlspecialchars($log['user_name']) ?></strong>
                                            <small class="text-muted fs-xs"><?= date('M d, H:i', strtotime($log['created_at'])) ?></small>
                                        </div>
                                        <div class="text-secondary mt-1 fs-xs"><?= htmlspecialchars($log['description']) ?></div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js Config -->
<script>
document.addEventListener('DOMContentLoaded', () => {
    Chart.defaults.color = '#94a3b8';
    Chart.defaults.font.family = "'Plus Jakarta Sans', sans-serif";

    // 1. Status Chart
    const ctxStatus = document.getElementById('statusChart').getContext('2d');
    new Chart(ctxStatus, {
        type: 'doughnut',
        data: {
            labels: ['Backlog', 'In Progress', 'Under Review', 'Completed'],
            datasets: [{
                data: [
                    <?= $statusCounts['todo'] ?>,
                    <?= $statusCounts['in_progress'] ?>,
                    <?= $statusCounts['review'] ?>,
                    <?= $statusCounts['done'] ?>
                ],
                backgroundColor: ['#64748b', '#6366f1', '#f59e0b', '#10b981'],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'bottom', labels: { padding: 20 } }
            }
        }
    });

    // 2. Priority Chart
    const ctxPriority = document.getElementById('priorityChart').getContext('2d');
    new Chart(ctxPriority, {
        type: 'bar',
        data: {
            labels: ['Low', 'Medium', 'High', 'Urgent'],
            datasets: [{
                label: 'Tasks Count',
                data: [
                    <?= $priorityCounts['low'] ?>,
                    <?= $priorityCounts['medium'] ?>,
                    <?= $priorityCounts['high'] ?>,
                    <?= $priorityCounts['urgent'] ?>
                ],
                backgroundColor: ['#94a3b8', '#38bdf8', '#fbbf24', '#f87171'],
                borderRadius: 8
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: { beginAtZero: true, grid: { color: 'rgba(255, 255, 255, 0.05)' } },
                x: { grid: { display: false } }
            },
            plugins: {
                legend: { display: false }
            }
        }
    });
});
</script>
