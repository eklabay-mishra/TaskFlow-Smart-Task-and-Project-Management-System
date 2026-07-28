<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <h3 class="fw-extrabold mb-1">Project & Task Calendar</h3>
        <p class="text-secondary mb-0">Visual timeline of upcoming deadlines, task milestones, and deliverables.</p>
    </div>
    <a href="/tasks" class="btn btn-primary rounded-pill px-4 shadow-sm"><i class="bi bi-plus-lg me-1"></i> New Task</a>
</div>

<div class="card border-0 shadow-sm p-4">
    <div id="calendar-loading" class="text-center py-5">
        <div class="spinner-border text-primary" role="status"></div>
        <div class="mt-2 text-muted fs-sm">Loading task schedule events...</div>
    </div>
    <div id="calendar-container" class="row g-3 d-none">
        <!-- Rendered dynamically -->
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    fetch('/calendar/events')
        .then(res => res.json())
        .then(events => {
            document.getElementById('calendar-loading').classList.add('d-none');
            const container = document.getElementById('calendar-container');
            container.classList.remove('d-none');

            if (events.length === 0) {
                container.innerHTML = '<div class="col-12 text-center py-4 text-muted">No scheduled tasks with due dates found.</div>';
                return;
            }

            let html = '';
            events.forEach(evt => {
                html += `
                    <div class="col-md-6 col-lg-4">
                        <div class="p-3 border rounded shadow-sm bg-body card-hover" style="border-left: 5px solid ${evt.color} !important;">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="badge bg-light text-dark border fs-xs">${evt.start}</span>
                                <small class="text-muted fs-xs">${evt.description}</small>
                            </div>
                            <h6 class="fw-bold mb-2">
                                <a href="${evt.url}" class="text-body text-decoration-none">${evt.title}</a>
                            </h6>
                            <a href="${evt.url}" class="btn btn-xs btn-outline-primary rounded-pill mt-2">View Task Details</a>
                        </div>
                    </div>
                `;
            });

            container.innerHTML = html;
        })
        .catch(err => console.error(err));
});
</script>
