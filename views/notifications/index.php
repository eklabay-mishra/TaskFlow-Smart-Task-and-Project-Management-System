<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <h3 class="fw-extrabold mb-1">Notification Center</h3>
        <p class="text-secondary mb-0">System alerts, task assignments, and activity updates.</p>
    </div>

    <form action="/notifications/read-all" method="POST">
        <?= \Core\CSRF::field() ?>
        <button type="submit" class="btn btn-outline-primary rounded-pill px-3 shadow-sm">
            <i class="bi bi-check2-all me-1"></i> Mark All as Read
        </button>
    </form>
</div>

<div class="card border-0 shadow-sm">
    <div class="list-group list-group-flush">
        <?php if (empty($notifications)): ?>
            <div class="p-5 text-center text-muted">
                <i class="bi bi-bell-slash display-4 mb-2 d-block"></i>
                <h6>Your notification inbox is clean!</h6>
            </div>
        <?php else: ?>
            <?php foreach ($notifications as $n): ?>
                <div class="list-group-item p-4 d-flex justify-content-between align-items-start <?= $n['is_read'] ? 'opacity-75 bg-body-tertiary' : 'bg-body' ?>">
                    <div>
                        <div class="d-flex align-items-center gap-2 mb-1">
                            <span class="badge <?= $n['is_read'] ? 'bg-secondary' : 'bg-danger' ?> rounded-pill fs-xs">
                                <?= $n['is_read'] ? 'READ' : 'UNREAD' ?>
                            </span>
                            <h6 class="fw-bold mb-0 text-body"><?= htmlspecialchars($n['title']) ?></h6>
                            <small class="text-muted fs-xs">• <?= date('M d, Y H:i', strtotime($n['created_at'])) ?></small>
                        </div>
                        <p class="text-secondary mb-2 fs-sm"><?= htmlspecialchars($n['message']) ?></p>
                        <?php if ($n['link']): ?>
                            <a href="<?= htmlspecialchars($n['link']) ?>" class="btn btn-xs btn-outline-primary rounded-pill">View Link <i class="bi bi-arrow-right"></i></a>
                        <?php endif; ?>
                    </div>

                    <?php if (!$n['is_read']): ?>
                        <form action="/notifications/<?= $n['id'] ?>/read" method="POST">
                            <?= \Core\CSRF::field() ?>
                            <button type="submit" class="btn btn-sm btn-light border btn-mark-notif-read" data-notif-id="<?= $n['id'] ?>">Mark Read</button>
                        </form>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>
