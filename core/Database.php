<?php
namespace Core;

use PDO;
use PDOException;
use Exception;

class Database
{
    private static ?Database $instance = null;
    private ?PDO $pdo = null;

    private function __construct()
    {
        $dsn = sprintf(
            "mysql:host=%s;port=%s;dbname=%s;charset=%s",
            DB_HOST,
            DB_PORT,
            DB_NAME,
            DB_CHARSET
        );

        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        try {
            $this->pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch (PDOException $e) {
            http_response_code(500);
            echo "<!DOCTYPE html><html data-theme='dark'><head><meta charset='UTF-8'><meta name='viewport' content='width=device-width, initial-scale=1.0'><title>Database Setup - TaskFlow Enterprise</title><link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css'><link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css'><link rel='stylesheet' href='/assets/css/style.css'></head><body class='p-4 d-flex align-items-center justify-content-center min-vh-100' style='background-color:#05070b;'><div class='card p-5 border-0 shadow-lg text-start' style='max-width: 680px; background: #0c0f1c;'><div class='text-warning mb-3'><i class='bi bi-database-fill-gear display-4'></i></div><h3 class='fw-extrabold text-white mb-2'>Cloud Database Initialization Notice</h3><p class='text-secondary fs-sm mb-4'>TaskFlow is attempting to connect to MySQL host <code>" . htmlspecialchars(DB_HOST) . "</code> on port <code>" . htmlspecialchars(DB_PORT) . "</code>.</p><div class='p-3 rounded-3 mb-4 bg-dark text-danger fs-xs font-monospace border border-danger border-opacity-25'>" . htmlspecialchars($e->getMessage()) . "</div><div class='p-3 rounded-3 bg-secondary-subtle border border-secondary border-opacity-10 text-muted fs-xs mb-3'><strong class='text-white d-block mb-1'>Render Cloud Deployment Guide:</strong>To connect an external MySQL database (e.g. Aiven, Railway, PlanetScale, or CleverCloud):<br>1. Go to <strong>Render Dashboard -> TaskFlow Web Service -> Environment</strong>.<br>2. Set <code>DB_HOST</code>, <code>DB_NAME</code>, <code>DB_USER</code>, <code>DB_PASS</code>, <code>DB_PORT</code>.<br>3. Save and click <strong>Manual Deploy -> Clear Build Cache & Deploy</strong>.</div><div class='text-center pt-2'><a href='/' class='btn btn-primary rounded-pill px-4 btn-sm'>Retry Connection <i class='bi bi-arrow-clockwise ms-1'></i></a></div></div></body></html>";
            exit;
        }
    }

    public static function getInstance(): Database
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getPdo(): PDO
    {
        return $this->pdo;
    }

    public function query(string $sql, array $params = [])
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    public function fetch(string $sql, array $params = []): ?array
    {
        $result = $this->query($sql, $params)->fetch();
        return $result !== false ? $result : null;
    }

    public function fetchAll(string $sql, array $params = []): array
    {
        return $this->query($sql, $params)->fetchAll();
    }

    public function execute(string $sql, array $params = []): bool
    {
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($params);
    }

    public function lastInsertId(): string
    {
        return $this->pdo->lastInsertId();
    }

    public function beginTransaction(): bool
    {
        return $this->pdo->beginTransaction();
    }

    public function commit(): bool
    {
        return $this->pdo->commit();
    }

    public function rollBack(): bool
    {
        return $this->pdo->rollBack();
    }
}
