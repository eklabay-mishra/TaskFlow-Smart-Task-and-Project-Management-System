<?php
namespace App\Models;

use Core\Model;

class Setting extends Model
{
    protected string $table = 'settings';

    public function getAllAsKeyValue(): array
    {
        $rows = $this->findAll();
        $settings = [];
        foreach ($rows as $row) {
            $settings[$row['setting_key']] = $row['setting_value'];
        }
        return $settings;
    }

    public function get(string $key, $default = null)
    {
        $row = $this->db->fetch("SELECT setting_value FROM settings WHERE setting_key = ?", [$key]);
        return $row ? $row['setting_value'] : $default;
    }

    public function set(string $key, ?string $value): bool
    {
        return $this->db->execute(
            "INSERT INTO settings (setting_key, setting_value) VALUES (?, ?)
             ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)",
            [$key, $value]
        );
    }
}
