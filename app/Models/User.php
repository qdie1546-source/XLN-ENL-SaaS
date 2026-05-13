<?php

namespace LinkHub\Models;

class User extends Model
{
    protected $table = 'users';

    public function findByEmail($email)
    {
        return $this->findBy('email', $email);
    }

    public function authenticate($email, $password)
    {
        $user = $this->findByEmail($email);
        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        return false;
    }

    public function register($email, $password, $name = null)
    {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        return $this->create([
            'email' => $email,
            'password' => $hashedPassword,
            'name' => $name ?? explode('@', $email)[0],
            'is_active' => 1,
            'is_admin' => 0,
            'user_type' => 'individual',
        ]);
    }

    public function enterpriseProfile($userId)
    {
        $sql = "SELECT * FROM `{$this->prefix}enterprise_profiles` WHERE `user_id` = ?";
        return $this->db->fetch($sql, [$userId]);
    }
    
    public function count()
    {
        $sql = "SELECT COUNT(*) as count FROM `{$this->prefix}users`";
        $result = $this->db->fetch($sql);
        return $result['count'] ?? 0;
    }
    
    public function recent($limit = 10)
    {
        $sql = "SELECT * FROM `{$this->prefix}users` ORDER BY `created_at` DESC LIMIT ?";
        return $this->db->fetchAll($sql, [$limit]);
    }
}
