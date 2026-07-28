<!-- Header Banner -->
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <h2 class="fw-extrabold mb-1">Welcome back, <?= htmlspecialchars($user['name']) ?>! 👋</h2>
        <p class="text-secondary mb-0">Here's what's happening with your projects today.</p>
    </div>
    <div class="d-flex gap-2">
        <a href="/kanban" class="btn btn-primary rounded-pill px-4 shadow-sm">
            <i class="bi bi-kanban me-1"></i> Execution Board
        </a>
    </div>
</div>

<!-- 5 Top Stat Cards -->
<div class="row g-3 mb-4">
    <div class="col">
        <div class="card stat-card">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-muted fs-xs fw-bold text-uppercase">Total Projects</span>
                <div class="stat-icon-box text-primary">
                    <i class="bi bi-folder2-open"></i>
                </div>
            </div>
            <div class="stat-number"><?= $metrics['total_projects'] ?></div>
            <div class="stat-trend text-info mt-1"><i class="bi bi-arrow-up-short"></i> 12% from last month</div>
        </div>
    </div>
    <div class="col">
        <div class="card stat-card">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-muted fs-xs fw-bold text-uppercase">Active Pipelines</span>
                <div class="stat-icon-box text-info">
                    <i class="bi bi-activity"></i>
                </div>
            </div>
            <div class="stat-number"><?= $metrics['active_projects'] ?></div>
            <div class="stat-trend text-info mt-1"><i class="bi bi-arrow-up-short"></i> 15% from last month</div>
        </div>
    </div>
    <div class="col">
        <div class="card stat-card">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-muted fs-xs fw-bold text-uppercase">Completed Tasks</span>
                <div class="stat-icon-box text-success">
                    <i class="bi bi-check-circle"></i>
                </div>
            </div>
            <div class="stat-number"><?= $metrics['completed_tasks'] ?></div>
            <div class="stat-trend text-success mt-1"><i class="bi bi-arrow-up-short"></i> 8% from last month</div>
        </div>
    </div>
    <div class="col">
        <div class="card stat-card">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-muted fs-xs fw-bold text-uppercase">Pending Tasks</span>
                <div class="stat-icon-box text-warning">
                    <i class="bi bi-clock-history"></i>
                </div>
            </div>
            <div class="stat-number"><?= $metrics['pending_tasks'] ?></div>
            <div class="stat-trend text-warning mt-1"><i class="bi bi-arrow-down-short"></i> 5% from last month</div>
        </div>
    </div>
    <div class="col">
        <div class="card stat-card">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-muted fs-xs fw-bold text-uppercase">Team Members</span>
                <div class="stat-icon-box text-purple">
                    <i class="bi bi-people"></i>
                </div>
            </div>
            <div class="stat-number"><?= $metrics['team_members'] ?></div>
            <div class="stat-trend text-success mt-1"><i class="bi bi-arrow-up-short"></i> 3% from last month</div>
        </div>
    </div>
</div>

