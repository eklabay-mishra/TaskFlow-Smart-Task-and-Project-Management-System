<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <h3 class="fw-extrabold mb-1">Reports & Performance Analytics</h3>
        <p class="text-secondary mb-0">Database-backed analytics, workload distribution, and CSV report export.</p>
    </div>

    <div class="d-flex gap-2">
        <a href="/reports/export?type=projects" class="btn btn-outline-success rounded-pill px-3 shadow-sm">
            <i class="bi bi-file-earmark-excel me-1"></i> Export Projects CSV
        </a>
        <a href="/reports/export?type=tasks" class="btn btn-success rounded-pill px-3 shadow-sm">
            <i class="bi bi-file-earmark-spreadsheet me-1"></i> Export Tasks CSV
        </a>
    </div>
</div>

<!-- Workload Summary Table -->
<div class="card border-0 shadow-sm p-4 mb-4">
    <h5 class="fw-bold mb-3"><i class="bi bi-people-fill text-primary me-2"></i>Team Member Workload & Logged Hours</h5>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light fs-xs text-uppercase text-secondary">
                <tr>
                    <th>Team Member</th>
                    <th>Role</th>
                    <th>Assigned Tasks</th>
                    <th>Completed</th>
                    <th>Est. Hours</th>
                    <th>Logged Hours</th>
                    <th>Efficiency</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($workload as $w): ?>
                    <?php
                        $efficiency = $w['est_hours'] > 0 ? round(($w['logged_hours'] / $w['est_hours']) * 100) : 100;
                        $effClass = $efficiency > 120 ? 'text-danger' : ($efficiency < 80 ? 'text-info' : 'text-success');
                    ?>
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <img src="/uploads/<?= htmlspecialchars($w['avatar'] ?? 'default-avatar.png') ?>" class="avatar-sm" alt="avatar" onerror="this.src='https://ui-avatars.com/api/?name=<?= urlencode($w['name']) ?>'">
                                <span class="fw-bold text-body"><?= htmlspecialchars($w['name']) ?></span>
                            </div>
                        </td>
                        <td><span class="badge bg-secondary-subtle text-secondary fs-xs"><?= htmlspecialchars($w['role']) ?></span></td>
                        <td class="fw-bold"><?= $w['task_count'] ?> tasks</td>
                        <td class="text-success fw-bold"><?= $w['completed_count'] ?> tasks</td>
                        <td><?= $w['est_hours'] ?> hrs</td>
                        <td class="fw-bold text-primary"><?= $w['logged_hours'] ?> hrs</td>
                        <td><strong class="<?= $effClass ?>"><?= $efficiency ?>%</strong></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
