#!/bin/bash
set -e

# Render dynamic PORT support
if [ -n "$PORT" ]; then
    echo "Configuring Apache to listen on Render PORT $PORT..."
    sed -i "s/Listen 80/Listen $PORT/g" /etc/apache2/ports.conf
    sed -i "s/<VirtualHost \*:80>/<VirtualHost *:$PORT>/g" /etc/apache2/sites-available/*.conf
fi

# Auto-initialize database on cloud start if DB credentials exist
if [ -n "$DB_HOST" ]; then
    echo "Verifying Cloud MySQL Connection..."
    php -r "
    require 'config/config.php';
    try {
        \$pdo = new PDO('mysql:host='.DB_HOST.';port='.DB_PORT, DB_USER, DB_PASS);
        \$pdo->exec('CREATE DATABASE IF NOT EXISTS \`'.DB_NAME.'\` CHARACTER SET utf8mb4;');
        \$dbPdo = new PDO('mysql:host='.DB_HOST.';port='.DB_PORT.';dbname='.DB_NAME, DB_USER, DB_PASS);
        \$stmt = \$dbPdo->query('SHOW TABLES');
        \$tables = \$stmt ? \$stmt->fetchAll() : [];
        if (count(\$tables) === 0) {
            echo \"[✓] Importing database schema & running seeder on Render Cloud...\n\";
            \$sql = file_get_contents('database/schema.sql');
            \$dbPdo->exec(\$sql);
            require 'database/seed.php';
        } else {
            echo \"[✓] Cloud Database verified and ready.\n\";
        }
    } catch (Exception \$e) {
        echo \"[Notice] DB Connection check: \" . \$e->getMessage() . \"\n\";
    }
    " || true
fi

exec apache2-foreground