<!-- 3 Analytical Charts Section -->
<div class="row g-4 mb-4">
    <!-- Line Area Chart: Project Progress Overview -->
    <div class="col-lg-6">
        <div class="card p-4 h-100">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold mb-0"><i class="bi bi-graph-up text-primary me-2"></i>Project Progress Overview</h5>
                <select class="form-select form-select-sm w-auto rounded-pill border-0 bg-secondary-subtle fs-xs">
                    <option>This Week</option>
                    <option>This Month</option>
                </select>
            </div>
            <div style="height: 250px; position: relative;">
                <canvas id="progressLineChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Donut Chart 1: Tasks by Priority -->
    <div class="col-lg-3">
        <div class="card p-4 h-100">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold mb-0">Tasks by Priority</h5>
            </div>
            <div class="d-flex align-items-center justify-content-between gap-2">
                <!-- Center Text Donut -->
                <div class="donut-chart-container" style="height: 160px; width: 160px;">
                    <canvas id="priorityDonutChart"></canvas>
                    <div class="donut-center-label">
                        <div class="donut-center-number"><?= array_sum($priorityCounts) ?></div>
                        <div class="donut-center-text">Total Tasks</div>
                    </div>
                </div>
                <!-- Stacked Legend Right -->
                <ul class="chart-legend-list flex-grow-1 ms-2">
                    <?php
                    $pTotal = max(1, array_sum($priorityCounts));
                    $pMap = [
                        'high' => ['High Priority', '#f43f5e'],
                        'medium' => ['Medium Priority', '#f59e0b'],
                        'low' => ['Low Priority', '#3b82f6'],
                        'urgent' => ['Urgent', '#8b5cf6']
                    ];
                    ?>
                    <?php foreach ($pMap as $key => $meta): ?>
                        <?php
                            $cnt = $priorityCounts[$key] ?? 0;
                            $pct = round(($cnt / $pTotal) * 100, 1);
                        ?>
                        <li class="chart-legend-item">
                            <span class="d-flex align-items-center">
                                <span class="legend-dot" style="background-color: <?= $meta[1] ?>;"></span>
                                <span class="fs-xs text-secondary"><?= $meta[0] ?></span>
                            </span>
                            <span class="fs-xs fw-bold text-body ms-1"><?= $cnt ?> (<?= $pct ?>%)</span>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>

    <!-- Donut Chart 2: Tasks by Status -->
    <div class="col-lg-3">
        <div class="card p-4 h-100">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold mb-0">Tasks by Status</h5>
                <select class="form-select form-select-sm w-auto rounded-pill border-0 bg-secondary-subtle fs-xs">
                    <option>This Month</option>
                </select>
            </div>
            <div class="d-flex align-items-center justify-content-between gap-2">
                <!-- Center Text Donut -->
                <div class="donut-chart-container" style="height: 160px; width: 160px;">
                    <canvas id="statusDonutChart"></canvas>
                    <div class="donut-center-label">
                        <div class="donut-center-number"><?= array_sum($statusCounts) ?></div>
                        <div class="donut-center-text">Total Tasks</div>
                    </div>
                </div>
                <!-- Stacked Legend Right -->
                <ul class="chart-legend-list flex-grow-1 ms-2">
                    <?php
                    $sTotal = max(1, array_sum($statusCounts));
                    $sMap = [
                        'done' => ['Completed', '#10b981'],
                        'in_progress' => ['In Progress', '#3b82f6'],
                        'review' => ['Under Review', '#f59e0b'],
                        'todo' => ['Backlog', '#8b5cf6']
                    ];
                    ?>
                    <?php foreach ($sMap as $key => $meta): ?>
                        <?php
                            $cnt = $statusCounts[$key] ?? 0;
                            $pct = round(($cnt / $sTotal) * 100, 1);
                        ?>
                        <li class="chart-legend-item">
                            <span class="d-flex align-items-center">
                                <span class="legend-dot" style="background-color: <?= $meta[1] ?>;"></span>
                                <span class="fs-xs text-secondary"><?= $meta[0] ?></span>
                            </span>
                            <span class="fs-xs fw-bold text-body ms-1"><?= $cnt ?> (<?= $pct ?>%)</span>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- Widgets Row: Upcoming Deadlines, Activity Stream, Projects by Category -->
<div class="row g-4 mb-4">
    <!-- Upcoming Deadlines -->
    <div class="col-lg-4">
        <div class="card p-4 h-100">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold mb-0"><i class="bi bi-calendar3 text-primary me-2"></i>Upcoming Deadlines</h5>
                <a href="/tasks" class="text-primary fs-xs text-decoration-none">View All</a>
            </div>
            <div class="list-group list-group-flush fs-sm">
                <?php if (empty($upcomingDeadlines)): ?>
                    <div class="text-center py-4 text-muted">No upcoming deadlines scheduled.</div>
                <?php else: ?>
                    <?php foreach ($upcomingDeadlines as $d): ?>
                        <?php
                            $dueDays = round((strtotime($d['due_date']) - time()) / 86400);
                            $dueLabel = ($dueDays <= 0) ? 'Due today' : "Due in {$dueDays} days";
                        ?>
                        <div class="list-group-item bg-transparent px-0 py-3 d-flex align-items-center justify-content-between border-0 border-bottom border-secondary border-opacity-10">
                            <div class="d-flex align-items-center gap-3">
                                <div class="date-box">
                                    <strong class="d-block text-body fs-5 lh-1"><?= date('d', strtotime($d['due_date'])) ?></strong>
                                    <small class="text-muted text-uppercase fs-xs"><?= date('M', strtotime($d['due_date'])) ?></small>
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-1 text-body fs-sm">
                                        <a href="/tasks/<?= $d['id'] ?>" class="text-body text-decoration-none"><?= htmlspecialchars($d['title']) ?></a>
                                    </h6>
                                    <span class="badge badge-priority-<?= $d['priority'] ?> rounded-pill fs-xs"><?= ucfirst($d['priority']) ?> Priority</span>
                                </div>
                            </div>
                            <small class="text-muted fs-xs"><?= $dueLabel ?></small>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Recent Activity Stream -->
    <div class="col-lg-4">
        <div class="card p-4 h-100">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold mb-0">Recent Activity</h5>
                <a href="#" class="text-primary fs-xs text-decoration-none">View All</a>
            </div>
            <div class="list-group list-group-flush fs-sm">
                <?php foreach (array_slice($activityLogs, 0, 5) as $log): ?>
                    <div class="list-group-item bg-transparent px-0 py-2 d-flex align-items-center gap-3 border-0">
                        <img src="/uploads/<?= htmlspecialchars($log['user_avatar'] ?? 'default-avatar.png') ?>" class="avatar-sm" alt="user" onerror="this.src='https://ui-avatars.com/api/?name=<?= urlencode($log['user_name']) ?>'">
                        <div class="flex-grow-1 text-truncate">
                            <div class="d-flex justify-content-between">
                                <strong class="text-body fs-xs"><?= htmlspecialchars($log['user_name']) ?></strong>
                                <span class="text-muted fs-xs"><?= date('H:i', strtotime($log['created_at'])) ?> •</span>
                            </div>
                            <div class="text-muted fs-xs text-truncate"><?= htmlspecialchars($log['description']) ?></div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- Projects by Category Bar Chart -->
    <div class="col-lg-4">
        <div class="card p-4 h-100">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold mb-0">Projects by Category</h5>
                <select class="form-select form-select-sm w-auto rounded-pill border-0 bg-secondary-subtle fs-xs">
                    <option>This Month</option>
                </select>
            </div>
            <div style="height: 240px; position: relative;">
                <canvas id="categoryBarChart"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Team Performance Overview Card with Vector Graphic -->
