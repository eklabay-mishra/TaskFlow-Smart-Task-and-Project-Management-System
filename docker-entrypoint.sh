#!/bin/bash
set -e

# Render dynamic PORT support
if [ -n "$PORT" ]; then
    echo "Configuring Apache to listen on Render PORT $PORT..."
    sed -i "s/Listen 80/Listen $PORT/g" /etc/apache2/ports.conf
    sed -i "s/<VirtualHost \*:80>/<VirtualHost *:$PORT>/g" /etc/apache2/sites-available/*.conf
fi

# If DB_HOST is local or default, start internal MariaDB server inside container
if [ "$DB_HOST" = "127.0.0.1" ] || [ -z "$DB_HOST" ]; then
    echo "[✓] Starting internal MariaDB database engine..."
    service mariadb start || service mysql start || true
fi

echo "Verifying Database Connection & Running Seeder..."
php -r "
require 'config/config.php';
try {
    \$pdo = new PDO('mysql:host='.DB_HOST.';port='.DB_PORT, DB_USER, DB_PASS);
    \$pdo->exec('CREATE DATABASE IF NOT EXISTS \`'.DB_NAME.'\` CHARACTER SET utf8mb4;');
    \$dbPdo = new PDO('mysql:host='.DB_HOST.';port='.DB_PORT.';dbname='.DB_NAME, DB_USER, DB_PASS);
    \$stmt = \$dbPdo->query('SHOW TABLES');
    \$tables = \$stmt ? \$stmt->fetchAll() : [];
    if (count(\$tables) === 0) {
        echo \"[✓] Importing database schema & running initial seeder...\n\";
        \$sql = file_get_contents('database/schema.sql');
        \$dbPdo->exec(\$sql);
        require 'database/seed.php';
        require 'database/create_avatars.php';
    } else {
        echo \"[✓] Database verified and ready.\n\";
    }
} catch (Exception \$e) {
    echo \"[Notice] DB Check: \" . \$e->getMessage() . \"\n\";
}
" || true

exec apache2-foreground
