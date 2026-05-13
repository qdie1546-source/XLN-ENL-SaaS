<?php

namespace App\Libraries;

class Database
{
    private static $instance = null;
    private $pdo;

    public static function getInstance($config = null)
    {
        if (is_null(self::$instance)) {
            self::$instance = new self($config);
        }
        return self::$instance;
    }

    public function __construct($config = null)
    {
        if (is_null($config)) {
            $driver = Config::get('database.driver', 'mysql');
            $host = Config::get('database.host', 'localhost');
            $port = Config::get('database.port', 3306);
            $dbname = Config::get('database.name', 'linkhub');
            $username = Config::get('database.user', 'root');
            $password = Config::get('database.pass', '');
        } else {
            $driver = $config['driver'] ?? 'mysql';
            $host = $config['host'] ?? 'localhost';
            $port = $config['port'] ?? 3306;
            $dbname = $config['name'] ?? 'linkhub';
            $username = $config['user'] ?? 'root';
            $password = $config['pass'] ?? '';
        }

        try {
            if ($driver === 'sqlite') {
                $path = $dbname;
                if (!str_contains($dbname, '/') && !str_contains($dbname, '\\')) {
                    $path = BASE_PATH . '/database/' . $dbname . '.sqlite';
                }
                $this->pdo = new \PDO("sqlite:" . $path);
            } else {
                $dsn = "mysql:host={$host};port={$port};dbname={$dbname};charset=utf8mb4";
                $this->pdo = new \PDO($dsn, $username, $password);
            }
            $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            $this->pdo->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            die("Database connection failed: " . $e->getMessage());
        }
    }

    public function query($sql, $params = [])
    {
        if (empty($params)) {
            return $this->pdo->query($sql);
        }
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    public function prepare($sql)
    {
        return $this->pdo->prepare($sql);
    }

    public function fetch($sql, $params = [])
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetch();
    }

    public function fetchAll($sql, $params = [])
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function lastInsertId()
    {
        return $this->pdo->lastInsertId();
    }

    public function lastId()
    {
        return $this->pdo->lastInsertId();
    }
}