<div class="card p-4 mb-4 overflow-hidden position-relative">
    <div class="row align-items-center">
        <div class="col-lg-8">
            <h5 class="fw-bold mb-3"><i class="bi bi-trophy-fill text-warning me-2"></i>Team Performance Overview</h5>
            <div class="row g-3">
                <div class="col-sm-3 col-6">
                    <div class="p-3 rounded-3 bg-secondary-subtle border border-secondary border-opacity-10">
                        <span class="text-muted fs-xs d-block mb-1">Average Completion Time</span>
                        <h4 class="fw-extrabold mb-0 text-white">2.4 days</h4>
                        <small class="text-success fs-xs"><i class="bi bi-arrow-down-short"></i> 8% faster</small>
                    </div>
                </div>
                <div class="col-sm-3 col-6">
                    <div class="p-3 rounded-3 bg-secondary-subtle border border-secondary border-opacity-10">
                        <span class="text-muted fs-xs d-block mb-1">Task Success Rate</span>
                        <h4 class="fw-extrabold mb-0 text-success">94.2%</h4>
                        <small class="text-success fs-xs"><i class="bi bi-arrow-up-short"></i> 5% higher</small>
                    </div>
                </div>
                <div class="col-sm-3 col-6">
                    <div class="p-3 rounded-3 bg-secondary-subtle border border-secondary border-opacity-10">
                        <span class="text-muted fs-xs d-block mb-1">Team Productivity</span>
                        <h4 class="fw-extrabold mb-0 text-info">87.5%</h4>
                        <small class="text-info fs-xs"><i class="bi bi-arrow-up-short"></i> 12% higher</small>
                    </div>
                </div>
                <div class="col-sm-3 col-6">
                    <div class="p-3 rounded-3 bg-secondary-subtle border border-secondary border-opacity-10">
                        <span class="text-muted fs-xs d-block mb-1">Client Satisfaction</span>
                        <h4 class="fw-extrabold mb-0 text-warning">★ 4.8/5</h4>
                        <small class="text-warning fs-xs"><i class="bi bi-arrow-up-short"></i> 0.3 points</small>
                    </div>
                </div>
            </div>
        </div>
        <!-- Right Isometric Team SVG Vector -->
        <div class="col-lg-4 text-center d-none d-lg-block">
            <svg width="220" height="140" viewBox="0 0 240 150" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M20 130 L120 70 L220 130" stroke="#7c3aed" stroke-width="3" stroke-dasharray="4 4"/>
                <circle cx="120" cy="70" r="8" fill="#7c3aed"/>
                <circle cx="60" cy="100" r="6" fill="#10b981"/>
                <circle cx="180" cy="100" r="6" fill="#3b82f6"/>
                <path d="M40 140 H200 V144 H40 Z" fill="url(#grad2)"/>
                <defs>
                    <linearGradient id="grad2" x1="40" y1="140" x2="200" y2="144" gradientUnits="userSpaceOnUse">
                        <stop stop-color="#7c3aed"/>
                        <stop offset="1" stop-color="#3b82f6"/>
                    </linearGradient>
                </defs>
            </svg>
        </div>
    </div>
