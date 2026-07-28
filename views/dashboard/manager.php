<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <h2 class="fw-extrabold mb-1">Project Manager Workspace 📊</h2>
        <p class="text-secondary mb-0">Monitor sprint progress, team workload, milestone deliverables & project performance.</p>
    </div>
    <!-- PM Quick Action Buttons -->
    <div class="d-flex gap-2 flex-wrap">
        <a href="/projects" class="btn btn-primary rounded-pill px-3 shadow-sm">
            <i class="bi bi-folder-plus me-1"></i> Create Project
        </a>
        <a href="/tasks" class="btn btn-outline-primary rounded-pill px-3">
            <i class="bi bi-plus-lg me-1"></i> Assign Task
        </a>
        <a href="/reports/export?type=projects" class="btn btn-outline-secondary rounded-pill px-3">
            <i class="bi bi-file-earmark-spreadsheet me-1"></i> Generate Report
        </a>
    </div>
</div>

<!-- 4 PM Stat Cards -->
<div class="row g-3 mb-4">
    <div class="col-xl-3 col-sm-6">
        <div class="card stat-card">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-muted fs-xs fw-bold text-uppercase">Assigned Projects</span>
                <div class="stat-icon-box text-primary">
                    <i class="bi bi-folder-check"></i>
                </div>
            </div>
            <div class="stat-number"><?= $metrics['assigned_projects'] ?></div>
            <div class="stat-trend text-info mt-1"><i class="bi bi-activity"></i> <?= $metrics['active_projects'] ?> Active Pipelines</div>
        </div>
    </div>

    <div class="col-xl-3 col-sm-6">
        <div class="card stat-card">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-muted fs-xs fw-bold text-uppercase">Sprint Progress</span>
                <div class="stat-icon-box text-info">
                    <i class="bi bi-flag"></i>
                </div>
            </div>
            <div class="stat-number"><?= $metrics['sprint_progress'] ?>%</div>
            <div class="stat-trend text-info mt-1"><i class="bi bi-check-all"></i> Based on completed milestones</div>
        </div>
    </div>

    <div class="col-xl-3 col-sm-6">
        <div class="card stat-card">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-muted fs-xs fw-bold text-uppercase">Team Productivity</span>
                <div class="stat-icon-box text-success">
                    <i class="bi bi-speedometer"></i>
                </div>
            </div>
            <div class="stat-number"><?= $metrics['team_productivity'] ?>%</div>
            <div class="stat-trend text-success mt-1"><i class="bi bi-arrow-up-short"></i> Logged vs Estimated Ratio</div>
        </div>
    </div>

    <div class="col-xl-3 col-sm-6">
        <div class="card stat-card">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-muted fs-xs fw-bold text-uppercase">Total Milestones</span>
                <div class="stat-icon-box text-warning">
                    <i class="bi bi-trophy"></i>
                </div>
            </div>
            <div class="stat-number"><?= $metrics['total_milestones'] ?></div>
            <div class="stat-trend text-warning mt-1"><i class="bi bi-clock"></i> Scheduled deliverables</div>
        </div>
    </div>
</div>

<!-- Team Workload & Hours Tracking Table -->
<div class="card p-4 mb-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="fw-bold mb-0"><i class="bi bi-people-fill text-primary me-2"></i>Team Workload & Productivity Matrix</h5>
        <a href="/reports" class="text-primary fs-xs text-decoration-none">View Full Report</a>
    </div>
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead>
                <tr>
                    <th>Team Member</th>
                    <th>Role</th>
                    <th>Assigned Tasks</th>
                    <th>Completed</th>
                    <th>Est. Hours</th>
                    <th>Logged Hours</th>
                    <th>Workload Ratio</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($workload as $w): ?>
                    <?php
                        $pct = $w['est_hours'] > 0 ? min(100, round(($w['logged_hours'] / $w['est_hours']) * 100)) : 0;
                    ?>
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <img src="/uploads/<?= htmlspecialchars($w['avatar'] ?? 'default-avatar.png') ?>" class="avatar-sm" alt="avatar" onerror="this.src='https://ui-avatars.com/api/?name=<?= urlencode($w['name']) ?>'">
                                <span class="fw-bold text-body fs-sm"><?= htmlspecialchars($w['name']) ?></span>
                            </div>
                        </td>
                        <td><span class="badge bg-secondary-subtle text-secondary fs-xs"><?= htmlspecialchars($w['role']) ?></span></td>
                        <td class="fw-bold fs-sm"><?= $w['task_count'] ?> tasks</td>
                        <td class="text-success fw-bold fs-sm"><?= $w['completed_count'] ?> done</td>
                        <td class="fs-sm"><?= $w['est_hours'] ?> hrs</td>
                        <td class="fw-bold text-primary fs-sm"><?= $w['logged_hours'] ?> hrs</td>
                        <td style="width: 140px;">
                            <div class="d-flex align-items-center gap-2">
                                <div class="progress flex-grow-1" style="height: 6px;">
                                    <div class="progress-bar" style="width: <?= $pct ?>%;"></div>
                                </div>
                                <span class="fs-xs fw-bold"><?= $pct ?>%</span>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Managed Projects & Stage Summary -->
<div class="row g-4 mb-4">
    <!-- Managed Projects List -->
    <div class="col-lg-7">
        <div class="card p-4 h-100">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold mb-0"><i class="bi bi-folder-fill text-primary me-2"></i>My Managed Projects</h5>
                <a href="/projects" class="btn btn-sm btn-link text-decoration-none">View All</a>
            </div>
            <div class="list-group list-group-flush fs-sm">
                <?php if (empty($projects)): ?>
                    <div class="text-center py-4 text-muted">No projects assigned yet.</div>
                <?php else: ?>
                    <?php foreach ($projects as $p): ?>
                        <div class="list-group-item bg-transparent px-0 py-3 d-flex align-items-center justify-content-between border-0 border-bottom border-secondary border-opacity-10">
                            <div>
                                <h6 class="fw-bold mb-1">
                                    <a href="/projects/<?= $p['id'] ?>" class="text-body text-decoration-none"><?= htmlspecialchars($p['title']) ?></a>
                                </h6>
                                <span class="badge badge-status-<?= $p['status'] ?> rounded-pill fs-xs me-2"><?= strtoupper(str_replace('_', ' ', $p['status'])) ?></span>
                                <small class="text-muted fs-xs">$<?= number_format($p['budget'], 2) ?> Budget</small>
                            </div>
                            <div class="text-end" style="width: 130px;">
                                <small class="text-muted fs-xs d-block mb-1">Progress <?= $p['progress_pct'] ?>%</small>
                                <div class="progress" style="height: 6px;">
                                    <div class="progress-bar" style="width: <?= $p['progress_pct'] ?>%;"></div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- PM Donut Chart -->
    <div class="col-lg-5">
        <div class="card p-4 h-100">
            <h5 class="fw-bold mb-3"><i class="bi bi-kanban text-info me-2"></i>Stage Summary Breakdown</h5>
            <div style="height: 240px; position: relative;">
                <canvas id="pmStatusChart"></canvas>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    Chart.defaults.color = '#9ca3af';
    Chart.defaults.font.family = "'Plus Jakarta Sans', sans-serif";

    const ctxStatus = document.getElementById('pmStatusChart').getContext('2d');
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
                backgroundColor: ['#8b5cf6', '#3b82f6', '#f59e0b', '#10b981'],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '72%',
            plugins: {
                legend: { position: 'bottom', labels: { padding: 15 } }
            }
        }
    });
});
</script>
