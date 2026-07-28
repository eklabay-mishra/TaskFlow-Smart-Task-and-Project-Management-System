-- TaskFlow Enterprise Database Schema
-- Compatible with MySQL 8+ / MySQL 9+

CREATE DATABASE IF NOT EXISTS `taskflow_db` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `taskflow_db`;

-- Drop tables in order of dependency if re-creating
SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS `contact_inquiries`;
DROP TABLE IF EXISTS `settings`;
DROP TABLE IF EXISTS `activity_logs`;
DROP TABLE IF EXISTS `notifications`;
DROP TABLE IF EXISTS `attachments`;
DROP TABLE IF EXISTS `comments`;
DROP TABLE IF EXISTS `tasks`;
DROP TABLE IF EXISTS `milestones`;
DROP TABLE IF EXISTS `project_members`;
DROP TABLE IF EXISTS `projects`;
DROP TABLE IF EXISTS `users`;
DROP TABLE IF EXISTS `roles`;
SET FOREIGN_KEY_CHECKS = 1;

-- Roles table
CREATE TABLE `roles` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(50) NOT NULL UNIQUE,
    `description` VARCHAR(255) NULL,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Users table
CREATE TABLE `users` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `role_id` INT NOT NULL,
    `name` VARCHAR(100) NOT NULL,
    `email` VARCHAR(150) NOT NULL UNIQUE,
    `password_hash` VARCHAR(255) NOT NULL,
    `avatar` VARCHAR(255) DEFAULT 'default-avatar.png',
    `phone` VARCHAR(30) NULL,
    `bio` TEXT NULL,
    `theme_mode` ENUM('light', 'dark') DEFAULT 'light',
    `status` ENUM('active', 'inactive') DEFAULT 'active',
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Projects table
CREATE TABLE `projects` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `title` VARCHAR(200) NOT NULL,
    `description` TEXT NULL,
    `category` VARCHAR(100) DEFAULT 'General',
    `status` ENUM('planning', 'active', 'on_hold', 'completed', 'cancelled') DEFAULT 'planning',
    `priority` ENUM('low', 'medium', 'high', 'urgent') DEFAULT 'medium',
    `start_date` DATE NULL,
    `due_date` DATE NULL,
    `budget` DECIMAL(12,2) DEFAULT 0.00,
    `created_by` INT NOT NULL,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
    INDEX `idx_projects_status` (`status`),
    INDEX `idx_projects_priority` (`priority`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Project Members table
CREATE TABLE `project_members` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `project_id` INT NOT NULL,
    `user_id` INT NOT NULL,
    `role_in_project` VARCHAR(50) DEFAULT 'Member',
    `joined_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY `unique_project_user` (`project_id`, `user_id`),
    FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE,
    FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Milestones table
CREATE TABLE `milestones` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `project_id` INT NOT NULL,
    `title` VARCHAR(200) NOT NULL,
    `description` TEXT NULL,
    `due_date` DATE NULL,
    `status` ENUM('pending', 'in_progress', 'completed') DEFAULT 'pending',
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tasks table
CREATE TABLE `tasks` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `project_id` INT NOT NULL,
    `milestone_id` INT NULL,
    `assigned_to` INT NULL,
    `created_by` INT NOT NULL,
    `title` VARCHAR(200) NOT NULL,
    `description` TEXT NULL,
    `status` ENUM('todo', 'in_progress', 'review', 'done') DEFAULT 'todo',
    `priority` ENUM('low', 'medium', 'high', 'urgent') DEFAULT 'medium',
    `due_date` DATE NULL,
    `estimated_hours` DECIMAL(6,2) DEFAULT 0.00,
    `logged_hours` DECIMAL(6,2) DEFAULT 0.00,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE,
    FOREIGN KEY (`milestone_id`) REFERENCES `milestones` (`id`) ON DELETE SET NULL,
    FOREIGN KEY (`assigned_to`) REFERENCES `users` (`id`) ON DELETE SET NULL,
    FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
    INDEX `idx_tasks_status` (`status`),
    INDEX `idx_tasks_priority` (`priority`),
    INDEX `idx_tasks_assigned` (`assigned_to`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Comments table
CREATE TABLE `comments` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `task_id` INT NOT NULL,
    `user_id` INT NOT NULL,
    `comment` TEXT NOT NULL,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`task_id`) REFERENCES `tasks` (`id`) ON DELETE CASCADE,
    FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Attachments table
CREATE TABLE `attachments` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `task_id` INT NULL,
    `project_id` INT NULL,
    `user_id` INT NOT NULL,
    `file_name` VARCHAR(255) NOT NULL,
    `file_path` VARCHAR(255) NOT NULL,
    `file_size` INT NOT NULL,
    `file_type` VARCHAR(100) NOT NULL,
    `uploaded_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`task_id`) REFERENCES `tasks` (`id`) ON DELETE CASCADE,
    FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE,
    FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Notifications table
CREATE TABLE `notifications` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT NOT NULL,
    `title` VARCHAR(200) NOT NULL,
    `message` TEXT NOT NULL,
    `link` VARCHAR(255) NULL,
    `is_read` TINYINT(1) DEFAULT 0,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
    INDEX `idx_notifications_read` (`user_id`, `is_read`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Activity Logs table
CREATE TABLE `activity_logs` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT NOT NULL,
    `project_id` INT NULL,
    `task_id` INT NULL,
    `action` VARCHAR(100) NOT NULL,
    `description` TEXT NOT NULL,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
    FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE SET NULL,
    FOREIGN KEY (`task_id`) REFERENCES `tasks` (`id`) ON DELETE SET NULL,
    INDEX `idx_activity_created` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Contact Inquiries table
CREATE TABLE `contact_inquiries` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL,
    `email` VARCHAR(150) NOT NULL,
    `subject` VARCHAR(200) NOT NULL,
    `message` TEXT NOT NULL,
    `status` ENUM('new', 'read', 'replied') DEFAULT 'new',
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Settings table
CREATE TABLE `settings` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `setting_key` VARCHAR(100) NOT NULL UNIQUE,
    `setting_value` TEXT NULL,
    `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
