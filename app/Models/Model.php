<?php

namespace LinkHub\Models;

use App\Libraries\Database;
use App\Libraries\Config as ConfigLib;
use PDO;

class Model
{
    protected $db;
    protected $table;
    protected $primaryKey = 'id';
    protected $prefix;

    public function __construct()
    {
        $this->db = Database::getInstance();
        $this->prefix = ConfigLib::get('database.prefix', 'lh_');
    }

    public function find($id)
    {
        $sql = "SELECT * FROM `{$this->prefix}{$this->table}` WHERE `{$this->primaryKey}` = ?";
        return $this->db->fetch($sql, [$id]);
    }

    public function findBy($field, $value)
    {
        $sql = "SELECT * FROM `{$this->prefix}{$this->table}` WHERE `$field` = ?";
        return $this->db->fetch($sql, [$value]);
    }

    public function all()
    {
        $sql = "SELECT * FROM `{$this->prefix}{$this->table}`";
        return $this->db->fetchAll($sql);
    }

    public function create($data)
    {
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['updated_at'] = date('Y-m-d H:i:s');
        $columns = array_keys($data);
        $placeholders = array_fill(0, count($columns), '?');
        
        $sql = "INSERT INTO `{$this->prefix}{$this->table}` (`" . implode('`, `', $columns) . "`) VALUES (" . implode(', ', $placeholders) . ")";
        $this->db->query($sql, array_values($data));

        $lastId = $this->db->lastId();
        return $this->find($lastId);
    }

    public function update($id, $data)
    {
        $data['updated_at'] = date('Y-m-d H:i:s');
        $set = [];
        foreach (array_keys($data) as $column) {
            $set[] = "`$column` = ?";
        }
        
        $sql = "UPDATE `{$this->prefix}{$this->table}` SET " . implode(', ', $set) . " WHERE `{$this->primaryKey}` = ?";
        $params = array_values($data);
        $params[] = $id;
        $this->db->query($sql, $params);
        
        return $this->find($id);
    }

    public function delete($id)
    {
        $sql = "DELETE FROM `{$this->prefix}{$this->table}` WHERE `{$this->primaryKey}` = ?";
        return $this->db->query($sql, [$id])->rowCount() > 0;
    }

    public function count()
    {
        $sql = "SELECT COUNT(*) FROM `{$this->prefix}{$this->table}`";
        return (int)$this->db->fetch($sql)['COUNT(*)'];
    }

    public function recent($limit = 10)
    {
        $sql = "SELECT * FROM `{$this->prefix}{$this->table}` ORDER BY `created_at` DESC LIMIT ?";
        return $this->db->fetchAll($sql, [(int)$limit]);
    }

    public function getPrefix()
    {
        return $this->prefix;
    }

    public function rawQuery($sql, $params = [])
    {
        return $this->db->fetch($sql, $params);
    }

    public function rawQueryAll($sql, $params = [])
    {
        return $this->db->fetchAll($sql, $params);
    }
}
