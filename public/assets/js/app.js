/**
 * TaskFlow Enterprise JavaScript Engine
 */

document.addEventListener('DOMContentLoaded', () => {
    // 1. Dark Mode Toggle
    const themeToggleBtn = document.getElementById('theme-toggle-btn');
    if (themeToggleBtn) {
        themeToggleBtn.addEventListener('click', () => {
            const currentTheme = document.documentElement.getAttribute('data-theme') || 'light';
            const newTheme = currentTheme === 'light' ? 'dark' : 'light';
            
            document.documentElement.setAttribute('data-theme', newTheme);
            localStorage.setItem('tf_theme', newTheme);

            // Sync with backend session
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            fetch('/profile/theme', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: `theme=${newTheme}&csrf_token=${encodeURIComponent(csrfToken || '')}`
            }).catch(console.error);
        });
    }

    // Restore saved theme on page load
    const savedTheme = localStorage.getItem('tf_theme');
    if (savedTheme) {
        document.documentElement.setAttribute('data-theme', savedTheme);
    }

    // 2. Sidebar Toggle for Mobile
    const sidebarToggle = document.getElementById('sidebar-toggle');
    const wrapper = document.getElementById('wrapper');
    if (sidebarToggle && wrapper) {
        sidebarToggle.addEventListener('click', (e) => {
            e.preventDefault();
            wrapper.classList.toggle('toggled');
        });
    }

    // 3. SweetAlert2 Delete Confirmations
    document.querySelectorAll('.btn-confirm-delete').forEach(button => {
        button.addEventListener('click', function (e) {
            e.preventDefault();
            const form = this.closest('form');
            const itemTitle = this.getAttribute('data-title') || 'this record';

            Swal.fire({
                title: 'Are you sure?',
                text: `You are about to delete "${itemTitle}". This action cannot be undone.`,
                icon: 'warning',
                showCancelButton: true,
                confirmColor: '#ef4444',
                cancelColor: '#64748b',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });

    // 4. Kanban Drag & Drop / Inline Status Update
    const kanbanCards = document.querySelectorAll('.kanban-card');
    const kanbanColumns = document.querySelectorAll('.kanban-cards');

    kanbanCards.forEach(card => {
        card.addEventListener('dragstart', () => {
            card.classList.add('dragging');
        });

        card.addEventListener('dragend', () => {
            card.classList.remove('dragging');
        });
    });

    kanbanColumns.forEach(column => {
        column.addEventListener('dragover', e => {
            e.preventDefault();
            column.classList.add('bg-light-subtle');
        });

        column.addEventListener('dragleave', () => {
            column.classList.remove('bg-light-subtle');
        });

        column.addEventListener('drop', function (e) {
            e.preventDefault();
            column.classList.remove('bg-light-subtle');
            const draggingCard = document.querySelector('.kanban-card.dragging');
            if (!draggingCard) return;

            const newStatus = this.getAttribute('data-status');
            const taskId = draggingCard.getAttribute('data-task-id');
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

            this.appendChild(draggingCard);

            // Trigger AJAX update
            fetch(`/tasks/${taskId}/status`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: `status=${encodeURIComponent(newStatus)}&csrf_token=${encodeURIComponent(csrfToken || '')}`
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'success',
                        title: 'Task status updated!',
                        showConfirmButton: false,
                        timer: 2000
                    });
                } else {
                    Swal.fire('Error', data.message || 'Failed to update status', 'error');
                }
            })
            .catch(err => console.error(err));
        });
    });

    // 5. AJAX Quick Comment Submission
    const commentForm = document.getElementById('ajax-comment-form');
    if (commentForm) {
        commentForm.addEventListener('submit', function (e) {
            e.preventDefault();
            const taskId = this.getAttribute('data-task-id');
            const commentInput = document.getElementById('comment-text');
            const commentText = commentInput.value.trim();
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

            if (!commentText) return;

            fetch(`/tasks/${taskId}/comments`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: `comment=${encodeURIComponent(commentText)}&csrf_token=${encodeURIComponent(csrfToken || '')}`
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    const thread = document.getElementById('comments-thread');
                    const c = data.comment;
                    const commentHtml = `
                        <div class="d-flex gap-3 mb-3 p-3 rounded bg-body-tertiary">
                            <img src="/uploads/${c.user_avatar}" class="avatar-sm" alt="avatar" onerror="this.src='https://ui-avatars.com/api/?name=${encodeURIComponent(c.user_name)}'">
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <h6 class="mb-0 fw-bold">${c.user_name} <span class="badge bg-secondary ms-1 fs-xs">${c.role_name}</span></h6>
                                    <small class="text-muted fs-xs">Just now</small>
                                </div>
                                <p class="mb-0 text-secondary">${c.comment}</p>
                            </div>
                        </div>
                    `;
                    thread.insertAdjacentHTML('beforeend', commentHtml);
                    commentInput.value = '';

                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'success',
                        title: 'Comment posted',
                        showConfirmButton: false,
                        timer: 1500
                    });
                }
            });
        });
    }

    // 6. Mark Notification Read AJAX
    document.querySelectorAll('.btn-mark-notif-read').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const notifId = this.getAttribute('data-notif-id');
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

            fetch(`/notifications/${notifId}/read`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: `csrf_token=${encodeURIComponent(csrfToken || '')}`
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    this.closest('.list-group-item').classList.add('opacity-50');
                    this.remove();
                    const badge = document.getElementById('nav-unread-badge');
                    if (badge) {
                        badge.textContent = data.unread_count;
                        if (data.unread_count === 0) badge.style.display = 'none';
                    }
                }
            });
        });
    });
});
