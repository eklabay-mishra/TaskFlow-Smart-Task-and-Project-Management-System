<?php
/**
 * TaskFlow Enterprise Configuration File
 */

// Application Constants
define('APP_NAME', getenv('APP_NAME') ?: 'TaskFlow Enterprise');
define('APP_URL', getenv('APP_URL') ?: 'http://127.0.0.1:8000');
define('APP_ROOT', dirname(__DIR__));
define('UPLOAD_DIR', APP_ROOT . '/public/uploads/');
define('MAX_FILE_SIZE', 10 * 1024 * 1024); // 10MB limit

// Database Credentials (Environment Variable Support for Cloud Deployment)
define('DB_HOST', getenv('DB_HOST') ?: '127.0.0.1');
define('DB_PORT', getenv('DB_PORT') ?: '3306');
define('DB_NAME', getenv('DB_NAME') ?: 'taskflow_db');
define('DB_USER', getenv('DB_USER') ?: 'root');
define('DB_PASS', getenv('DB_PASS') !== false ? getenv('DB_PASS') : '');
define('DB_CHARSET', getenv('DB_CHARSET') ?: 'utf8mb4');

// Session Config
define('SESSION_LIFETIME', 86400); // 24 hours
ini_set('display_errors', '1');
error_reporting(E_ALL);