</div>

<!-- Chart.js Config -->
<script>
document.addEventListener('DOMContentLoaded', () => {
    Chart.defaults.color = '#9ca3af';
    Chart.defaults.font.family = "'Plus Jakarta Sans', sans-serif";

    // 1. Progress Line Area Chart
    const ctxLine = document.getElementById('progressLineChart').getContext('2d');
    const gradCompleted = ctxLine.createLinearGradient(0, 0, 0, 250);
    gradCompleted.addColorStop(0, 'rgba(16, 185, 129, 0.25)');
    gradCompleted.addColorStop(1, 'rgba(16, 185, 129, 0.0)');

    const gradInProgress = ctxLine.createLinearGradient(0, 0, 0, 250);
    gradInProgress.addColorStop(0, 'rgba(59, 130, 246, 0.25)');
    gradInProgress.addColorStop(1, 'rgba(59, 130, 246, 0.0)');

    new Chart(ctxLine, {
        type: 'line',
        data: {
            labels: <?= json_encode($progressTrend['labels']) ?>,
            datasets: [
                {
                    label: 'Completed Tasks',
                    data: <?= json_encode($progressTrend['completed']) ?>,
                    borderColor: '#10b981',
                    borderWidth: 3,
                    backgroundColor: gradCompleted,
                    fill: true,
                    tension: 0.4,
                    pointRadius: 4,
                    pointHoverRadius: 6
                },
                {
                    label: 'In Progress Tasks',
                    data: <?= json_encode($progressTrend['in_progress']) ?>,
                    borderColor: '#3b82f6',
                    borderWidth: 3,
                    backgroundColor: gradInProgress,
                    fill: true,
                    tension: 0.4,
                    pointRadius: 4,
                    pointHoverRadius: 6
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: { beginAtZero: true, grid: { color: 'rgba(255, 255, 255, 0.04)' } },
                x: { grid: { display: false } }
            },
            plugins: {
                legend: { position: 'top', align: 'end' }
            }
        }
    });

    // 2. Priority Donut Chart
    const ctxPriority = document.getElementById('priorityDonutChart').getContext('2d');
    new Chart(ctxPriority, {
        type: 'doughnut',
        data: {
            labels: ['High Priority', 'Medium Priority', 'Low Priority', 'Urgent'],
            datasets: [{
                data: [
                    <?= $priorityCounts['high'] ?>,
                    <?= $priorityCounts['medium'] ?>,
                    <?= $priorityCounts['low'] ?>,
                    <?= $priorityCounts['urgent'] ?>
                ],
                backgroundColor: ['#f43f5e', '#f59e0b', '#3b82f6', '#8b5cf6'],
                borderWidth: 0,
                hoverOffset: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '76%',
            plugins: {
                legend: { display: false }
            }
        }
    });

    // 3. Status Donut Chart
    const ctxStatus = document.getElementById('statusDonutChart').getContext('2d');
    new Chart(ctxStatus, {
        type: 'doughnut',
        data: {
            labels: ['Completed', 'In Progress', 'Under Review', 'Backlog'],
            datasets: [{
                data: [
                    <?= $statusCounts['done'] ?>,
                    <?= $statusCounts['in_progress'] ?>,
                    <?= $statusCounts['review'] ?>,
                    <?= $statusCounts['todo'] ?>
                ],
                backgroundColor: ['#10b981', '#3b82f6', '#f59e0b', '#8b5cf6'],
                borderWidth: 0,
                hoverOffset: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '76%',
            plugins: {
                legend: { display: false }
            }
        }
    });

    // 4. Category Bar Chart
    const ctxCat = document.getElementById('categoryBarChart').getContext('2d');
    const catLabels = <?= json_encode(array_column($categoryCounts, 'category')) ?>;
    const catData   = <?= json_encode(array_map('intval', array_column($categoryCounts, 'count'))) ?>;

    new Chart(ctxCat, {
        type: 'bar',
        data: {
            labels: catLabels.length ? catLabels : ['Web Dev', 'Mobile App', 'Analytics', 'DevOps', 'UI/UX'],
            datasets: [{
                label: 'Projects',
                data: catData.length ? catData : [9, 6, 4, 3, 2],
                backgroundColor: ['#7c3aed', '#6366f1', '#10b981', '#f59e0b', '#f43f5e'],
                borderRadius: 8
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: { beginAtZero: true, ticks: { precision: 0 }, grid: { color: 'rgba(255, 255, 255, 0.04)' } },
                x: { grid: { display: false } }
            },
            plugins: {
                legend: { display: false }
            }
        }
    });
});
</script>
