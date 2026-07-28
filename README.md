# TaskFlow Enterprise - Smart Task & Project Management System

TaskFlow Enterprise is a production-quality, full-stack Project & Task Management System built with **PHP 8 (Pure OOP & Custom MVC Architecture)**, **MySQL**, **HTML5/CSS3**, **Bootstrap 5**, **JavaScript (ES6)**, **AJAX**, **Chart.js**, and **SweetAlert2**.

Designed to be portfolio-quality, deployment-ready, and interview-ready, TaskFlow avoids dummy data or placeholder pages—every single feature is fully backed by real MySQL database operations and security guards.

---

## Key Features & Modules

### 1. Responsive Public SaaS Portal
- **Home Page**: Hero section, live system counters, quick demo credential showcase, feature matrix.
- **About & Features**: Technical architecture breakdown and complete platform specs.
- **Contact Form**: Interactive contact form saving inquiries to the `contact_inquiries` database table with AJAX & SweetAlert2 notifications.
- **Authentication**: Client-side & server-side validation, `password_hash()` BCRYPT encryption, session hijacking protection, CSRF token verification.

### 2. Multi-Role Access Control (RBAC)
- **Admin**: Full system control, user permission management, role assignments, global settings, audit logs.
- **Project Manager**: Project creation, milestone tracking, team member assignment, task management, CSV reports export.
- **Team Member**: View assigned tasks, update task status, log working hours, participate in discussion threads, upload attachments.

### 3. Core Management Modules
- **Projects Workspace**: Grid & table views, status/priority filtering, budget tracking, progress percentage auto-calculation, team roster.
- **Tasks Management**: Comprehensive list, milestone linking, estimated vs logged hours tracking, inline status updates.
- **Interactive AJAX Kanban Board**: Drag-and-drop columns (*To Do*, *In Progress*, *Under Review*, *Completed*) with real-time MySQL state sync.
- **Interactive Calendar**: Timeline view of project milestones and task due dates.
- **Analytics & Reports**: Chart.js status ratio doughnut charts, priority bar graphs, member workload metrics, and 1-click CSV report exports.
- **Notification Center**: Real-time unread notification count badge, task assignment alerts, AJAX mark-as-read.
- **Task Discussions & Attachments**: Interactive comment threads, secure file uploader (PDF, DOCX, Images, ZIP) with file validation.
- **Dark Mode**: Built-in dark/light SaaS theme toggle with CSS custom properties and persistent user session sync.

---

## Technical Architecture

```
TaskFlow Architecture Overview
├── config/
│   └── config.php          # Database credentials & App constants
├── core/
│   ├── Database.php        # Singleton PDO Connection Wrapper
│   ├── Router.php          # RESTful Front Controller Routing Engine
│   ├── Controller.php      # Base MVC Controller (Render, JSON, Redirect, Auth guards)
│   ├── Model.php           # Abstract PDO Model Base
│   ├── Session.php         # Secure Session & Flash Message Handler
│   ├── CSRF.php            # CSRF Token Generator & Verifier
│   └── Auth.php            # Authentication & RBAC Helper
├── app/
│   ├── Controllers/        # Auth, Dashboard, Project, Task, Kanban, Calendar, Report, Notification, Profile, User, Setting
│   └── Models/             # User, Role, Project, Task, Milestone, Comment, Attachment, Notification, ActivityLog, ContactInquiry, Setting
├── views/
│   ├── layouts/            # public.php & dashboard.php
│   ├── public/             # Home, About, Features, Contact, Login, Register
│   ├── dashboard/          # Dynamic Role Dashboard
│   ├── projects/           # Projects list & workspace tabs
│   ├── tasks/              # Task list & detail view
│   ├── kanban/             # Drag & drop Kanban board
│   ├── calendar/           # Interactive calendar
│   ├── reports/            # Analytics & CSV export
│   ├── notifications/      # Notification center
│   ├── profile/            # Profile & password management
│   ├── users/              # Admin user management
│   └── settings/           # Admin platform settings
├── public/                 # Web Root (index.php, CSS, JS, Uploads)
│   ├── assets/             # style.css & app.js
│   ├── uploads/            # Secure file uploads folder
│   └── index.php           # Front Controller Entrypoint
└── database/
    ├── schema.sql          # Normalized MySQL Relational Schema
    └── seed.php            # CLI Seeder for Demo Accounts & Sample Data
```

