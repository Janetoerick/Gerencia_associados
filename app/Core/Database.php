<?php
// app/Core/Database.php

namespace App\Core;

use PDO;
use PDOException;

class Database {
    private static $instance = null;
    private $connection;

    protected ?\PDO $pdo = null;
    
    public function __construct() {
        $host = 'db'; 
        $db_name = 'associacao';
        $user = 'root';
        $password = 'root_password';
        
        $dsn = "mysql:host={$host};dbname={$db_name};charset=utf8";
        
        try {
            $this->pdo = new \PDO($dsn, $user, $password);
            $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        } catch (\PDOException $e) {
            die("Erro de conexão com o banco de dados: " . $e->getMessage()); 
        }
    }

    public static function getInstance() 
    {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    public function getConnection()
    {
        return $this->pdo;
    }
}