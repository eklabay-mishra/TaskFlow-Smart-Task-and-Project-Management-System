<?php
/**
 * TaskFlow Enterprise Database Seeder
 * Populates database with initial roles, admin user, PM, members, projects, tasks, comments & logs.
 */

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../core/Database.php';

use Core\Database;

try {
    $db = Database::getInstance();
    echo "Starting TaskFlow Database Seeding...\n";

    // 1. Seed Roles
    $db->execute("INSERT INTO roles (id, name, description) VALUES
        (1, 'Admin', 'System Administrator with full permissions'),
        (2, 'Project Manager', 'Manages projects, milestones, task assignments and reports'),
        (3, 'Team Member', 'Executes assigned tasks, logs hours, adds comments and files')
        ON DUPLICATE KEY UPDATE name=VALUES(name), description=VALUES(description)");
    echo "[✓] Roles seeded.\n";

    // 2. Seed Users
    $adminPassword = password_hash('Admin@123', PASSWORD_BCRYPT);
    $pmPassword    = password_hash('Manager@123', PASSWORD_BCRYPT);
    $memberPassword= password_hash('Member@123', PASSWORD_BCRYPT);

    $db->execute("INSERT INTO users (id, role_id, name, email, password_hash, avatar, phone, bio, status) VALUES
        (1, 1, 'Alexander Pierce', 'admin@taskflow.com', '$adminPassword', 'avatar-admin.png', '+1 (555) 019-2834', 'Lead System Administrator at TaskFlow Corp.', 'active'),
        (2, 2, 'Sophia Martinez', 'pm@taskflow.com', '$pmPassword', 'avatar-pm.png', '+1 (555) 014-9821', 'Senior Technical Project Manager with 8+ years experience.', 'active'),
        (3, 3, 'David Chen', 'member@taskflow.com', '$memberPassword', 'avatar-david.png', '+1 (555) 017-3344', 'Full Stack Developer specialized in PHP & JS.', 'active'),
        (4, 3, 'Emily Watson', 'emily@taskflow.com', '$memberPassword', 'avatar-emily.png', '+1 (555) 018-4455', 'UI/UX Designer and Frontend Specialist.', 'active'),
        (5, 3, 'Marcus Vance', 'marcus@taskflow.com', '$memberPassword', 'avatar-marcus.png', '+1 (555) 012-7788', 'QA Automation Engineer and DevOps enthusiast.', 'active')
        ON DUPLICATE KEY UPDATE name=VALUES(name), email=VALUES(email), password_hash=VALUES(password_hash)");
    echo "[✓] Users seeded.\n";

    // 3. Seed Projects
    $db->execute("INSERT INTO projects (id, title, description, category, status, priority, start_date, due_date, budget, created_by) VALUES
        (1, 'Enterprise TaskFlow SaaS Upgrade', 'Revamping core project management workflow, implementing real-time Kanban board, role-based dashboards and automated analytics.', 'Software Engineering', 'active', 'urgent', '2026-07-01', '2026-08-15', 45000.00, 1),
        (2, 'FinTech Mobile Portal Redesign', 'Designing responsive financial portal UI with accessibility standard compliance, chart analytics, and secure session guards.', 'UI/UX Design', 'active', 'high', '2026-06-15', '2026-08-30', 28000.00, 2),
        (3, 'Cloud Infrastructure Migration', 'Migrating legacy monolith servers to high-availability cloud architecture with Docker containers and automated CI/CD pipelines.', 'DevOps & Cloud', 'planning', 'medium', '2026-08-01', '2026-10-15', 35000.00, 1),
        (4, 'AI Data Analytics Integration', 'Integrating predictive project completion metrics, automatic workload balance suggestions, and custom reporting widgets.', 'Data Science', 'completed', 'medium', '2026-05-01', '2026-07-20', 18000.00, 2)
        ON DUPLICATE KEY UPDATE title=VALUES(title), description=VALUES(description)");
    echo "[✓] Projects seeded.\n";

    // 4. Seed Project Members
    $db->execute("INSERT INTO project_members (project_id, user_id, role_in_project) VALUES
        (1, 1, 'Project Owner'),
        (1, 2, 'Project Manager'),
        (1, 3, 'Lead Developer'),
        (1, 4, 'UI/UX Designer'),
        (1, 5, 'QA Engineer'),
        (2, 2, 'Project Manager'),
        (2, 4, 'Lead Designer'),
        (2, 3, 'Frontend Developer'),
        (3, 1, 'Project Owner'),
        (3, 5, 'DevOps Specialist')
        ON DUPLICATE KEY UPDATE role_in_project=VALUES(role_in_project)");
    echo "[✓] Project members seeded.\n";

    // 5. Seed Milestones
    $db->execute("INSERT INTO milestones (id, project_id, title, description, due_date, status) VALUES
        (1, 1, 'Phase 1: Architecture & DB Schema', 'Finalize normalized MySQL relational tables and MVC framework core.', '2026-07-10', 'completed'),
        (2, 1, 'Phase 2: Kanban & Dashboard Modules', 'Deliver interactive drag & drop Kanban board and Chart.js analytics.', '2026-07-25', 'in_progress'),
        (3, 1, 'Phase 3: QA & Final Deployment', 'End-to-end security audit, performance tuning, and production release.', '2026-08-15', 'pending'),
        (4, 2, 'UX Research & Wireframing', 'Complete user flow diagrams and high-fidelity Figma prototypes.', '2026-07-05', 'completed'),
        (5, 2, 'Frontend Component System', 'Develop reusable Bootstrap 5 component library with accessibility testing.', '2026-08-10', 'in_progress')
        ON DUPLICATE KEY UPDATE title=VALUES(title)");
    echo "[✓] Milestones seeded.\n";

    // 6. Seed Tasks
    $db->execute("INSERT INTO tasks (id, project_id, milestone_id, assigned_to, created_by, title, description, status, priority, due_date, estimated_hours, logged_hours) VALUES
        (1, 1, 1, 3, 2, 'Design Normalized MySQL Database Schema', 'Create tables for users, roles, projects, tasks, milestones, comments, attachments and activity logs.', 'done', 'high', '2026-07-08', 16.0, 16.0),
        (2, 1, 1, 3, 2, 'Implement Custom PHP MVC Framework Core', 'Build Front Controller router, base PDO Database singleton, CSRF protection, and Auth middleware.', 'done', 'urgent', '2026-07-12', 24.0, 26.0),
        (3, 1, 2, 3, 2, 'Build Drag-and-Drop AJAX Kanban Board', 'Implement interactive Kanban columns (To Do, In Progress, Review, Done) with instant AJAX status updates.', 'in_progress', 'urgent', '2026-07-28', 20.0, 14.5),
        (4, 1, 2, 4, 2, 'Craft Modern SaaS UI & Responsive Dashboard Layout', 'Style glassmorphism headers, dark mode toggle, sidebar navigation, and SweetAlert2 dynamic feedback.', 'in_progress', 'high', '2026-07-29', 18.0, 12.0),
        (5, 1, 2, 5, 2, 'Write Unit & E2E Verification Tests for Auth Routes', 'Test registration, login session persistence, password hashing, and role permission guards.', 'todo', 'medium', '2026-08-02', 12.0, 0.0),
        (6, 1, 3, 3, 1, 'Configure PDF & CSV Report Generators', 'Export project status breakdowns and member workload logs directly to downloadable CSV files.', 'review', 'high', '2026-08-05', 14.0, 10.0),
        (7, 2, 4, 4, 2, 'Create Responsive Design System & CSS Variables', 'Setup custom color tokens, typography scales, card shadow elevations, and dark mode overrides.', 'done', 'high', '2026-07-15', 30.0, 32.0),
        (8, 2, 5, 3, 2, 'Integrate Chart.js Analytics into Portfolio View', 'Build dynamic doughnut and bar charts fed by live database endpoints.', 'todo', 'medium', '2026-08-12', 15.0, 0.0)
        ON DUPLICATE KEY UPDATE title=VALUES(title), status=VALUES(status)");
    echo "[✓] Tasks seeded.\n";

    // 7. Seed Comments
    $db->execute("INSERT INTO comments (id, task_id, user_id, comment) VALUES
        (1, 1, 3, 'Database schema finalized and executed smoothly. All foreign keys and indexes verified!'),
        (2, 1, 2, 'Great work David. Schema looks clean and perfectly normalized for subqueries.'),
        (3, 3, 3, 'Kanban AJAX endpoint is complete. Currently tuning smooth animation transitions and SweetAlert toasts.'),
        (4, 4, 4, 'Dark mode styles tested across Chrome and Firefox. Looks extremely polished!')
        ON DUPLICATE KEY UPDATE comment=VALUES(comment)");
    echo "[✓] Comments seeded.\n";

    // 8. Seed Notifications
    $db->execute("INSERT INTO notifications (id, user_id, title, message, link, is_read) VALUES
        (1, 3, 'New Task Assigned', 'You have been assigned to task: Build Drag-and-Drop AJAX Kanban Board', '/tasks', 0),
        (2, 3, 'Milestone Update', 'Milestone Phase 1: Architecture & DB Schema has been marked as Completed', '/projects/1', 1),
        (3, 2, 'New Comment Added', 'David Chen commented on Task #1 (Design Database Schema)', '/tasks', 0),
        (4, 4, 'Task Priority Escalated', 'Task: Craft Modern SaaS UI has been marked as High Priority', '/tasks', 0)
        ON DUPLICATE KEY UPDATE title=VALUES(title)");
    echo "[✓] Notifications seeded.\n";

    // 9. Seed Activity Logs
    $db->execute("INSERT INTO activity_logs (user_id, project_id, task_id, action, description) VALUES
        (1, 1, NULL, 'PROJECT_CREATED', 'Alexander Pierce created project TaskFlow SaaS Upgrade'),
        (2, 1, 1, 'TASK_CREATED', 'Sophia Martinez created task #1: Design Normalized MySQL Database Schema'),
        (3, 1, 1, 'TASK_COMPLETED', 'David Chen marked task #1 as Done'),
        (3, 1, 3, 'STATUS_UPDATE', 'David Chen updated task #3 status from todo to in_progress'),
        (4, 1, 4, 'COMMENT_ADDED', 'Emily Watson added a comment to task #4')");
    echo "[✓] Activity logs seeded.\n";

    // 10. Seed Settings
    $db->execute("INSERT INTO settings (setting_key, setting_value) VALUES
        ('app_name', 'TaskFlow Enterprise'),
        ('app_email', 'support@taskflow.com'),
        ('allow_registration', '1'),
        ('default_role', '3'),
        ('company_name', 'TaskFlow Inc.')
        ON DUPLICATE KEY UPDATE setting_value=VALUES(setting_value)");
    echo "[✓] Settings seeded.\n";

    echo "Database Seeding Completed Successfully!\n";

} catch (\Exception $e) {
    echo "Seeding Error: " . $e->getMessage() . "\n";
    exit(1);
}