---

## Database Schema ERD Highlights

The system relies on a normalized MySQL relational schema (`taskflow_db`):
- `roles`: `(id, name, description)`
- `users`: `(id, role_id, name, email, password_hash, avatar, phone, bio, theme_mode, status)`
- `projects`: `(id, title, description, category, status, priority, start_date, due_date, budget, created_by)`
- `project_members`: `(id, project_id, user_id, role_in_project)`
- `milestones`: `(id, project_id, title, description, due_date, status)`
- `tasks`: `(id, project_id, milestone_id, assigned_to, created_by, title, description, status, priority, due_date, estimated_hours, logged_hours)`
- `comments`: `(id, task_id, user_id, comment, created_at)`
- `attachments`: `(id, task_id, project_id, user_id, file_name, file_path, file_size, file_type)`
- `notifications`: `(id, user_id, title, message, link, is_read)`
- `activity_logs`: `(id, user_id, project_id, task_id, action, description)`
- `contact_inquiries`: `(id, name, email, subject, message, status)`
- `settings`: `(id, setting_key, setting_value)`

---

## Demo Credentials

Upon running `php database/seed.php`, the following accounts are available:

| Role | Email | Password | Access Rights |
| :--- | :--- | :--- | :--- |
| **Admin** | `admin@taskflow.com` | `Admin@123` | Full enterprise control, user & settings management |
| **Project Manager** | `pm@taskflow.com` | `Manager@123` | Project creation, task assignment, report generation |
| **Team Member** | `member@taskflow.com` | `Member@123` | Task execution, status updates, comments, hours logging |

---

## Quick Setup Instructions

1. **Clone & Set Up Directory**:
   ```bash
   cd "TaskFlow | Smart Task & Project Management System"
   ```

2. **Import Database & Run Seeder**:
   Ensure MySQL service is running locally, then execute:
   ```bash
   mysql -u root -e "CREATE DATABASE IF NOT EXISTS taskflow_db;"
   mysql -u root taskflow_db < database/schema.sql
   php database/seed.php
   ```

3. **Start Development Server**:
   ```bash
   php -S 127.0.0.1:8000 -t public
   ```

4. **Access in Browser**:
   Navigate to `http://127.0.0.1:8000` to access TaskFlow Enterprise.

---

## Security Implementation

- **SQL Injection Prevention**: All database queries use PDO prepared statements with parameter binding.
- **XSS Protection**: All user inputs rendered in HTML are sanitized using `htmlspecialchars()`.
- **CSRF Token Verification**: Form submissions and AJAX POST requests validate session CSRF tokens (`CSRF::verify()`).
- **Password Security**: Passwords hashed using `password_hash($pass, PASSWORD_BCRYPT)` and verified via `password_verify()`.
- **Session Security**: Session IDs regenerated upon login; HTTP-only and SameSite cookie options enforced.
- **Upload Guards**: Strict file extension whitelist (`png`, `jpg`, `pdf`, `docx`, `zip`, `txt`, `csv`) and file size limits.

---

## Software Engineering Interview Discussion Points

1. **Why Pure PHP 8 MVC over heavy frameworks?**
   Demonstrates a deep comprehension of Front Controller routing, dependency injection, PDO singleton connection handling, base controllers, and object-oriented design patterns without relying on black-box framework abstractions.
2. **How does Kanban AJAX sync work?**
   When a user drags a task card, JavaScript captures `dragend` / `drop` events, retrieves the target column status, and transmits an asynchronous POST payload to `/tasks/{id}/status`. The backend validates CSRF and RBAC permissions before updating MySQL and generating an audit log.
3. **How is project progress calculated dynamically?**
   Progress is computed via SQL subqueries calculating `(completed_tasks / total_tasks) * 100`, avoiding stale cached values and ensuring real-time accuracy across dashboards.
