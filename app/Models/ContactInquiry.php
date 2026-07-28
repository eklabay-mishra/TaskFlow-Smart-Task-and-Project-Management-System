<?php
namespace App\Models;

use Core\Model;

class ContactInquiry extends Model
{
    protected string $table = 'contact_inquiries';

    public function create(array $data): int
    {
        $this->db->execute(
            "INSERT INTO contact_inquiries (name, email, subject, message) VALUES (?, ?, ?, ?)",
            [$data['name'], $data['email'], $data['subject'], $data['message']]
        );
        return (int)$this->db->lastInsertId();
    }
}
